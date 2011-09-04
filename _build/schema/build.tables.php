<?php


require_once dirname(dirname(dirname(__FILE__))) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modelPath = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/').'model/';

$modx->addPackage('subscribeme',$modelPath);

$manager = $modx->getManager();

$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('html');

echo <<<STYLE
<style>
pre {
 white-space: pre-wrap;       /* css-3 */
 white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
 white-space: -pre-wrap;      /* Opera 4-6 */
 white-space: -o-pre-wrap;    /* Opera 7 */
 word-wrap: break-word;       /* Internet Explorer 5.5+ */
 width: 99%;
}
</style>
STYLE;


echo 'Starting log...';

echo '<pre style="word-wrap: ">';
$manager->createObjectContainer('smSubscription');
$manager->createObjectContainer('smProduct');
$manager->createObjectContainer('smProductPermissions');
$manager->createObjectContainer('smTransaction');
$manager->createObjectContainer('smPaypalToken');
echo '</pre>';

echo 'Done.';
?>