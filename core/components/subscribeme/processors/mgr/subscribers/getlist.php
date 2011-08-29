<?php

$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'Profile.fullname');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$search = $modx->getOption('query',$scriptProperties,null);

$subfilter = $modx->getOption('product',$scriptProperties,null);

$c = $modx->newQuery('modUser');


$c->select(
    array(
        'modUser.*',
        'Profile.*',
        /*'modUser.id',
        'modUser.active',
        'modUser.username',
        'Profile.fullname',
        'Profile.email',*/
    )
);

$c->innerJoin('modUserProfile','Profile');

if (!empty($search)) {
    $c->where(array(
                  'Profile.fullname:LIKE' => "%$search%",
                  'OR:Profile.email:like' => "%$search%",
                  'OR:modUser.username:like' => "%$search%",
                  'OR:modUser.id:LIKE' => "%$search%",
              ));
}

if (is_numeric($subfilter)) {
    $c->leftJoin('smSubscription','S','modUser.id = S.user_id');
    //$c->leftJoin('smProduct','ST','S.type_id = ST.type_id');
    $c->where(array(
                   'S.type_id' => $subfilter,
                   'AND:S.expires:>' => date('Y-m-d H:i:s',time())
              ));
}
elseif ($subfilter == 'current') {
    $c->leftJoin('smSubscription','S','modUser.id = S.user_id');
    $c->where(array(
                   'S.type_id:>' => 0,
                   'AND:S.expires:>' => date('Y-m-d H:i:s',time())
              ));
}

$total = $modx->getCount('modUser',$c);

$c->sortby($sort,$dir);


/*$c->prepare();
return $modx->error->failure($c->toSql());*/

$results = array();
$qr = $modx->getIterator('modUser',$c);
foreach ($qr as $idx => $r) {
    if (defined('returnasarray') && isset($fields))
        $ta = $r->get($fields);
    else
        $ta = $r->get(array('id','active','fullname','email','username'));

    $subs = $modx->getCollection(
        'smSubscription',
        array(
             'user_id' => $ta['id'],
             'expires:>' => date('Y-m-d H:i:s')
        )
    );

    $ta['subscriptions'] = array();
    foreach ($subs as $s) {
        $st = $s->getOne('Product');
        if ($st instanceof smProduct) {
            $ta['subscriptions'][] = $st->get('name').
                                     ' ('.
                                     date($modx->config['manager_date_format'],strtotime($s->get('start'))).
                                     ' - '.
                                     date($modx->config['manager_date_format'],strtotime($s->get('end'))).
                                     ')';
        }
    }

    if (count($ta['subscriptions']) > 0) $ta['subscriptions'] = implode(",<br /> ",$ta['subscriptions']); //@todo Make seperator configurable?
    else $ta['subscriptions'] = (defined('returnasarray')) ? '' : '<i>None</i>'; //@todo Lexiconify

    $results[] = $ta;
}

if (defined('returnasarray'))
    return $results;

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