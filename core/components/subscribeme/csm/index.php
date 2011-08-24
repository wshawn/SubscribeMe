<?php

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
            $modx->regClientStartupHTMLBlock('
<script type="text/javascript">
    Ext.onReady(function() {
        SM.record = '.$modx->toJSON($data).';
    });
</script>');
        }
    }

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriber.action.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/grid.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/combo.paid.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/markaspaid.window.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/viewsubscriptions.window.transactions.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/grid.subscriptions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptions/add.window.subscriptions.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptiontypes/combo.subscriptiontypes.js');

}
else {
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/index.action.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribers/grid.subscribers.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribers/export.window.subscribers.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscribers/combo.subscribers.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/grid.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/combo.paid.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/markaspaid.window.transactions.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/transactions/viewsubscriptions.window.transactions.js');

    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptiontypes/grid.subscriptiontypes.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptiontypes/window.subscriptiontypes.js');
    $modx->regClientStartupScript($sm->config['js_url'].'mgr/subscriptiontypes/combo.subscriptiontypes.js');
}

return '<div id="subscribeme"></div>';
?>