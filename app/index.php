<?php
//dengjing34@vip.qq.com
require_once strtr(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR, "\\", '/') . 'config/init.php';

$uri = array_filter(explode('/', current(explode('?', $_SERVER['REQUEST_URI']))));
$default_controller = isset($uri[1]) ? ucfirst($uri[1]) . "_Controller" : DEFAULT_CONTROLLER;
$default_method = isset($uri[2]) ? $uri[2] : DEFAULT_METHOD;
define('CONTROLLER', $default_controller);
$controllers = array ('App_Controller');

if (is_file(CONTROLLER_DIR . CONTROLLER .  '.php')) {
    require_once(CONTROLLER_DIR . CONTROLLER .  '.php');
    $class = new $default_controller ();
    if (in_array(CONTROLLER, $controllers)) {
        $class->{DEFAULT_METHOD} ();        
    } else {        
        if (method_exists($class, $default_method)) {
            $class->{$default_method} ();
        }  else {
            ErrorHandler::show_404();
        }
    }
} else {
    ErrorHandler::show_404();
}
?>
