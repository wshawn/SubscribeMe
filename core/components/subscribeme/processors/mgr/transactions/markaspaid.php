<?php
/* @var modX $modx
 * @var array $scriptProperties
 */
$id = $modx->getOption('transaction',$scriptProperties,null);
$ref = $modx->getOption('reference',$scriptProperties,null);

/* @var smTransaction $obj */
$obj = $modx->getObject('smTransaction',$id);
if (!($obj instanceof smTransaction)) {
    return $modx->error->failure($modx->lexicon('sm.error.invalidobject'));
}

$obj->set('method','manual');
$obj->set('reference',$ref);
if (!$obj->save())
    return $modx->lexicon('sm.error.savefail');

$result = $modx->sm->processTransaction($obj, $ref);

if ($result) {
    return $modx->error->success();
}
return $modx->error->failure($modx->lexicon('sm.error.processtransfail')) 

?>