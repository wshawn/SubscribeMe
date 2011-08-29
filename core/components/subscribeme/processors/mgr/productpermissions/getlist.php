<?php
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'product_id');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$product  = $modx->getOption('product',$scriptProperties,null);
if (!$product)
    return $modx->error->failure('No product specified.');

$results = array();

$c = $modx->newQuery('smProductPermissions');
$c->where(array('product_id' => $product));

$matches = $modx->getCount('smProductPermissions');

$c->sortby($sort,$dir);
$c->limit($limit,$start);

$r = $modx->getCollection('smProductPermissions',$c);
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