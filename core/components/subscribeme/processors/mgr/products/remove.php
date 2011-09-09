<?php
/*
 * @param modX $modx modX object
 * @package modx
 */

$eid = $modx->getOption('eid',$scriptProperties,null);
if (!$eid)
    return $modx->error->failure('No ID found.');

$obj = $modx->getObject('smProduct',$eid);
if (!($obj instanceof smProduct))
    return $modx->error->failure($modx->lexicon('sm.error.invalidobject'));


// Let's check if there's any subscriptions for this type.. to prevent data corruption, we'll not accept that
$chk = $modx->newQuery('smSubscription');
$chk->where(
    array(
        'product_id' => $eid
    )
);
$count = $modx->getCount('smSubscription',$chk);
if ($count > 0)
    return $modx->error->failure($modx->lexicon('sm.error.cantremoveproductinuse'));

// Remove the object
if ($obj->remove())
    return $modx->error->success();
return $modx->error->failure($modx->lexicon('sm.error.removefail'));

?>