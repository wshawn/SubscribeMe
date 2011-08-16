SM.window.ExportSubscribers = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.button.exportsubs'),
        id: 'sm-window-contributors',
        url: SM.config.connector_url,
        closeAction: 'close',
        baseParams: {
            action: 'mgr/subscribers/export'
        },
        fields: [{
            name: 'subscriptiontype',
            xtype: 'sm-combo-subscriptiontype',
            fieldLabel: _('sm.combo.filter_on',{what: _('sm.subscriptions')}),
            width: 200
        },{
            name: 'query',
            fieldLabel: _('sm.search'),
            xtype: 'textfield',
            width: 200
        },{
            name: 'limit',
            fieldLabel: _('sm.limit'),
            xtype: 'numberfield',
            allowDecimal: false,
            allowNegative: false
        }],
        listeners: {
            success: function(result,form) {
                window.location.href = SM.config.connector_url + '?action=mgr/subscribers/dlexport&export='+result.a.result.message+'&HTTP_MODAUTH='+MODx.siteId;
                this.close();
            }
        }
    });
    SM.window.ExportSubscribers.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.ExportSubscribers,MODx.Window);
Ext.reg('sm-window-exportsubs',SM.window.ExportSubscribers);