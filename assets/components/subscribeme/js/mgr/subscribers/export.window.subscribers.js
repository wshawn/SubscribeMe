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
            name: 'product',
            xtype: 'sm-combo-product',
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