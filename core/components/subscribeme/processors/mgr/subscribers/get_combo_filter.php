<?php

$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'Profile.fullname');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$search = $modx->getOption('query',$scriptProperties,'');

$c = $modx->newQuery('modUser');
$c->innerJoin('modUserProfile','Profile');
$c->select(array('modUser.id','Profile.fullname','modUser.username'));

if (strlen($search) > 1) {
    $c->where(array(
                  'Profile.fullname:LIKE' => "%$search%",
                  'OR:modUser.username:LIKE' => "%$search%",
              ));
}

$c->sortby($sort,$dir);

$total = $modx->getCount('modUser',$c);

$c->limit($limit,$start);

$query = $modx->getCollection('modUser',$c);
foreach ($query as $r) {
    $ta = $r->get(array('id','fullname','username'));
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