<?php
/**
 * SubscribeMe
 *
 * Copyright 2011 by Mark Hamstra <business@markhamstra.nl>
 *
 * This file is part of SubscribeMe, a subscriptions management extra for MODX Revolution
 *
 * SubscribeMe is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * SubscribeMe is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SubscribeMe; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
*/

/* @var modX $modx
 * @var array $scriptProperties
 */

$path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/').'classes/';
$modx->getService('sm','SubscribeMe',$path);
require_once(dirname(dirname(dirname(__FILE__))).'/classes/paypal/paypal.class.php');

$debug = $modx->getOption('debug',$scriptProperties,$modx->getOption('subscribeme.debug',null,false));
$redirect = $modx->getOption('redirect',$scriptProperties,false);
$tpl = $modx->getOption('tpl',$scriptProperties,'smcheckout.paymentoptions');
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,null);

/* We will need to be logged in */
if (!is_numeric($modx->user->id)) return $modx->sendUnauthorizedPage();

/* Make sure we have a transaction ID */
$subid = (int)$modx->getOption('subid',$_REQUEST);
if (!is_numeric($subid) || empty($subid)) return 'No subscription found.';

/* Fetch subscription  */
/* @var smSubscription $subscription */
$subscription = $modx->getObject('smSubscription',$subid);
if (!($subscription instanceof smSubscription)) return 'Unable to find the subscriptions.';
if ($subscription->get('user_id') != $modx->user->id) { return 'Please make sure you are logged in with the user that requested this subscription.'; }
if ($subscription->get('active') === true) return 'Subscription already activated.';

$product = $subscription->getOne('Product');

/* If we passed the initial checks, let's move forward. */
/* Prepare PayPal settings */
$p = array();
$p['currency_code'] = $modx->getOption('subscribeme.currencycode',null,'USD');
$p['amount'] = $product->get('price');

$p['return_url'] = $modx->makeUrl($modx->getOption('return_id',$scriptProperties,$modx->getOption('subscribeme.paypal.return_id')), '', '', 'full');
$p['cancel_url'] = $modx->makeUrl($modx->getOption('cancel_id',$scriptProperties,$modx->getOption('subscribeme.paypal.cancel_id')), '', array('transid' => $subid), 'full');
$p['fail_id'] = $modx->getOption('fail_id',$scriptProperties,$modx->getOption('subscribeme.paypal.fail_id'));

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

$prod  = $product->toArray();
$sub   = $subscription->toArray();

if ($debug) var_dump(array('PayPal Settings' => $p, 'User' => $user, 'Product' => $prod,'Subscription' => $sub));

/* Start filling in some data */
$paypal->version = '57.0';
$paypal->invoice_number = $sub['sub_id'];
$paypal->currency_code = $p['currency_code'];
$paypal->amount_total = $p['amount'];
$paypal->tax_amount = $prod['amount_vat'];
$paypal->ship_amount = $prod['amount_shipping'];
$paypal->email = $user['email'];
/*$paypal->shipping_name = $user['fullname'];
$paypal->shipping_address1 = $user['address'];
$paypal->shipping_postal_code = $user['zip'];
$paypal->shipping_state = $user['state'];
$paypal->shipping_city = $user['city'];
$paypal->shipping_country_name = $user['country'];*/
$paypal->description = urlencode($prod['name'] . ' (#' . $sub['sub_id'] . ')' );
$paypal->billing_type = 'RecurringPayments';
$paypal->billing_type2 = 'RecurringPayments';
$paypal->billing_agreement = urlencode($prod['name'].' (#'.$sub['sub_id'].')');
$paypal->billing_agreement2 = urlencode($prod['name'].' (#'.$sub['sub_id'].')');


$paypal->return_url = $p['return_url'];
$paypal->cancel_url = $p['cancel_url'];

/* Set up a token with express checkout */
$paypal->set_express_checkout();

if (!$paypal->_error) {
    $_SESSION['token'] = $paypal->token;

    /* Save token to the database to use it in retrieving the transaction at a later point */
    /* @var smPaypalToken $tt */
    $tt = $modx->newObject('smPaypalToken');
    $tt->fromArray(
        array(
            'sub_id' => $subid,
            'token' => $paypal->token,
        )
    );
    if (!$tt->save()) {
        $modx->log(modX::LOG_LEVEL_ERROR,'Unable to save paypal token to database.');
        return $modx->sendRedirect($modx->makeUrl($p['fail_id'],'',array('transid' => $subid,'errorcode' => 'PPTOKEN', 'errormsg' => 'Error processing PayPal token.')));
    }
    
    /* When token was saved, create the URL and pass that to the chunk for display. */
    $paypalurl = $paypal->PAYPAL_URL.$paypal->token;

    if ($redirect && !$debug) {
        return $modx->sendRedirect($paypalurl);
    } elseif ($redirect && $debug) {
        return 'Redirect prevented due to debug mode. Use this link to continue: <a href="'.$paypalurl.'">Checkout with PayPal</a>';
    } else {
        $placeholders = array_merge($product->toArray(),array('paypalurl' => $paypalurl));
        $readablePeriod = ($placeholders['periods'] > 1) ? $placeholders['periods'].' '.$modx->sm->periodUsable[$placeholders['period']].'s' : $modx->sm->periodUsable[$placeholders['period']];
        $placeholders['period'] = $readablePeriod;

        $output = $modx->sm->getChunk($tpl,$placeholders);
        $modx->toPlaceholders($placeholders);

        if (!empty($toPlaceholder)) {
            $modx->toPlaceholder('paymentoptions',$output);
            return '';
        }
        return $output;
    }

} else {
    $modx->log(modX::LOG_LEVEL_ERROR, $paypal->_error_code.': '.$paypal->_error_long_message);
    return $modx->sendRedirect($modx->makeUrl($p['fail_id'],'',array('transid' => $subid,'errorcode' => $paypal->_error_code, 'errormsg' => $paypal->_error_long_message)));
}


?>