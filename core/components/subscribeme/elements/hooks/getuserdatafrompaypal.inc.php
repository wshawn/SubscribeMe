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
 * @var fiHooks $hook
 * @var FormIt $formit
 * @var string $path
 */
require_once($path.'classes/paypal/paypal.class.php');

$debug = $modx->getOption('debug',$scriptProperties,$modx->getOption('subscribeme.debug',null,false));

$fromPaypal = true;

/* We will need to be logged in */
if (!is_numeric($modx->user->id)) return $modx->sendUnauthorizedPage();

/* Make sure we have a token and accompanying subscription ID */
$token = $modx->getOption('token',$_REQUEST);
if (empty($token)) $fromPaypal = false;

$profile = $modx->user->getOne('Profile');
$user    = array_merge($profile->toArray(),$modx->user->toArray());

if ($fromPaypal) {
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

if ($debug) var_dump(array('PayPal Settings' => $p, 'User' => $user));

/* Start filling in some data */
$paypal->version = '57.0';
$paypal->token = $_GET['token'];

/* Get the users details */
$paypal->get_express_checkout_details();
if ($debug) var_dump($paypal->Response);

$pp = $paypal->Response;
$ppfields = array(
  'fullname' => $pp['FIRSTNAME'] . ' '. $pp['LASTNAME'],
  'address' => $pp['SHIPTOSTREET'],
  'city' => $pp['SHIPTOCITY'],
  'state' => $pp['SHIPTOSTATE'],
  'zip' => $pp['SHIPTOZIP'],
  'country' => $pp['SHIPTOCOUNTRYNAME'],
);

foreach ($ppfields as $key => $value) {
  if (!empty($value)) $user[$key] = $value;
}

$user['pp_token'] = $_REQUEST['token'];
$user['pp_payerid'] = $_REQUEST['PayerID'];

} // end of if fromPaypal

// We don't want to leak these
unset ($user['password'], $user['salt'], $user['hash_class']);

$hook->setValues($user);
return true;

?>