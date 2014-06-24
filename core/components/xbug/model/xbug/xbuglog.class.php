<?php
/**
 * @package xBug
 */
class xBugLog extends xPDOSimpleObject {
    private static $log = array(
        'collection' => array(),
        'error' => false,
        'explain' => null,
        'fields' => array(),
        'indexes' => array(),
        'joins' => array(),
        'memory' => array(
            'init' => null,
            'pre_query' => null,
            'post_query' => null,
            'pre_collector' => null,
            'post_collector' => null,
            'total_query' => null,
            'total_collector' => null
        ),
        'query' => null,
        'timings' => array(
            'query' => null,
            'collector' => null
        )
    );
    private static $init = false;
    
    public function __construct(xPDO &$xpdo) {
        parent::__construct($xpdo);
    }
    
    protected static function _isCriteria(& $xpdo, $criteria) {
        self::$log['memory']['init'] = memory_get_usage(true);
        if (!$criteria instanceof xPDOQuery) {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Criteria passed for xBugLog has to be instance of xPDOQuery');
            return false;
        }
        return true;
    }
    
    public function _cacheControl(xPDO &$xpdo, $mode = true) {
        // Implemented in child classes
    }
    
    public static function & _loadRows(& $xpdo, $className, $criteria) {
        if (!self::$init) {
            $xpdo->call('xBugLog', '_cacheControl', array($xpdo, true));
            self::$init = true;
        }
        $rows= null;
        if ($criteria->prepare()) {
            self::$log['query'] = $criteria->toSQL();
            $tstart= $xpdo->getMicroTime();
            self::$log['memory']['pre_query'] = memory_get_usage(true);
            if (!$criteria->stmt->execute()) {
                self::$log['error'] = array(
                    'code' => $criteria->stmt->errorCode(),
                    'info' => $criteria->stmt->errorInfo(),
                );
            } else {
                self::$log['memory']['post_query'] = memory_get_usage(true);
                self::$log['timings']['query'] = round(($xpdo->getMicroTime() - $tstart) * 1000, 4);
                $rows= & $criteria->stmt;
            }
        } else {
            self::$log['query'] = $criteria->construct();
            self::$log['error'] = array(
                'code' => $xpdo->errorCode(),
                'info' => $xpdo->errorInfo(),
            );
        }
        if (self::$init) {
            $xpdo->call('xBugLog', '_cacheControl', array($xpdo, false));
            self::$init = false;
        }
        return $rows;
    }

    /**
     * Load an instance of an xPDOObject or derivative class.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param string $className Name of the class.
     * @param mixed $criteria A valid primary key, criteria array, or
     * xPDOCriteria instance.
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     * @return object|null An instance of the requested class, or null if it
     * could not be instantiated.
     */
    public static function load(xPDO & $xpdo, $className, $criteria, $cacheFlag= true) {
        if (!self::_isCriteria($xpdo, $criteria)) {
            return false;
        }
        $instance= null;
        $className = $criteria->getClass();
        if ($className= $xpdo->loadClass($className)) {
            $criteria = $xpdo->addDerivativeCriteria($className, $criteria);
            $row= null;
            if ($rows= xBugLog :: _loadRows($xpdo, $className, $criteria)) {
                $row= $rows->fetch(PDO::FETCH_ASSOC);
                $rows->closeCursor();
            }
            if (!is_array($row)) {
                $xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Fetched empty result set from statement: " . print_r($criteria->sql, true) . " with bindings: " . print_r($criteria->bindings, true));
            } else {
                self::$log['memory']['pre_collector'] = memory_get_usage(true);
                $tstart= $xpdo->getMicroTime();
                $instance= xPDOObject :: _loadInstance($xpdo, $className, $criteria, $row);
                self::$log['timings']['collector'] = round(($xpdo->getMicroTime() - $tstart) * 1000, 4);
                self::$log['memory']['post_collector'] = memory_get_usage(true);
                if (is_object($instance)) {
                    $xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Loaded object instance: " . print_r($instance->toArray('', true, true), true));
                }
            }
        } else {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Invalid class specified: ' . $className);
        }
        return self::outputArray($xpdo, $instance);
    }

