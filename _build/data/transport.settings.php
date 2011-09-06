<?php
$s = array(
    'currencycode' => 'EUR',
    'currencysign' => '&euro;',
    'debug' => false,
    'paypal.api_username' => '',
    'paypal.api_password' => '',
    'paypal.api_signature' => '',
    'paypal.sandbox' => true,
    'paypal.sandbox_username' => '',
    'paypal.sandbox_password' => '',
    'paypal.sandbox_signature' => '',
    'paypal.cancel_id' => 1,
    'paypal.fail_id' => 1,
    'paypal.return_id' => 1,
    'paypal.completed_id' => 1,
    'email.confirmtransaction' => 'smConfirmTransactionEmail',
    'email.confirmtransaction.subject' => 'Transaction processed for [[+product]] subscription',
);

$settings = array();

foreach ($s as $key => $value) {
    if (is_string($value) || is_int($value)) { $type = 'textfield'; }
    elseif (is_bool($value)) { $type = 'combo-boolean'; }
    else { $type = 'textfield'; }

    $area = (substr($key,0,7) == 'paypal.') ? 'PayPal' : (substr($key,0,6) == 'email.') ? 'Email' : 'Default';
    $settings['subscribeme.'.$key] = $modx->newObject('modSystemSetting');
    $settings['subscribeme.'.$key]->set('key', 'subscribeme.'.$key);
    $settings['subscribeme.'.$key]->fromArray(array(
        'value' => $value,
        'xtype' => $type,
        'namespace' => 'subscribeme',
        'area' => $area
    ));
}

return $settings;


