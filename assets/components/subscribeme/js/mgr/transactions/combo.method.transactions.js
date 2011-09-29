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

SM.combo.TransactionsMethod = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'paid',
        hiddenName: 'paid',
        displayField: 'display',
        valueField: 'id',
        mode: 'local',
        store: new Ext.data.SimpleStore({
            fields: ['display','id'],
            data: [[_('sm.combo.paypal'),'paypal'],[_('sm.combo.manual'),'manual'],[_('sm.combo.complimentary'),'complimentary']]
        }),
        baseParams: {
            action: 'mgr/transactions/get_combo_paid_filter'
        },
        typeAhead: true
    });
    SM.combo.TransactionsMethod.superclass.constructor.call(this,config);
};
Ext.extend(SM.combo.TransactionsMethod,MODx.combo.ComboBox);
Ext.reg('sm-combo-transactionsmethod',SM.combo.TransactionsMethod);