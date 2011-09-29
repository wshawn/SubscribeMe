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

if (!$scriptProperties['user_id'] || !is_numeric($scriptProperties['user_id']))
    return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => $modx->lexicon('user'))));
if (!$scriptProperties['sub_id'] || !is_numeric($scriptProperties['sub_id']))
    return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => $modx->lexicon('sm.subscription'))));
if (!$scriptProperties['reference'])
    return $modx->error->failure($modx->lexicon('sm.error.noresults',array('what' => $modx->lexicon('sm.reference'))));

$trans = $modx->newObject('smTransaction');
$trans->fromArray(
    array_merge($scriptProperties,array(
        'method' => 'manual'
    ))
);

if (!$trans->save())
    return $modx->error->failure($modx->lexicon('sm.error.savefail'));

$processTrans = $modx->sm->processTransaction($trans);
if ($processTrans !== true)
    return $modx->error->failure($modx->lexicon('sm.error.processtransfail',array('result' => $processTrans)));

return $modx->error->success();
?>