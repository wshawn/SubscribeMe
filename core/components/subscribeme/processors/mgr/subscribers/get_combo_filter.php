<?php

$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'Profile.fullname');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$search = $modx->getOption('query',$scriptProperties,'');

$c = $modx->newQuery('modUser');
$c->innerJoin('modUserProfile','Profile');
$c->select(array('modUser.id','Profile.fullname as fullname','modUser.username as username'));

if (strlen($search) > 1) {
    $c->where(array(
                  'Profile.fullname:LIKE' => "%$search%",
                  'OR:modUser.username:LIKE' => "%$search%",
              ));
}

$total = $modx->getCount('modUser',$c);

$c->sortby($sort,$dir);
$c->limit($limit,$start);

$results = array();
$query = $modx->getCollection('modUser',$c);
foreach ($query as $r) {
    $ta = $r->toArray();
    $results[] = array(
        'id' => $ta['id'],
        'display' => $ta['fullname'] . ' (' . $ta['username'] . ')'
    );
}

$returnArray = array(
    'success' => true,
    'total' => $total,
    'results' => $results
);
return $modx->toJSON($returnArray);