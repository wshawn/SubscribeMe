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

SM.combo.Product = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'product',
        hiddenName: 'product',
        displayField: 'display',
        valueField: 'id',
        fields: ['id','display'],
        url: SM.config.connector_url,
        baseParams: {
            action: 'mgr/products/get_combo_filter',
            options: (config.hideOptions) ? 0 : 1
        }
    });
    SM.combo.Product.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.Product,MODx.combo.ComboBox);
Ext.reg('sm-combo-product',SM.combo.Product);

SM.combo.Period = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'period',
        hiddenName: 'period',
        displayField: 'd',
        valueField: 'v',
        mode: 'local',
        store: new Ext.data.SimpleStore({
            fields: ['d','v'],
            data: [[_('sm.combo.day'),'D'],[_('sm.combo.week'),'W'],[_('sm.combo.month'),'M'],[_('sm.combo.year'),'Y']]
        })
    });
    SM.combo.Period.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.Period,MODx.combo.ComboBox);
Ext.reg('sm-combo-period',SM.combo.Period);