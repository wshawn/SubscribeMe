
SM.panel.Profile = function(config) {
    config = config || {};
    Ext.apply(config,{
        url: SM.config.connector_url,
        baseParams: {
            action: 'mgr/subscribers/save',
            id: (SM.record) ? SM.record.id : 0
        },
        layout: 'fit',
        id: 'sm-panel-subscribers',
        border: false,
        defaults: {
            autoHeight: true,
            deferredRender: false
        },
        deferredRender: false,
        forceLayout: true,
        baseCls: 'modx-formpanel',
        width: '98%',
        items: [{
            xtype: 'modx-tabs',
            width: '100%',
            items: [{
                title: _('sm.subscriber.account'), // Account information
                layout: 'form',
                labelWidth: 175,
                border: true,
                bodyStyle: 'padding: 10px;',
                defaults: {
                    width: '80%',
                    border: false,
                    layout: 'form'
                },
                items: [{
                    xtype: 'hidden',
                    name: 'id'
                },{
                    xtype: 'checkbox',
                    name: 'active',
                    fieldLabel: _('sm.active')
                },{
                    xtype: 'textfield',
                    name: 'username',
                    fieldLabel: _('username'),
                    allowBlank: false
                },{
                    xtype: 'textfield',
                    fieldLabel: _('email'),
                    name: 'email',
                    allowBlank: false
                },{
                    xtype: (SM.record) ? 'button' : 'hidden',
                    text: (SM.record) ? _('change_password_new') : '',
                    fieldLabel: _('password'),
                    handler: (SM.record) ? this.changePassWindow : null,
                    width: 200
                }]
            },{
                title: _('sm.subscriber.profile'),
                layout: 'form',
                labelWidth: 175,
                border: true,
                bodyStyle: 'padding: 10px;',
                defaults: {
                    width: '80%',
                    border: false
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('user_full_name'),
                    name: 'fullname',
                    allowBlank: false
                },{
                    xtype: 'textfield',
                    fieldLabel: _('user_phone'),
                    name: 'phone'
                },{
                    xtype: 'textfield',
                    fieldLabel: _('user_mobile'),
                    name: 'mobilephone'
                },{
                    xtype: 'datefield',
                    fieldLabel: _('user_dob'),
                    name: 'dob',
                    format: MODx.config.manager_date_format
                },{
                    xtype: 'modx-combo-gender',
                    fieldLabel: _('user_gender'),
                    name: 'gender',
                    hiddenName: 'gender',
                    format: MODx.config.manager_date_format
                },{
                    xtype: 'textarea',
                    fieldLabel:  _('address'),
                    grow: true,
                    name: 'address'
                },{
                    name: 'city',
                    fieldLabel: _('city'),
                    xtype: 'textfield'
                },{
                    name: 'state',
                    fieldLabel: _('user_state'),
                    xtype: 'textfield'
                },{
                    name: 'zip',
                    fieldLabel: _('user_zip'),
                    xtype: 'textfield'
                },{
                    fieldLabel: _('user_country'),
                    xtype: 'modx-combo-country',
                    value: '',
                    name: 'country'
                },{
                    xtype: 'textarea',
                    grow: true,
                    name: 'comment',
                    fieldLabel: _('comment')
                },{
                    xtype: 'statictextfield',
                    fieldLabel: _('user_logincount'),
                    name: 'logincount'
                },{
                    xtype: 'statictextfield',
                    fieldLabel: _('user_failedlogincount'),
                    name: 'failedlogincount'
                },{
                    xtype: 'statictextfield',
                    fieldLabel: _('user_prevlogin'),
                    name: 'lastlogin'
                }]
            }]
        }],
        listeners: {
            'success': function (res) {
                if (SM.record) {
                    MODx.msg.status({title: _('save_successful'), delay: 3});
                } else {
                    window.location.href = '?a='+MODx.request['a']+'&action=subscriber&id='+res.result.message;
                }
            }
        }
    });
    SM.panel.Profile.superclass.constructor.call(this,config);
};
Ext.extend(SM.panel.Profile,MODx.FormPanel,{
    changePassWindow: function() {
        cp = new SM.window.NewPassword({
            record: {
                uid: SM.record.id,
                username: SM.record.username
            }
        });
        cp.show();
    }
});
Ext.reg('sm-panel-subscribers',SM.panel.Profile);

/* From MODX 2.1.1-pl, modx.panel.user.js, line 514 */
/**
 * Displays a gender combo
 *
 * @class MODx.combo.Gender
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-gender
 */
MODx.combo.Gender = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [['',0],[_('user_male'),1],[_('user_female'),2]]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
    });
    MODx.combo.Gender.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Gender,Ext.form.ComboBox);
Ext.reg('modx-combo-gender',MODx.combo.Gender);