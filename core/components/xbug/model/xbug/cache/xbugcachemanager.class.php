<?php

class xBugCacheManager extends modCacheManager {
    function __construct(& $xpdo, $options = array()) {
        parent :: __construct($xpdo, $options);
    }
	
	public function get($key, $options = array()) {
        $log['key'] = $key;
        $tstart = microtime(true);
        $return = parent::get($key, $options);
        $log['processTime'] = ((microtime(true)- $tstart));
        if ($return == false) {
            $log['hit'] = 'false';
        } else {
            $log['hit'] = 'true';
        }
        $this->xpdo->xbugprofiler->addLogEvent('cache', $log);
        return $return;

	}
}