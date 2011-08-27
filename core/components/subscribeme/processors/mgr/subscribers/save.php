<?php
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
/*
$data['extended'] = $modx->fromJSON($userprof->get('extended'));
$data['extended'] = $modx->toJSON($data['extended']);
*/
$userprof->fromArray($data);
$userprofresult = $userprof->save();


if ($userresult && $userprofresult) {
    if (isset($rpass)) {
        $phs = array_merge($modx->config,$user->toArray(),array('password' => $rpass));
        $mail = $modx->sm->getChunk('RegistrationMail',$phs);
        $options = array(
            'subject' => 'Welcome!'
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