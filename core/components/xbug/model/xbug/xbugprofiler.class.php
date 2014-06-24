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
		'OnInitCulture',
		'OnHandleRequest',
		'OnWebPageInit',
		'OnLoadWebDocument',
		'OnParseDocument',
		'OnWebPagePrerender',
		'OnBeforeSaveWebPageCache',
		'OnWebPageComplete'
	);
	
	public function __construct(modX &$modx, $params = array()) {
		$this->modx = $modx;
		$this->xbug = $params['xbug'];
	}
	
	public function addLogEvent($key, $value) {
		$this->logger[$key][] = $value;
	}
	
	public function enablePlugins() {		
        /*$this->modx->setOption('cache_disabled', 1);
        $this->modx->setOption('cache_resource', 0);
        $this->modx->setOption('cache_scripts', 0);
        $this->modx->setOption('cache_db', 0);
		$this->modx->setOption('cache_lang_js', 0);
		$this->modx->setOption('cache_lexicon_topics', 0);
		$this->modx->setOption('cache_action_map', 0);
		$this->modx->setOption('cache_alias_map', 0);
		$this->modx->setOption('cache_context_settings', 0);
		*/
		//$this->modx->cacheManager = null;
		//$this->modx->getCacheManager('cache.xBugCacheManager', $options = array('path' => $this->xbug->config['modelPath'].'xbug/', 'ignorePkg' => true));

		$plugin = $this->modx->getObject('modPlugin', array('name' => 'xBugEvents'));
		$plugin->disabled = 0;
		$plugId = $plugin->get('id');
		$this->modx->pluginCache[$plugId] = $plugin->toArray('', false, true);
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
