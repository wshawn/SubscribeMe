<?php
/* @var modX $modx
 * @var array $scriptProperties
 */

if (!$scriptProperties['user_id'] || !is_numeric($scriptProperties['user_id']))
    return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => 'ID')));

/* @var smSubscription $subscription */
$subscription = $modx->newObject('smSubscription');
$subscription->fromArray(
    array(
        'user_id' => $scriptProperties['user_id'],
        'product_id' => $scriptProperties['product_id'],
        'start' => date('Y-m-d H:i:s'),
        'expires' => $scriptProperties['expires'],
        'active' => true
    )
);
if(!$subscription->save())
    return $modx->error->failure($modx->lexicon('sm.error.savefail'));

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
    return $modx->error->failure($modx->lexicon('sm.error.savefail'));

/* We add in the second parameter to prevent the method from recalculating the expires column. We already set that,
so it shouldn't do that. 
*/
$processTrans = $modx->sm->processTransaction($transaction,false);
if ($processTrans !== true)
    return $modx->error->failure($modx->lexicon('sm.error.processtransfail',array('result' => $processTrans)));

return $modx->error->success();

?>