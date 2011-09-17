<?php

if (!$modx->user->id)
    return $modx->sendUnauthorizedPage();

$sub_id = $hook->getValue('sub_id');
if (empty($sub_id))
    $sub_id = (int)$_REQUEST['sub_id'];

$msid = $hook->formit->config['manageSubscriptionsID'];

if ($sub_id < 1) {
    $hook->addError('subscription','Invalid Subscription ID.');
    if ($msid) return $modx->sendRedirect($modx->makeUrl($msid));
    return false;
}

$subscription = $modx->getObject('smSubscription',$sub_id);
if (!($subscription instanceof smSubscription)) {
    $hook->addError('subscription','Invalid Subscription.');
    if ($msid) return $modx->sendRedirect($modx->makeUrl($msid));
    return false;
}

if ($subscription->get('user_id') != $modx->user->id) {
    $hook->addError('subscription','Invalid Subscription.');
    if ($msid) return $modx->sendRedirect($modx->makeUrl($msid));
    return false;
}

$fields = $subscription->toArray();
$product = $subscription->getOne('Product');
if ($product instanceof smProduct)
    $fields = array_merge($fields,$product->toArray());

$hook->setValues($fields);
return true;
?>