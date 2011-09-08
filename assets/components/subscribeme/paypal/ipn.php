<?php
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modX();
if (!($modx instanceof modX)) { error_log('Failure setting up modX.'); die(); }
$modx->initialize('web');
$modx->getService('error','error.modError');

$debug = false;
if ($modx->getOption('subscribeme.debug',null,false)) $debug = true;

$corePath = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/');
require_once $corePath.'classes/subscribeme.class.php';
$modx->sm = new SubscribeMe($modx);

$ipn_post_data = $_POST;

if ($debug) $modx->log(MODX_LEVEL_ERROR,'IPN Triggered with data: '.print_r($ipn_post_data,true));

// Choose url based on test_ipn value
if(array_key_exists('test_ipn', $ipn_post_data) && 1 === (int) $ipn_post_data['test_ipn'])
    $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
else
    $url = 'https://www.paypal.com/cgi-bin/webscr';

// Build the postfields
$req = 'cmd=_notify-validate'; 
foreach ($ipn_post_data as $key => $value) {  
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1){  
		$value = urlencode(stripslashes($value)); 
	} else { 
		$value = urlencode($value); 
	} 
	$req .= "&$key=$value"; 
} 

// Set up request to PayPal using cUrl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
curl_setopt($ch, CURLOPT_HEADER , 0); 
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Execute request and get response and status code
$response = curl_exec($ch);
$status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($debug) {
    $modx->log(MODX_LEVEL_ERROR,'[Request] Posted to '.$url.': '.$req);
    $modx->log(MODX_LEVEL_ERROR,'[Response] Status: '.$status.' Response: '.$response);
}
if($status == 200 && $response == 'VERIFIED') {
    // All good! Proceed...
    switch ($ipn_post_data['txn_type']) {
        case 'recurring_payment':
            // We received a recurring payment
            if ($debug) $modx->log(MODX_LEVEL_ERROR,'IPN identified as a recurring payment.');
            $transid = $ipn_post_data['txn_id'];
            $existingTransaction = $modx->getObject('smTransaction',array('reference' => $transid));
            if ($existingTransaction instanceof smTransaction) 
               return ''; // Transaction already processed.
               
            // Make sure the payment was completed.
            if ($ipn_post_data['payment_status'] != 'Completed') {
               if ($debug) $modx->log(MODX_LEVEL_ERROR,'IPN received, but payment status not confirmed. Doing nothing.');
               return '';
            }
            
            // If this is a new transaction (weehoo, money!), let's process it. First get the pp_profileid.
            $subtoken = $ipn_post_data['recurring_payment_id'];
            $subscription = $modx->getObject('smSubscription',array('pp_profileid' => $subtoken));
            if (!($subscription instanceof smSubscription)) {
               $modx->log(MODX_LEVEL_ERROR,'IPN received with profile ID '.$subtoken.', however cant find a matching subscription. Doing nothing. Transaction ID: '.$ipn_post_data['txn_id']);
               return '';
            }
            
            // If all is well let's add it as a new transaction.
            $transaction = $modx->newObject('smTransaction');
            $transaction->fromArray(array(
                'user_id' => $subscription->get('user_id'),
                'sub_id' => $subscription->get('sub_id'),
                'reference' => $ipn_post_data['txn_id'],
                'method' => 'paypal',
                'amount' => $ipn_post_data['amount']
            ));
            if (!$transaction->save()) {
                $modx->log(MODX_LEVEL_ERROR,'Failed saving transaction for subcription '.$subscription->get('sub_id').'. Transaction ID: '.$ipn_post_data['txn_id']);
                return '';
            }
            
            $result = $modx->sm->processTransaction($transaction);
            if ($result !== true) {
                $modx->log(MODX_LEVEL_ERROR,'Failed processing transaction: '.$result);
                return '';
            }
            
            return true;
            break;
        case 'recurring_payment_expired':
            if ($debug) $modx->log(MODX_LEVEL_ERROR,'IPN identified as an expired Recurring Payments Profile.');
            // A recurring payment expired - we should probably cancel the subscription.
                // Let's just check if the subscriptions have expired.

                $pp_profileid = $ipn_post_data['recurring_payment_id'];
                /* @var smSubscription $subscription */
                $subscription = $modx->getObject('smSubscription',array('pp_profileid' => $pp_profileid));
                if (!($subscription instanceof smSubscription)) {
                    $modx->log(MODX_LEVEL_ERROR,'Payment Profile cancellation received via IPN, however related subscription could not be found.');
                    return '';
                }
                $user = $subscription->get('user_id');
                // We've got a User ID here... let's just verify their user groups.
                $modx->sm->checkForExpiredSubscriptions($subscription->get('user'));


                // Send a notification email to notify them of the skipped payment
                /* @var modUser $user */
                $user = $this->modx->getObject('modUser',$subscription->get('user_id'));
                $product = $subscription->getOne('Product');
                if ($user instanceof modUser) {
                    $result = $modx->sm->sendNotificationEmail($ipn_post_data['txn_type'], $subscription, $user, $product);
                    if ($result !== true)
                        $modx->log(MODX_LEVEL_ERROR,'Error sending notification email to user #'.$user->get('id').' for IPN type '.$ipn_post_data['txn_type'].': '.$result);
                    return true;
                }
                else {
                    return 'Error fetching user to send notification about skipped payment.';
                }
            return '';
            break;
        case 'recurring_payment_skipped':
            if ($debug) $modx->log(MODX_LEVEL_ERROR,'Recurring payment skipped');
            // A payment was skipped. That doesn't mean we need to deactivate right away, but it can't hurt to check
                // if the subscriptions haven't expired yet.

                // Check if the subscription expired
                $pp_profileid = $ipn_post_data['recurring_payment_id'];
                /* @var smSubscription $subscription */
                $subscription = $modx->getObject('smSubscription',array('pp_profileid' => $pp_profileid));
                if (!($subscription instanceof smSubscription)) {
                    $modx->log(MODX_LEVEL_ERROR,'Payment Profile cancellation received via IPN, however related subscription could not be found.');
                    return '';
                }
                $user = $subscription->get('user_id');
                // We've got a User ID here... let's just verify their user groups.
                $modx->sm->checkForExpiredSubscriptions($subscription->get('user'));

                // Send a notification email to notify them of the skipped payment
                /* @var modUser $user */
                $user = $this->modx->getObject('modUser',$subscription->get('user_id'));
                $product = $subscription->getOne('Product');
                if ($user instanceof modUser) {
                    $result = $modx->sm->sendNotificationEmail($ipn_post_data['txn_type'], $subscription, $user, $product);
                    if ($result !== true)
                        $modx->log(MODX_LEVEL_ERROR,'Error sending notification email to user #'.$user->get('id').' for IPN type '.$ipn_post_data['txn_type'].': '.$result);
                    return true;
                }
                else {
                    return 'Error fetching user to send notification about skipped payment.';
                }
                
            break;
        case 'recurring_payment_profile_cancel':
        // A recurring payment was canceled by the user. We'll mark the subscription as inactive and send an email confirming the deactivation.
            $pp_profileid = $ipn_post_data['recurring_payment_id'];
                /* @var smSubscription $subscription */
                $subscription = $modx->getObject('smSubscription',array('pp_profileid' => $pp_profileid));
                if (!($subscription instanceof smSubscription)) {
                    $modx->log(MODX_LEVEL_ERROR,'Payment Profile cancellation received via IPN, however related subscription could not be found.');
                    return '';
                }

                // We've got a User ID here... let's just verify their user groups.
                $modx->sm->checkForExpiredSubscriptions($subscription->get('user_id'));

                if ($ipn_post_data['profile_status'] != 'Cancelled') {
                    $modx->log(MODX_LEVEL_ERROR,'Payment Profile cancellation received via IPN, however profile status is not "Cancelled" but '.$ipn_post_data['profile_status']);
                    return '';
                }

                // Mark as inactive to indicate the subscription has been cancelled.
                $subscription->set('active',false);
                if (!$subscription->save()) {
                    $modx->log(MODX_LEVEL_ERROR,'Error trying to deactivate subscription '.$subscription->get('sub_id'));
                }

                // Send a notification email to notify them of the skipped payment
                /* @var modUser $user */
                $user = $this->modx->getObject('modUser',$subscription->get('user_id'));
                $product = $subscription->getOne('Product');
                if ($user instanceof modUser) {
                    $result = $modx->sm->sendNotificationEmail($ipn_post_data['txn_type'], $subscription, $user, $product);
                    if ($result !== true)
                        $modx->log(MODX_LEVEL_ERROR,'Error sending notification email to user #'.$user->get('id').' for IPN type '.$ipn_post_data['txn_type'].': '.$result);
                    return true;
                }
                else {
                    return 'Error fetching user to send notification about skipped payment.';
                }

            break;

        default:
            if ($debug) $modx->log(MODX_LEVEL_ERROR,'IPN identified as other transaction type ('.$ipn_post_data['txn_type'].'), no handling built in at this point.');
            // Don't care about others
           
            return '';
            break;
    }
}
else {
    $modx->log(MODX_LEVEL_ERROR,'IPN was found to be INVALID');
    return '';
}
return '';
?>