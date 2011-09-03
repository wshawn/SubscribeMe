<?php
/* @var modX $modx
 * @var array $scriptProperties
 */
$id = $modx->getOption('transaction',$scriptProperties,null);
$ref = $modx->getOption('reference',$scriptProperties,null);

/* @var smTransaction $obj */
$obj = $modx->getObject('smTransaction',$id);
if (!($obj instanceof smTransaction)) {
    return $modx->error->failure('Invalid object'); //@todo lexiconify
}

$obj->set('method','manual');
$obj->set('reference',$ref);
if (!$obj->save())
    return 'Error updating transaction data.';

$result = $modx->sm->processTransaction($obj, $ref);

if ($result) {
    return $modx->error->success();
}
return $modx->error->failure('Error saving data.') //@todo Lexiconify

?>