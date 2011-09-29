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
        },{
            name: 'amount',
            fieldLabel: _('sm.amount'),
            xtype: 'statictextfield'
        }],
        listeners: {
            success: function(result,form) {
                this.close();
                Ext.getCmp(config.parentid).refresh();
                if (config.parentid != 'sm-grid-transactions') {
                    Ext.getCmp('sm-grid-transactions').refresh();
                }
            },scope: this
        }
    });
    SM.window.MarkAsPaid.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.MarkAsPaid,MODx.Window);
Ext.reg('sm-window-markaspaid',SM.window.MarkAsPaid);
