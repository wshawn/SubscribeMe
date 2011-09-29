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

SM.window.PayPalProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('sm.subscription.paypalprofile'),
        closeAction: 'hide',
        width: 700,
        fields: [{
            xtype: 'panel',
            html: _('sm.subscription.paypalprofile.text'),
            bodyStyle: 'padding-bottom: 12px;'
        },{
            xtype: 'sm-grid-viewprofileinformationgrid',
            baseParams: {
                action: 'mgr/subscriptions/getppprofile',
                profileid: config.config.pp_profileid
            }
        }],
        buttons: [{
            text: _('close'),
            scope: this,
            handler: function() { this.hide(); }
        }]
    });
    SM.window.PayPalProfile.superclass.constructor.call(this,config);
};
Ext.extend(SM.window.PayPalProfile,MODx.Window);
Ext.reg('sm-window-viewprofileinformation',SM.window.PayPalProfile);


SM.grid.PayPalProfileGrid = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: SM.config.connector_url,
        id: 'grid-subscription-paypalprofile',
        params: [],
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            emptyText: 'No data, or still processing request.'
        },
        fields: [
            {name: 'col1', type: 'string'},
            {name: 'col2', type: 'string'}
        ],
        paging: false,
        columns: [{
            header: _('sm.col1'),
            dataIndex: 'col1',
            sortable: true,
            width: 5,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
        },{
            header: _('sm.col2'),
            dataIndex: 'col2',
            sortable: true,
            width: 5,
            renderer: function(val) {
                return '<div style="white-space: normal !important;">'+ val +'</div>';
            }
        }]
    });
    SM.grid.PayPalProfileGrid.superclass.constructor.call(this,config);
};
Ext.extend(SM.grid.PayPalProfileGrid,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var d = r.data;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
        } else {
            m.push({
                text: _('sm.nooptions'),
                handler: function() { return false; }
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
});
Ext.reg('sm-grid-viewprofileinformationgrid',SM.grid.PayPalProfileGrid);