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