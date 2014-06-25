<?php

class XbugFurlProcessor extends modProcessor {
    public function process() {
        if ($this->modx->getOption('friendly_urls') == true) {
            $arr = array(
                'url' => $this->modx->makeUrl($this->getProperty('id')),
                'success' => 1
            );
            return json_encode($arr);
        }
    }
}
return 'XbugFurlProcessor';