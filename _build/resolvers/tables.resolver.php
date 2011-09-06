<?php

if ($object->xpdo) {
    $modx =& $object->xpdo;

    $modelPath = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/').'model/';
    $modx->addPackage('subscribeme',$modelPath);

    $manager = $modx->getManager();

    $objects = array(
        'smSubscription','smProduct','smProductPermissions','smTransaction','smPaypalToken'
    );

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
        case xPDOTransport::ACTION_INSTALL:
            foreach ($objects as $obj) {
                $manager->createObjectContainer($obj);
            }
        break;
    }
}
return true;