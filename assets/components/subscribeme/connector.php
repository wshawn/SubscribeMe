<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/');
require_once $corePath.'classes/subscribeme.class.php';
$modx->sm = new SubscribeMe($modx);
$modx->sm->initialize('mgr');

/* handle request */
$path = $modx->sm->config['processors_path'];
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
?>