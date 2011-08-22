<?php
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'sortorder');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$search = $modx->getOption('query',$scriptProperties,null);
$subscriber = $modx->getOption('subscriber',$scriptProperties,null);
$paid = $modx->getOption('paid',$scriptProperties,null);

$c = $modx->newQuery('smSubscriptionType');

if ($search) {
    $c->where(
        array(
            'name:LIKE' => "%$search%",
            'OR:description:LIKE' => "%$search%",
        )
    );
    if (is_numeric($search))
        $c->orCondition(array('type_id' => $search));
}

$matches = $modx->getCount('smSubscriptionType',$c);

if (0) {
    $c->prepare();
    return $modx->error->failure($c->toSql());
}

$c->sortby($sort,$dir);
$c->sortby('type_id','desc');
$c->limit($limit,$start);

$results = array();

$r = $modx->getCollection('smSubscriptionType',$c);
foreach ($r as $rs) {
    $ta = $rs->toArray();
    $results[] = $ta;
}

if (count($results) == 0) {
    return $modx->error->failure('No results found.'); //@todo Lexiconify
}
$ra = array(
    'success' => true,
    'total' => $matches,
    'results' => $results
);

return $modx->toJSON($ra);

?>