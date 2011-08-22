SM.combo.TransactionsPaid = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'paid',
        hiddenName: 'paid',
        displayField: 'display',
        valueField: 'id',
        mode: 'local',
        store: new Ext.data.SimpleStore({
            fields: ['display','id'],
            data: [[_('sm.combo.paid'),1],[_('sm.combo.unpaid'),-1]]
        }),
        baseParams: {
            action: 'mgr/transactions/get_combo_paid_filter'
        },
        typeAhead: true
    });
    SM.combo.TransactionsPaid.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.TransactionsPaid,MODx.combo.ComboBox);
Ext.reg('sm-combo-transactionspaid',SM.combo.TransactionsPaid);