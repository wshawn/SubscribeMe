<?php
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'start');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$transaction = $modx->getOption('transaction',$scriptProperties,null);
if (!$transaction || !is_numeric($transaction)) return $modx->error->failure('No transaction specified.');

$c = $modx->newQuery('smSubscription');
$c->where(array('trans_id' => $transaction));
$c->innerJoin('smSubscriptionType','SubscriptionType');
$c->innerJoin('modUser','User');
$c->innerJoin('modUserProfile','Profile','User.id = Profile.internalKey');

$total = $modx->getCount($c);
$c->limit($limit,$start);
$c->sortby($sort,$dir);

$c->select(array('smSubscription.*','SubscriptionType.name AS subscription','User.username AS username','Profile.fullname AS fullname'));

$results = array();

$r = $modx->getCollection('smSubscription',$c);
foreach ($r as $rs) {
    $ta = $rs->get(array('sub_id','trans_id','user_id','type_id','start','end','active','fullname','username','subscription'));
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