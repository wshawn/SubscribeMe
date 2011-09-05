<?php
/* @var modX $modx
 * @var array $scriptProperties
 */
// We don't want to use this anymore.
return '';


$path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/').'classes/';
$modx->getService('sm','SubscribeMe',$path);
require_once(dirname(dirname(dirname(__FILE__))).'/classes/paypal/paypal.class.php');

$debug = $modx->getOption('debug',$scriptProperties,$modx->getOption('subscribeme.debug',null,false));
$confirmAddress = $modx->getOption('confirmAddress',$scriptProperties,true);
$tpl = $modx->getOption('tpl',$scriptProperties,'smcheckout.paymentoptions');
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,null);

/* We will need to be logged in */
if (!is_numeric($modx->user->id)) return $modx->sendUnauthorizedPage();

/* Make sure we have a token and accompanying subscription ID */
$token = $modx->getOption('token',$_REQUEST);
if (empty($token)) return 'Error, no token found.';

/* @var smPaypalToken $pt */
$pt = $modx->getObject('smPaypalToken',array('token' => $token));
if (!($pt instanceof smPaypalToken)) return 'Error, no transaction token found.';

/* @var smSubscription $subscription */
$subscription = $pt->getOne('Subscription');
if (!($subscription instanceof smSubscription)) return 'Error, no subscription found.';

/* Confirm valid transaction */
if ($subscription->get('user_id') != $modx->user->id) { return 'Please make sure you are logged in with the user that requested this subscription.'; }
if ($subscription->get('active') === true) return 'Subscription already Active.';

/* Prepare order / transaction data */
$product = $subscription->getOne('Product');
$profile = $modx->user->getOne('Profile');
$user    = array_merge($profile->toArray(),$modx->user->toArray());
$prod    = $product->toArray();
$sub     = $subscription->toArray();

/* If we passed the initial checks, let's move forward. */
/* Prepare PayPal settings */
$p = array();
$p['currency_code'] = $modx->getOption('subscribeme.currencycode',null,'USD');
$p['amount'] = $prod['price'];
$p['return_url'] = $modx->makeUrl($modx->getOption('subscribeme.paypal.completed_id'), '', '', 'full');
$p['cancel_url'] = $modx->makeUrl($modx->getOption('subscribeme.paypal.cancel_id'), '', array('transid' => $transid), 'full');
$p['fail_id'] = $modx->getOption('subscribeme.paypal.fail_id');

/* Check if we're in the sandbox or live and fetch the appropriate credentials */
$p['sandbox'] = $modx->getOption('subscribeme.paypal.sandbox',null,true);
if (!$p['sandbox']) {
    /* We're live */
    $paypal = new phpPayPal(false);
    $p['username'] = $modx->getOption('subscribeme.paypal.api_username');
    $p['password'] = $modx->getOption('subscribeme.paypal.api_password');
    $p['signature'] = $modx->getOption('subscribeme.paypal.api_signature');
} else {
    /* We're using the sandbox */
    $paypal = new phpPayPal(true);
    $p['username'] = $modx->getOption('subscribeme.paypal.sandbox_username');
    $p['password'] = $modx->getOption('subscribeme.paypal.sandbox_password');
    $p['signature'] = $modx->getOption('subscribeme.paypal.sandbox_signature');
}

$paypal->API_USERNAME = $p['username'];
$paypal->API_PASSWORD = $p['password'];
$paypal->API_SIGNATURE = $p['signature'];

$paypal->ip_address = $_SERVER['REMOTE_ADDR'];

if ($debug) var_dump(array('PayPal Settings' => $p, 'User' => $user, 'Transaction' => $trans, 'Product' => $prod,'Subscription' => $sub));

/* Start filling in some data */
$paypal->version = '57.0';
$paypal->token = $_REQUEST['token'];

/* Get the users details */
$paypal->get_express_checkout_details();
if ($debug) var_dump($paypal->Response);


/* @TODO: Split it up here to allow a form where users need to confirm their shipping address & confirm the subscription */

$paypal->return_url = $p['return_url'];
$paypal->cancel_url = $p['cancel_url'];

/* Set recurring payment information */
$start_time = strtotime(date('m/d/Y'));
$start_date = gmdate('Y-m-d\T00:00:00\Z',$start_time);
$paypal->profile_start_date = urlencode($start_date);

$paypal->invoice_number = $trans['id'];
$paypal->currency = $p['currency_code'];
$paypal->billing_start = gmdate('d-m-Y\TH:i:s\Z');// '2011-09-05T05:00:00.0000000Z'; //$start_date;//'2011-09-04 T01:09:14Z%20'; //date('Y-m-d\T+H:i:s\Z ',strtotime('+1month'));
$paypal->billing_period = ucfirst($modx->sm->periodUsable[$prod['period']]);
$paypal->billing_frequency = $prod['periods'];
$paypal->billing_amount = $prod['price'];
$paypal->billing_type = 'RecurringPayments';
$paypal->billing_type2 = 'RecurringPayments';
$paypal->billing_agreement = urlencode($prod['name'].' (#'.$sub['sub_id'].')');
$paypal->description = urlencode($prod['name'].' (#'.$sub['sub_id'].')');
$paypal->profile_reference = $sub['sub_id'];
$paypal->payer_id = $_REQUEST['PayerID'];
$paypal->tax_amount = 0.00;
$paypal->ship_amount = 0.00;
$paypal->subscriber_name = urlencode($user['fullname']);

/* Create the profile */
$paypal->create_recurring_payments_profile();

$response = $paypal->Response;
$success = false;

if (isset($response['PROFILESTATUS'])) {
    switch (strtolower($response['PROFILESTATUS'])) {
        case 'activeprofile':
            $success = true;
            /* We succesfully set up the recurring payments profile! PARTY!!!
             * Do note that that doesn't mean we got money yet - we'll need to wait for the IPN message for that.
             * We will, however, set the PROFILEID to the subscription so we can identify it and use that to fetch info.
             */
            $subscription->set('pp_profileid',$response['PROFILEID']);
                /* @todo: mark as active */
            if ($subscription->save())
                echo 'Subscription set up properly!';
            if ($debug) var_dump($response);
            break;
        
        case 'pendingprofile':
            $success = true;
            $subscription->set('pp_profileid',$response['PROFILEID']);
            if ($subscription->save())
                echo 'Subscription set up, but currently pending activation.';
            if ($debug) var_dump($response);
            break;

        default:
            echo 'Something unexpected happened.';
            if ($debug) var_dump($response);
            break;
    }
}
else {
    // Uh oh.. trouble!
    if ($debug) var_dump($response);
    echo 'Something went wrong creating recurring payments profile.';
}

?>