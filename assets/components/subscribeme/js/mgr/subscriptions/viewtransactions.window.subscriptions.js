
SM.window.ViewTransactions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.subscription.viewtransactions'),
        closeAction: 'close',
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
        listeners: {
            success: function(result,form) {
                this.close();
                Ext.getCmp('grid-subscriptions').refresh();
            }
        },
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
/*      <field key="user_id" dbtype="int" precision="11" phptype="integer" null="false" index="fk" generated="native" attributes="unsigned" />
        <field key="sub_id" dbtype="int" precision="11" phptype="integer" null="false" index="fk" generated="native" attributes="unsigned" />

        <field key="reference" dbtype="varchar" precision="256" phptype="string" null="true" default="" />
        <field key="method" dbtype="varchar" precision="25" phptype="string" null="true" default="" />
        <field key="amount" dbtype="float" precision="10,2" phptype="string" null="true" default="" />

        <field key="completed" dbtype="tinyint" precision="1" phptype="boolean" null="true" default="0" />

        <field key="createdon" dbtype="timestamp" phptype="timestamp" null="false" default="CURRENT_TIMESTAMP" />
        <field key="updatedon" dbtype="timestamp" phptype="timestamp" null="false" />*/
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
			width: 3
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
		,listeners: {
	    }
    });
    SM.grid.ViewTransactionsGrid.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.ViewTransactionsGrid,MODx.grid.Grid);
Ext.reg('sm-grid-viewsubscriptions',SM.grid.ViewTransactionsGrid);