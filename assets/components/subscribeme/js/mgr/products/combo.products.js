SM.combo.Product = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'product',
        hiddenName: 'product',
        displayField: 'display',
        valueField: 'id',
        fields: ['id','display'],
        url: SM.config.connector_url,
        baseParams: {
            action: 'mgr/products/get_combo_filter',
            options: (config.hideOptions) ? 0 : 1
        }
    });
    SM.combo.Product.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.Product,MODx.combo.ComboBox);
Ext.reg('sm-combo-product',SM.combo.Product);

SM.combo.Period = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'period',
        hiddenName: 'period',
        displayField: 'd',
        valueField: 'v',
        mode: 'local',
        store: new Ext.data.SimpleStore({
            fields: ['d','v'],
            data: [[_('sm.combo.day'),'D'],[_('sm.combo.week'),'W'],[_('sm.combo.month'),'M'],[_('sm.combo.year'),'Y']]
        })
    });
    SM.combo.Period.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.Period,MODx.combo.ComboBox);
Ext.reg('sm-combo-period',SM.combo.Period);