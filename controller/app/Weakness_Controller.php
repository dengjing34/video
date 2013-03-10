<?php
//dengjing34@vip.qq.com
class Weakness_Controller extends App_Controller{
    function __construct() {        
        parent::__construct();
        $this->checklogin();
    }
    
    function index() {
        $weibo = new WeiBoTencent($this->appObject);
        $this->render($weibo->user_info()->nick);
    }
}
?>
