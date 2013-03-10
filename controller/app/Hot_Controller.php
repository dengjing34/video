<?php
//dengjing34@vip.qq.com
class Hot_Controller extends App_Controller{
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->render('hot page');
    }
}

?>
