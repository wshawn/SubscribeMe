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
 * @var array $scriptProperties
 **/
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'expires');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');

$tplOuter = $modx->getOption('tplOuter',$scriptProperties,'smGetSubsOuter');
$tplRow = $modx->getOption('tplRow',$scriptProperties,'smGetSubsRow');
$activeOnly = $modx->getOption('activeOnly',$scriptProperties,false);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
$rowSeparator = $modx->getOption('rowSeparator',$scriptProperties,"\n");

if (!($modx->user instanceof modUser))
    return '';

$path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/');
$modx->getService('sm','SubscribeMe',$path.'classes/');

$c = $modx->newQuery('smSubscription');
$c->where(array('user_id' => $modx->user->get('id')));
if ($activeOnly) {
    $c->where(
        array(
             'expires:>' => date('Y-m-d H:i:s')
        )
    );
}

$c->innerJoin('smProduct','Product');

$c->select(array('smSubscription.*','Product.name as product_name','Product.description as product_description'));

$total = $modx->getCount('smSubscription',$c);

$c->sortby($sort,$dir);
$c->limit($limit,$start);

$collection = $modx->getCollection('smSubscription',$c);
//return '';
$results = array();
foreach ($collection as $res) {
    /* @var smSubscription $res */
    if ($res instanceof smSubscription) {
        $ta = $res->toArray();
        $ta['active'] = ($ta['active']) ? 1 : 0;
        $ta['expired'] = ((strtotime($ta['expires']) < time()) || ($ta['expires'] == '0000-00-00 00:00:00')) ? 1 : 0;
        $results[] = $modx->sm->getChunk($tplRow,$ta);
    }
}

$results = implode($rowSeparator,$results);
$results = $modx->sm->getChunk($tplOuter,array('rows' => $results));

if (strlen($toPlaceholder) > 0) {
    $modx->toPlaceholder($toPlaceholder,$results);
    return '';
}
return $results;
?>