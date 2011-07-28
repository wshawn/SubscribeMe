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
