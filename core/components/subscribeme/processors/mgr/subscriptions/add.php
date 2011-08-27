<?php


if (!$scriptProperties['user_id'] || !is_numeric($scriptProperties['user_id']))
    return $modx->error->failure('No user ID found.');

$transaction = $modx->newObject('smTransaction');
$transaction->fromArray(
    array(
         'user_id' => $scriptProperties['user_id'],
         'reference' => $scriptProperties['reference'],
         'method' => 'COMPLIMENTARY',
         'completed' => true
    )
);

// @todo Set up proper permissions


$subscription = $modx->newObject('smSubscription');
$subscription->fromArray(
    array(
        'user_id' => $scriptProperties['user_id'],
        'type_id' => $scriptProperties['type_id'],
        'start' => date('Y-m-d H:i:s',strtotime($scriptProperties['start'].' 00:00:00')),
        'end' => date('Y-m-d H:i:s',strtotime($scriptProperties['end'].' 23:59:00')),
        'active' => true
    )
);

$transaction->addMany($subscription);

if ($transaction->save())
    return $modx->error->success();
return $modx->error->failure('Error saving transaction and subscription.');

?>