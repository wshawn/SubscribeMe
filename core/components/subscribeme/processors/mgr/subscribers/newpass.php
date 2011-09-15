<?php
/* @var modX $modx
 * @var array $scriptProperties
 **/

$uid = $modx->getOption('uid',$scriptProperties,null);
if (!$uid)
    return $modx->error->failure($modx->lexicon('sm.error.notspecified',array('what' => $modx->lexicon('user'))));

/* @var modUser $user */
$user = $modx->getObject('modUser',$uid);
if (!($user instanceof modUser))
    return $modx->error->failure($modx->lexicon('sm.error.invalidobject'));

$newPassword = $user->generatePassword();

/* From Revolution 2.1.3 core/model/modx/modUser.class.php line 232 */
$user->set('password', $newPassword);
$changed = $user->save();
if ($changed) {
    $modx->invokeEvent('OnUserChangePassword', array (
        'user' => &$this,
        'newpassword' => $newPassword,
        'oldpassword' => 'N/A (changed through SubscribeMe)',
        'userid' => $user->get('id'),/* deprecated */
        'username' => $user->get('username'),/* deprecated */
        'userpassword' => $newPassword,/* deprecated */
    ));

    $up = $user->getOne('Profile');
    $upa = ($up instanceof modUserProfile) ? $up->toArray() : array();
    $phs = array('newpassword' => $newPassword) + $modx->config + $user->toArray() + $upa;

    $chunk = $modx->getOption('subscribeme.email.passwordchanged',null,'smPasswordChangedEmail');
    $subject = $modx->getOption('subscribeme.email.passwordchanged.subject',null,'Your Password was Changed');

    $msg = $modx->sm->getChunk($chunk,$phs);

    if ($user->sendEmail($msg,array('subject' => $subject)) !== true)
        return $modx->error->failure($modx->lexicon('sm.error.sendmailfailed'));

    $up = $user->getOne('Profile');
    $email = '???';
    if ($up instanceof modUserProfile)
        $email = $up->get('email');

    return $modx->error->success($email);
}
return $modx->error->failure($modx->lexicon('sm.error.savefail'));

?>