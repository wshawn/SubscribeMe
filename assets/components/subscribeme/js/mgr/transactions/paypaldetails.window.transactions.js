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

SM.window.TransactionDetails = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.transaction.transactiondetails'),
        closeAction: 'hide',
        width: 700,
        fields: [{
            xtype: 'panel',
            html: _('sm.transaction.transactiondetails.text'),
            bodyStyle: 'padding-bottom: 12px;'
        },{
            xtype: 'sm-grid-transactiondetailsgrid',
            baseParams: {
                action: 'mgr/transactions/getppdetails',
                reference: config.reference
            }
        }],
        buttons: [{
            text: _('close'),
            scope: this,
            handler: function() { this.hide(); }
        }]
    });
    SM.window.TransactionDetails.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.TransactionDetails,MODx.Window);
Ext.reg('sm-window-transactiondetails',SM.window.TransactionDetails);


SM.grid.TransactionDetailsGrid = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: SM.config.connector_url,
        id: 'grid-transaction-transactiondetails',
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            emptyText: 'No data, or still processing request.'
        },
        fields: [
            {name: 'col1', type: 'string'},
            {name: 'col2', type: 'string'}
        ],
        paging: false,
        columns: [{
            header: _('sm.col1'),
            dataIndex: 'col1',
            sortable: true,
            width: 5,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
        },{
            header: _('sm.col2'),
            dataIndex: 'col2',
            sortable: true,
            width: 5,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
        }]
    });
    SM.grid.TransactionDetailsGrid.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.TransactionDetailsGrid,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var d = r.data;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
        } else {
            m.push({
                text: _('sm.nooptions'),
                handler: function() { return false; }
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
});
Ext.reg('sm-grid-transactiondetailsgrid',SM.grid.TransactionDetailsGrid);