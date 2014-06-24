xBug.stores.Results =  new Ext.data.JsonStore({
    autoDestroy: true,
    autoLoad: false,
    url : xBug.config.connector_url,
    listeners : {
        'metachange' : function(s, meta) {
            var cols = [];
            var cMeta = s.reader.jsonData.columnMeta;
            var colWidth = Math.floor(xBug.grid.Results.getWidth() / cMeta.length);
            for (var i = 0; i < cMeta.length; i++) {
                cols.push({
                    header : cMeta[i].header,
                    dataIndex : cMeta[i].dataIndex,
                    menuDisabled : true,
                    width : colWidth
                });
            }
            xBug.grid.ResultsModel.setConfig(cols);
            xBug.grid.Results.reconfigure(s, xBug.grid.Results.getColumnModel());
            xBug.stores.Explain.loadData(s.reader.jsonData.explain);
            /*Ext.getDom('xbug-query-time-value').innerHTML = s.reader.jsonData.timers.query + "ms";
            Ext.getDom('xbug-collection-time-value').innerHTML = s.reader.jsonData.timers.collector + "ms";
            Ext.getDom('xbug-memory-usage-value').innerHTML = s.reader.jsonData.memory.total_collector + "Mb";
			Ext.getDom('xbug-row-count-value').innerHTML = s.reader.jsonData.total;
			*/
			
			xBug.stores.Stats.loadData({stats_rows: [{total : s.reader.jsonData.total, 
				query : s.reader.jsonData.timers.query,
				collector : s.reader.jsonData.timers.collector, 
				total_collector : s.reader.jsonData.memory.total_collector
				}]});
            Ext.getCmp('xbug-sql-query').update(s.reader.jsonData.sql);
            
        }, 
        'exception' : function(proxy, response, read, store) {
            var e = store.reader.jsonData.error;
            Ext.MessageBox.show({
                title: _('error')
                ,msg: _('xbug.error_code') + e.code+' <br /> ' + _('xbug.error_info') + e.info
                ,buttons: Ext.MessageBox.OK
                ,minWidth: 400
                ,maxWidth: 750
                ,modal: false
                ,width: 600
            });
        }
    }
});

xBug.grid.ResultsModel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: false,
        menuDisabled: true,
        resizable : true
    },
    defaultSortable : false,
});


xBug.grid.Results = new Ext.grid.GridPanel ({
    store: xBug.stores.Results,
    cm : xBug.grid.ResultsModel,
    viewConfig: {
        scrollOffset: 0,
        emptyText : '0 Results',
        forceFit : true
    },
    autoWidth: true,
    height : 100,
    frame: false,
    title: _('xbug.results'),
    id : 'xbug-results-grid'
});

xBug.stores.Explain =  new Ext.data.JsonStore({
    autoDestroy: true,
    autoLoad: false,
    url : xBug.config.connector_url,
    listeners : {
        'metachange' : function(s, meta) {
            var cols = [];
            var cMeta = s.reader.jsonData.explainMeta;
            var colWidth = Math.floor(xBug.grid.Results.getWidth() / cMeta.length);
            for (var i = 0; i < cMeta.length; i++) {
                cols.push({
                    header : cMeta[i].header,
                    dataIndex : cMeta[i].dataIndex,
                    menuDisabled : true,
                    width: colWidth
                });
            }
            xBug.grid.ExplainModel.setConfig(cols);
            xBug.grid.Explain.reconfigure(this, xBug.grid.Explain.getColumnModel());         
        }
    }
});

xBug.grid.ExplainModel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: false,
        menuDisabled: true,
        resizable : true
    },
    defaultSortable : false,
});

xBug.grid.Explain = new Ext.grid.GridPanel ({
    store: xBug.stores.Explain,
    cm : xBug.grid.ExplainModel,
    viewConfig: {
        emptyText : 'No Query processed'
    },
    autoWidth: true,
    autoHeight : true,
    frame: false,
    title: _('xbug.explain'),
    id : 'xbug-explain-grid',
    listeners : {
        'reconfigure' : function(g, s, c) {
        }
    }
});

xBug.stores.Stats =  new Ext.data.JsonStore({
    autoDestroy: true,
    autoLoad: false,
    url : xBug.config.connector_url,
	root : 'stats_rows',
	fields : ['total', 'query', 'collector', 'total_collector']
});

xBug.grid.Stats = new Ext.grid.GridPanel ({
    store: xBug.stores.Stats,
    columns :[{header : _('xbug.row_count'), dataIndex : 'total', sortable : false, width : 120, align: 'right'}, 
			{header : _('xbug.query_time'), dataIndex : 'query', sortable : false, width : 150, align: 'right'},
			{header : _('xbug.collection_time'), dataIndex : 'collector', sortable : false, width : 200, align: 'right'},
			{header : _('xbug.collection_memory'), dataIndex : 'total_collector', sortable : false, width : 200, align: 'right'}],
    viewConfig: {
        emptyText : 'No Query processed'
    },
    autoWidth: true,
    autoHeight : true,
    frame: false,
    title: _('xbug.stats'),
    id : 'xbug-stats-grid',
});

