SM.window.AddManualTransaction = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.subscription.manualtransaction'),
        id: 'sm-window-addsubscription',
        url: SM.config.connector_url,
        closeAction: 'close',
        baseParams: {
            action: 'mgr/transactions/add'
        },
        fields: [{
            xtype: 'panel',
            html: '<p>'+_('sm.subscription.manualtransaction.text')+'</p>',
            bodyStyle: 'padding-bottom: 12px;'
        },{
            name: 'user_id',
            hiddenName: 'user_id',
            xtype: (config.record) ? (config.record.user_id) ? 'hidden' : 'sm-combo-subscribers' : 'sm-combo-subscribers',
            fieldLabel: _('user'),
            width: 200
        },{
            name: 'sub_id',
            hiddenName: 'sub_id',
            xtype: 'hidden',
            fieldLabel:  _('sm.product'),
            width: 200
        },{
            name: 'reference',
            fieldLabel: _('sm.reference'),
            xtype: 'textfield',
            allowBlank: false,
            width: 200
        },{
            name: 'amount',
            fieldLabel: _('sm.amount'),
            xtype: 'statictextfield',
            allowBlank: false,
            width: 200,
            submitValue: true
        },{
            name: 'period',
            fieldLabel: _('sm.period'),
            xtype: 'statictextfield',
            width: 200
        }],
        listeners: {
            success: function(result,form) {
                Ext.getCmp('grid-subscriptions').refresh();
                Ext.getCmp('grid-transactions').refresh();
                this.close();
            }
        }
    });
    SM.window.AddManualTransaction.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.AddManualTransaction,MODx.Window);
Ext.reg('sm-window-manualtransaction',SM.window.AddManualTransaction);