SM.window.SubscriptionTypes = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.subscriptiontype'),
        url: SM.config.connector_url,
        closeAction: 'close',
        baseParams: {
            action: 'mgr/subscriptiontypes/save'
        },
        fields: [{
            name: 'type_id',
            xtype: 'hidden'
        },{
            name: 'name',
            xtype: 'textfield',
            fieldLabel: _('sm.name'),
            width: 270,
            allowBlank: false
        },{
            name: 'description',
            fieldLabel: _('sm.description'),
            xtype: 'textarea',
            width: 270,
            height: 130
        },{
            name: 'price',
            fieldLabel: _('sm.price'),
            xtype: 'numberfield',
            allowNegative: false,
            allowBlank: false
        },{
            name: 'periods',
            fieldLabel: _('sm.periods'),
            xtype: 'numberfield',
            allowNegative: false,
            allowDecimal: false,
            allowBlank: false
        },{
            name: 'period',
            fieldLabel: _('sm.period'),
            xtype: 'textfield', //@todo dropdown
            allowBlank: false
        },{
            name: 'usergroup',
            fieldLabel: _('sm.usergroup'),
            xtype: 'textfield' //@todo grid + button?
        },{
            name: 'active',
            fieldLabel: _('sm.active'),
            xtype: 'checkbox'
        }]
    });
    SM.window.SubscriptionTypes.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.SubscriptionTypes,MODx.Window);
Ext.reg('sm-window-subscriptiontypes',SM.window.SubscriptionTypes);