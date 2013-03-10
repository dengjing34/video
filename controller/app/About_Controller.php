<?php
//dengjing34@vip.qq.com
class About_Controller extends App_Controller{
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->render('about us');
    }
}

?>
