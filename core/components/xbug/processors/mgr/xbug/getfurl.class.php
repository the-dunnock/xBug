<?php

class XbugFurlProcessor extends modProcessor {
    public function process() {
        if ($this->modx->getOption('friendly_urls') == true) {
            $c = $this->modx->newQuery('modResource');
            $c->select(array('uri'));
            $c->where(array('id' => $this->getProperty('id')));
            $c->prepare();
            $c->stmt->execute();
            $uri = $c->stmt->fetchColumn(0);
            $arr = array(
                'url' => $uri,
                'success' => 1
            );
        }
        if (!$arr['url']) {
            $arr = array(
                'url' => $this->getProperty('id'),
                'success' => 1
            );
        }
        return json_encode($arr);
    }
}
return 'XbugFurlProcessor';