
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
        tbar: [/*{
            xtype: 'button',
            text: _('sm.button.add',{ what: _('sm.subscribers') } ),
            handler: function() {
                window.location.href = '?a='+MODx.action['csm/subscribers'];
            }

        }*/],
		fields: [
            {name: 'id', type: 'int'},
            {name: 'subscriptions', type: 'string'},
            {name: 'fullname', type: 'string'},
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
			header: _('sm.email'),
			dataIndex: 'email',
			sortable: true,
			width: 4
		},{
			header: _('sm.active'),
			dataIndex: 'active',
			sortable: true,
			width: 2
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
Ext.extend(SM.grid.Subscribers,MODx.grid.Grid);
Ext.reg('sm-grid-subscribers',SM.grid.Subscribers);