<?php
//dengjing34@vip.qq.com
class WeiBoApp extends MysqlData {
    public $id, $name, $title, $description, $hits, $appKey, $appSecret, $appUrl, $appIcon, $appPreview;
    function __construct() {
        $options = array(
			'key' => 'id',
			'table' => 'gx_app',
			'columns' => array(
				'id' => 'id',
                'name' => 'name',
                'title' => 'title',
                'description' => 'description',
                'hits' => 'hits',
                'appId' => 'app_id',
                'appKey' => 'app_key',
                'appSecret' => 'app_secret',
                'appUrl' => 'app_url',
                'appIcon' => 'app_icon',
                'appPreview' => 'app_preview',
			),
			'saveNeeds' => array(
				'name',
                'title',
                'appKey',
                'appSecret',
                'appUrl',
			),            
        );
        parent::init($options);        
    }    
    
    public static function loadByName($name) {
        $o = new self();
        $o->name = $name;
        return current($o->find(array('limit' => '1')));        
    }
    
    public static function hotApps($limit = 10) {
        $o = new self();        
        return $res = $o->find(array(
            'order' => array('hits' => 'DESC'),
            'limit' => "{$limit}",
        ));        
    }
}
?>
