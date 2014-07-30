<?php

class xBugLoadPageProcessor extends modProcessor {
    public function process() {
        $url = $this->getProperty('url');
        $domain = $this->getProperty('domain');
        if (!$domain) {
            $domain = $this->modx->getOption('site_url');
        }

        if ($this->modx->getOption('friendly_urls') == true && is_numeric($url)) {
            $uri = str_replace($this->modx->getOption('site_url'), '', $this->modx->makeUrl($url, '', '', 0));
            $startParam = '?';
        } else if (is_string($url)) {
            $uri = $url;
            $startParam = '?';
        } else {
            $uri = $this->modx->makeUrl($url);
            $startParam = '&';
        }

        $post = trim($this->getProperty('post', ''),"' ");
        $get = trim($this->getProperty('get', ''),"' ");
        $cookie = trim($this->getProperty('cookie', ''),"' ");

        $field_array= array(
            'Accept' => 'HTTP_ACCEPT',
            'Accept-Charset' => 'HTTP_ACCEPT_CHARSET',
            'Accept-Encoding' => 'HTTP_ACCEPT_ENCODING',
            'Accept-Language' => 'HTTP_ACCEPT_LANGUAGE',
            'Connection' => 'HTTP_CONNECTION',
            'Host' => 'HTTP_HOST',
            'Referer' => 'HTTP_REFERER',
            'User-Agent' => 'HTTP_USER_AGENT'
        );

        $url = $domain . $uri . $startParam."xbug=".$this->modx->getOption('xbug.xbug_auth_key').$get."&clear_cache=" . $this->getProperty('clear_cache');
        $curl_request_headers=array();
        foreach ($field_array as $key => $value) {
            if(isset($_SERVER["$value"])) {
                $server_value=$_SERVER["$value"];
                $curl_request_headers[]="$key: $server_value";
            }
        };
        $curl_request_headers[]="Expect: ";

        $curl_handle = curl_init();
        $postCount = ($post != '') ? substr_count("&", $post) : 0;
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($curl_handle, CURLOPT_HEADER, 1);
        curl_setopt($curl_handle, CURLOPT_COOKIE, $cookie);
        curl_setopt($curl_handle, CURLOPT_POST, $postCount);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, ltrim($post, "&"));
        curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $curl_request_headers);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl_handle);
        curl_close($curl_handle);

        return $this->outputArray(array('success' => 1), 1);
    }

    private function getUri($id) {
        $c = $this->modx->newQuery('modResource');
        $c->select(array('uri'));
        $c->where(array('id' => $id));
        $c->prepare();
        $c->stmt->execute();
        $uri = $c->stmt->fetchColumn(0);

        if (!$uri) {
            $uri = $id;
        } else if (is_numeric($uri)) {
            $uri = $this->getOption('request_param_id') . "=" . $id;
        }
        return $uri;
    }
}

return 'xBugLoadPageProcessor';