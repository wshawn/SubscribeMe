SM.window.MarkAsPaid = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.transaction.markaspaid'),
        id: 'sm-window-markaspaid',
        url: SM.config.connector_url,
        closeAction: 'close',
        baseParams: {
            action: 'mgr/transactions/markaspaid'
        },
        fields: [{
            xtype: 'panel',
            html: _('sm.transaction.markaspaid.text'),
            bodyStyle: 'padding-bottom: 12px;'
        },{
            name: 'transaction',
            xtype: 'numberfield',
            fieldLabel: _('id'),
            hidden: true
        },{
            name: 'reference',
            fieldLabel: _('sm.reference'),
            xtype: 'textfield',
            width: 200,
            allowBlank: false
        }],
        listeners: {
            success: function(result,form) {
                this.close();
                Ext.getCmp('grid-transactions').refresh();
            }
        }
    });
    SM.window.MarkAsPaid.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.MarkAsPaid,MODx.Window);
Ext.reg('sm-window-markaspaid',SM.window.MarkAsPaid);