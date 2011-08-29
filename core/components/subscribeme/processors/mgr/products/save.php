<?php
$id = $modx->getOption('product_id',$scriptProperties,null);

if (!$id)
    $st = $modx->newObject('smProduct');
else
    $st = $modx->getObject('smProduct',$id);

if (!($st instanceof smProduct))
    return $modx->error->failure('Invalid object');

$data = $scriptProperties;
$data['active'] = ($data['active'] == 'on') ? true : false;

$st->fromArray($data);

if ($st->save())
    return $modx->error->success();
return $modx->error->failure('Error saving object');

?>