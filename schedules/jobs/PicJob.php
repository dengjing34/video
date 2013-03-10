<?php
class PicJob extends Job {
    private $uploadDir = UPLOAD_DIR;
    private $existsDir = false;
    private $dateDir;
    private $pageSize = 500;
    private $where = array(
        array('picurl', "like 'http://%'"),
        array('reurl', "like 'http://www.bdzy.cc/detail/?%'"),
    );


    function __construct() {
        $this->dateDir = "{$this->uploadDir}video/" . date('Y-m-d') . '/';
        parent::__construct();
    }
    
    function log($msg) {
        parent::log($msg);
    }
    
    function notified() {
        for ($i = 1; $i <= $this->totalPage(); $i++) {
            foreach ($this->getVideo($i) as $video) {
                $this->getPic($video);
            }            
        }
    }
    
    private function totalPage() {
        $o = new Video();
        $o->status = Video::STATUS_ACTIVE;
        return ceil($o->count(array(
           'whereAnd' => $this->where, 
        )) / $this->pageSize);
    }


    private function getVideo($page = 1) {
        $o = new Video();
        $o->status = Video::STATUS_ACTIVE;        
        $offset = ($page - 1) * $this->pageSize;
        return $o->find(array(
            'whereAnd' => $this->where,
            'limit' => "{$offset}, {$this->pageSize}",
        ));
    }
    
    private function getPic(Video $video) {
        if (($pic = $this->downloadPic($video->picurl))) {
            $this->log("{$video->id}:{$video->picurl} ok");
            $this->checkdir()->savePic($this->getFileName($video->picurl), $pic, $video);                        
        } elseif (($xmlUrl = $this->xmlUrl($video->reurl)) && ($xml = $this->getFile($xmlUrl))) {
            $this->log("{$video->id}:{$xmlUrl} ok");
            if (($picurl = $this->parseXml($xml)) && ($pic = $this->downloadPic($picurl))) {
                $this->checkdir()->savePic($this->getFileName($picurl), $pic, $video);
            } else {
                $this->log("{$video->id}:save from xml failed {$picurl}");
            }            
        } else {
            $this->log("{$video->id}:save failed");
        }
    }
    
    private function checkdir() {
        if (!$this->existsDir && !is_dir($this->dateDir)) {
            mkdir($this->dateDir);
            chown($this->dateDir, 'www-data');
            $this->existsDir = true;
        }
        return $this;
    }
    
    private function savePic($filename, $pic, Video $video) {
        $filePath = "{$this->dateDir}{$filename}";        
        $result = false;
        if (file_put_contents($filePath, $pic)) {
            $video->picurl = str_replace($this->uploadDir, '', $filePath);
            try {
                $video->save();
                $result = true;
                $this->log("{$video->id}:{$video->picurl}");
            } catch (Exception $e) {
                $this->log($e->getMessage());
            }
        } else {
            $this->log("{$video->id}:file_put_contents faild");
        }
        return $result;
    }
    
    private function xmlUrl($reurl) {
        $id = str_replace('http://www.bdzy.cc/detail/?', '', $reurl);
        return ctype_digit((string)$id) ? "http://www.bdzy.cc/xml/caiji.asp?ac=videolist&ids={$id}" : false;
    }
    
    private function parseXml($xml) {
        $picurl = false;
        if (preg_match('#<pic>(?P<picurl>.*)</pic>#U', $xml, $matches)) {
            $picurl = $matches['picurl'];
        }
        return $picurl;
    }


    private function getFileName($url) {
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        return uniqid() . ".{$ext}";
    }
    
    private function getFile($url, $contentType = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);//if get binary data need to set header false
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 3s
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        $body = curl_exec($ch);
        $info = curl_getinfo($ch);        
        if ($body === false || $info['http_code'] != 200 || ($contentType && strpos($info['content_type'], $contentType) === false) || curl_error($ch) != '') {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        sleep(1);
        return $body;        
    }
    
    private function downloadPic($url) {
        return $this->getFile($url, 'image/');
    }
}
?>
