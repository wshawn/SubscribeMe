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

require_once (dirname(dirname(dirname(dirname(__FILE__))))).'/classes/paypal/paypal.class.php';

$ppid = $modx->getOption('reference',$scriptProperties,'');
if (empty($ppid)) return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => $modx->lexicon('sm.reference'))));

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

$paypal->transaction_id = $ppid;
$paypal->version = '57.0';
//return $modx->error->failure($paypal->generateNVPString('GetRecurringPaymentsProfileDetails'));
$paypal->get_transaction_details();
//return $modx->error->failure(print_r($paypal->Response,true));

$response = $paypal->Response;

$return = array();
foreach ($response as $key => $value) {
    if (!empty($response[$key]) && !in_array($key,array('ACK','VERSION'))) {
        $return[] = $key . ': ' . $response[$key];
    }
}

if (count($return) == 0) {
    return $modx->error->failure($modx->lexicon('sm.error.noresults'));
}

$count = count($return);
$first = ($count / 2) + 1;
$i = 0;
while ($i <= $first) {
    $col1[] = array_shift($return);
    $i++;
}

$ra = array(
    'success' => true,
    'total' => 1,
    'results' => array(array('col1' => implode("\n <br />",$col1),'col2' => implode("\n <br />",$return)))
);

return $modx->toJSON($ra);

?>