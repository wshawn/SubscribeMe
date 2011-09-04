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

/* Make sure we have a transaction ID */
$transid = (int)$modx->getOption('transid',$_REQUEST);
if (!is_numeric($transid) || empty($transid)) return 'No transaction found.';

/* Fetch transaction */
/* @var smTransaction $transaction */
$transaction = $modx->getObject('smTransaction',$transid);
if (!($transaction instanceof smTransaction)) return 'Unable to find the transaction.';
if ($transaction->get('user_id') != $modx->user->id) { return 'Please make sure you are logged in with the user that requested this transaction.'; }
if ($transaction->get('completed') === true) return 'Transaction already completed.';
if (($transaction->get('method') == 'complimentary') || ($transaction->get('amount') == 0.00)) return 'You don\'t have to pay a free transaction.';

/* If we passed the initial checks, let's move forward. */
/* Prepare PayPal settings */
$p = array();
$p['currency_code'] = $modx->getOption('subscribeme.currencycode',null,'USD');
$p['amount'] = $transaction->get('amount');
$p['return_url'] = $modx->makeUrl($modx->getOption('subscribeme.paypal.return_id'), '', '', 'full');
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
$paypal->version = '56.0';
$paypal->invoice_number = $trans['id'];
$paypal->currency_code = $p['currency_code'];
$paypal->amount_total = $p['amount']; // Recurring payment, so 0: https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_r_SetExpressCheckout
$paypal->email = $user['email'];
$paypal->shipping_name = $user['fullname'];
$paypal->shipping_address1 = $user['address'];
$paypal->shipping_postal_code = $user['zip'];
$paypal->shipping_state = $user['state'];
$paypal->shipping_city = $user['city'];
$paypal->shipping_country_name = $user['country'];
$paypal->description = 'Subscription #'.$sub['sub_id'].' for '.$prod['name'];
$paypal->billing_type = 'RecurringPayments';
$paypal->billing_agreement = 'Subscription #'.$sub['sub_id'].' for '.$prod['name'];

$paypal->return_url = $p['return_url'];
$paypal->cancel_url = $p['cancel_url'];

/* Set up a token with express checkout */
$paypal->set_express_checkout();

if (!$paypal->_error) {
    $_SESSION['token'] = $paypal->token;


    /* Save token to the database to use it in retrieving the transaction */
    /* @var smTransactionPaypal $tt */
    $tt = $modx->newObject('smTransactionPaypal');
    $tt->fromArray(
        array(
            'trans_id' => $transid,
            'token' => $paypal->token,
        )
    );
    if (!$tt->save())
        return $modx->sendRedirect($modx->makeUrl($p['fail_id'],'',array('transid' => $transid,'errorcode' => 'PPTOKEN', 'errormsg' => 'Error processing PayPal token.')));

    /* When token was saved, redirect to the checkout. */
    $paypal->set_express_checkout_successful_redirect();
    //return $paypal->token;
} else {
    $modx->log(modX::LOG_LEVEL_ERROR, $paypal->_error_code.': '.$paypal->_error_long_message);
    return $modx->sendRedirect($modx->makeUrl($p['fail_id'],'',array('transid' => $transid,'errorcode' => $paypal->_error_code, 'errormsg' => $paypal->_error_long_message)));
}


?>