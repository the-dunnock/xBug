<?php
/**
 * @package xBug
 */
class xBugProfiler {
	public $xbug = null;
	public $modx = null;
	public $logger = array('parser' => array(),
		'profiles' => array());
	public $_cm = null;
	
	private $_events = array(
		'OnWebPageComplete'
	);

    public function __construct(modX &$modx, $params = array()) {
        $this->modx = $modx;
        $this->xbug = $params['xbug'];
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Profiler Construct');
    }

    public function addLogEvent($key, $value) {
        $this->logger[$key][] = $value;
    }

    public function enablePlugins() {
        $plugin = $this->modx->getObject('modPlugin', array('name' => 'xBugEvents'));
        $plugin->disabled = 0;
        $plugId = $plugin->get('id');
        $this->modx->pluginCache[$plugId] = $plugin->toArray('', false, true);
        $events = array();
        foreach($this->_events as $e) {
            $events[$e] = array($plugId => $plugId);
        }
        $this->modx->eventMap = array_merge($this->modx->eventMap, $events);
        $this->modx->query('SET SESSION query_cache_type = OFF');
        $this->modx->query('SET profiling_history_size = 100');
        $this->modx->query('SET PROFILING = 1');
    }

    public function forceParser() {
        $this->modx->setOption('parser_class', 'xBugParser');
        $this->modx->setOption('parser_class_path', $this->xbug->config['modelPath'].'xbug/parsers/');
    }

    public function readLog() {
        if ($this->_cm === null) {
            $this->_cm = $this->modx->getCacheManager();
        }
        $profile = $this->_cm->get('profile', array(
            xPDO::OPT_CACHE_KEY => $this->xbug->cacheSettings[xPDO::OPT_CACHE_KEY],
        ));
        return $profile;
    }

    public function writeLog() {
        //$this->modx->log(xPDO::LOG_LEVEL_ERROR, "CM" . $this->modx->cacheManager);
        if ($this->_cm === null) {
            $this->_cm = $this->modx->getCacheManager();
        }
        $this->_cm->set('profile', $this->logger, 0, array(
            xPDO::OPT_CACHE_KEY => $this->xbug->cacheSettings[xPDO::OPT_CACHE_KEY],
        ));
    }
}
