<?php
/**
 * @package xBug
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/xbugprofiler.class.php');
class xBugProfiler_mysql extends xBugProfiler {}
?>