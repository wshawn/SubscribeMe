<?php
$data = $modx->fromJSON($scriptProperties['data']);
$id = $modx->getOption('id',$data,null);

if (!$id)
    $st = $modx->newObject('smProductPermissions');
else
    $st = $modx->getObject('smProductPermissions',$id);

if (!($st instanceof smProductPermissions))
    return $modx->error->failure('Invalid object');

$st->fromArray($data);

if ($st->save())
    return $modx->error->success();

return $modx->error->failure('Error saving object');

?>