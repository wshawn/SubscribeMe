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
 */
$path = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/').'classes/';
$modx->getService('sm','SubscribeMe',$path);

$defaults = array(
    'start' => 0,
    'limit' => 5,
    'sort' => 'sortorder',
    'sortdir' => 'asc',
    'tplOuter' => 'smListProducts.outer',
    'tplRow' => 'smListProducts.row',
    'activeOnly' => true,
    'separator' => "\n",
    'toPlaceholder' => '',
    'debug' => false,
);

$config = array_merge($defaults,$scriptProperties);
if ($config['debug']) var_dump($config);

$c = $modx->newQuery('smProduct');

if ($config['activeOnly'] > 0)
    $c->where(array('active' => true));

$count = $modx->getCount('smProduct',$c);
if ($config['debug']) echo '<pre>Result count: '.$count.'</pre>';

$c->sortby($config['sort'],$config['sortdir']);
$c->limit($config['limit'],$config['start']);

if ($config['debug']) {
    $c->prepare();
    echo '<pre>Query: '.$c->toSQL().'</pre>';
}

$results = array();
$currencySign = $modx->getOption('subscribeme.currencysign',null,'&euro;');
$currencyCode = $modx->getOption('subscribeme.currencycode',null,'EUR');
$collection = $modx->getCollection('smProduct',$c);
foreach ($collection as $obj) {
    if ($obj instanceof smProduct) {
        $ta = $obj->toArray();
        $periodLexicon = 'sm.combo.'.$ta['period'].(($ta['periods'] > 1) ? 's' : '');
        $ta['period'] = $modx->lexicon($periodLexicon);
        $ta['count'] = $count;
        $ta['amount_total'] = $ta['price'] + $ta['amount_shipping'] + $ta['amount_vat'];
        $ta['currency'] = $currencySign; 
        $ta['currencycode'] = $currencyCode;  
        $results[] = $modx->sm->getChunk($config['tplRow'],$ta);
        if ($config['debug'])
            echo '<pre>Result: '.print_r($ta,true).'</pre>';
    }
}

$results = implode($config['separator'],$results);

$results = $modx->sm->getChunk($config['tplOuter'],array('products' => $results));

if (!empty($config['toPlaceholder'])) {
    $modx->setPlaceholder('sm.products',$results);
    return '';
}

return $results;



?>