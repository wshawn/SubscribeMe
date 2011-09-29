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
            hiddenName: 'user_id',
            xtype: (config.record) ? (config.record.user_id) ? 'hidden' : 'sm-combo-subscribers' : 'sm-combo-subscribers',
            fieldLabel: _('user'),
            width: 200
        },{
            name: 'product_id',
            hiddenName: 'product_id',
            xtype: 'sm-combo-product',
            fieldLabel:  _('sm.product'),
            width: 200,
            hideOptions: true
        },{
            name: 'expires',
            fieldLabel: _('sm.expires'),
            xtype: 'datefield',
            allowDecimal: false,
            allowNegative: false,
            width: 200
        },{
            name: 'reference',
            fieldLabel: _('sm.reference'),
            xtype: 'textfield',
            allowBlank: false,
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
Ext.reg('sm-window-freesubscription',SM.window.AddSubscription);