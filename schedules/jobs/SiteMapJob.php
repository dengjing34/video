<?php
//dengjing34@vip.qq.com
class SiteMapJob extends Job{
    private static $urlset_open = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
    private static $urlset_close = "</urlset>";
    private static $sitemapindex_open = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    private static $sitemapindex_close = "</sitemapindex>";	
    private $mapdir;
    private $baseUrl;

    const PAGESIZE = 200;
    
    function __construct() {
        $this->baseUrl = BASEURL ? BASEURL : 'http://www.84fun.com/';
        $this->mapdir = "maps/";
        parent::__construct();
    }
    
    function log($msg) {
        //echo "{$msg}\n";
        parent::log($msg);
    }
    
    function notified() {
        $this->sitemapIndex();
        $email = new Email();
        $sitemapIndex = BASEDIR .$this->mapdir . "sitemap_index.xml";
        $indexModifiedTime = filemtime($sitemapIndex) ? date('Y-m-d H:i:s', filemtime($sitemapIndex)) : 'not found';
        $content = "{$sitemapIndex} => {$indexModifiedTime}";
        $email->send('dengjing34@vip.qq.com', "SiteMap更新成功" . date('Y-m-d H:i'), $content);
    }
    
	private function sitemapIndex() {        
        $lastmod = date("Y-m-d");
        $xml = self::$sitemapindex_open;
        $xml .= $this->buildIndex("channel/sitemap.xml", $lastmod);
        $this->channel();
        $o = new Video();
        $o->status = Video::STATUS_ACTIVE;        
        $totalCount = $o->count();
        $page = ceil($totalCount/self::PAGESIZE);
        for ($i = 1; $i <= $page; $i++) {
            $xml .= $this->buildIndex("video/sitemap-{$i}.xml", $lastmod);
            $this->video($i);
            $xml .= $this->buildIndex("player/sitemap-{$i}.xml", $lastmod);
            $this->player($i);
        }
        $xml .= self::$sitemapindex_close;
        $this->generateXml($xml, BASEDIR . "{$this->mapdir}sitemap_index.xml");
    }
    
    private function video($page) {
        $dir = BASEDIR . "{$this->mapdir}video";
        $lastmod = date('Y-m-d');
        $xml = self::$urlset_open;
        $o = new Video();
        $o->status = Video::STATUS_ACTIVE;
        $offset = ($page - 1) * self::PAGESIZE;
        foreach ($o->find(array('limit' => "{$offset}, " . self::PAGESIZE, 'order' => array('addtime' => 'DESC'))) as $obj) {
            $xml .= $this->buildMap("movie/{$obj->id}.html", date('Y-m-d', $obj->addtime), 'hourly', 0.7);
        }
        $xml .= self::$urlset_close;
        if (!is_dir($dir)) mkdir ($dir, 777);
        $this->generateXml($xml, "{$dir}/sitemap-{$page}.xml");                  
    }
    
    private function channel() {
        $dir = BASEDIR . "{$this->mapdir}channel";
        $lastmod = date('Y-m-d');
        $xml = self::$urlset_open;
        $xml .= $this->buildMap('', $lastmod);
        $o = new Channel();
        foreach ($o->find(array('order' => array('id' => 'ASC'))) as $obj) {
            $xml .= $this->buildMap("list/{$obj->id}.html", $lastmod, 'hourly', 0.8);
        }
        $xml .= self::$urlset_close;        
        if (!is_dir($dir)) mkdir ($dir, 777);
        $this->generateXml($xml, "{$dir}/sitemap.xml");
    }    
    
    private function player($page) {
        $dir = BASEDIR . "{$this->mapdir}player";
        $lastmod = date('Y-m-d');
        $xml = self::$urlset_open;
        $o = new Video();
        $o->status = Video::STATUS_ACTIVE;
        $offset = ($page - 1) * self::PAGESIZE;
        foreach ($o->find(array('limit' => "{$offset}, " . self::PAGESIZE, 'order' => array('addtime' => 'DESC'))) as $obj) {
            $xml .= $this->buildMap("player/{$obj->id}-1.html", date('Y-m-d', $obj->addtime), 'hourly', 0.7);                                
        }
        $xml .= self::$urlset_close;
        if (!is_dir($dir)) mkdir ($dir, 777);
        $this->generateXml($xml, "{$dir}/sitemap-{$page}.xml");                             
    }    
    private function generateXml($content, $file) {
        if ($content != '') {
            file_put_contents($file, $content);
        }
    }
    
    private function buildIndex($loc, $lastmod) {
        return "\t<sitemap><loc>{$this->baseUrl}{$this->mapdir}{$loc}</loc><lastmod>{$lastmod}</lastmod></sitemap>\n";
    }
    
    private function buildMap($loc, $lastmod, $changefreq = 'hourly', $priority = 0.9){
        return "\t<url><loc>{$this->baseUrl}{$loc}</loc><lastmod>{$lastmod}</lastmod><changefreq>{$changefreq}</changefreq><priority>{$priority}</priority></url>\n";
    }    
}
?>
