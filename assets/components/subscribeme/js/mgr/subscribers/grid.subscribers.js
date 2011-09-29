/**
 * SubscribeMe
 *
 * Copyright 2011 by Mark Hamstra <business@markhamstra.nl>
 *
 * This file is part of SubscribeMe, a subscriptions management extra for MODX Revolution
 *
 * SubscribeMe is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * SubscribeMe is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SubscribeMe; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
*/

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
                window.location.href = '?a='+MODx.action['csm/index']+'&action=subscriber';
            }
        },'-',{
            xtype: 'button',
            text: _('sm.button.exportsubs'),
            handler: function() {
                r = {
                    product: Ext.getCmp('sm-product-filter').getValue(),
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
                            this.blur(); // calling blur() will make the field lose focus, which in turn prevents it from resubmitting again when you click out of the field
                            return true;
                        },
                        scope: cmp
                    });
                },scope: this}
            }
        },'-',{
            xtype: 'sm-combo-product',
            emptyText: _('sm.combo.filter_on',{what: _('sm.products')}),
            id: 'sm-product-filter',
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
		fields: [
            {name: 'id', type: 'int'},
            {name: 'subscriptions', type: 'string'},
            {name: 'fullname', type: 'string'},
            {name: 'username', type: 'string'},
            {name: 'email', type: 'string'},
            {name: 'active', type: 'boolean'}
        ],
        paging: true,
        primaryKey: 'id',
		remoteSort: true,
        sortBy: 'fullname',
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
			'rowcontextmenu': function(grid, rowIndex, e) {
                var _contextMenu = new Ext.menu.Menu({
                    items: [{
                        text: _('update')+' '+_('user'),
                        handler: function(grid, rowIndex, e) {
                            var eid = Ext.getCmp('grid-subscribers').getSelectionModel().getSelected().data.id;
                            window.location.href = '?a='+MODx.action['csm/index']+'&action=subscriber&id='+eid;
                        }
                    }]
                });
                _contextMenu.showAt(e.getXY());
			}
		}
    });
    SM.grid.Subscribers.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.Subscribers,MODx.grid.Grid,{
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
        Ext.getCmp('sm-product-filter').reset();
        Ext.getCmp('sm-subs-search').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('sm-grid-subscribers',SM.grid.Subscribers);