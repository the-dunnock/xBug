<?php
/**
 * @package xBug
 */
$xpdo_meta_map['xBugLog']= array (
  'package' => 'xbug',
  'version' => '1.1',
  'table' => 'log',
  'fields' => 
  array (
    'init' => 'CURRENT_TIMESTAMP',
    'query' => NULL,
    'query_time' => NULL,
  ),
  'fieldMeta' => 
  array (
    'init' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
    'query' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'query_time' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
    ),
  ),
  'fieldAliases' => 
  array (
    'time' => 'query_time',
  ),
);
