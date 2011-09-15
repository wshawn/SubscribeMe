<?php
/* @var string $path
 * @var modX $modx
 * @var fiHooks $hook
 * @var FormIt $formit
 */
$scriptProperties = array_merge($modx->config,$hook->formit->config,$hook->getValues());
require_once($path.'classes/paypal/paypal.class.php');

/* We will need to be logged in */
if (!is_numeric($modx->user->id)) return $modx->sendUnauthorizedPage();


$subid = $modx->getOption('sub_id',$scriptProperties,null);
if (!$subid)
    return $modx->lexicon('sm.error.notspecified',array('what' => $modx->lexicon('sm.subscription').' '.$modx->lexicon('id')));
/* @var smSubscription $sub */
$sub = $modx->getObject('smSubscription',$subid);
if (!($sub instanceof smSubscription))
    return $modx->lexicon('sm.error.invalidobject');

if ($sub->get('user_id') != $modx->user->id)
    return $modx->lexicon('sm.error.invalidobject');

$ppid = $sub->get('pp_profileid');
if (empty($ppid))
    return $modx->lexicon('sm.error.notspecified',array('what' => $modx->lexicon('sm.pp_profileid')));

$successUrl = $modx->makeUrl($hook->formit->config['successResource']);

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
//$modx->log(MODX_LEVEL_ERROR,$paypal->generateNVPString('GetRecurringPaymentsProfileDetails'));
$paypal->get_recurring_payments_profile_details();
//return $modx->error->failure(print_r($paypal->Response,true));
if ((strtolower($paypal->Response['STATUS']) != 'active') || (strtolower($paypal->Response['STATUS']) != 'active'))
    return $modx->lexicon('sm.error.cancelsubscription.notactive',array('status' => $paypal->Response['STATUS']));

$paypal->profile_id = $ppid;
$paypal->version = '57.0';
$paypal->action = 'Cancel';
$paypal->note = $modx->lexicon('sm.notification.admincancelledsubscription');

//$modx->log(MODX_LEVEL_ERROR,$paypal->generateNVPString('ManageRecurringPaymentsProfileStatus'));
$paypal->manage_recurring_payments_profile_status();
//return $modx->error->failure(print_r($paypal->Response,true));

if (strtolower($paypal->Response['ACK']) == 'success') {
    $sub->set('active',false);
    if (!$sub->save())
        return $modx->lexicon('sm.error.sendmailfailed');


    // Send a notification email to notify them of the skipped payment
    /* @var modUser $user */
    $user = $modx->getObject('modUser',$sub->get('user_id'));
    $product = $sub->getOne('Product');
    if ($user instanceof modUser) {
        $result = $modx->sm->sendNotificationEmail('recurring_payment_cancelledbyadmin', $sub, $user, $product);
        if ($result !== true)
            $modx->log(MODX_LEVEL_ERROR,'Error sending notification email to user #'.$user->get('id').' for IPN type '.$ipn_post_data['txn_type'].': '.$result);
        return true;
    }
    else {
        return $modx->lexicon('sm.error.invalidobject');
    }

}

?>