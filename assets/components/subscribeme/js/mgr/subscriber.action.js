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

Ext.onReady(function() {
    Ext.QuickTips.init();
    var o = MODx.load({ xtype: 'sm-page-subscriber'});
    if ((SM.record) && (Ext.getCmp('sm-panel-subscribers'))) {
        Ext.getCmp('sm-panel-subscribers').getForm().setValues(SM.record);
    }
    o.show();
});

/*
Subscriber page configuration.
 */
SM.page.Subscriber = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        renderTo: 'subscribeme',
        buttons: [{
            process: 'submit',
            text: _('save'),
            handler: function () {
                var panel = Ext.getCmp('sm-panel-subscribers');
                if (panel.getForm().isValid()) {
                    Ext.getCmp('sm-panel-subscribers').submit();
                } else {
                    MODx.msg.alert(_('error'),_('correct_errors'))
                }
            }
        },'-',{
            process: 'cancel',
            text: _('sm.back'),
            handler: function () {
                window.location.href = '?a='+MODx.request['a'];
            }
        }],
        components: [{
            xtype: 'sm-panel-header'
        },{
            xtype: 'modx-tabs',
            width: '98%',
            bodyStyle: 'padding: 10px 10px 10px 10px;',
            border: true,
            defaults: {
                border: false,
                autoHeight: true,
                bodyStyle: 'padding: 5px 8px 5px 5px;'
            },
            items: [{
                title: _('sm.subscriber'),
                items: [{
                    xtype: 'sm-panel-subscribers',
                    border: false
                }]
            },{
                title: _('sm.subscriptions'),
                items: [{
                    xtype: 'sm-grid-subscriptions',
                    baseParams: {
                        action: 'mgr/subscriptions/getlist',
                        subscriber: (SM.record) ? SM.record['id'] : 0
                    },
                    border: false,
                    hideSubscribersCombo: true

                }],
                disabled: (SM.record) ? false : true
            },{
                title: _('sm.transactions'),
                disabled: (SM.record) ? false : true,
                items: [{
                    xtype: 'sm-grid-transactions',
                    border: false,
                    baseParams: {
                        action: 'mgr/transactions/getlist',
                        subscriber: (SM.record) ? SM.record['id'] : 0
                    },
                    hideSubscribersCombo: true
                }]
            }]

        }]
    });
    SM.page.Subscriber.superclass.constructor.call(this,config);
};
Ext.extend(SM.page.Subscriber,MODx.Component);
Ext.reg('sm-page-subscriber',SM.page.Subscriber);

/*
Subscriber page header configuration.
 */
SM.panel.Header = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('subscribeme')+': '+((SM.record) ? SM.record.fullname+' ('+SM.record.username+')' : _('sm.button.add',{what: _('user')})) + '</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        }]
    });
    SM.panel.Header.superclass.constructor.call(this,config);
};
Ext.extend(SM.panel.Header,MODx.Panel);
Ext.reg('sm-panel-header',SM.panel.Header);



