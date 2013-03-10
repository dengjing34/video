<?php
//dengjing34@vip.qq.com
class Channel extends MysqlData {
    public $id, $pid, $cname;
    function __construct() {
        $options = array(
			'key' => 'id',
			'table' => 'gx_channel',
			'columns' => array(
				'id' => 'id',
                'pid' => 'pid',
				'cname' => 'cname',
			),
			'saveNeeds' => array(
				
			),            
        );
        parent::init($options);        
    }
}
?>
