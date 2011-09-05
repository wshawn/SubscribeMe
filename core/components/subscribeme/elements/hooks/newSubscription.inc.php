<?php
/* @var modX $modx
 * @var fiHooks $hook
 * @var FormIt $formit
 */
if (!is_numeric($modx->user->id)) { return $modx->sendUnauthorizedPage(); }

$path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/').'classes/';
$modx->getService('sm','SubscribeMe',$path);

$prod = $hook->getValue('product');

$product = $modx->getObject('smProduct',$prod);
if (!($product instanceof smProduct)) {
  $modx->log(1,'There is no product with ID '.$prod);
  return 'There is no product with ID '.$prod;
}

/* @var smSubscription $sub */
$sub = $modx->newObject('smSubscription');
$sub->fromArray(array(
  'user_id' => $modx->user->id,
  'product_id' => $prod,
  'active' => false
));
if ($sub->save()) {
  $subid = $sub->get('sub_id');
  $url = $modx->makeUrl($formit->config['optionsResource'], '', array('subid' => $subid));
  return $modx->sendRedirect($url);
}
else {
  return 'Error saving subscription.';
}

?>