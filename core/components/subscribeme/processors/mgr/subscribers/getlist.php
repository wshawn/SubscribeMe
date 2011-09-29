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
$sort = $modx->getOption('sort',$scriptProperties,'Profile.fullname');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$search = $modx->getOption('query',$scriptProperties,null);

$subfilter = $modx->getOption('product',$scriptProperties,null);

$c = $modx->newQuery('modUser');


$c->select(
    array(
        'modUser.*',
        'Profile.*',
    )
);

$c->innerJoin('modUserProfile','Profile');

if (!empty($search)) {
    $c->where(array(
                  'Profile.fullname:LIKE' => "%$search%",
                  'OR:Profile.email:like' => "%$search%",
                  'OR:modUser.username:like' => "%$search%",
                  'OR:modUser.id:LIKE' => "%$search%",
              ));
}

if (is_numeric($subfilter)) {
    $c->leftJoin('smSubscription','S','modUser.id = S.user_id');
    $c->where(array(
                   'S.product_id' => $subfilter,
                   'AND:S.expires:>' => date('Y-m-d H:i:s',time())
              ));
}
elseif ($subfilter == 'current') {
    $c->leftJoin('smSubscription','S','modUser.id = S.user_id');
    $c->where(array(
                   'S.product_id:>' => 0,
                   'AND:S.expires:>' => date('Y-m-d H:i:s',time())
              ));
}

$total = $modx->getCount('modUser',$c);

$c->sortby($sort,$dir);

$results = array();
$qr = $modx->getIterator('modUser',$c);
foreach ($qr as $idx => $r) {
    if (defined('returnasarray') && isset($fields))
        $ta = $r->get($fields);
    else
        $ta = $r->get(array('id','active','fullname','email','username'));

    $subs = $modx->getCollection(
        'smSubscription',
        array(
             'user_id' => $ta['id'],
             'expires:>' => date('Y-m-d H:i:s')
        )
    );

    $ta['subscriptions'] = array();
    foreach ($subs as $s) {
        $st = $s->getOne('Product');
        if ($st instanceof smProduct) {
            $ta['subscriptions'][] = $st->get('name').
                                     ' ('.
                                     date($modx->config['manager_date_format'],strtotime($s->get('start'))).
                                     ' - '.
                                     date($modx->config['manager_date_format'],strtotime($s->get('expires'))).
                                     ')';
        }
    }

    if (count($ta['subscriptions']) > 0) $ta['subscriptions'] = implode(",<br /> ",$ta['subscriptions']); //@todo Make seperator configurable?
    else $ta['subscriptions'] = '';

    $results[] = $ta;
}

if (defined('returnasarray'))
    return $results;

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