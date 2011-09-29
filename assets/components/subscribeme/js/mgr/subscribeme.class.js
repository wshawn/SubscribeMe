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

var SM = function(config) {
    config = config || {};
    SM.superclass.constructor.call(this,config);
};
Ext.extend(SM,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},
    columnWrap: function(val) {
        return '<div style="word-wrap: normal !important;">'+ val +'</div>';
    }
});
Ext.reg('sm',SM);
SM = new SM();
