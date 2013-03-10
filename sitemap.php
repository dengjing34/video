<?php
//dengjing34@vip.qq.com
require_once strtr(dirname(__FILE__) . DIRECTORY_SEPARATOR, "\\", '/') . 'config/init.php';
class SiteMap {
    private static $urlset_open;
    private static $urlset_close = "</urlset>";
    private static $sitemapindex_open = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    private static $sitemapindex_close = "</sitemapindex>";
	const URLSET_1 = "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
	const URLSET_2 = "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    const URLSET_3 = "<urlset>\n";
	private static $spider;
	private static $spiderTagMapping = array(
		'Baiduspider' => self::URLSET_3,
		'Googlebot' => self::URLSET_2,
		'Mediapartners-Google' => self::URLSET_2,
		'AdsBot-Google' => self::URLSET_2,
		'Sosospider' => self::URLSET_1,
		'Sogou' => self::URLSET_2,		
	);	
    const PAGESIZE = 1000;	
    function index() {	
        header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");		
		$xmlTag = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		self::$spider = self::getSpider();
		self::$urlset_open = is_null(self::$spider) ? $xmlTag . self::URLSET_2 : $xmlTag . self::$spiderTagMapping[self::$spider];
        $action = array_filter(explode('/', $_SERVER['REQUEST_URI']));
        if (isset($action[2])) {
            $method = $action[2];
            if (preg_match('/^sitemap_index\.xml$/', $method)) {
                $this->sitemapIndex();
            }elseif (is_callable(array($this, $method))) {
                $this->$method();                
            }
        }
    }
	
	private static function getSpider() {
		return preg_match("/(" . implode('|', array_keys(self::$spiderTagMapping)) .")/i", $_SERVER['HTTP_USER_AGENT'], $matches) ? $matches[1] : null;
	}

	private function sitemapIndex() {        
        $lastmod = date("Y-m-d");
        $xml = self::$sitemapindex_open;
        $xml .= $this->buildIndex("channel/sitemap.xml", $lastmod);
        $o = new Video();
        $o->status = Video::STATUS_ACTIVE;        
        $totalCount = $o->count();
        $page = ceil($totalCount/self::PAGESIZE);
        for ($i = 1; $i <= $page; $i++) {
            $xml .= $this->buildIndex("video/sitemap-{$i}.xml", $lastmod);
            $xml .= $this->buildIndex("player/sitemap-{$i}.xml", $lastmod);
        }
        $xml .= self::$sitemapindex_close;
        header("Content-type: text/xml;charset=utf-8");
        echo $xml;
    }
    
    private function channel() {
        $lastmod = date('Y-m-d');
        $xml = self::$urlset_open;
        $xml .= $this->buildMap('', $lastmod);
        $o = new Channel();
        foreach ($o->find(array('order' => array('id' => 'ASC'))) as $obj) {
            $xml .= $this->buildMap("list/{$obj->id}.html", $lastmod, 'hourly', 0.8);
        }
        $xml .= self::$urlset_close;
        header("Content-type: text/xml;charset=utf-8");
        echo $xml;
    }
    
    private function video() {
        if (preg_match('/sitemap\/video\/sitemap-(\d+)\.xml$/', $_SERVER['REQUEST_URI'], $matches)) {
            $page = $matches[1];
            $lastmod = date('Y-m-d');
            $xml = self::$urlset_open;
            $o = new Video();
			$o->status = Video::STATUS_ACTIVE;
            $offset = ($page - 1) * self::PAGESIZE;
            foreach ($o->find(array('limit' => "{$offset}, " . self::PAGESIZE, 'order' => array('addtime' => 'DESC'))) as $obj) {
                $xml .= $this->buildMap("movie/{$obj->id}.html", date('Y-m-d', $obj->addtime), 'hourly', 0.7);
            }
            $xml .= self::$urlset_close;
            header("Content-type: text/xml;charset=utf-8");
            echo $xml;            
        }
    }
    
    private function player() {
        if (preg_match('/sitemap\/player\/sitemap-(\d+)\.xml$/', $_SERVER['REQUEST_URI'], $matches)) {
            $page = $matches[1];
            $lastmod = date('Y-m-d');
            $xml = self::$urlset_open;
            $o = new Video();
			$o->status = Video::STATUS_ACTIVE;
            $offset = ($page - 1) * self::PAGESIZE;
            foreach ($o->find(array('limit' => "{$offset}, " . self::PAGESIZE, 'order' => array('addtime' => 'DESC'))) as $obj) {
                $xml .= $this->buildMap("player/{$obj->id}-1.html", date('Y-m-d', $obj->addtime), 'hourly', 0.7);                                
            }
            $xml .= self::$urlset_close;
            header("Content-type: text/xml;charset=utf-8");
            echo $xml;            
        }        
    }
    
    private function buildIndex($loc, $lastmod) {
        return "\t<sitemap><loc>" . BASEURL  . "sitemap/{$loc}</loc><lastmod>{$lastmod}</lastmod></sitemap>\n";
    }
    
    private function buildMap($loc, $lastmod, $changefreq = 'hourly', $priority = 0.9){
        return "\t<url><loc>" . BASEURL  . "{$loc}</loc><lastmod>{$lastmod}</lastmod><changefreq>{$changefreq}</changefreq><priority>{$priority}</priority></url>\n";
    }
 }
$sitemap = new SiteMap();
$sitemap->index();
?>
