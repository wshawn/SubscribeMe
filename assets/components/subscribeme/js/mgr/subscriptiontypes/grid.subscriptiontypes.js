
SM.grid.SubscriptionTypes = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		url: SM.config.connector_url,
		id: 'grid-subscriptiontypes',
		baseParams: {
            action: 'mgr/subscriptiontypes/getlist'
        },
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true
        },
        tbar: [{
            xtype: 'button',
            text: _('sm.button.add',{ what: _('sm.subscriptiontype') } ),
            handler: function() {
                win = new SM.window.SubscriptionTypes();
                win.show();
            }
        },'->',{
            xtype: 'textfield',
            id: 'sm-subtypes-search',
            emptyText: _('sm.search...'),
            listeners: {
                'change': { fn:this.searchSubTypes, scope:this},
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
            xtype: 'button',
            text: _('sm.button.clearfilter'),
            listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }],
		fields: [
            {name: 'type_id', type: 'int'},
            {name: 'name', type: 'string'},
            {name: 'description', type: 'string'},
            {name: 'sortorder', type: 'int'},
            {name: 'price', type: 'float'},
            {name: 'periods', type: 'int'},
            {name: 'period', type: 'string'},
            {name: 'usergroup', type: 'int'},
            {name: 'role', type: 'int'},
            {name: 'active', type: 'boolean'}
        ],
        paging: true,
        primaryKey: 'type_id',
		remoteSort: true,
        autosave: true,
        save_action: 'mgr/subscriptiontypes/savefromgrid',
        sortBy: 'name',
		columns: [{
			header: _('id'),
			dataIndex: 'type_id',
			sortable: true,
			width: 1
		},{
			header: _('sm.name'),
            dataIndex: 'name',
            sortable: true,
            width: 4,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            },
            editor: { xtype: 'textfield' }
		},{
			header: _('sm.description'),
			dataIndex: 'description',
			sortable: true,
			width: 5,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            },
            editor: { xtype: 'textarea' }
		},{
			header: _('sm.price'),
			dataIndex: 'price',
			sortable: true,
			width: 2,
            editor: { xtype: 'numberfield' }
		},{
			header: _('sm.periods'),
			dataIndex: 'periods',
			sortable: true,
			width: 2,
            editor: { xtype: 'numberfield', allowDecimals: false, allowNegative: false }
		},{
			header: _('sm.period'),
			dataIndex: 'period',
			sortable: true,
			width: 2,
            editor: { xtype: 'sm-combo-period', renderer: true }
		},{
			header: _('sm.usergroup'),
			dataIndex: 'usergroup',
			sortable: true,
			width: 3,
            editor: { xtype: 'modx-combo-usergroup', renderer: false }
		},{
			header: _('sm.role'),
			dataIndex: 'role',
			sortable: true,
			width: 2,
            editor: { xtype: 'modx-combo-usergrouprole', renderer: false }
		},{
            header: _('sm.sortorder'),
            dataIndex: 'sortorder',
            sortable: true,
            width: 2,
            editor: { xtype: 'numberfield' }
        },{
			header: _('sm.active'),
			dataIndex: 'active',
			sortable: true,
			width: 2,
            renderer: function(val) {
                if (val === true) return '<span style="color: green">'+_('yes')+'</span>';
                else return '<span style="color: red">'+_('no')+'</span>';
            },
            editor: { xtype: 'modx-combo-boolean', renderer: false }
		}]
		,listeners: {
			'rowcontextmenu': function(grid, rowIndex, e) {
                var _contextMenu = new Ext.menu.Menu({
                    items: [{
                        text: _('update'),
                        handler: function(grid, rowIndex, e) {
                            win = new SM.window.SubscriptionTypes({
                                record: this.getSelectionModel().getSelected().data
                            });
                            win.show();
                        },
                        scope: this
                    },'-',{
                        text: _('remove')+' '+_('sm.subscriptiontype'),
                        handler: function(grid, rowIndex, e) {
                            MODx.msg.confirm({
                                title: _('remove',{what: _('sm.subscriptiontype')}),
                                text: _('confirm_remove'),
                                url: SM.config.connector_url,
                                params: {
                                    action: 'mgr/subscriptiontypes/remove',
                                    eid: Ext.getCmp('grid-subscriptiontypes').getSelectionModel().getSelected().data.type_id
                                },
                                listeners: {
                                    'success': { fn:function (r) {
                                        MODx.msg.status({
                                            title: _('sm.removed', {what: _('sm.subscriptiontypes')}),
                                            message: _('sm.remove_successful', {what: _('sm.subscriptiontypes')}),
                                            delay: 3
                                        });
                                        Ext.getCmp('grid-subscriptiontypes').refresh();
                                    }, scope: true}
                                }
                            });
                        }
                    }]
                });
                _contextMenu.showAt(e.getXY());
			}
		}
    });
    SM.grid.SubscriptionTypes.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.SubscriptionTypes,MODx.grid.Grid,{
    searchSubTypes: function(tf, nv, ov) {
        var store = this.getStore();
        store.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    clearFilter: function() {
        this.getStore().baseParams['query'] = '';
        Ext.getCmp('sm-subtypes-search').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('sm-grid-subscriptiontypes',SM.grid.SubscriptionTypes);