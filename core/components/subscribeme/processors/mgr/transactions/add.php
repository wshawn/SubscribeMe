<?php
/* @var modX $modx
 * @var array $scriptProperties
 */

if (!$scriptProperties['user_id'] || !is_numeric($scriptProperties['user_id']))
    return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => $modx->lexicon('user'))));
if (!$scriptProperties['sub_id'] || !is_numeric($scriptProperties['sub_id']))
    return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => $modx->lexicon('sm.subscription'))));
if (!$scriptProperties['reference'])
    return $modx->error->failure($modx->lexicon('sm.error.noresults',array('what' => $modx->lexicon('sm.reference'))));

$trans = $modx->newObject('smTransaction');
$trans->fromArray(
    array_merge($scriptProperties,array(
        'method' => 'manual'
    ))
);

if (!$trans->save())
    return $modx->error->failure($modx->lexicon('sm.error.savefail'));

$processTrans = $modx->sm->processTransaction($trans);
if ($processTrans !== true)
    return $modx->error->failure($modx->lexicon('sm.error.processtransfail',array('result' => $processTrans)));

return $modx->error->success();
?>