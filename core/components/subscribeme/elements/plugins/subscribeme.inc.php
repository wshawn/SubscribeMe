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