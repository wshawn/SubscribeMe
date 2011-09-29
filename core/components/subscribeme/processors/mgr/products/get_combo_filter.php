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
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$search = $modx->getOption('query',$scriptProperties,'');

if ($modx->getOption('options',$scriptProperties,true)) {
    $results = array(
        array(
            'id' => 'current',
            'display' => $modx->lexicon('sm.combo.current')
        ),
        array(
            'id' => '',
            'display' => $modx->lexicon('sm.combo.all')
        )
    );
} else {
    $results = array();
}
$c = $modx->newQuery('smProduct');

if (strlen($search) > 1) {
    $c->where(array(
                  'name:LIKE' => "%$search%",
                  'OR:description:LIKE' => "%$search%",
              ));
}

$c->sortby($sort,$dir);

$total = $modx->getCount('smProduct',$c);

$c->limit($limit,$start);

$query = $modx->getCollection('smProduct',$c);
foreach ($query as $r) {
    $ta = $r->toArray();
    $results[] = array(
        'id' => $ta['product_id'],
        'display' => $ta['name'] . ' (' . $ta['price'] . '/' . $ta['periods'].$ta['period'] . ')'
    );
}

$returnArray = array(
    'success' => true,
    'total' => $total,
    'results' => $results
);
return $modx->toJSON($returnArray);