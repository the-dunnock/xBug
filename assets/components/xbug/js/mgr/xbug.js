var xBug = function(config) {
    config = config || {};
    xBug.superclass.constructor.call(this,config);
};
Ext.extend(xBug,Ext.Component,{
    page:{},window:{},grid:{},panel:{},combo:{},config: {},view: {}, stores : {}, extra : {}
});
Ext.reg('xbug',xBug);

xBug = new xBug();