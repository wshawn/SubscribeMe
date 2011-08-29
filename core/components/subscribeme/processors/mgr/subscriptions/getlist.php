<?php

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
$c->select(array('smSubscription.*','Product.name as type'));

$total = $modx->getCount('smSubscription',$c);

$c->sortby($sort,$dir);
$c->limit($limit,$start);

$collection = $modx->getCollection('smSubscription',$c);
foreach ($collection as $r) {
    $ta = $r->toArray();

    $results[] = $ta;
}

if (count($results) == 0) {
    return $modx->error->failure('No results found.'); //@todo Lexiconify
}
$ra = array(
    'success' => true,
    'total' => $total,
    'results' => $results
);

return $modx->toJSON($ra);

?>


?>