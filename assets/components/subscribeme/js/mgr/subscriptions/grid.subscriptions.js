
SM.grid.Subscriptions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: SM.config.connector_url,
        id: 'grid-subscriptions',
        baseParams: {
            action: 'mgr/subscriptions/getlist'
        },
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true
        },
        tbar: [{
            xtype: 'button',
            text: _('sm.button.add',{ what: _('sm.freesubscription') } ),
            handler: function() {
                win = new SM.window.AddSubscription({
                    record: {
                        user_id: (SM.record) ? SM.record['id'] : 0
                    }
                });
                win.show();
            }
        },'->',{
            xtype: 'textfield',
            id: 'sm-subscriptions-search',
            emptyText: _('sm.search...'),
            listeners: {
                'change': { fn:this.searchSubs, scope:this},
                'render': { fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER,
                        fn: function() {
                            this.fireEvent('change',this);
                            this.blur(); // calling blur() will make the field lose focus, which in turn prevents it from resubmitting again when you click out of the field
                            return true;
                        },
                        scope: cmp
                    });
                },scope: this}
            }
        },'-',{
            xtype: 'sm-combo-product',
            emptyText: _('sm.combo.filter_on',{what: _('sm.subscriptions')}),
            id: 'sm-subscriptions-product-filter',
            width: 200,
            listeners: {
                'select': {fn: this.filterByProduct, scope: this}
            }
        },'-',{
            xtype: 'button',
            text: _('sm.button.clearfilter'),
            listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }],
        paging: true,
        primaryKey: 'sub_id',
        remoteSort: true,
        sortBy: 'sub_id',
        fields: [
            {name: 'sub_id', type: 'int'},
            {name: 'user_id', type: 'int'},
            {name: 'product_id', type: 'int'},
            {name: 'user', type: 'string'},
            {name: 'product', type: 'string'},
            {name: 'start', type: 'string'},
            {name: 'expires', type: 'string'},
            {name: 'active', type: 'boolean'}
        ],
        columns: [{
			header: _('id'),
			dataIndex: 'sub_id',
			sortable: true,
			width: 1
		},{
			header: _('user')+' '+_('id'),
			dataIndex: 'user_id',
			sortable: true,
			width: 1,
            hidden: true
		},{
			header: _('sm.product')+' '+_('id'),
			dataIndex: 'product_id',
			sortable: true,
			width: 1,
            hidden: true
		},{
			header: _('user'),
            dataIndex: 'user',
            sortable: true,
            width: 3,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
		},{
			header: _('sm.product'),
            dataIndex: 'product',
            sortable: true,
            width: 7,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
		},{
			header: _('sm.start'),
			dataIndex: 'start',
			sortable: true,
			width: 3
		},{
            header: _('sm.expires'),
            dataIndex: 'expires',
            sortable: true,
            width: 3
        },{
			header: _('sm.active'),
			dataIndex: 'active',
			sortable: true,
			width: 2,
            renderer: function(val) {
                if (val === true) return '<span style="color: green">'+_('yes')+'</span>';
                else return '<span style="color: red">'+_('no')+'</span>';
            }
		}]
        ,listeners: {
			'rowcontextmenu': function(grid, rowIndex, e) {
                var _contextMenu = new Ext.menu.Menu({
                    items: [{
                        text: _('update')+' '+_('user'),
                        handler: function(grid, rowIndex, e) {
                            var eid = Ext.getCmp('grid-subscriptions').getSelectionModel().getSelected().data.sub_id;
                            window.location.href = '?a='+MODx.action['csm/index']+'&action=subscriber&id='+eid;
                        }
                    },{
                        text: _('sm.subscription.viewtransactions'),
                        handler: function() {
                            win = new SM.window.ViewTransactions({
                                config: { subscription: Ext.getCmp('grid-subscriptions').getSelectionModel().getSelected().data.sub_id }
                            });
                            win.show();}
                    }]
                });
                _contextMenu.showAt(e.getXY());
			}
		}
    });
    SM.grid.Subscriptions.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.Subscriptions,MODx.grid.Grid,{
    filterByProduct: function (cb, rec, ri) {
        this.getStore().baseParams['product'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    searchSubs: function(tf, nv, ov) {
        var store = this.getStore();
        store.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    clearFilter: function() {
        this.getStore().baseParams['query'] = '';
        this.getStore().baseParams['product'] = '';
        Ext.getCmp('sm-subscriptions-product-filter').reset();
        Ext.getCmp('sm-subscriptions-search').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('sm-grid-subscriptions',SM.grid.Subscriptions);