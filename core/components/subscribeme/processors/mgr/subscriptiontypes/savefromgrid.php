<?php
$data = $modx->fromJSON($scriptProperties['data']);
$id = $modx->getOption('type_id',$data,null);

if (!$id)
    $st = $modx->newObject('smSubscriptionType');
else
    $st = $modx->getObject('smSubscriptionType',$id);

if (!($st instanceof smSubscriptionType))
    return $modx->error->failure('Invalid object');

$data['active'] = ($data['active'] == 'on') ? true : false;

$st->fromArray($data);

if ($st->save())
    return $modx->error->success();
return $modx->error->failure('Error saving object');

?>