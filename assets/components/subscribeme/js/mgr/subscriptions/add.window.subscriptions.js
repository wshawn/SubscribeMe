SM.window.AddSubscription = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.freesubscription'),
        id: 'sm-window-addsubscription',
        url: SM.config.connector_url,
        closeAction: 'close',
        baseParams: {
            action: 'mgr/subscriptions/add'
        },
        fields: [{
            xtype: 'panel',
            html: '<p>'+_('sm.freesubscription.text')+'</p>',
            bodyStyle: 'padding-bottom: 12px;'
        },{
            name: 'user_id',
            xtype: 'hidden'
        },{
            name: 'type_id',
            hiddenName: 'type_id',
            xtype: 'sm-combo-subscriptiontype',
            fieldLabel:  _('sm.subscription'),
            width: 200,
            hideOptions: true
        },{
            name: 'start',
            fieldLabel: _('sm.start'),
            xtype: 'datefield',
            width: 200
        },{
            name: 'end',
            fieldLabel: _('sm.end'),
            xtype: 'datefield',
            allowDecimal: false,
            allowNegative: false,
            width: 200
        },{
            name: 'reference',
            fieldLabel: _('sm.reference'),
            xtype: 'textfield',
            allowDecimal: false,
            allowNegative: false,
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
    SM.window.AddSubscription.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.AddSubscription,MODx.Window);
Ext.reg('sm-window-exportsubs',SM.window.AddSubscription);