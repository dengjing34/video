<?php
//dengjing34@vip.qq.com
class Video extends MysqlData {
    public $id, $cid, $title, $addtime, $status,$playurl, $picurl, $reurl;
    public $actor, $director, $area, $year, $score, $content, $attributeData;
    const STATUS_ACTIVE = 1, STATUS_INACTIVE = -1;
    public static $_status = array(
        self::STATUS_ACTIVE => '有效',
        self::STATUS_INACTIVE => '无效',
    );
    function __construct() {
        $options = array(
            'key' => 'id',
            'table' => 'gx_video',
            'columns' => array(
                'id' => 'id',
                'cid' => 'cid',
                'title' => 'title',
                'addtime' => 'addtime',
                'playurl' => 'playurl',
                'status' => 'status',
                'reurl' => 'reurl',
                'picurl' => 'picurl',
                'actor' => 'actor',
                'director' => 'director',
                'area' => 'area',
                'year' => 'year',
                'score' => 'score',
                'language' => 'language',
                'content' => 'content',
                'attributeData' => 'attributeData',
            ),
            'saveNeeds' => array(
            ),
            'searcher' => 'VideoSearcher',//指定searcher的类名
        );
        parent::init($options);        
    }
    
    public function actor() {
        return explode(' ', $this->actor);
    }
    
    public function actorLink($hightLight = null) {
        $link = '';
        foreach ($this->actor() as $actor) {
            $encodeActor = urlencode($actor);
            $actorText = $this->highLight($hightLight, $actor);
            $link .= "<a href=\"/search/?q={$encodeActor}\">{$actorText}</a> ";
        }
        return $link;
    }
    
    public function content() {
        $pattern = array(
            '/#xbb1234|{display:none}|百度影音/',
            '/&[a-zA-Z]+;/',
        );
        return preg_replace($pattern, '', strip_tags($this->content));
    }
    
    public function addtime() {
        $date = date('Y-m-d', $this->addtime);
        if ((int)$this->addtime - strtotime('today') >= 0) {
            return "<span style=\"color:orangered\">{$date}</span>";
        }
        return $date;
    }
    
    public function highLight($search, $subject) {
        return $search ? preg_replace("#({$search})#i", "<em>$1</em>", $subject) : $subject;
    }

	public static function latestVideo() {
        	$video = new self();
        	$video->status = self::STATUS_ACTIVE;
        	$where = array(
                	array('addtime', '>=' . strtotime('today')),
        	);  
        	$order = array('addtime' => 'desc');
        	$videos = $video->find(array(
                	'whereAnd' => $where,
                	'order' => $order,
        	));
		return $videos;
	}
    
    /**
     * 获取视频数据中的地区维度的数据
     * @return array
     */
    public static function area() {
        $area = array();
        $key = 'area';
        $expire = 86400;
        $o = new self();
        if (($area = $o->cacheGet($key))) {
            return $area;
        } else {
            $o->status = self::STATUS_ACTIVE;
            $area = $o->groupBy(array('area'), array('count' => '1'), array(
                'limit' => 20,
                'order' => array('count' => 'DESC')
            ));
            $o->cacheSet($key, $area, $expire);
        }
        return $area;
    }    
}
?>
