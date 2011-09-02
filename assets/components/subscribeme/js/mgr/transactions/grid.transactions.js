
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
            id: 'sm-subscriber-search',
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
            id: 'sm-transaction-filter',
            width: 200,
            listeners: {
                'select': {fn: this.filterBySubscriber, scope: this}
            }
        },'-',{
            xtype: 'sm-combo-transactionsmethod',
            emptyText: _('sm.combo.filter_on',{what: _('sm.combo.method')}),
            id: 'sm-method-filter',
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
            {name: 'amount', type: 'float'},
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
			width: 3
		},{
            header: _('sm.username'),
            dataIndex: 'user_username',
            sortable: true,
            width: 2
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
            editor: { xtype: 'modx-combo-boolean', renderer: true },
            editable: false
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
        Ext.getCmp('sm-transaction-filter').reset();
        Ext.getCmp('sm-subscriber-search').reset();
        Ext.getCmp('sm-method-filter').reset();
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
                        }
                    });
                    win.show();
                }
            });
        }
        else {
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
Ext.reg('sm-grid-transactions',SM.grid.Transactions);