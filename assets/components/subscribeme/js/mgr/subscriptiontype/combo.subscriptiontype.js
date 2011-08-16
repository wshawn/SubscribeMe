SM.combo.SubscriptionType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'subscriptiontype',
        hiddenName: 'subscriptiontype',
        displayField: 'display',
        valueField: 'id',
        fields: ['id','display'],
        url: SM.config.connector_url,
        baseParams: {
            action: 'mgr/subscriptiontypes/get_combo_filter'
        }
    });
    SM.combo.SubscriptionType.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.SubscriptionType,MODx.combo.ComboBox);
Ext.reg('sm-combo-subscriptiontype',SM.combo.SubscriptionType);