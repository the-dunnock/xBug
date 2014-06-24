<?php

class XbugProfile extends modProcessor {
    private $xbug = null;
	private $profiler = null;
	
    function __construct(modX & $modx,array $properties = array()) {
        parent::__construct($modx, $properties);
        $this->xbug = $modx->getService('xbug', 'xBug', $modx->getOption('xbug.core_path').'model/xbug/');
    }
    
    function process() {
		$profiler = $this->xbug->loadProfiler();
		$profiles = $profiler->readLog();
		return $this->outputArray($profiles, 1, true);
    }
	
	function outputArray($results, $count, $success) {
		$results['total'] = $count;
		$results['success'] = true;
		return $this->modx->toJSON($results);
	}
}

return 'XbugProfile';