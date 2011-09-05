<?php
require_once (dirname(dirname(dirname(dirname(__FILE__))))).'/classes/paypal/paypal.class.php';

$ppid = $modx->getOption('profileid',$scriptProperties,'');
if (empty($ppid)) return $modx->error->failure('No profile ID specified');

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

$paypal->profile_id = $ppid;
$paypal->version = '57.0';
//return $modx->error->failure($paypal->generateNVPString('GetRecurringPaymentsProfileDetails'));
$paypal->get_recurring_payments_profile_details();
//return $modx->error->failure(print_r($paypal->Response,true));

$response = $paypal->Response;

$return = array();
foreach ($response as $key => $value) {
    if (!empty($response[$key]) && !in_array($key,array('ACK','VERSION'))) {
        $return[] = $key . ': ' . $response[$key];
    }
}

if (count($return) == 0) {
    return $modx->error->failure('Unable to retrieve information.'); //@todo Lexiconify
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