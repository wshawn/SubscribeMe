SM.window.NewPassword = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('change_password_new'),
        url: SM.config.connector_url,
        closeAction: 'close',
        baseParams: {
            action: 'mgr/subscribers/newpass'
        },
        fields: [{
            xtype: 'panel',
            html: '<p>' + _('sm.newpass.confirm') + '</p>'
        },{
            name: 'uid',
            fieldLabel: _('user') + ' ' + _('id'),
            xtype: 'statictextfield',
            submitValue: true
        },{
            name: 'username',
            fieldLabel: _('username'),
            xtype: 'statictextfield'
        }],
        listeners: {
            success: function(r) {
                MODx.msg.alert(_('change_password_new'),_('sm.passwordchanged',{email: r.a.result.message}));
            }
        }
    });
    SM.window.NewPassword.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.NewPassword,MODx.Window);
Ext.reg('sm-window-newpass',SM.window.NewPassword);