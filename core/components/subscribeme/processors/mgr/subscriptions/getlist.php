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

$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'start');
$dir = $modx->getOption('dir',$scriptProperties,'desc');

$user = $modx->getOption('subscriber',$scriptProperties,null);
$subfilter = $modx->getOption('product',$scriptProperties,null);
$results = array();

$c = $modx->newQuery('smSubscription');

if (is_numeric($user))
    $c->where(array(
                  'user_id' => $user
              ));
$c->innerJoin('smProduct','Product');
$c->innerJoin('modUserProfile','Profile','smSubscription.user_id = Profile.internalKey');
$c->select(
    array(
        'smSubscription.*',
        'Product.name as product',
        'Product.price as product_price',
        'Product.periods as product_periods',
        'Product.period as product_period',
        'Profile.fullname as user',
    )
);

$total = $modx->getCount('smSubscription',$c);

$c->sortby($sort,$dir);
$c->limit($limit,$start);

$cs = $modx->getOption('subscribeme.currencysign',null,'$');
$collection = $modx->getCollection('smSubscription',$c);
foreach ($collection as $r) {
    $ta = $r->toArray();
    $ta['start'] = ($ta['start'] == '0000-00-00 00:00:00') ? '' : date($modx->config['manager_date_format'].' '.$modx->config['manager_time_format'],strtotime($ta['start']));
    $ta['expires'] = ($ta['expires'] == '0000-00-00 00:00:00') ? '' : date($modx->config['manager_date_format'].' '.$modx->config['manager_time_format'],strtotime($ta['expires']));
    $ta['product_price'] = $cs.$ta['product_price'];
    $results[] = $ta;
}

if (count($results) == 0) {
    return $modx->error->failure($modx->lexicon('sm.error.noresults'));
}
$ra = array(
    'success' => true,
    'total' => $total,
    'results' => $results
);

return $modx->toJSON($ra);

?>


?>