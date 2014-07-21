<?php

class xBugCacheManager extends modCacheManager {
    function __construct(& $xpdo, array $options = array()) {
        parent :: __construct($xpdo, $options);
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'CacheManager constructed');
    }
	
	public function get($key, $options = array()) {
		$this->modx->log(xPDO::LOG_LEVEL_ERROR, 'xBugCache get : ' . $key);
		return false;
	}
}