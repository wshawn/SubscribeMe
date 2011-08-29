Ext.onReady(function() {
    Ext.QuickTips.init();
    MODx.load({ xtype: 'sm-page-index'});
});

/*
Index page configuration.
 */
SM.page.Index = function(config) {
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
                title: _('users'),
                items: [{
                    xtype: 'sm-grid-subscribers',
                    border: false
                }]
            },{
                title: _('sm.subscriptions'),
                items: [{
                    //xtype: 'sm-grid-subscriptions',
                    border: false
                }]
            },{
                title: _('sm.transactions'),
                items: [{
                    xtype: 'sm-grid-transactions',
                    border: false
                }]
            },{
                title: _('sm.products'),
                items: [{
                    xtype: 'sm-grid-products',
                    border: false
                }]
            }]

        }]
    });
    SM.page.Index.superclass.constructor.call(this,config);
};
Ext.extend(SM.page.Index,MODx.Component);
Ext.reg('sm-page-index',SM.page.Index);

/*
Index page header configuration.
 */
SM.panel.Header = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('subscribeme')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        }]
    });
    SM.panel.Header.superclass.constructor.call(this,config);
};
Ext.extend(SM.panel.Header,MODx.Panel);
Ext.reg('sm-panel-header',SM.panel.Header);



