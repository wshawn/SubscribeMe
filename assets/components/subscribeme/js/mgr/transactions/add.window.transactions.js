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