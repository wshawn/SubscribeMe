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


require_once dirname(dirname(dirname(__FILE__))) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modelPath = $modx->getOption('subscribeme.core_path',null,$modx->getOption('core_path').'components/subscribeme/').'model/';

$modx->addPackage('subscribeme',$modelPath);

$manager = $modx->getManager();

$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('html');

echo <<<STYLE
<style>
pre {
 white-space: pre-wrap;       /* css-3 */
 white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
 white-space: -pre-wrap;      /* Opera 4-6 */
 white-space: -o-pre-wrap;    /* Opera 7 */
 word-wrap: break-word;       /* Internet Explorer 5.5+ */
 width: 99%;
}
</style>
STYLE;


echo 'Starting log...';

echo '<pre style="word-wrap: ">';
$manager->createObjectContainer('smSubscription');
$manager->createObjectContainer('smProduct');
$manager->createObjectContainer('smProductPermissions');
$manager->createObjectContainer('smTransaction');
$manager->createObjectContainer('smPaypalToken');
echo '</pre>';

echo 'Done.';
?>