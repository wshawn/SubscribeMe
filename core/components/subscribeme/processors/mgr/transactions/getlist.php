<?php
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'updatedon');
$dir = $modx->getOption('dir',$scriptProperties,'desc');

$search = $modx->getOption('query',$scriptProperties,null);
$subscriber = $modx->getOption('subscriber',$scriptProperties,null);
$method = $modx->getOption('method',$scriptProperties,null);

$c = $modx->newQuery('smTransaction');

$c->innerJoin('modUser','User');
$c->innerJoin('modUserProfile','Profile','User.id = Profile.internalKey');

if ($search) {
    $c->where(
        array(
            'reference:LIKE' => "%$search%",
            'OR:method:LIKE' => "%$search%",
            'OR:User.username:LIKE' => "%$search%",
            'OR:Profile.fullname:LIKE' => "%$search%",
        )
    );
    if (is_numeric($search))
        $c->orCondition(array('trans_id' => $search));
}

if (is_numeric($subscriber)) {
    $c->where(array('user_id' => $subscriber));
}

if ($method) {
    $c->where(array('method' => $method));
}

$matches = $modx->getCount('smTransaction',$c);

if (0) {
    $c->prepare();
    return $modx->error->failure($c->toSql());
}

$c->sortby($sort,$dir);
$c->sortby('createdon','desc');
$c->limit($limit,$start);

$c->select(array('smTransaction.*','User.username AS user_username','Profile.fullname AS user_name'));

$results = array();

$r = $modx->getCollection('smTransaction',$c);
foreach ($r as $rs) {
    $ta = $rs->get(array('trans_id','sub_id','user_id','reference','method','amount','createdon','updatedon','user_name','user_username'));
    $ta['updatedon'] = ($ta['updatedon'] == '0000-00-00 00:00:00') ? '' : date($modx->config['manager_date_format'].' '.$modx->config['manager_time_format'],strtotime($ta['updatedon']));
    $ta['createdon'] = ($ta['createdon'] == '0000-00-00 00:00:00') ? '' : date($modx->config['manager_date_format'].' '.$modx->config['manager_time_format'],strtotime($ta['createdon']));
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