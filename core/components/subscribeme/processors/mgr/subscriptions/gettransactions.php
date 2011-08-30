<?php
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'createdon');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$subscription = $modx->getOption('subscription',$scriptProperties,null);
if (!$subscription || !is_numeric($subscription)) return $modx->error->failure('No subscription specified.');

$c = $modx->newQuery('smTransaction');
$c->where(array('sub_id' => $subscription));

$total = $modx->getCount('smTransaction',$c);
$c->limit($limit,$start);
$c->sortby($sort,$dir);

$c->select(array('smTransaction.*'));

$results = array();

$r = $modx->getCollection('smTransaction',$c);
foreach ($r as $rs) {
    $ta = $rs->toArray();
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