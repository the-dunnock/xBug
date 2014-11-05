<?php

class XbugProfile extends modProcessor {
    private $xbug = null;
	private $profiler = null;
	
    function __construct(modX & $modx,array $properties = array()) {
        parent::__construct($modx, $properties);
        $this->xbug = $modx->getService('xbug', 'xBug', $modx->getOption('xbug.core_path').'model/xbug/');
    }
    
    function process() {
        $profiles = array();
		$profiler = $this->xbug->loadProfiler();
		$profiles = $profiler->readLog();
        $totals = array(
           'parser' => 0,
           'profiles' => 0,
           'cache' => 0
        );
        foreach ($profiles['parser'] as $row) {
            $totals['parser'] = ($row['processTime'] + $totals['parser']);
        }
        foreach ($profiles['profiles'] as $row) {
            $totals['profiles'] = ($row['duration'] + $totals['profiles']);
        }
        foreach ($profiles['cache'] as $row) {
            $totals['cache'] = ($row['processTime'] + $totals['cache']);
        }
        $profiles = array_merge($profiles, array(
            'totals' => $totals
        ));
		return $this->outputArray($profiles);
    }
	
	function outputArray(array $results, $count = false) {
		if ($count === false) { $count = count($results); }
		$results['total'] = $count;
		$results['success'] = true;
		return $this->modx->toJSON($results);
	}
}

return 'XbugProfile';