<?php
//dengjing34@vip.qq.com
class Home_Controller extends App_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $o = new WeiBoApp;
        $apps = $o->find();        
        $view = new View('app/welcome', compact('apps'));
        $this->render($view->render());
    }
}
?>
