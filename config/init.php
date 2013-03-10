<?php
//dengjing34@vip.qq.com
date_default_timezone_set('PRC'); //设置时区
define('START_TIME', microtime(true));
define('BASEDIR', strtr(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR, "\\", '/'));
define('DEFAULT_CONTROLLER', 'Home_Controller');
define('DEFAULT_METHOD', 'index');
define('CONTROLLER_DIR', BASEDIR . 'controller/');
define('BASEURL', isset($_SERVER['HTTP_HOST']) ?  'http://' . $_SERVER['HTTP_HOST'] . '/' : null);
define('UPLOAD_DIR', BASEDIR . 'uploads/');
set_include_path(get_include_path() . PATH_SEPARATOR . BASEDIR . 'lib/');

function __autoload($className) {
	require_once $className . '.php';
}
?>