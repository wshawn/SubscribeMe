
SM.grid.Subscribers = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		url: SM.config.connector_url,
		id: 'grid-subscribers',
		baseParams: {
            action: 'mgr/subscribers/getlist'
        },
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true
        },
        tbar: [{
            xtype: 'button',
            text: _('sm.button.add',{ what: _('sm.subscriber') } ),
            handler: function() {
                window.location.href = '?a='+MODx.action['csm/subscribers'];
            }
        },'-',{
            xtype: 'button',
            text: _('sm.button.exportsubs'),
            handler: function() {
                r = {
                    subscriptiontype: Ext.getCmp('sm-subscriptiontype-filter').getValue(),
                    search: Ext.getCmp('sm-subs-search').getValue(),
                    limit: 500
                };
                o = new SM.window.ExportSubscribers({
                    record: r
                });
                o.show();
            }
        },'->',{
            xtype: 'textfield',
            id: 'sm-subs-search',
            emptyText: _('sm.search...'),
            listeners: {
                'change': { fn:this.searchSubs, scope:this},
                'render': { fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER,
                        fn: function() {
                            this.fireEvent('change',this);
                            //this.blur();
                            return true;
                        },
                        scope: cmp
                    });
                },scope: this}
            }
        },'-',{
            xtype: 'sm-combo-subscriptiontype',
            emptyText: _('sm.combo.filter_on',{what: _('sm.subscriptions')}),
            id: 'sm-subscriptiontype-filter',
            width: 200,
            listeners: {
                'select': {fn: this.filterBySubscriptionType, scope: this}
            }
        }],
		fields: [
            {name: 'id', type: 'int'},
            {name: 'subscriptions', type: 'string'},
            {name: 'fullname', type: 'string'},
            {name: 'username', type: 'string'},
            {name: 'email', type: 'string'},
            {name: 'active', type: 'boolean'}
        ],
        paging: true,
        primaryKey: 'user_id',
		remoteSort: true,
        sortBy: 'name',
		columns: [{
			header: _('id'),
			dataIndex: 'id',
			sortable: true,
			width: 1
		},{
			header: _('sm.subscriptions'),
            dataIndex: 'subscriptions',
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
			header: _('sm.email'),
			dataIndex: 'email',
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
			/*'rowcontextmenu': function(grid, rowIndex, e) {
                var _contextMenu = new Ext.menu.Menu({
                    items: [{
                        text: _('update'),
                        handler: function(grid, rowIndex, e) {
                            var eid = Ext.getCmp('grid-subscribers').getSelectionModel().getSelected().data.subscribers_id;
                            window.location.href = '?a='+MODx.action['csm/subscribers']+'&id='+eid;
                        }
                    },'-',{
                        text: _('remove')+' '+_('sm.subscribers'),
                        handler: function(grid, rowIndex, e) {
                            MODx.msg.confirm({
                                title: _('sm.remove',{what: _('sm.subscribers')}),
                                text: _('confirm_remove'),
                                url: SM.config.connector_url,
                                params: {
                                    action: 'mgr/subscribers/remove',
                                    eid: Ext.getCmp('grid-subscribers').getSelectionModel().getSelected().data.subscribers_id
                                },
                                listeners: {
                                    'success': { fn:function (r) {
                                        MODx.msg.status({
                                            title: _('sm.removed', {what: _('sm.subscribers')}),
                                            message: _('sm.remove_successful', {what: _('sm.subscribers')}),
                                            delay: 3
                                        });
                                        Ext.getCmp('grid-subscribers').refresh();
                                    }, scope: true}
                                }
                            });
                        }
                    }]
                });
                _contextMenu.showAt(e.getXY());
			}
		*/}
    });
    SM.grid.Subscribers.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.Subscribers,MODx.grid.Grid,{
    filterBySubscriptionType: function (cb, rec, ri) {
        console.log(cb, rec, ri, rec.data);
        this.getStore().baseParams['subscriptiontype'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    searchSubs: function(tf, nv, ov) {
        var store = this.getStore();
        store.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('sm-grid-subscribers',SM.grid.Subscribers);