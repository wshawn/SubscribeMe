<?php
/* @var modX $modx
 * @var array $scriptProperties
 */

if (!$scriptProperties['user_id'] || !is_numeric($scriptProperties['user_id']))
    return $modx->error->failure('No user ID found.');

/* @var smSubscription $subscription */
$subscription = $modx->newObject('smSubscription');
$subscription->fromArray(
    array(
        'user_id' => $scriptProperties['user_id'],
        'product_id' => $scriptProperties['product_id'],
        'start' => date('Y-m-d H:i:s',strtotime($scriptProperties['start'].' 00:00:00')),
        'expires' => date('Y-m-d H:i:s',strtotime($scriptProperties['end'].' 23:59:00')),
        'active' => true
    )
);
if(!$subscription->save())
    return $modx->error->failure('Error saving subscription.');

/* @var smTransaction $transaction*/
$transaction = $modx->newObject('smTransaction');
$transaction->fromArray(
    array(
        'sub_id' => $subscription->getPrimaryKey(),
        'user_id' => $scriptProperties['user_id'],
        'reference' => $scriptProperties['reference'],
        'method' => 'complimentary',
        'amount' => 0,
    )
);


if (!$transaction->save())
    return $modx->error->failure('Error saving transaction.');

$processTrans = $modx->sm->processTransaction($transaction);
if ($processTrans !== true)
    return $modx->error->failure('Error processing transaction: '.$processTrans);

return $modx->error->success();

?>