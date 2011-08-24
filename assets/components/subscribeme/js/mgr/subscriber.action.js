Ext.onReady(function() {
    Ext.QuickTips.init();
    MODx.load({ xtype: 'sm-page-subscriber'});
});

/*
Subscriber page configuration.
 */
SM.page.Subscriber = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        renderTo: 'subscribeme',
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
                    //xtype: 'sm-panel-subscribers',
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
                    border: false
                }],
                disabled: (SM.record) ? false : true
            },{
                title: _('sm.transactions'),
                items: [{
                    xtype: 'sm-grid-transactions',
                    border: false,
                    baseParams: {
                        action: 'mgr/transactions/getlist',
                        subscriber: (SM.record) ? SM.record['id'] : 0
                    },
                    hideSubscribersCombo: true
                }],
                disabled: (SM.record) ? false : true
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
            html: '<h2>'+_('subscribeme')+': '+_('sm.subscriber')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        }]
    });
    SM.panel.Header.superclass.constructor.call(this,config);
};
Ext.extend(SM.panel.Header,MODx.Panel);
Ext.reg('sm-panel-header',SM.panel.Header);



