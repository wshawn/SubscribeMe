<?php
$profile = $modx->user->getOne('Profile');
if (!($profile instanceof modUserProfile)) return false;

$profile->fromArray($hook->getValues());

return $profile->save();
?>