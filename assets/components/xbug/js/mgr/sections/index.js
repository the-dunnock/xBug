Ext.onReady(function() {
    MODx.load({ xtype: 'xbug-page-index'});
});

xBug.page.Index = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout : 'fit',
        components: [{
            xtype: 'xbug-panel-index'
            ,renderTo: 'xbug-panel-index-div'
        }]
    }); 
    xBug.page.Index.superclass.constructor.call(this,config);
};
Ext.extend(xBug.page.Index,MODx.Component);
Ext.reg('xbug-page-index',xBug.page.Index);