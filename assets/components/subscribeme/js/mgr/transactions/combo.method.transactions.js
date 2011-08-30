SM.combo.TransactionsMethod = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'paid',
        hiddenName: 'paid',
        displayField: 'display',
        valueField: 'id',
        mode: 'local',
        store: new Ext.data.SimpleStore({
            fields: ['display','id'],
            data: [[_('sm.combo.paypal'),'paypal'],[_('sm.combo.manual'),'manual'],[_('sm.combo.complimentary'),'complimentary']]
        }),
        baseParams: {
            action: 'mgr/transactions/get_combo_paid_filter'
        },
        typeAhead: true
    });
    SM.combo.TransactionsMethod.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.TransactionsMethod,MODx.combo.ComboBox);
Ext.reg('sm-combo-transactionsmethod',SM.combo.TransactionsMethod);