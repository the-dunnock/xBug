<?php
/**
 * @package xBug
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/xbuglog.class.php');
class xBugLog_mysql extends xBugLog {
    public function _cacheControl($xpdo, $mode = true) {
        if($mode) {
            if (!$xpdo->pdo->query('SET SESSION query_cache_type = OFF;')) {
                $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not disable MySQL query cache');
            }          
        } else {
            if (!$xpdo->pdo->query('SET SESSION query_cache_type = ON;')) {
                $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not enable MySQL query cache');
            } 
        }
    }
}
?>