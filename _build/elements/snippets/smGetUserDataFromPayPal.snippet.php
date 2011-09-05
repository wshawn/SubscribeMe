<?php
$path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/');
$modx->getService('sm','SubscribeMe',$path.'classes/');

$result = include($path.'elements/hooks/getuserdatafrompaypal.inc.php');
if ($result !== true) {
  $hook->addError('data',$result);
  return false;
}
return true;
?>