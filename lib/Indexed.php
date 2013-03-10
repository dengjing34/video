<?php
//dengjing34@vip.qq.com
class Indexed extends MysqlData {
    public $id, $baidu, $google, $sogou, $soso, $day, $insertedTime, $attributeData;
    function __construct() {
        $options = array(
			'key' => 'id',
			'table' => 'gx_indexed',
			'columns' => array(
				'id' => 'id',
				'baidu' => 'baidu',
				'google' => 'google',
				'sogou' => 'sogou',
				'soso' => 'soso',
                'day' => 'day',
                'insertedTime' => 'insertedTime',
                'attributeData' => 'attributeData'
			),
			'saveNeeds' => array(
				'day',
			),            
        );
        parent::init($options);
    }
    
    function save() {
        if(is_null($this->id)) {
            $this->day = date('Y-m-d');
        }
        parent::save();
    }
}
?>