SM.window.ProductPermissions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.productpermission'),
        url: SM.config.connector_url,
        closeAction: 'close',
        baseParams: {
            action: 'mgr/productpermissions/save'
        },
        fields: [{
            name: 'id',
            xtype: 'hidden'
        },{
            name: 'product_id',
            xtype: 'hidden'
        },{
            name: 'usergroup',
            hiddenName: 'usergroup',
            fieldLabel: _('sm.usergroup'),
            xtype: 'modx-combo-usergroup'
        },{
            name: 'role',
            hiddenName: 'role',
            fieldLabel: _('sm.role'),
            xtype: 'modx-combo-role'
        }],
        listeners: {
            success: function() {
                Ext.getCmp(config.win).refresh();
                Ext.getCmp('grid-products').refresh();
            }
        }
    });
    SM.window.ProductPermissions.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.ProductPermissions,MODx.Window);
Ext.reg('sm-window-productpermissions',SM.window.ProductPermissions);
