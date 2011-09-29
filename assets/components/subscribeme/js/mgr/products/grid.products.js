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

SM.grid.products = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		url: SM.config.connector_url,
		id: 'grid-products',
		baseParams: {
            action: 'mgr/products/getlist'
        },
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true
        },
        tbar: [{
            xtype: 'button',
            text: _('sm.button.add',{ what: _('sm.product') } ),
            handler: function() {
                win = new SM.window.Products();
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
            {name: 'product_id', type: 'int'},
            {name: 'name', type: 'string'},
            {name: 'description', type: 'string'},
            {name: 'sortorder', type: 'int'},
            {name: 'price', type: 'float'},
            {name: 'amount_shipping', type: 'float'},
            {name: 'amount_vat', type: 'float'},
            {name: 'periods', type: 'int'},
            {name: 'period', type: 'string'},
            {name: 'permissions', type: 'string'},
            {name: 'active', type: 'boolean'}
        ],
        paging: true,
        primaryKey: 'product_id',
		remoteSort: true,
        autosave: true,
        save_action: 'mgr/products/savefromgrid',
        sortBy: 'name',
		columns: [{
			header: _('id'),
			dataIndex: 'product_id',
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
			header: _('sm.amount_shipping'),
			dataIndex: 'amount_shipping',
			sortable: true,
			width: 2,
            editor: { xtype: 'numberfield' }
		},{
			header: _('sm.amount_vat'),
			dataIndex: 'amount_vat',
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
			header: _('sm.permissions'),
			dataIndex: 'permissions',
			sortable: true,
			width: 3,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
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
                            win = new SM.window.Products({
                                record: this.getSelectionModel().getSelected().data
                            });
                            win.show();
                        },
                        scope: this
                    },'-',{
                        text: _('remove')+' '+_('sm.product'),
                        handler: function(grid, rowIndex, e) {
                            MODx.msg.confirm({
                                title: _('remove',{what: _('sm.product')}),
                                text: _('confirm_remove'),
                                url: SM.config.connector_url,
                                params: {
                                    action: 'mgr/products/remove',
                                    eid: Ext.getCmp('grid-products').getSelectionModel().getSelected().data.product_id
                                },
                                listeners: {
                                    'success': { fn:function (r) {
                                        MODx.msg.status({
                                            title: _('sm.removed', {what: _('sm.products')}),
                                            message: _('sm.remove_successful', {what: _('sm.products')}),
                                            delay: 3
                                        });
                                        Ext.getCmp('grid-products').refresh();
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
    SM.grid.products.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.products,MODx.grid.Grid,{
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
Ext.reg('sm-grid-products',SM.grid.products);