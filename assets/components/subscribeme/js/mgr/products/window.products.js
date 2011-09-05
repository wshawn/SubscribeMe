SM.window.Products = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.product'),
        url: SM.config.connector_url,
        closeAction: 'close',
        baseParams: {
            action: 'mgr/products/save'
        },
        fields: [{
            name: 'product_id',
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
            name: 'amount_shipping',
            fieldLabel: _('sm.amount_shipping'),
            xtype: 'numberfield',
            allowNegative: false
        },{
            name: 'amount_vat',
            fieldLabel: _('sm.amount_vat'),
            xtype: 'numberfield',
            allowNegative: false
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
            xtype: 'sm-combo-period',
            allowBlank: false
        },{
            name: 'active',
            fieldLabel: _('sm.active'),
            xtype: 'checkbox'
        },{
            name: 'sortorder',
            fieldLabel: _('sm.sortorder'),
            xtype: 'numberfield',
            allowDecimal: false,
            allowNegative: false
        },{
            xtype: (config.record) ? 'sm-grid-productpermissions' : 'hidden',
            product: (config.record) ? config.record.product_id : 0,
            fieldLabel: _('sm.permissions')
        }],
        listeners: {
            'success': function() {
                Ext.getCmp('grid-products').refresh();
            }
        }
    });
    SM.window.Products.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.Products,MODx.Window);
Ext.reg('sm-window-products',SM.window.Products);
