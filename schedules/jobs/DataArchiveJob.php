<?php
//dengjing34@vip.qq.com
class DataArchiveJob extends Job {
	private $db_user, $db_pwd, $db_name, $backupDir;
	function __construct() {
		parent::__construct();
		$conf = new Conf();
		foreach(array('db_user', 'db_pwd', 'db_name') as $v) {
			$this->$v = $conf->get($v);
		}  
		$this->backupDir = BASEDIR . "temp/DataBack/";
	}
	
	function notified() {
		$file = "{$this->backupDir}{$this->db_name}" . date('Y-m-d') . ".sql.gz" ;
		$cmd = "mysqldump --default-character-set=utf8 -u{$this->db_user} -p{$this->db_pwd} --add-drop-table {$this->db_name}|gzip > {$file}";
        //$file = "{$this->backupDir}{$this->db_name}" . date('Y-m-d') . ".sql" ;
        //$cmd = "mysqldump --default-character-set=utf8 -u{$this->db_user} -p{$this->db_pwd} --add-drop-table {$this->db_name} > {$file}";
		exec($cmd);
		$fileSize = 0;
		if (is_file($file)) $fileSize = round((filesize($file) / 1024 / 1024), 2);
		$email = new Email();
		$result = $fileSize == 0 ? 'failed' : 'successed';
		$title = "mysql archive {$result} {$file}:" . $fileSize . "M";
		$body = "{$file}:{$fileSize}M";
		$email->send("dengjing34@vip.qq.com", $title, $body);
	}
}
?>
