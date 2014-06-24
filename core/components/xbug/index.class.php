<?php
/**
 * xBug
 *
 * @package xbug
 */
require_once dirname(__FILE__) . '/model/xbug/xbug.class.php';
/**
 * @package xbug
 */
 
class IndexManagerController extends xBugManagerController {
    public static function getDefaultController() { return 'index'; }
}

abstract class xBugManagerController extends modExtraManagerController {
    /** @var xBug $xbug */
    public $xbug;
    public function initialize() {
        $this->xbug = new xBug($this->modx);

        $this->addCss($this->xbug->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->xbug->config['jsUrl'].'mgr/xbug.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            xBug.config = '.$this->modx->toJSON($this->xbug->config).';
            
        });
        xBug.config.connector_url = "'.$this->xbug->config['connectorUrl'].'";
        </script>');
        return parent::initialize();
    }
    public function getLanguageTopics() {
        return array('xbug:default');
    }
    public function checkPermissions() { return true;}
	
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $action = call_user_func(array($className,'getDefaultController'));
        if (isset($_REQUEST['action'])) {
            $action = str_replace(array('../','./','.','-','@'),'',$_REQUEST['action']);
        }
        $className = self::getControllerClassName($action,$config['namespace']);
        $classPath = $config['namespace_path'].'controllers/'.$action.'.class.php';
        require_once $classPath;
        /** @var modManagerController $controller */
        $controller = new $className($modx,$config);
        return $controller;
    }
    
    public static function getControllerClassName($action,$namespace = '',$postFix = 'ManagerController') {
        $className = explode('/',$action);
        $o = array();
        foreach ($className as $k) {
            $o[] = ucfirst(str_replace(array('.','_','-'),'',$k));
        }
        return ucfirst($namespace).implode('',$o).$postFix;

    }
}