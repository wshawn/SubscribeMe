<?php
/* @var modX $modx
 */

if ($modx->user->id > 0) {
  $path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/');
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
}