xBug.panel.Index = function(config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'xbug-formpanel',
        cls: 'container',
        title : '<h2>' + _('xbug') + '</h2>',
        layout : 'fit',
        frame : false,
        border: false,
        renderTo : 'xbug-panel-index-div',      
        items :[{
            layout : 'border',
            width: 1200,
            height: 800,
            border : false,
            items : [{
                region: 'north',
                id : 'xbug-north',
                cls: 'modx-page-header',
                height: 50,
                html : '<p>' + _('xbug.description') + '</p>',
                border: false,
                
            }, {
                header : false,
                region : 'center',
                xtype : 'panel',
                id : 'xbug-center',
                border: false,
                padding: '5 5 5 5',
                flex : 1,
                width : .5,
                layout : {
                    type: 'vbox',
                    align : 'stretch'
                },
                defaultMargins : {
                    top : 5,
                    right : 5,
                    bottom : 5,
                    left: 5
                },
                items : [{
                    xtype : 'textarea',
                    id : 'xbug-editor-text',
                    name : 'query',
                    flex : 1,
                    hideLabel: true,
                    margin: {
                        top: 5,
                        right: 5,
                        bottom : 5,
                        left: 5
                    },
                    value : "<\?php\n\n$crit = $modx->newQuery('modResource');\n" +
                        "$crit->where(array('pagetitle:!=' => 10));\n" +
                        "return $crit;\n"
                }],
                tbar : [{
                    xtype : 'modx-panel',
                    items : [{
                        'xtype' : 'query-method-combo',
                        fieldLabel: _('xbug.collector_method'),
                        name: 'method',
                        hiddenName: 'unit',
                        anchor: '100%',
                    }]
                }],
                bbar : [{
                    id : 'xbug-statusbar',
                    xtype : 'toolbar',
                    items : [{
                        name : 'process',
                        text : _('xbug.process'),
                        handler : this.processEditor
                    }]
                }]
            }, {
                region : 'south',
                height : 300,
                split : true,
                id : 'xbug-south',
                border: false,
                items : [{
                    xtype : 'modx-tabs',

                    items: [
                        xBug.grid.Results.show(), 
                        xBug.grid.Explain.show(),
						xBug.grid.Stats.show()
                    ]
                }]
            }, {
                region : 'east',
                split : true,
                id : 'xbug-east',
                border : false,
                autoScroll : true,
                collapsible: true,
                collapseMode: 'mini',
                useSplitTips: true,
                minWidth : 200,
                maxWidth: 700,
                header : false,
                defaultMargins: {
                        top: 5,
                        right: 5,
                        bottom : 5,
                        left: 5
                    },
                items : [{
                    xtype : 'panel',
                    border : true,
                    emptyText : 'No query has been processed',
                    id : 'xbug-sql-query',
                    layout : 'fit'
                }]
            }]
        }], listeners : {
            /*'render' : function() { Disabled tree hiding
                var tree = Ext.getCmp('modx-leftbar-tabs');
                if (tree.collapsed !== true) {
                    tree.collapse();
                }
            }*/
        }
    });
    xBug.panel.Index.superclass.constructor.call(this, config);
}

Ext.extend(xBug.panel.Index, MODx.Panel, {
    processEditor : function(b, e) {
        var q = (Ext.ux.Ace) ? Ext.getCmp('xbug-editor-ace').getValue() : Ext.getCmp('xbug-editor-text').getValue();
        xBug.stores.Results.load({
            params : {
                action : 'mgr/xbug/process',
                'query' : q,
                'collector' : Ext.getCmp('xbug-collector-method').getValue(),
                'toJSON' : true
            }, callback : function(r, op, success) {
            },
            scope : this
        })
    }
});

Ext.reg('xbug-panel-index', xBug.panel.Index);

xBug.combo.MethodCombo = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 0
            ,fields: ['collector','display']
            ,data: [
                ['getObject','getObject']
                ,['getCollection','getCollection']
                ,['getObjectGraph','getObjectGraph']
                ,['getCollectionGraph','getCollectionGraph']
                ,['SQLQuery','Pure SQL Query']
            ]
        })
        ,mode: 'local'
        ,displayField: 'display'
        ,valueField: 'collector',
        listeners : {
            render : function (field) {
                field.setValue('getCollection');
            }
        },
        width: '200',
        id : 'xbug-collector-method'
    });
    xBug.combo.MethodCombo.superclass.constructor.call(this, config);
}
Ext.extend(xBug.combo.MethodCombo,MODx.combo.ComboBox);
Ext.reg('query-method-combo', xBug.combo.MethodCombo);

