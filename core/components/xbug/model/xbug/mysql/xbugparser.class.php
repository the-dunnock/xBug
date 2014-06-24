<?php
/**
 * @package xBug
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/xbugparser.class.php');
class xBugParser_mysql extends xBugParser {}
?>