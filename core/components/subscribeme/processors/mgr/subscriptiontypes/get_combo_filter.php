<?php

$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$search = $modx->getOption('query',$scriptProperties,'');

$results = array(
    array(
        'id' => 'current',
        'display' => 'Current Subscribers' //@todo Lexiconify
    ),
    array(
        'id' => '',
        'display' => 'All User Accounts'
    )
);

$c = $modx->newQuery('smSubscriptionType');

if (strlen($search) > 1) {
    $c->where(array(
                  'name:LIKE' => "%$search%",
                  'OR:description:LIKE' => "%$search%",
              ));
}

$c->sortby($sort,$dir);

$total = $modx->getCount('smSubscriptionType',$c);

$c->limit($limit,$start);

$query = $modx->getCollection('smSubscriptionType',$c);
foreach ($query as $r) {
    $ta = $r->toArray();
    $results[] = array(
        'id' => $ta['type_id'],
        'display' => $ta['name'] . ' (' . $ta['price'] . '/' . $ta['periods'].$ta['period'] . ')'
    );
}

$returnArray = array(
    'success' => true,
    'total' => $total,
    'results' => $results
);
return $modx->toJSON($returnArray);