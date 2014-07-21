
// utilize custom extension for Group Summary
var summary = new Ext.ux.grid.GroupSummary();

xBug.stores.Parser =  new Ext.data.GroupingStore({
    autoDestroy: true,
    autoLoad: false,
    url : xBug.config.connector_url,
	baseParams : {
		action : 'mgr/xbug/profile'
	},
	reader : new Ext.data.JsonReader({
		successProperty : 'success',
		totalProperty : 'total',
		root : 'parser',
		fields : [{name : 'tag'}, {name : 'outerTag'}, {name : 'processTime'}, {name : 'cacheable'}]
	}),
	groupField : 'tag',
    listeners : {
		'load' : function(store, records, opts) {
			xBug.stores.Profile.loadData({
				total : 1,
				success : true,
				profiles : store.reader.jsonData.profiles
			});
		},
        'exception' : function(misc) {
			console.log('exception');

        }
    }
});

xBug.grid.Parser  = new Ext.grid.GridPanel ({
    store: xBug.stores.Parser,
    view: new Ext.grid.GroupingView({
        forceFit: true,
        startCollapsed : true
    }),
    columns: [{header: 'Tag', dataIndex: 'tag', width: 150, fixed: true, align: 'right', sortable: true, summaryType: 'count',
        summaryRenderer: function(v, params, data){
            return ((v === 0 || v > 1) ? '(' + v +' Tags processed)' : '(1 Tag processed)');

        }},
        {header: 'outerTag', dataIndex: 'outerTag'},
        {header: 'Processing Time (S)', dataIndex : 'processTime', width: 150, fixed: true, align : 'right', sortable: true, summaryType: 'sum'},
        {header: 'cacheable', dataIndex: 'cacheable', width: 100, fixed: true, align: 'right', sortable: true}],
    autoWidth: true,
    height: 400,
    frame: false,
    title: 'Parser Data',
    id: 'xbug-parser-grid',
    margins: { top: 5, right : 0, bottom : 5, left : 0},
    plugins: summary
});

xBug.stores.Profile =  new Ext.data.JsonStore({
    autoDestroy: true,
    autoLoad: false,
    url : null,
	fields : [{name : 'id'}, {name : 'duration'}, {name : 'sql'}],
	root : 'profiles'
});


