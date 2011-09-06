<?php

require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/');
require_once $corePath.'classes/subscribeme.class.php';
$modx->sm = new SubscribeMe($modx);

$ipn_post_data = array_merge($_POST,$_GET);
$modx->log(1,'tracking');
var_dump($modx->log(1,print_r(array_merge($_POST,$_GET),true)));
// Choose url
if(array_key_exists('test_ipn', $ipn_post_data) && 1 === (int) $ipn_post_data['test_ipn'])
    $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
else
    $url = 'https://www.paypal.com/cgi-bin/webscr';

// Set up request to PayPal
$request = curl_init();
curl_setopt_array($request, array
(
    CURLOPT_URL => $url,
    CURLOPT_POST => TRUE,
    CURLOPT_POSTFIELDS => http_build_query(array('cmd' => '_notify-validate') + $ipn_post_data),
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HEADER => FALSE,
    CURLOPT_SSL_VERIFYPEER => TRUE,
    CURLOPT_CAINFO => 'cacert.pem',
));

// Execute request and get response and status code
$response = curl_exec($request);
$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

// Close connection
curl_close($request);

if($status == 200 && $response == 'VERIFIED') {
    // All good! Proceed...
    switch ($ipn_post_data['txn_type']) {
        case 'recurring_payment':
            // We received a recurring payment
            $transid = $ipn_post_data['txn_id'];
            $existingTransaction = $modx->getObject('smTransaction',array('reference' => $transid));
            if ($existingTransaction instanceof smTransaction) 
               return ''; // Transaction already processed.
               
            // Make sure the payment was completed.
            if ($ipn_post_data['payment_status'] != 'Completed') {
               $modx->log(1,'IPN received, but payment status not confirmed. '.print_r($ipn_post_data,true));
               return '';
            }
            
            // If this is a new transaction (weehoo, money!), let's process it. First get the pp_profileid.
            $subtoken = $ipn_post_data['recurring_payment_id'];
            $subscription = $modx->getObject('smSubscription',array('pp_profileid' => $subtoken));
            if (!($subscription instanceof smSubscription)) {
               $modx->log(1,'IPN received with profile ID '.$subtoken.', however cant find a matching subscription.');
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
                $modx->log(1,'Failed saving transaction for sub '.$subscription->get('sub_id').', transaction '.$ipn_post_data['txn_id']);
                return '';
            }
            
            $result = $modx->sm->processTransaction($transaction);
            if ($result !== true) {
                $modx->log(1,'Failed processing transaction: '.$result);
                return '';
            }
            
            return true;

            break;
        case 'recurring_payment_expired':
            // A recurring payment expired - we should probably cancel the subscription.

            return '';
            break;
        case 'recurring_payment_skipped':
            // Recurring payment skipped; it will be retried up to a total of 3 times, 5 days apart
            
            return '';
            break;

        default:
            // Don't care about others
            $modx->log(1,'IPN received: '.print_r($ipn_post_data,true));
            
            return '';
            break;
    }
}
else {
    $modx->log(1,'Invalid IPN message received: '.print_r($ipn_post_data,true));
    return '';
}
return '';

?>