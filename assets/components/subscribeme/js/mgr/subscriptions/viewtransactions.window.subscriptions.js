
SM.window.ViewTransactions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.subscription.viewtransactions'),
        closeAction: 'hide',
        width: '70%',
        fields: [{
            xtype: 'panel',
            html: _('sm.subscription.viewtransactions.text'),
            bodyStyle: 'padding-bottom: 12px;'
        },{
            xtype: 'sm-grid-transactions',
            baseParams: {
                action: 'mgr/subscriptions/gettransactions',
                subscription: config.config.subscription || false
            },
            id: 'sm-subscriptions-transactions',
            pageSize: 5,
            hideUser: true,
            preventBugs: true // Just wish this would always work...
        }],
        buttons: [{
            text: _('close'),
            scope: this,
            handler: function() { this.hide(); }
        }]
    });
    SM.window.ViewTransactions.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.ViewTransactions,MODx.Window);
Ext.reg('sm-window-viewsubscriptions',SM.window.ViewTransactions);
