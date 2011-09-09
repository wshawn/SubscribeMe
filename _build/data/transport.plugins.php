<?php
$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','SubscribeMe');
$plugins[0]->set('description','Checks subscription states on pageviews to make sure users don\'t have access to benefits longer than paid for.');
$plugins[0]->set('plugincode', getSnippetContent($sources['elements'] . 'plugins/subscribeme.inc.php'));
$plugins[0]->set('category', 0);

$events = array();

$events['OnWebAuthentication']= $modx->newObject('modPluginEvent');
$events['OnWebAuthentication']->fromArray(array(
    'event' => 'OnWebAuthentication',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);
$events['OnWebPageInit']= $modx->newObject('modPluginEvent');
$events['OnWebPageInit']->fromArray(array(
    'event' => 'OnWebPageInit',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for SubscribeMe.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for SubscribeMe!');
}
unset($events);

return $plugins;

?>