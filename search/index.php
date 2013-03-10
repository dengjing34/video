<?php
require_once strtr(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR, "\\", '/') . 'config/init.php';
$uri = Url::segmentsArray();
$default_controller = isset($uri[1]) ? ucfirst($uri[1]) . "_Controller" : DEFAULT_CONTROLLER;
$default_method = isset($uri[2]) ? $uri[2] : DEFAULT_METHOD;
define('CONTROLLER', $default_controller);
$controllers = array ('Search_Controller');

if (is_file(CONTROLLER_DIR . CONTROLLER .  '.php')) {
    require_once(CONTROLLER_DIR . CONTROLLER .  '.php');
    $class = new $default_controller ();
    if (in_array(CONTROLLER, $controllers)) {
        $class->{DEFAULT_METHOD} ();        
    } else {        
        if (method_exists($class, $default_method) && is_callable(array($class, $default_method))) {
            $class->{$default_method} ();
        }  else {
            ErrorHandler::show_404();
        }
    }
} elseif (is_file(CONTROLLER_DIR . DEFAULT_CONTROLLER .  '.php') && isset($uri[1])) {
    require_once(CONTROLLER_DIR . DEFAULT_CONTROLLER .  '.php');
    $controller = DEFAULT_CONTROLLER;
    $class = new $controller();
    if (method_exists($class, $uri[1]) && is_callable(array($class, $uri[1]))) {
        $class->{$uri[1]}();
    } else {
        ErrorHandler::show_404();
    }    
} else {
    ErrorHandler::show_404();
}
?>
