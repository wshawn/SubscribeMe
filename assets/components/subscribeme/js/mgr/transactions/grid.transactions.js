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

SM.grid.Transactions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		url: SM.config.connector_url,
		id: 'grid-transactions',
		baseParams: {
            action: 'mgr/transactions/getlist'
        },
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true
        },
        tbar: ['->',{
            xtype: 'textfield',
            id: (config.preventBugs) ? 'sm-subscriber-search2' : 'sm-subscriber-search',
            emptyText: _('sm.search...'),
            listeners: {
                'change': { fn:this.searchTransactions, scope:this},
                'render': { fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER,
                        fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        },
                        scope: cmp
                    });
                },scope: this}
            }
        },'-',{
            xtype: (config.hideSubscribersCombo) ? 'hidden' : 'sm-combo-subscribers',
            emptyText: _('sm.combo.filter_on',{what: _('user')}),
            id: (config.preventBugs) ? 'sm-transaction-filter2' :'sm-transaction-filter',
            width: 200,
            listeners: {
                'select': {fn: this.filterBySubscriber, scope: this}
            }
        },'-',{
            xtype: 'sm-combo-transactionsmethod',
            emptyText: _('sm.combo.filter_on',{what: _('sm.combo.method')}),
            id: (config.preventBugs) ? 'sm-method-filter2' :'sm-method-filter',
            width: 150,
            listeners: {
                'select': {fn: this.filterByPaid, scope: this}
            }
        },'-',{
            xtype: 'button',
            text: _('sm.button.clearfilter'),
            listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }],
		fields: [
            {name: 'trans_id', type: 'int'},
            {name: 'user_id', type: 'int'},
            {name: 'sub_id', type: 'int'},
            {name: 'user_name', type: 'string'},
            {name: 'user_username', type: 'string'},
            {name: 'reference', type: 'string'},
            {name: 'method', type: 'string'},
            {name: 'amount', type: 'string'},
            {name: 'createdon', type: 'string'},
            {name: 'updatedon', type: 'string'},
            {name: 'completed', type: 'boolean'}
        ],
        paging: true,
        primaryKey: 'trans_id',
		remoteSort: true,
        sortBy: 'createdon',
		columns: [{
			header: _('id'),
			dataIndex: 'trans_id',
			sortable: true,
			width: 1
		},{
            header: _('sm.subscr')+' '+_('id'),
            dataIndex: 'sub_id',
            sortable: true,
            width: 2
        },{
            header: _('sm.createdon'),
            dataIndex: 'createdon',
            sortable: true,
            width: 2
        },{
            header: _('sm.updatedon'),
            dataIndex: 'updatedon',
            sortable: true,
            width: 2
        },{
            header: _('user')+' '+_('id'),
            dataIndex: 'user_id',
            sortable: true,
            width: 2,
            hidden: true
		},{
			header: _('sm.fullname'),
			dataIndex: 'user_name',
			sortable: true,
			width: 3,
            hidden: (config.hideUser) ? true : false
		},{
            header: _('sm.username'),
            dataIndex: 'user_username',
            sortable: true,
            width: 2,
            hidden: (config.hideUser) ? true : false
        },{
			header: _('sm.reference'),
			dataIndex: 'reference',
			sortable: true,
			width: 3
		},{
			header: _('sm.method'),
			dataIndex: 'method',
			sortable: true,
			width: 2,
            renderer: function(val) {
                return _('sm.combo.'+val);
            }
		},{
			header: _('sm.amount'),
			dataIndex: 'amount',
			sortable: true,
			width: 1
		},{
			header: _('sm.completed'),
			dataIndex: 'completed',
			sortable: true,
			width: 1,
            renderer: function(val) {
                if (val === true) return '<span style="color: green">'+_('yes')+'</span>';
                else return '<span style="color: red">'+_('no')+'</span>';
            }
		}]
    });
    SM.grid.Transactions.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.Transactions,MODx.grid.Grid,{
    filterBySubscriber: function (cb, rec, ri) {
        this.getStore().baseParams['subscriber'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    filterByPaid: function (cb, rec, ri) {
        this.getStore().baseParams['method'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    searchTransactions: function(tf, nv, ov) {
        var store = this.getStore();
        store.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    clearFilter: function() {
        this.getStore().baseParams['query'] = '';
        if (!this.config.hideSubscribersCombo) this.getStore().baseParams['subscriber'] = '';
        this.getStore().baseParams['method'] = '';
        var prevBugs = (this.config.preventBugs) ? '2' : '';
        Ext.getCmp('sm-transaction-filter'+prevBugs).reset();
        Ext.getCmp('sm-subscriber-search'+prevBugs).reset();
        Ext.getCmp('sm-method-filter'+prevBugs).reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var d = r.data;

        var m = [];
        if (!d.completed && (d.method != 'complimentary')) {
            m.push({
                text: _('sm.transaction.markaspaid'),
                handler: function() {
                    win = new SM.window.MarkAsPaid({
                        record: {
                            transaction: this.getSelectionModel().getSelected().data.trans_id,
                            amount: this.getSelectionModel().getSelected().data.amount
                        },
                        parentid: this.id
                    });
                    win.show();
                }
            });
        }
        if (d.reference && (d.method == 'paypal')) {
            m.push({
                text: _('sm.transaction.transactiondetails'),
                handler: function() {
                    win = new SM.window.TransactionDetails({
                        reference: d.reference
                    });
                    win.show();
                }
            });
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
        else {
            m.push({
                text: _('sm.nooptions'),
                handler: function() { return false; }
            });
            this.addContextMenuItem(m);
        }
    }
});
Ext.reg('sm-grid-transactions',SM.grid.Transactions);