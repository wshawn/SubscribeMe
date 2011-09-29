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

/*
 * @param modX $modx modX object
 * @package modx
 */

$eid = $modx->getOption('eid',$scriptProperties,null);
if (!$eid)
    return $modx->error->failure('No ID found.');

$obj = $modx->getObject('smProduct',$eid);
if (!($obj instanceof smProduct))
    return $modx->error->failure($modx->lexicon('sm.error.invalidobject'));


// Let's check if there's any subscriptions for this type.. to prevent data corruption, we'll not accept that
$chk = $modx->newQuery('smSubscription');
$chk->where(
    array(
        'product_id' => $eid
    )
);
$count = $modx->getCount('smSubscription',$chk);
if ($count > 0)
    return $modx->error->failure($modx->lexicon('sm.error.cantremoveproductinuse'));

// Remove the object
if ($obj->remove())
    return $modx->error->success();
return $modx->error->failure($modx->lexicon('sm.error.removefail'));

?>