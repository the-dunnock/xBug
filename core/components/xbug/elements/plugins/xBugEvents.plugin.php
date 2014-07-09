<?php
if ($modx->context->key != 'mgr') {
    if ($modx->event->name == 'OnWebPageComplete') {
        $modx->query('SET PROFILING = 0');
        $modx->query('SET SESSION query_cache_type = ON');
        $profiles = $modx->query('SHOW profiles')->fetchAll(PDO::FETCH_ASSOC);
        foreach($profiles as $profile) {
            $modx->xbugprofiler->addLogEvent('profiles', array('id' => $profile['Query_ID'], 'duration' => $profile['Duration'], 'sql' => $profile['Query']));
        }
        if ($modx->xbugprofiler) {
            $modx->xbugprofiler->writeLog();
        }
    }
}