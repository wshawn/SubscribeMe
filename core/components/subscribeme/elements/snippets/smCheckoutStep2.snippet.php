<?php
/* @var modX $modx
 * @var array $scriptProperties
 */

$path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/').'classes/';
$modx->getService('sm','SubscribeMe',$path);
require_once(dirname(dirname(dirname(__FILE__))).'/classes/paypal/paypal.class.php');

$debug = true;

/* We will need to be logged in */
if (!is_numeric($modx->user->id)) return $modx->sendUnauthorizedPage();

/* Make sure we have a token and accompanying transaction ID */
$token = $modx->getOption('token',$_REQUEST);
if (empty($token)) return 'Error, no token found.';

/* @var smTransactionPaypal $pt */
$pt = $modx->getObject('smTransactionPaypal',array('token' => $token));
if (!($pt instanceof smTransactionPaypal)) return 'Error, no transaction token found.';

/* @var smTransaction $transaction */
$transaction = $pt->getOne('Transaction');
if (!($transaction instanceof smTransaction)) return 'Error, no transaction found.';
$transid = $transaction->get('trans_id');

/* Confirm valid transaction */
if ($transaction->get('user_id') != $modx->user->id) { return 'Please make sure you are logged in with the user that requested this transaction.'; }
if ($transaction->get('completed') === true) return 'Transaction already completed.';

/* If we passed the initial checks, let's move forward. */
/* Prepare PayPal settings */
$p = array();
$p['currency_code'] = $modx->getOption('subscribeme.currencycode',null,'USD');
$p['amount'] = $transaction->get('amount');
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

/* Prepare order / transaction data */
$profile = $modx->user->getOne('Profile');
$user    = array_merge($profile->toArray(),$modx->user->toArray());

$subscription = $transaction->getOne('Subscription');
$product      = $subscription->getOne('Product');

$trans = $transaction->toArray();
$prod  = $product->toArray();
$sub   = $subscription->toArray();

if ($debug) var_dump(array('PayPal Settings' => $p, 'User' => $user, 'Transaction' => $trans, 'Product' => $prod,'Subscription' => $sub));

/* Start filling in some data */
$paypal->version = '57.0';
$paypal->token = $token;
$paypal->invoice_number = $trans['id'];
$paypal->currency = $p['currency_code'];
$paypal->amount_total = $p['amount'];
$paypal->email = $user['email'];
$paypal->shipping_name = $user['fullname'];
$paypal->shipping_address1 = $user['address'];
$paypal->shipping_postal_code = $user['zip'];
$paypal->shipping_state = $user['state'];
$paypal->shipping_city = $user['city'];
$paypal->shipping_country_name = $user['country'];

$paypal->return_url = $p['return_url'];
$paypal->cancel_url = $p['cancel_url'];

/*
 * 'description' 				=> array('name' => 'DESC',					'required' => 'yes'), // You must match the billing agreement var in set Express checkout
    'currency'					=> array('name' => 'CURRENCYCODE',			'required' => 'yes'),
    'payment_type' 				=> array('name' => 'PAYMENTACTION',			'required' => 'yes'),
    'billing_type' 				=> array('name' => 'L_BILLINGTYPE0',		'required' => 'yes'),
    'billing_agreement' 		=> array('name' => 'L_BILLINGAGREEMENTDESCRIPTION0', 'required' => 'yes'),
    'profile_start_date'		=> array('name' => 'PROFILESTARTDATE',		'required' => 'yes'),
    'billing_period' 			=> array('name' => 'BILLINGPERIOD',			'required' => 'yes'), // Day Week Month SemiMonth Year
    'billing_frequency' 		=> array('name' => 'BILLINGFREQUENCY',		'required' => 'yes'),
    'billing_amount' 			=> array('name' => 'AMT',					'required' => 'yes'),
    'tax_amount' 				=> array('name' => 'TAXAMT',				'required' => 'yes'),
    'ship_amount' 				=> array('name' => 'SHIPPINGAMT',			'required' => 'yes'),
    'inital_amount' 			=> array('name' => 'INITAMT',				'required' => 'no'),
    'failed_inital_amount' 		=> array('name' => 'FAILEDINITAMTACTION',	'required' => 'no'),
    'billing_total_cycles' 		=> array('name' => 'TOTALBILLINGCYCLES',	'required' => 'no'),
    'trial_billing_period' 		=> array('name' => 'TRIALBILLINGPERIOD',	'required' => 'no'),
    'trial_billing_frequency' 	=> array('name' => 'TRIALBILLINGFREQUENCY',	'required' => 'no'),
    'trial_amount' 				=> array('name' => 'TRIALAMT', 				'required' => 'no'),
    'trial_billing_cycle' 		=> array('name' => 'TRIALTOTALBILLINGCYCLES', 'required' => 'no'),
    'max_failed_attempts'  		=> array('name' => 'MAXFAILEDPAYMENTS', 	'required' => 'no'),
    'auto_bill_amt'  			=> array('name' => 'AUTOBILLOUTAMT', 		'required' => 'no')
 */

