
SM.grid.ProductPermissions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		url: SM.config.connector_url,
		baseParams: {
            action: 'mgr/productpermissions/getlist',
            product: config.product || 0
        },
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true
        },
        tbar: [{
            xtype: 'button',
            text: _('sm.button.add',{ what: _('sm.productpermission') } ),
            handler: function() {
                win = new SM.window.ProductPermissions({
                    record: { product_id: config.product },
                    win: this.id
                });
                win.show();
            }
        }],
		fields: [
            {name: 'id', type: 'int'},
            {name: 'product_id', type: 'int'},
            {name: 'usergroup', type: 'int'},
            {name: 'role', type: 'int'}
        ],
        primaryKey: 'id',
		remoteSort: true,
        autosave: true,
        paging: false,
        save_action: 'mgr/productpermissions/savefromgrid',
        sortBy: 'name',
		columns: [{
			header: _('id'),
			dataIndex: 'id',
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
			header: _('sm.usergroup'),
            dataIndex: 'usergroup',
            sortable: true,
            width: 5,
            editor: { xtype: 'modx-combo-usergroup', renderer: true }
		},{
			header: _('sm.role'),
			dataIndex: 'role',
			sortable: true,
			width: 5,
            editor: { xtype: 'modx-combo-usergrouprole', renderer: true }
		}]
		,listeners: {
			'rowcontextmenu': function(grid, rowIndex, e) {
                var _contextMenu = new Ext.menu.Menu({
                    items: [{
                        text: _('update'),
                        handler: function(grid, rowIndex, e) {
                            win = new SM.window.ProductPermissions({
                                record: this.getSelectionModel().getSelected().data,
                                win: this.id
                            });
                            win.show();
                        },
                        scope: this
                    },'-',{
                        text: _('remove')+' '+_('sm.productpermission'),
                        handler: function(grid, rowIndex, e) {
                            MODx.msg.confirm({
                                title: _('remove',{what: _('sm.productpermission')}),
                                text: _('confirm_remove'),
                                url: SM.config.connector_url,
                                params: {
                                    action: 'mgr/productpermissions/remove',
                                    eid: this.getSelectionModel().getSelected().data.id
                                },
                                listeners: {
                                    'success': { fn:function (r) {
                                        MODx.msg.status({
                                            title: _('sm.removed', {what: _('sm.productpermissions')}),
                                            message: _('sm.remove_successful', {what: _('sm.productpermissions')}),
                                            delay: 3
                                        });
                                        Ext.getCmp(this.id).refresh();
                                        Ext.getCmp('grid-products').refresh();
                                    }, scope: this}
                                }
                            });
                        },
                        scope: this
                    }]
                });
                _contextMenu.showAt(e.getXY());
			}
		}
    });
    SM.grid.ProductPermissions.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.ProductPermissions,MODx.grid.Grid);
Ext.reg('sm-grid-productpermissions',SM.grid.ProductPermissions);