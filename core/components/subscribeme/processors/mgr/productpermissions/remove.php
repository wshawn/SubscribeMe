<?php
/*
 * @param modX $modx modX object
 * @package modx
 */

$eid = $modx->getOption('eid',$scriptProperties,null);
if (!$eid)
    return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => 'ID')));

$obj = $modx->getObject('smProductPermissions',$eid);
if (!($obj instanceof smProductPermissions))
    return $modx->error->failure($modx->lexicon('sm.error.invalidobject'));

// Remove the object
if ($obj->remove())
    return $modx->error->success();
return $modx->error->failure($modx->lexicon('sm.error.removefail'));

?>