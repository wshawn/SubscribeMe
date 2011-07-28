<?php

$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'User.fullname');
$dir = $modx->getOption('dir',$scriptProperties,'asc');

$c = $modx->newQuery('modUser');

$total = $modx->getCount('modUser',$c);

$c->sortby($sort,$dir);

$c->select(
    array(
        'modUser.id',
        'modUser.active',
        'Profile.fullname',
        'Profile.email',
    )
);

$c->innerJoin('modUserProfile','Profile');

/*$c->prepare();
return $modx->error->failure($c->toSql());*/

$results = array();
$qr = $modx->getCollection('modUser',$c);
foreach ($qr as $r) {
    $ta = $r->toArray();

    $subs = $modx->getCollection(
        'smSubscription',
        array(
             'user_id' => $ta['id'],
             'end:>' => date('Y-m-d H:i:s')
        )
    );

    $ta['subscriptions'] = array();
    foreach ($subs as $s) {
        $st = $s->getOne('SubscriptionType');
        if ($st instanceof smSubscriptionType) {
            $ta['subscriptions'][] = $st->get('name').
                                     ' ('.
                                     date($modx->config['manager_date_format'],strtotime($s->get('start'))).
                                     ' - '.
                                     date($modx->config['manager_date_format'],strtotime($s->get('end'))).
                                     ')';
        }
    }

    if (count($ta['subscriptions']) > 0) $ta['subscriptions'] = implode(",<br /> ",$ta['subscriptions']); //@todo Make seperator configurable?
    else $ta['subscriptions'] = '';

    $results[] = $ta;
}

$ra = array(
    'success' => true,
    'total' => $total,
    'results' => $results
);

return $modx->toJSON($ra);

?>


?>