$start_time = strtotime(date('m/d/Y'));
$start_date = gmdate('Y-m-d\T00:00:00\Z',$start_time);

$paypal->profile_start_date = gmdate('d-m-Y\TH:i:s\Z');// '2011-09-05T05:00:00.0000000Z'; //$start_date;//'2011-09-04 T01:09:14Z%20'; //date('Y-m-d\T+H:i:s\Z ',strtotime('+1month'));
$paypal->billing_start = gmdate('d-m-Y\TH:i:s\Z');// '2011-09-05T05:00:00.0000000Z'; //$start_date;//'2011-09-04 T01:09:14Z%20'; //date('Y-m-d\T+H:i:s\Z ',strtotime('+1month'));
$periodUsable = array('D' => 'Day', 'W' => 'Week', 'M' => 'Month', 'Y' => 'Year');
$paypal->billing_period = $periodUsable[$prod['period']];
$paypal->billing_frequency = $prod['periods'];
$paypal->billing_amount = $prod['price'];
$paypal->billing_type = 'RecurringPayments';
$paypal->billing_agreement = 'Subscription #'.$sub['sub_id'].' for '.$prod['name'];
$paypal->description = 'Subscription #'.$sub['sub_id'].' for '.$prod['name'];
$paypal->profile_reference = $user['id'];
$paypal->payer_id = $_REQUEST['PayerID'];
$paypal->tax_amount = 5.12;
$paypal->ship_amount = 1.32;
$paypal->subscriber_name = $user['fullname'];

$country_array = preg_grep('/^'.$user['country'].'/', $paypal->countries);
foreach ($country_array as $key => $value) {
    $ta[] = $key;
}
$paypal->shipping_country_code = $ta[0];
//https://api-3t.sandbox.paypal.com/nvp?USER=spiral_1241371009_biz_api1.gmail.com&PWD=1241371016&SIGNATURE=ABUaThl3V14Xe5aZuHM17VrRhoaQAgCa6gxyRuUEJDM41CW9AmXbhtth&VERSION=53.0&METHOD=CreateRecurringPaymentsProfile&TOTALBILLINGCYCLES=2&BILLINGPERIOD=Month&COUNTRYCODE=US&CREDITCARDTYPE=Visa&ZIP=95131&FIRSTNAME=John&EXPDATE=012010&PROFILESTARTDATE=2009-05-022T05:00:00.0000000Z&CITY=Omaha&AUTOBILLOUTAMT=NoAutoBill&STREET=123+Fake+St&ACCT=4560151348340840&DESC=Test&LASTNAME=Doe&AMT=1.00&BILLINGFREQUENCY=1&STATE=CA&CVV2=962&INITAMT=1.00&FAILEDINITAMTACTION=CancelOnFailure&PAYMENTACTION=sale&CURRENCYCODE=USD

//$paypal->get_express_checkout_details();

$paypal->create_recurring_payments_profile();

$response = $paypal->Response;
$success = false;

if (isset($response['PAYMENTSTATUS'])) {
    switch (strtolower($response['PAYMENTSTATUS'])) {
        case 'completed':
            $success = true;
            echo 'Completed';
            var_dump($response);
            break;
        case 'pending':
            $success = true;
            echo 'Pending';
            var_dump($response);
            break;
        default:
            echo 'Dunno';
            var_dump($response);
            break;
    }
}
else {
    // Uh oh.. trouble!
    var_dump($response);
}

?>