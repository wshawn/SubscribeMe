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
            xtype: (config.hideSubscribersCombo) ? 'hidden' : 'sm-combo-subscribers',
            emptyText: _('sm.combo.filter_on',{what: _('user')}),
            id: 'sm-subscriptions-user-filter',
            width: 200,
            listeners: {
                'select': {fn: this.filterBySubscriber, scope: this}
            }
        },'-',{
            xtype: 'sm-combo-product',
            emptyText: _('sm.combo.filter_on',{what: _('sm.product')}),
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
        sortBy: 'start',
        fields: [
            {name: 'sub_id', type: 'int'},
            {name: 'user_id', type: 'int'},
            {name: 'product_id', type: 'int'},
            {name: 'user', type: 'string'},
            {name: 'product', type: 'string'},
            {name: 'product_price', type: 'string'},
            {name: 'product_periods', type: 'periods'},
            {name: 'product_period', type: 'period'},
            {name: 'pp_profileid', type: 'string'},
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
			header: _('sm.pp_profileid'),
			dataIndex: 'pp_profileid',
			sortable: true,
			width: 3
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
    });
    SM.grid.Subscriptions.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.Subscriptions,MODx.grid.Grid,{
    filterBySubscriber: function (cb, rec, ri) {
        this.getStore().baseParams['subscriber'] = rec.data['id'];
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
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
        this.getStore().baseParams['subscriber'] = '';
        Ext.getCmp('sm-subscriptions-user-filter').reset();
        Ext.getCmp('sm-subscriptions-product-filter').reset();
        Ext.getCmp('sm-subscriptions-search').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var d = r.data;

        var m = [];
        m.push({
            text: _('update')+' '+_('user'),
            handler: function(grid, rowIndex, e) {
                var eid = d.user_id;
                window.location.href = '?a='+MODx.action['csm/index']+'&action=subscriber&id='+eid;
            }
        },{
            text: _('sm.subscription.manualtransaction'),
            handler: function() {
                win = new SM.window.AddManualTransaction({
                    record: {
                        sub_id: d.sub_id,
                        user_id: d.user_id,
                        amount: d.product_price,
                        period: d.product_periods + ' ' + _('sm.combo.'+d.product_period)
                    }
                });
                win.show();
            }
        });

        m.push('-',{
            text: _('sm.subscription.viewtransactions'),
            handler: function() {
                win = new SM.window.ViewTransactions({
                    config: { subscription: Ext.getCmp('grid-subscriptions').getSelectionModel().getSelected().data.sub_id }
                });
                win.show();
            }
        });

        if (d.pp_profileid) {
            m.push('-',{
                text: _('sm.subscription.paypalprofile'),
                handler: function() {
                    win = new SM.window.PayPalProfile({
                        config: { pp_profileid: d.pp_profileid }
                    });
                    win.show();
                }
            });
            if (d.active) {
                m.push({
                    text: _('sm.subscription.cancel'),
                    handler: function() {
                        o = new MODx.Window({
                            title: _('sm.subscription.cancel'),
                            url: SM.config.connector_url,
                            baseParams: {
                                action: 'mgr/subscriptions/cancelpp'
                            },
                            fields: [{
                                xtype: 'panel',
                                html: '<p>'+_('sm.subscription.confirmcancel')+'</p>',
                                bodyStyle: 'padding-bottom: 12px;'
                            },{
                                fieldLabel: _('sm.product'),
                                name: 'product',
                                xtype: 'statictextfield',
                                value: d.product,
                                width: '100%'
                            },{
                                fieldLabel: _('sm.subscription') + ' ' + _('id'),
                                name: 'sub_id',
                                xtype: 'statictextfield',
                                value: d.sub_id,
                                submitValue: true,
                                width: '100%'
                            },{
                                fieldLabel: _('sm.pp_profileid'),
                                name: 'pp_profileid',
                                xtype: 'statictextfield',
                                value: d.pp_profileid,
                                width: '100%'
                            }],
                            saveBtnText: _('sm.subscription.docancel'),
                            cancelBtnText: _('sm.subscription.cancelcancel'),
                            listeners: {
                                'success': {fn: function(r) {
                                    Ext.getCmp('grid-subscriptions').refresh();
                                },scope: this},
                                'failure': {fn:function(r) {
                                    MODx.msg.alert(_('error'),r.message);
                                },scope: this}
                            }
                        });
                        o.show();
                    }
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
});
Ext.reg('sm-grid-subscriptions',SM.grid.Subscriptions);