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

require_once dirname(dirname(__FILE__)) . '/classes/subscribeme.class.php';
$sm = new SubscribeMe($modx);
$sm->initialize('mgr');

$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
    Ext.onReady(function() {
        SM.config = '.$modx->toJSON($sm->config).';
    });
</script>');

$modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribeme.class.js');

if ($_GET['action'] == 'subscriber') {

    if (is_numeric($_GET['id']) && $_GET['id'] > 0) {
        // Fetch the data we need.
        $user = $modx->getObject('modUser',$_GET['id']);
        if ($user instanceof modUser) {
            $profile = $user->getOne('Profile');
            $data = array_merge($user->toArray(),$profile->toArray());
            $data['lastlogin'] = date(DATE_RFC822,$data['lastlogin']);
            $data['dob'] = date($modx->config['manager_date_format'],$data['dob']);
            $modx->regClientStartupHTMLBlock('
<script type="text/javascript">
    Ext.onReady(function() {
        SM.record = '.$modx->toJSON($data).';
    });
</script>');
        }
    }

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriber.action.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/add.window.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/grid.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/combo.method.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/markaspaid.window.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/paypaldetails.window.transactions.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/grid.subscriptions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/add.window.subscriptions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/ppprofile.window.subscriptions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/viewtransactions.window.subscriptions.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribers/panel.subscribers.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribers/newpass.window.subscribers.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/products/combo.products.js');

}
else {
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/index.action.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribers/combo.subscribers.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribers/export.window.subscribers.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribers/grid.subscribers.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/add.window.subscriptions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/grid.subscriptions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/ppprofile.window.subscriptions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/viewtransactions.window.subscriptions.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/add.window.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/combo.method.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/grid.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/markaspaid.window.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/paypaldetails.window.transactions.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/products/combo.products.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/products/grid.products.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/products/window.products.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/productpermissions/grid.productpermissions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/productpermissions/window.productpermissions.js');
}

return '<div id="subscribeme"></div>';
?>