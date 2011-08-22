
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
            xtype: 'sm-combo-subscribers',
            emptyText: _('sm.combo.filter_on',{what: _('sm.subscribers')}),
            id: 'sm-subscriber-filter',
            width: 200,
            listeners: {
                'select': {fn: this.filterBySubscriber, scope: this}
            }
        },'-',{
            xtype: 'sm-combo-transactionspaid',
            emptyText: _('sm.combo.filter_on',{what: _('sm.combo.paid')}),
            id: 'sm-paid-filter',
            width: 100,
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
            {name: 'user_name', type: 'string'},
            {name: 'user_username', type: 'string'},
            {name: 'reference', type: 'string'},
            {name: 'method', type: 'string'},
            {name: 'amount', type: 'float'},
            {name: 'completed', type: 'boolean'},
            {name: 'createdon', type: 'string'},
            {name: 'updatedon', type: 'string'}
        ],
        paging: true,
        primaryKey: 'trans_id',
		remoteSort: true,
        sortBy: 'name',
		columns: [{
			header: _('id'),
			dataIndex: 'trans_id',
			sortable: true,
			width: 1
		},{
			header: _('sm.generated'),
			dataIndex: 'createdon',
			sortable: true,
			width: 2
		},{
			header: _('sm.updatedon'),
			dataIndex: 'updatedon',
			sortable: true,
			width: 2
		},{
			header: _('sm.subscriber')+' '+_('id'),
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
			width: 2
		},{
			header: _('sm.amount'),
			dataIndex: 'amount',
			sortable: true,
			width: 2
		},{
			header: _('sm.completed'),
			dataIndex: 'completed',
			sortable: true,
			width: 2,
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
        this.getStore().baseParams['paid'] = rec.data['id'];
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
        this.getStore().baseParams['subscriber'] = '';
        this.getStore().baseParams['paid'] = '';
        Ext.getCmp('sm-subscriber-filter').reset();
        Ext.getCmp('sm-subscriber-search').reset();
        Ext.getCmp('sm-paid-filter').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var d = r.data;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
        } else {
            if (d.completed == false) {
                m.push({
                    text: _('sm.transaction.markaspaid'),
                    handler: function() {
                        win = new SM.window.MarkAsPaid({
                            record: {transaction: this.getSelectionModel().getSelected().data.trans_id}
                        });
                        win.show();
                    }
                });
            }
            m.push({
                text: _('sm.transaction.viewsubscriptions'),
                handler: function() {
                    win = new SM.window.ViewSubscriptions({
                        config: { transaction: this.getSelectionModel().getSelected().data.trans_id }
                    });
                    win.show();}
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
});
Ext.reg('sm-grid-transactions',SM.grid.Transactions);