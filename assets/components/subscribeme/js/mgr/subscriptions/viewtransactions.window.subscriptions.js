
SM.window.ViewTransactions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.subscription.viewtransactions'),
        closeAction: 'hide',
        width: '70%',
        fields: [{
            xtype: 'panel',
            html: _('sm.subscription.viewtransactions.text'),
            bodyStyle: 'padding-bottom: 12px;'
        },{
            xtype: 'sm-grid-viewsubscriptions',
            baseParams: {
                action: 'mgr/subscriptions/gettransactions',
                subscription: config.config.subscription || false
            }
        }],
        buttons: [{
            text: _('close'),
            scope: this,
            handler: function() { this.hide(); }
        }]
    });
    SM.window.ViewTransactions.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.ViewTransactions,MODx.Window);
Ext.reg('sm-window-viewsubscriptions',SM.window.ViewTransactions);


SM.grid.ViewTransactionsGrid = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		url: SM.config.connector_url,
		id: 'grid-subscription-transactions',
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true
        },
		fields: [
            {name: 'trans_id', type: 'int'},
            {name: 'user_id', type: 'int'},
            {name: 'sub_id', type: 'int'},
            {name: 'reference', type: 'string'},
            {name: 'method', type: 'string'},
            {name: 'amount', type: 'float'},
            {name: 'createdon', type: 'string'},
            {name: 'updatedon', type: 'string'}
        ],
        paging: true,
        primaryKey: 'trans_id',
		remoteSort: true,
        sortBy: 'createdon',
        pageSize: 5,
		columns: [{
			header: _('id'),
			dataIndex: 'trans_id',
			sortable: true,
			width: 1
		},{
			header: _('user')+' '+_('id'),
			dataIndex: 'user_id',
			sortable: true,
			width: 1
		},{
			header: _('sm.reference'),
            dataIndex: 'reference',
            sortable: true,
            width: 4,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
		},{
			header: _('sm.method'),
			dataIndex: 'method',
			sortable: true,
			width: 3,
            renderer: function(val) {
                return _('sm.combo.'+val);
            }
		},{
            header: _('sm.amount'),
            dataIndex: 'amount',
            sortable: true,
            width: 2
        },{
            header: _('sm.createdon'),
            dataIndex: 'createdon',
            sortable: true,
            width: 4
        },{
            header: _('sm.updatedon'),
            dataIndex: 'updatedon',
            sortable: true,
            width: 4
        }]
    });
    SM.grid.ViewTransactionsGrid.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.ViewTransactionsGrid,MODx.grid.Grid,{
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
Ext.reg('sm-grid-viewsubscriptions',SM.grid.ViewTransactionsGrid);