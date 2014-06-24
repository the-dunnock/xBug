<?php

class XbugProcessor extends modProcessor {
    private $xbug = null;
    private static $log = array( // Used for direct MySQL Queries
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

    function __construct(modX & $modx,array $properties = array()) {
        parent::__construct($modx, $properties);
        $this->xbug = $modx->getService('xbug', 'xBug', $modx->getOption('xbug.core_path').'model/xbug/');
        $this->xbug->setProperties($this->properties);
    }
    
    function process() {
        if ($this->getProperty('collector') == 'SQLQuery') { // Special case, we can process the whole content directly
            return $this->runQuery();
        }

        return $this->runCollector();
    }
    function runQuery() {
        $q = $this->getProperty('query');
        $this->modx->call('xBugLog', '_cacheControl', array($this->modx, true));
        $stmt = $this->modx->prepare($q);
        $collection = array();
        $this->log['memory']['pre_query'] = memory_get_usage(true);
        $tstart= $this->modx->getMicroTime();
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Got this far 1');
        if ($stmt->execute()) {
            $this->log['timings']['query'] = round(($this->modx->getMicroTime() - $tstart) * 1000, 4);
            $this->log['memory']['post_query'] = memory_get_usage(true);
            $tstart= $this->modx->getMicroTime();
            $this->log['memory']['pre_collector'] = memory_get_usage(true);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $collection[] = $row;
            }
            $this->log['memory']['post_collector'] = memory_get_usage(true);
            $this->log['timings']['collector'] = round(($this->modx->getMicroTime() - $tstart) * 1000, 4);
        } else {
            $this->log['query'] = $q;
            $this->log['error'] = array(
                'code' => $this->modx->errorCode(),
                'info' => $this->modx->errorInfo(),
            );

        }

        if (!$this->log['error']) {
            $expResults = $this->modx->pdo->query('EXPLAIN ' . $q, PDO::FETCH_ASSOC);
            foreach($expResults as $exp) {
                $this->log['explain'][] = $exp;
            }

        }

        $success = (!$this->log['error']) ? true : false;
        $this->modx->loadClass('SqlFormatter', $this->modx->getOption('xbug.core_path').'libs/', true, true);

        $this->log['query'] = SqlFormatter::format($q);
        $this->log['collection'] = array_values($collection);
        $this->log['columns'] = array_keys($collection[0]);
        $this->log['explainColumns'] = array_keys($this->log['explain'][0]);
        $this->log['memory']['total_query'] = round(($this->log['memory']['post_query'] - $this->log['memory']['pre_query']) / 1048576, 4);
        $this->log['memory']['total_collector'] = round(($this->log['memory']['post_collector'] - $this->log['memory']['pre_collector']) / 1048576, 4);
        return $this->outputArray($this->log, count($collection), $success);
    }

    function runCollector() {
        $this->xbug->processQueryFile();
        $func = $this->xbug->getOption('func');
        $crit = $func();
        $deleted = $this->xbug->removeQueryFile();
        $collector = $this->getProperty('collector');
        if (in_array($collector, array('getObjectGraph', 'getCollectionGraph')) && is_array($crit)) {
            $graph = $crit['graph'];
            $crit = $crit['crit'] ? $crit['crit'] : $crit['criteria'];
            $xBugLog = $this->modx->$collector('xBugLog', $graph, $crit);
        } else if (!is_array($crit) && in_array($collector, array('getObject', 'getCollection'))) {
            $xBugLog = $this->modx->$collector('xBugLog', $crit);
        }else {
            $xBugLog['error'] = array(
                'code' => 'xBUG : 0001',
                'info' => 'Invalid criteria object/array returned from xBug editor'
            );
        }

        foreach($xBugLog['collection'] as $k => $v) {
            $xBugLog['collection'][$k] = $v->toArray('', false, true);
        }
        //Rebase array to start from index 0
        $xBugLog['collection'] = array_values($xBugLog['collection']);
        $count = count($xBugLog['collection']);
        $xBugLog['columns'] = array_keys($xBugLog['collection'][0]);
        $xBugLog['explain'] = array_values($xBugLog['explain']);
        $xBugLog['explainColumns'] = array_keys($xBugLog['explain'][0]);

        $success = (!$xBugLog['error']) ? true : false;

        return $this->outputArray($xBugLog, $count, $success);
    }

    function outputArray($results, $count, $success = false) {
        $fields = array();
        $expFields = array();
        $columnMeta = array();
        $expMeta = array();
        
        if (is_array($results['error'])) {
            return '{"success" : false,
            "error" : ' . $this->modx->toJSON($results['error']) . '}';
        }
        foreach($results['columns'] as $col) {
            $fields[] = $col;
            $columnMeta[] = array('header' => $col,
                'dataIndex' => $col,
                'sortable' => false,
                'width' => 'auto');
        }
        foreach($results['explainColumns'] as $col) {
            $expFields[] = $col;
            $expMeta[] = array('header' => $col,
                'dataIndex' => $col,
                'sortable' => false);
        }
        
        $explainMeta = '{"metaData" : {
            "id" : "",
            "root" : "expRows",
            "totalProperty" : "explainResults",
            "successProperty" : "explainSuccess",
            "fields" : ' . $this->modx->toJSON($expFields) .'
        },
        "expRows" : ' . $this->modx->toJSON($results['explain']) . ',
        "explainResults" : '. count($results['explain']) .',
        "explainSuccess" : ' . 1 . ',
        "explainMeta" : ' . $this->modx->toJSON($expMeta) . '}';

        return '{"metaData":{
            "id": "",
            "root": "rows",
            "totalProperty": "results",
            "successProperty": "success",
            "fields" : ' . $this->modx->toJSON($fields) .'
        },
        "rows" : ' . $this->modx->toJSON($results['collection']) . ',
        "success" : ' . $success .',
        "total" : ' . $count .',
        "columnMeta" : ' . $this->modx->toJSON($columnMeta) . ', 
        "explain" : ' . $explainMeta. ',
        "timers" : ' . $this->modx->toJSON($results['timings']) .', 
        "memory" : ' . $this->modx->toJSON($results['memory']) .',
        "sql" : ' . json_encode($results['query'], JSON_HEX_TAG) . '}';
        return '{"total":"'.$count.'","results":'.$this->modx->toJSON($results).', "success":'.print_r($success, true).'}';
    }
}

return 'XbugProcessor';