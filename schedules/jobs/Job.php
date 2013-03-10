<?php
//dengjing34@vip.qq.com
class Job {
	protected $className, $logDir;
	function __construct() {
		$this->className = get_class($this);
		$this->logDir = BASEDIR  . "temp/Logs/schedules/" . date('Y-m') . "/";
		if (!is_dir($this->logDir)) mkdir ($this->logDir, 0777, true);//递归创建文件夹
	}
	
	function log($msg) {
		file_put_contents("{$this->logDir}{$this->className}-" . date('Y-m-d') . ".txt", getmypid() . ' ' . date("Y-m-d H:i:s") . " {$msg}\n", FILE_APPEND);
	}
	
	function notified(){		
        
    }       
}
?>