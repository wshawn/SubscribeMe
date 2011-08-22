SM.combo.Subscribers = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'subscribers',
        hiddenName: 'subscribers',
        displayField: 'display',
        valueField: 'id',
        fields: ['id','display'],
        url: SM.config.connector_url,
        baseParams: {
            action: 'mgr/subscribers/get_combo_filter'
        },
        typeAhead: true,
        editable: true,
        forceSelection: true,
        minChars: 1
    });
    SM.combo.Subscribers.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.Subscribers,MODx.combo.ComboBox);
Ext.reg('sm-combo-subscribers',SM.combo.Subscribers);