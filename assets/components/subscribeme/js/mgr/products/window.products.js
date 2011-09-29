/**
 * SubscribeMe
 *
 * Copyright 2011 by Mark Hamstra <business@markhamstra.nl>
 *
 * This file is part of SubscribeMe, a subscriptions management extra for MODX Revolution
 *
 * SubscribeMe is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * SubscribeMe is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SubscribeMe; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
*/

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
