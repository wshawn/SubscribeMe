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

/* @var array $scriptProperties
 * @var modX $modx
 */
$data = $scriptProperties;

// Transform data from modx-combo-boolean
$data['active'] = (boolean)$data['active'];
$data['dob'] = strtotime($data['dob']);

/* @var modUser $user */
if (is_numeric($data['id']) && !empty($data['id']))
    $user = $modx->getObject('modUser',$data['id']);
if (!($user instanceof modUser)) {
    $user = $modx->newObject('modUser');
    $rpass = substr(md5(md5(time().$data['username'].rand(111111,999999))),0,12);
    $user->set('password',$rpass);
}
$user->fromArray($data);
$userresult = $user->save();

/* @var modUserProfile $userprof */
$userprof = $user->getOne('Profile');
if (!($userprof instanceof modUserProfile)) {
    $userprof = $modx->newObject('modUserProfile');
    $userprof->set('internalKey',$user->get('id'));
}

$userprof->fromArray($data);
$userprofresult = $userprof->save();

if ($userresult && $userprofresult) {
    if (isset($rpass)) {
        $phs = array_merge($modx->config,$user->toArray(),array('password' => $rpass));
        $mail = $modx->sm->getChunk('RegistrationMail',$phs); //@todo Make configurable
        $options = array(
            'subject' => 'Welcome!' // @todo Make configurable
        );

        $sent = $user->sendEmail($mail,$options);
                       // Do not throw error if we're on a localhost as those are often not configured for email properly.
        if (!$sent && ($modx->config['http_host'] != 'localhost')) {
            return $modx->error->failure($modx->lexicon('sm.error.sendmailfailed'));
        }
    }
    return $modx->error->success($user->get('id'));
} else {
    if (!$userresult) {
        return $modx->error->failure($modx->lexicon('sm.error.savefailed.user'));
    }
    if (!$userprofresult) {
        return $modx->error->failure($modx->lexicon('sm.error.savefailed.userprofile'));
    }
    return $modx->error->failure($modx->lexicon('sm.error.savefailed'));
}

?>