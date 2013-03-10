<?php
//dengjing34@vip.qq.com
class Controller {
    protected $url, $className;
    
    function  __construct() {
        $this->url = new Url();
        $this->className = get_class($this);
    }

    //set client browser no cache
    protected function noCache() {
        header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    protected function render ($html = null) {
        $baseHtml = '';
        $header = new View('base/header');
        $baseHtml .= $header->render();
        $nav = new View('base/nav');
        $baseHtml .= $nav->render();
        $baseHtml .= $html;
        $bottom = new View('base/bottom');
        $baseHtml .= $bottom->render();
        $footer = new View('base/footer');
        $baseHtml .= $footer->render();
        echo $baseHtml;
    }
	//子入口
    protected function branch($controllerSegment = 2) {
        $uri = $this->url->segmentsArray();
        $defaultController = isset ($uri[$controllerSegment]) ? ucfirst($uri[$controllerSegment]) . '_Controller' : DEFAULT_CONTROLLER;        
        $defaultMethod = isset($uri[$controllerSegment+1]) ? $uri[$controllerSegment+1] : DEFAULT_METHOD;
        $folder = strtolower(str_replace('_Controller', '', $this->className));
        if (is_file(CONTROLLER_DIR . $folder . '/' .$defaultController . '.php')) require_once CONTROLLER_DIR . $folder .'/' . $defaultController . '.php';
        else ErrorHandler::show_404 ('有点儿问题', "controller:" . CONTROLLER_DIR ."{$folder}/{$defaultController}.php not found");
        $class = new $defaultController ();
        if (method_exists($class, $defaultMethod)) $class->{$defaultMethod} ();
        else ErrorHandler::show_404 ('有点儿问题', "method:{$defaultController}->{$defaultMethod}() not found");		
    }
    
    protected function fork($methodSegment = null) {
        $methodName = is_null($methodSegment) ? $this->url->segment(strtolower(str_replace('_Controller', '', $this->className))) : $this->url->segment($methodSegment);        
        if (!is_null($methodName) && method_exists($this, $methodName) && is_callable(array($this, $methodName))) {
            $this->$methodName();
            exit;
        }
    }    
}
?>
