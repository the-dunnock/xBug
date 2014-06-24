<?php
/**
 * The base xBug snippet.
 *
 * @package xbug
 */
$xBug = $modx->getService('xbug','xBug',$modx->getOption('xbug.core_path',null,$modx->getOption('core_path').'components/xbug/').'model/xbug/',$scriptProperties);
if (!($xBug instanceof xBug)) return '';

/**
 * Do your snippet code here. This demo grabs 5 items from our custom table.
 */
$tpl = $modx->getOption('tpl',$scriptProperties,'Item');
$sortBy = $modx->getOption('sortBy',$scriptProperties,'name');
$sortDir = $modx->getOption('sortDir',$scriptProperties,'ASC');
$limit = $modx->getOption('limit',$scriptProperties,5);
$outputSeparator = $modx->getOption('outputSeparator',$scriptProperties,"\n");

/* build query */
$c = $modx->newQuery('xBugItem');
$c->sortby($sortBy,$sortDir);
$c->limit($limit);
$items = $modx->getCollection('xBugItem',$c);

/* iterate through items */
$list = array();
foreach ($items as $item) {
    $itemArray = $item->toArray();
    $list[] = $xBug->getChunk($tpl,$itemArray);
}

/* output */
$output = implode($outputSeparator,$list);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if (!empty($toPlaceholder)) {
    /* if using a placeholder, output nothing and set output to specified placeholder */
    $modx->setPlaceholder($toPlaceholder,$output);
    return '';
}
/* by default just return output */
return $output;