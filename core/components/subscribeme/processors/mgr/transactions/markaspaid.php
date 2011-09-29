<?php
/**
 * SubscribeMe
 *
 * Copyright 2011 by Mark Hamstra <business@markhamstra.nl>
 *
 * This file is part of SubscribeMe, a subscriptions management extra for MODX Revolution
 *
 * SubscribeMe is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * SubscribeMe is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SubscribeMe; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
*/

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