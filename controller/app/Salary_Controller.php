<?php
//dengjing34@vip.qq.com
class Salary_Controller extends App_Controller{
    function __construct() {
        parent::__construct();
        $this->checklogin();
    }
    
    function index() {
        $weibo = new WeiBoTencent($this->appObject);
        $this->render();
    }
}

?>