    /**
     * Load a collection of xPDOObject instances.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param string $className Name of the class.
     * @param mixed $criteria A valid primary key, criteria array, or xPDOCriteria instance.
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     * @return array An array of xPDOObject instances or an empty array if no instances are loaded.
     */
    public static function loadCollection(xPDO & $xpdo, $className, $criteria= null, $cacheFlag= true) {
        if (!self::_isCriteria($xpdo, $criteria)) {
            return false;
        }
        $objCollection= array ();
        
        $className = $criteria->getClass();
        
        $rows= false;
        $criteria = $xpdo->addDerivativeCriteria($className, $criteria);
        $rows= xBugLog :: _loadRows($xpdo, $className, $criteria);

        if (is_array ($rows)) {
            $tstart= $xpdo->getMicroTime();
            self::$log['memory']['pre_collector'] = memory_get_usage(true);
            foreach ($rows as $row) {
                xPDOObject :: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row, false, false);
            }
            self::$log['memory']['post_collector'] = memory_get_usage(true);
            self::$log['timings']['collector'] = round(($xpdo->getMicroTime() - $tstart) * 1000, 4);
        } elseif (is_object($rows)) {
            $tstart= $xpdo->getMicroTime();
            self::$log['memory']['pre_collector'] = memory_get_usage(true);
            while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                xPDOObject :: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row, false, false);
            }
            self::$log['memory']['post_collector'] = memory_get_usage(true);
            self::$log['timings']['collector'] = round(($xpdo->getMicroTime() - $tstart) * 1000, 4);
        }
        return self::outputArray($xpdo, $objCollection);
    }

    /**
     * Load a collection of xPDOObject instances and a graph of related objects.
     *
     * @static
     * @param xPDO &$xpdo A valid xPDO instance.
     * @param string $className Name of the class.
     * @param string|array $graph A related object graph in array or JSON
     * format, e.g. array('relationAlias'=>array('subRelationAlias'=>array()))
     * or {"relationAlias":{"subRelationAlias":{}}}.  Note that the empty arrays
     * are necessary in order for the relation to be recognized.
     * @param mixed $criteria A valid primary key, criteria array, or xPDOCriteria instance.
     * @param boolean|integer $cacheFlag Indicates if the objects should be
     * cached and optionally, by specifying an integer value, for how many
     * seconds.
     * @return array An array of xPDOObject instances or an empty array if no instances are loaded.
     */
    public static function loadCollectionGraph(xPDO & $xpdo, $className, $graph, $criteria, $cacheFlag = false) {
        if (!self::_isCriteria($xpdo, $criteria)) {
            return false;
        }
        if (!self::$init) {
            $xpdo->call('xBugLog', '_cacheControl', array($xpdo, true));
            self::$init = true;
        }
        $objCollection = array();
        if ($query= $xpdo->newQuery($className, $criteria, $cacheFlag)) {
            $query = $xpdo->addDerivativeCriteria($className, $query);
            $query->bindGraph($graph);
            $rows = array();
            if ($query->prepare()) {
                self::$log['query'] = $query->toSQL();
                $tstart= $xpdo->getMicroTime();
                self::$log['memory']['pre_query'] = memory_get_usage(true);
                if ($query->stmt->execute()) {
                    self::$log['memory']['post_query'] = memory_get_usage(true);
                    self::$log['timings']['query'] = round(($xpdo->getMicroTime() - $tstart) * 1000, 4);
                    $tstart= $xpdo->getMicroTime();
                    self::$log['memory']['pre_collector'] = memory_get_usage(true);
                    $objCollection= $query->hydrateGraph($query->stmt, $cacheFlag);
                    self::$log['memory']['post_collector'] = memory_get_usage(true);
                    self::$log['timings']['collector'] = round(($xpdo->getMicroTime() - $tstart) * 1000, 4);
                } else {
                    self::$log['error'] = array(
                        'code' => $query->stmt->errorCode(),
                        'info' => $query->stmt->errorInfo(),
                    );
                }
            } else {
                self::$log['query'] = $query->construct();
                self::$log['error'] = array(
                    'code' => $xpdo->errorCode(),
                    'info' => $xpdo->errorInfo(),
                );
            }
        }
        if (self::$init) {
            $xpdo->call('xBugLog', '_cacheControl', array($xpdo, false));
            self::$init = false;
        }
        return self::outputArray($xpdo, $objCollection);
    }
    
    public static function outputArray(xPDO & $xpdo, $collection) {
        if (self::$log['error'] === false) {
            $expResults = $xpdo->pdo->query('EXPLAIN ' . self::$log['query'], PDO::FETCH_ASSOC);
            foreach($expResults as $exp) {
                self::$log['explain'][] = $exp;
            }
        }
        if (!is_array($collection)) {
            $collection = array($collection);
        }
        $xpdo->loadClass('SqlFormatter', $xpdo->getOption('xbug.core_path').'libs/', true, true);
        self::$log['query'] = SqlFormatter::format(self::$log['query']);
        self::$log['collection'] = $collection;
        self::$log['memory']['total_query'] = round((self::$log['memory']['post_query'] - self::$log['memory']['pre_query']) / 1048576, 4);
        self::$log['memory']['total_collector'] = round((self::$log['memory']['post_collector'] - self::$log['memory']['pre_collector']) / 1048576, 4);
        return self::$log;
    }
}
?>