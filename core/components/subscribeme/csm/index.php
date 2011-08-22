<?php

require_once dirname(dirname(__FILE__)) . '/classes/subscribeme.class.php';
$rex = new SubscribeMe($modx);
$rex->initialize('mgr');

$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
    Ext.onReady(function() {
        SM.config = '.$modx->toJSON($rex->config).';
    });
</script>');

$modx->regClientStartupScript($rex->config['js_url'].'mgr/subscribeme.class.js');
$modx->regClientStartupScript($rex->config['js_url'].'mgr/index.action.js');

$modx->regClientStartupScript($rex->config['js_url'].'mgr/subscribers/grid.subscribers.js');
$modx->regClientStartupScript($rex->config['js_url'].'mgr/subscribers/export.window.subscribers.js');
$modx->regClientStartupScript($rex->config['js_url'].'mgr/subscribers/combo.subscribers.js');

$modx->regClientStartupScript($rex->config['js_url'].'mgr/transactions/grid.transactions.js');
$modx->regClientStartupScript($rex->config['js_url'].'mgr/transactions/combo.paid.transactions.js');
$modx->regClientStartupScript($rex->config['js_url'].'mgr/transactions/markaspaid.window.transactions.js');
$modx->regClientStartupScript($rex->config['js_url'].'mgr/transactions/viewsubscriptions.window.transactions.js');

$modx->regClientStartupScript($rex->config['js_url'].'mgr/subscriptiontype/combo.subscriptiontype.js');

return '<div id="subscribeme"></div>';
?>