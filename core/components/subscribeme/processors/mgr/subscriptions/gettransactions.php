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
    $ta['updatedon'] = ($ta['updatedon'] == '0000-00-00 00:00:00') ? '' : date($modx->config['manager_date_format'].' '.$modx->config['manager_time_format'],strtotime($ta['updatedon']));
    $ta['createdon'] = ($ta['createdon'] == '0000-00-00 00:00:00') ? '' : date($modx->config['manager_date_format'].' '.$modx->config['manager_time_format'],strtotime($ta['createdon']));
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