xBug.grid.Profile  = new Ext.grid.GridPanel ({
    store: xBug.stores.Profile,
    columns: [{header: 'ID', dataIndex: 'id', width: 50, fixed: true, align: 'right'},
        {header: 'Duration (S)', dataIndex: 'duration', width: 100, fixed: true, align: 'right', sortable: true},
        {header: 'Query', dataIndex: 'sql'}],
    autoWidth: true,
    height: 400,
    frame: false,
    title: 'SQL Profiles',
    id: 'xbug-profile-grid',
    margins: { top: 5, right: 0, bottom: 5, left: 0},
    viewConfig: {
        forceFit: true  
    }
});
xBug.panel.Profiler = function(config) {
    config = config || {};
    Ext.apply(config, {
		id : 'xbug-profiler',
		baseCls: 'xbug-formpanel',
		cls: 'container',
		title : '<h2>' + _('xbug.profiler') + '</h2>' + '<p>' + _('xbug.profiler.desc') + '</p>',
        frame : false,
        border: false,
        allowDrop: true,
        xtype : 'modx-formpanel ',
		renderTo : 'xbug-panel-profiler-div',
		items : [{
			id : 'xbug-profiler-form',
			padding : 10,
			border : false,
			frame : true,
			autoWidth : true,
            items : [{
                layout : 'column',
                border: false
                ,anchor: '100%'
                ,id: 'modx-resource-main-columns'
                ,defaults: {
                    labelSeparator: ''
                    ,labelAlign: 'top'
                    ,border: false
                    ,msgTarget: 'under'
                },
                items : [{
                    columnWidth: .45
                    ,id: 'modx-resource-main-left'
                    ,defaults: { msgTarget: 'under' }
                    ,items : [{
                        xtype: 'label'
                        ,forId: 'domain'
                        ,html: '<p><b>Domain</b></p>'
                        ,cls: 'desc-under'

                    },{
                        xtype : 'textfield',
                        fieldLabel : 'Domain Name',
                        name : 'domain',
                        width : 400,
                        id : 'domain',
                        description : 'Domain name to be tested, defaults to base_url'
                    },{
                        xtype: 'label'
                        ,forId: 'domain'
                        ,html: '<p>Domain name to be tested, defaults to base_url</p>'
                        ,cls: 'desc-under'

                    },{
                        xtype: 'label'
                        ,forId: 'url'
                        ,html: '<p><b>URI to be tested</b></p>'
                        ,cls: 'desc-under'

                    },{
                        xtype : 'textfield',
                        fieldLabel : 'URI or resource id',
                        name : 'resource',
                        width : 400,
                        id : 'url',
                        description : 'Resource ID or URI from site without domain'
                    },{
                        xtype: 'label'
                        ,forId: 'url'
                        ,html: '<p>Resource ID or URI from site without domain</p>'
                        ,cls: 'desc-under'

                    },{
                        xtype : 'toolbar',
                        hideBorders: true,
                        items :  [{
                            xtype : 'checkbox',
                            name : 'clear_cache',
                            id : 'clear_cache',
                            boxLabel : 'Refresh cache before page load'
                        },{
                            xtype : 'tbspacer',
                            width : '20'
                        },{
                            xtype : 'button',
                            name : 'profile',
                            text : 'Profile Page',
                            handler : this.sendRequest
                        }]
                    }]
                },{
                    columnwidth: 0.5,
                    items :[{
                        xtype: 'label'
                        ,forId: 'parameters'
                        ,html: '<p><b>GET Parameters</b></p>'
                        ,cls: 'desc-under'

                    },{
                        xtype : 'textfield',
                        fieldLabel : 'URL parameters',
                        name : 'url-params',
                        width : 400,
                        id : 'parameters',
                        description : 'GET parameters in format &somevar=1&othervar=2',
                        allowDrop : false
                    },{
                        xtype: 'label'
                        ,forId: 'parameters'
                        ,html: '<p>GET parameters in format &somevar=1&othervar=2</p>'
                        ,cls: 'desc-under'


                    },{
                        xtype: 'label'
                        ,forId: 'post-parameters'
                        ,html: '<p><b>POST Parameters</b></p>'
                        ,cls: 'desc-under'

                    },{
                        xtype : 'textfield',
                        fieldLabel : 'POST parameters',
                        name : 'post-params',
                        width : 400,
                        id : 'post-parameters',
                        description : 'POST parameters in format &somevar=1&othervar=2',
                        allowDrop : false
                    },{
                        xtype: 'label'
                        ,forId: 'post-parameters'
                        ,html: '<p>POST parameters in format &somevar=1&othervar=2</p>'
                        ,cls: 'desc-under'
                    },{
                        xtype: 'label'
                        ,forId: 'cookie-parameters'
                        ,html: '<p><b>COOKIE Parameters</b></p>'
                        ,cls: 'desc-under'

                    },{
                        xtype : 'textfield',
                        fieldLabel : 'COOKIE parameters',
                        name : 'cookie-params',
                        width : 400,
                        id : 'cookie-parameters',
                        description : 'COOKIE parameters in format somevar=1;othervar=2',
                        allowDrop : false
                    },{
                        xtype: 'label'
                        ,forId: 'cookie-parameters'
                        ,html: '<p>COOKIE parameters in format somevar=1;othervar=2</p>'
                        ,cls: 'desc-under'
                    }]
                }]
            }]

		},{
			xtype : 'panel',
			autoHeight : true,
			height : 800,
			items : [xBug.grid.Parser.show(),
				xBug.grid.Profile.show()]
		}],
        listeners : {
            added : function(evt) {
                this.onReady(evt);
            }

        }
	});

    xBug.panel.Profiler.superclass.constructor.call(this, config);
}

Ext.extend(xBug.panel.Profiler, MODx.FormPanel, {
    onReady: function(r) {
        this.isReady = true;
        this.loadDropZones();
    },
    loadDropZones: function() {
        var flds = this.getForm().items;
        flds.each(function(fld) {
            if (fld.isFormField && (
                fld.isXType('textfield') || fld.isXType('textarea')
                ) && !fld.isXType('combo')) {
                var el = fld.getEl();
                if (el) {
                    new MODx.load({
                        xtype: 'modx-treedrop'
                        ,target: fld
                        ,targetEl: el.dom
                    });
                }
            }
        });
    },sendRequest : function () {

        var url = Ext.getCmp('url').getValue();
        var clear_cache = Ext.getCmp('clear_cache').getValue() ? 1 : 0;
        var domain = Ext.getCmp('domain').getValue();

        var getvars = "'" + Ext.getCmp('parameters').getValue() + "'";
        var postvars = "'" + Ext.getCmp('post-parameters').getValue() + "'";
        var cookievars = "'" + Ext.getCmp('cookie-parameters').getValue() + "'";

        var id = url;
        var pat = new RegExp(/\[\[\~[0-9]*\]\]/);

        if (pat.test(url)) {
            var match = url.match(/\d+/);
            id = match[0];
        }
        Ext.Ajax.request({
            url: xBug.config.connectorUrl+'?action=mgr/xbug/loadpage&url=' + id + "&domain=" +domain + "&clear_cache=" + clear_cache + "&get=" + getvars +
                "&post=" + postvars + "&cookie=" + cookievars,
            success: function(response, opts) {
                xBug.stores.Parser.load();
            },
            failure: function(response, opts) {
                console.log('server-side failure with status code ' + response.status);
            }
        });
    }
});

Ext.reg('xbug-panel-profiler', xBug.panel.Profiler);