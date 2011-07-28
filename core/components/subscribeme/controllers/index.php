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

$modx->regClientStartupScript($rex->config['js_url'].'mgr/subscribers/subscribers.grid.js');

return '<div id="subscribeme"></div>';
?>