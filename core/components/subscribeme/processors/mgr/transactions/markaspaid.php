<?php
/* @var modX $modx
 * @var array $scriptProperties
 */
$id = $modx->getOption('transaction',$scriptProperties,null);
$ref = $modx->getOption('reference',$scriptProperties,null);

/* var smTransaction @obj */
$obj = $modx->getObject('smTransaction',$id);
if (!($obj instanceof smTransaction)) {
    return $modx->error->failure('Invalid object'); //@todo lexiconify
}

$obj->set('reference',$ref);
$obj->set('method','MANUAL');
$obj->set('updatedon',date('Y-m-d H:i:s'));

// @todo Set up the proper permissions here

// When all went through, mark as completed.
$obj->set('completed',true);

if ($obj->save()) {
    return $modx->error->success();
}
return $modx->error->failure('Error saving data.') //@todo Lexiconify

?>