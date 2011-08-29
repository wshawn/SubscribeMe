<?php
/*
 * @param modX $modx modX object
 * @package modx
 */

$eid = $modx->getOption('eid',$scriptProperties,null);
if (!$eid)
    return $modx->error->failure('No ID found.');

$obj = $modx->getObject('smProductPermissions',$eid);
if (!($obj instanceof smProductPermissions))
    return $modx->error->failure('Invalid object');

// Remove the object
if ($obj->remove())
    return $modx->error->success();
return $modx->error->failure('Error removing object');

?>