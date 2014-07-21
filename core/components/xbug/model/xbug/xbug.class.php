<?php


class xBug {
    public $modx;
    public $config = array(
        'func' => '_xBugFunc'
    );
    public $cacheSettings = array(
        xPDO::OPT_CACHE_KEY => 'xbug',
        xPDO::OPT_CACHE_FORMAT => 0
    );
    
    private $_properties = array();
    private $_cache = null;
    private $xBugLog = null;
    private $profiler = null;

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('xbug.core_path',$config,$this->modx->getOption('core_path').'components/xbug/');
        $assetsUrl = $this->modx->getOption('xbug.assets_url',$config,$this->modx->getOption('assets_url').'components/xbug/');
        $connectorUrl = $assetsUrl.'connector.php';

        $this->config = array_merge($this->config, array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'imagesUrl' => $assetsUrl.'images/',

            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'chunksPath' => $corePath.'elements/chunks/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath.'elements/snippets/',
            'processorsPath' => $corePath.'processors/',
            'templatesPath' => $corePath.'templates/',
        ),$config);
        if (isset($_GET['xbug']) && $_GET['xbug'] == $modx->getOption('xbug.xbug_auth_key')) {
            $this->loadProfiler();
            $this->profiler->forceParser();
            $this->profiler->enablePlugins();
            if (isset($_GET['clear_cache']) && $_GET['clear_cache'] == 1) {
                $this->_clearMODXCache();
            }

        }
        $this->modx->addPackage('xbug',$this->config['modelPath'], 'xbug_');
    }

    private function _clearMODXCache () {
        $mgr = $this->modx->getCacheManager();
        $mgr->refresh();
    }

    public function getOption($key) {
        if (array_key_exists($key, $this->config)) { return $this->config[$key]; } 
        return false; 
    }
    
    public function loadProfiler() {
		if ($this->profiler === null) {
            $this->profiler = $this->modx->getService('xbugprofiler', 'xBugProfiler', $this->config['modelPath'].'xbug/', array('xbug' => $this));
		}
        return $this->profiler;
    }
    
    public function getProperty($key) {
        if (array_key_exists($key, $this->_properties)) { return $this->_properties[$key]; } 
        return false; 
    }
    
    public function getQueryFile() {
        if (!function_exists($this->getOption('func'))) {
            $path = $this->modx->getCachePath() . $this->cacheSettings[xPDO::OPT_CACHE_KEY] . "/xbug.cache.php";
            if (!file_exists($path)) { $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not load xBug cache file from : ' . $path); return false; }
            include($path);
            $out = function_exists($this->getOption('func')) ? $this->getOption('func') : false;
        }
        return $out;
    }
    
    public function processQueryFile() {
        $this->setQueryFile();
        $func = $this->getQueryFile();
        if (!$func) {
            return array('error' => $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'xBug query return function does not exist'));
        }
        return $func;
    }
    
    public function removeQueryFile() {
        if ($this->_cache === null) { $this->_cache = $this->modx->getCacheManager(); }
        return $this->_cache->delete('xbug', array(xPDO::OPT_CACHE_KEY => $this->cacheSettings[xPDO::OPT_CACHE_KEY]));
    }
    
    public function setProperties($properties = array()) {
        $this->_properties = array_merge($this->_properties, $properties);
    }
    
    public function setQueryFile() {
        $q = trim($this->getProperty('query'));
        if (stripos($q, '<?php') == 0) {
            $q = substr($q, 5);
        } else if (stripos($q, '<?') == 0) {
            $q = substr($q, 2);
        }
        $content = "<?php function ". $this->getOption('func') . "() {\nglobal \$modx;\n" . $q. "\n}";
        if ($this->_cache === null) { $this->_cache = $this->modx->getCacheManager(); }
        $this->_cache->writeFile($this->modx->getCachePath() . $this->cacheSettings[xPDO::OPT_CACHE_KEY] . "/" . 'xbug.cache.php', $content);
    }
}
