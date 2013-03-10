<?php
//dengjing34@vip.qq.com
class Conf {
    private $conf;
    
    function __construct() {
        $this->conf = include_once BASEDIR .'config.php';
    }
    
    function get($name) {
        return isset($this->conf[$name]) ? $this->conf[$name] : null;
    }
}
?>
