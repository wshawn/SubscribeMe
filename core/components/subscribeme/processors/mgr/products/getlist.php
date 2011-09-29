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
$sort = $modx->getOption('sort',$scriptProperties,'sortorder');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$search = $modx->getOption('query',$scriptProperties,null);
$subscriber = $modx->getOption('subscriber',$scriptProperties,null);
$paid = $modx->getOption('paid',$scriptProperties,null);

$c = $modx->newQuery('smProduct');

if ($search) {
    $c->where(
        array(
            'name:LIKE' => "%$search%",
            'OR:description:LIKE' => "%$search%",
        )
    );
    if (is_numeric($search))
        $c->orCondition(array('product_id' => $search));
}

$matches = $modx->getCount('smProduct',$c);

$c->sortby($sort,$dir);
$c->sortby('product_id','desc');
$c->limit($limit,$start);

$results = array();

$r = $modx->getCollection('smProduct',$c);
foreach ($r as $rs) {
    $ta = $rs->toArray();
    $perms = $rs->getMany('Permissions');
    $ta['permissions'] = array();
    foreach ($perms as $p) {
        if ($p instanceof smProductPermissions) {
            $ug = $p->getOne('UserGroup');
            $role = $p->getOne('Role');
            if (($ug instanceof modUserGroup) && ($role instanceof modUserGroupRole))
                $ta['permissions'][] = $ug->get('name') . ' (' . $role->get('name') . ')';
        }
    }
    $ta['permissions'] = implode(', ',$ta['permissions']);
    $results[] = $ta;
}

if (count($results) == 0) {
    return $modx->error->failure($modx->lexicon('sm.error.noresults')); 
}
$ra = array(
    'success' => true,
    'total' => $matches,
    'results' => $results
);

return $modx->toJSON($ra);

?>