<?php
/* @var modX $modx
 * @var array $scriptProperties
 */

$path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/');
$modx->getService('sm','SubscribeMe',$path.'classes/');

return include($path.'elements/hooks/cancelpaypalprofilepre.inc.php');

?>