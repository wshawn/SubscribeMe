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
$c->innerJoin('modUserProfile','Profile','smSubscription.user_id = Profile.internalKey');
$c->select(
    array(
        'smSubscription.*',
        'Product.name as product',
        'Product.price as product_price',
        'product.periods as product_periods',
        'product.period as product_period',
        'Profile.fullname as user',
    )
);

$total = $modx->getCount('smSubscription',$c);

$c->sortby($sort,$dir);
$c->limit($limit,$start);

$collection = $modx->getCollection('smSubscription',$c);
foreach ($collection as $r) {
    $ta = $r->toArray();
    $ta['start'] = ($ta['start'] == '0000-00-00 00:00:00') ? '' : date($modx->config['manager_date_format'].' '.$modx->config['manager_time_format'],strtotime($ta['start']));
    $ta['expires'] = ($ta['expires'] == '0000-00-00 00:00:00') ? '' : date($modx->config['manager_date_format'].' '.$modx->config['manager_time_format'],strtotime($ta['expires']));

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