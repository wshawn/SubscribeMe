<?php
/* @var modX $modx
 * @var string $path
 */

$e = $modx->event;
$modx->getService('sm','SubscribeMe',$path.'classes/');

if ($modx->sm->config['debug']) $modx->log(1,'Fired SubscribeMe plugin on event: '.$e->name.' for user '.$modx->user->id);

switch ($e->name) {
    case 'OnWebPageInit':
        $modx->sm->checkForExpiredSubscriptions($modx->user->id);
        break;
    case 'OnWebAuthentication':
        $modx->sm->checkForExpiredSubscriptions($user->get('id'));
        break;
}


?>