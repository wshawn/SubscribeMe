<?php
$id = $modx->getOption('transaction',$scriptProperties,null);
$ref = $modx->getOption('reference',$scriptProperties,null);

$obj = $modx->getObject('smTransaction',$id);
if (!($obj instanceof smTransaction)) {
    return $modx->error->failure('Invalid object'); //@todo lexiconify
}

$obj->set('reference',$ref);
$obj->set('method','MANUAL');
$obj->set('updatedon',date('Y-m-d H:i:s'));

if ($obj->save()) {
    return $modx->error->success();
}
return $modx->error->failure('Error saving data.') //@todo Lexiconify

?>