Ext.onReady(function() {
    MODx.load({ xtype: 'xbug-page-profiler'});
});

xBug.page.Profiler = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'xbug-panel-profiler'
        }]
    }); 
    xBug.page.Profiler.superclass.constructor.call(this,config);
};
Ext.extend(xBug.page.Profiler,MODx.Component);
Ext.reg('xbug-page-profiler',xBug.page.Profiler);