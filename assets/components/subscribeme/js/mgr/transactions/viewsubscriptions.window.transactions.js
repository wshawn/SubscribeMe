
SM.window.ViewSubscriptions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.transaction.viewsubscriptions'),
        closeAction: 'close',
        width: '70%',
        fields: [{
            xtype: 'panel',
            html: _('sm.transaction.viewsubscriptions.text'),
            bodyStyle: 'padding-bottom: 12px;'
        },{
            xtype: 'sm-grid-viewsubscribers',
            baseParams: {
                action: 'mgr/transactions/getsubscriptions',
                transaction: config.config.transaction || false
            }
        }],
        listeners: {
            success: function(result,form) {
                this.close();
                Ext.getCmp('grid-transactions').refresh();
            }
        },
        buttons: [{
            text: _('close'),
            scope: this,
            handler: function() { this.hide(); }
        }]
    });
    SM.window.ViewSubscriptions.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.ViewSubscriptions,MODx.Window);
Ext.reg('sm-window-viewsubscriptions',SM.window.ViewSubscriptions);


SM.grid.ViewSubscriptionsGrid = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		url: SM.config.connector_url,
		id: 'grid-transaction-subscriptions',
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true
        },

		fields: [
            {name: 'sub_id', type: 'int'},
            {name: 'subscription', type: 'string'},
            {name: 'user_id', type: 'int'},
            {name: 'fullname', type: 'string'},
            {name: 'username', type: 'string'},
            {name: 'start', type: 'string'},
            {name: 'end', type: 'string'},
            {name: 'active', type: 'boolean'}
        ],
        paging: true,
        primaryKey: 'user_id',
		remoteSort: true,
        sortBy: 'name',
		columns: [{
			header: _('id'),
			dataIndex: 'sub_id',
			sortable: true,
			width: 1
		},{
			header: _('sm.subscription'),
            dataIndex: 'subscription',
            sortable: true,
            width: 7,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
		},{
			header: _('sm.fullname'),
			dataIndex: 'fullname',
			sortable: true,
			width: 4
		},{
            header: _('sm.username'),
            dataIndex: 'username',
            sortable: true,
            width: 2
        },{
            header: _('sm.start'),
            dataIndex: 'start',
            sortable: true,
            width: 4
        },{
            header: _('sm.end'),
            dataIndex: 'end',
            sortable: true,
            width: 4
        },{
			header: _('sm.active'),
			dataIndex: 'active',
			sortable: true,
			width: 1,
            renderer: function(val) {
                if (val === true) return '<span style="color: green">'+_('yes')+'</span>';
                else return '<span style="color: red">'+_('no')+'</span>';
            }
		}]
		,listeners: {
	    }
    });
    SM.grid.ViewSubscriptionsGrid.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.ViewSubscriptionsGrid,MODx.grid.Grid);
Ext.reg('sm-grid-viewsubscribers',SM.grid.ViewSubscriptionsGrid);