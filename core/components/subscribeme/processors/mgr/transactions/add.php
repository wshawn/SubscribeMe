<?php
/* @var modX $modx
 * @var array $scriptProperties
 */

if (!$scriptProperties['user_id'] || !is_numeric($scriptProperties['user_id']))
    return $modx->error->failure('No user ID found.');
if (!$scriptProperties['sub_id'] || !is_numeric($scriptProperties['sub_id']))
    return $modx->error->failure('No subscription ID found.');
if (!$scriptProperties['reference'])
    return $modx->error->failure('No reference found.');

$trans = $modx->newObject('smTransaction');
$trans->fromArray(
    array_merge($scriptProperties,array(
        'method' => 'manual'
    ))
);

if (!$trans->save())
    return $modx->error->failure('Error saving transaction.');

$processTrans = $modx->sm->processTransaction($trans);
if ($processTrans !== true)
    return $modx->error->failure('Error processing transaction: '.$processTrans);

return $modx->error->success();
?>