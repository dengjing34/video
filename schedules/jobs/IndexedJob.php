<?php
//dengjing34@vip.qq.com
class IndexedJob extends Job {
    private $http, $html, $engines, $domain, $email;
    function __construct() {
        parent::__construct();
        $this->http = new Http();
        $this->email = new Email();
        $this->domain = "www.84fun.com";
        $this->engines = array(
            'baidu' => 'http://www.baidu.com/s?wd=site%3A',
            'google' => 'http://www.google.com/uds/GwebSearch?callback=google.search.WebSearch.RawCompletion&rsz=filtered_cse&hl=zh_CN&source=gcsc&gss=.com&sig=9ff9e3fdc9a75ff6108d814a8227c711&cx=017573130007257499328:oedlvui8uri&gl=www.google.com&qid=134ebf289b54ca13a&context=0&key=notsupplied&v=1.0&q=site%3A',
            'sogou' => 'http://www.sogou.com/web?query=site%3A',
            'soso' => 'http://www.soso.com/q?w=site%3A',
        );
    }
    
    function log($msg) {
        //echo "{$msg}\n";
        parent::log($msg);
    }
    
    function notified() {
        $obj = new Indexed();
        $title = array();
        foreach ($this->engines as $engine => $url) {
            $method = "query{$engine}";
            $obj->$engine = $this->query($engine, $url . $this->domain);
            $title[] = "{$engine}:{$obj->$engine}";
        }
        try {
            $indexed = new Indexed();
            $result = $indexed->find(array(
                'limit' => '3',
            ));
            $body = null;
            foreach ($result as $o) {
                $data = array();
                foreach (array_keys($this->engines) as $engine) $data[] = "{$engine}:{$o->$engine}";
                $body .= "{$o->day} " . implode(', ', $data) . "<br />\n";
            }
            $obj->save();
            $this->log("{$obj->id} saved");
            $subject = "{$obj->day} " . implode(', ', $title);
            $body = $subject . "<br />\n" . $body;
            $this->email->send('dengjing34@vip.qq.com', $subject, $body);
        }catch (Exception $e) {
            $this->log($e->getMessage());
        }
    }
    
    function query($engine, $url) {
        $result = 0;
        if ($this->html = $this->http->crawl($url, true)) {            
            $charset = mb_detect_encoding($this->html, "UTF-8, GBK");
            if ($charset != 'UTF-8') $this->html = mb_convert_encoding ($this->html, 'UTF-8', $charset);
            switch ($engine) {
            case 'baidu':
                $reg = "/找到相关结果数(.*)个/U";
                break;
            case 'google':
                $reg = "/\"estimatedResultCount\":\"(.*)\",/U";
                break;
            case 'sogou':
                $reg = "/<span id=\"scd_num\">(.*)<\/span>/U";
                break;
            case 'soso':
                $reg = "/搜索到约(.*)项结果/U";
                break;
            default:
                $reg = null;
                break;
            }
            if (!is_null($reg) && preg_match($reg, $this->html, $matches)) {                
                $result = str_replace(',', '',$matches[1]);
            }
        }
        return $result;        
    }
}
?>