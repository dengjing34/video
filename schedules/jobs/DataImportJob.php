<?php
//dengjing34@vip.qq.com
class DataImportJob extends Job {
	private $backupDir, $localBackupDir, $sqlFile, $db_user, $db_pwd, $db_name;
	function __construct() {
		$this->backupDir = 'gxcms/temp/DataBack/';
		$this->localBackupDir = BASEDIR . "temp/DataBack/";
		$conf = new Conf();
		foreach(array('db_user', 'db_pwd', 'db_name') as $v) {
			$this->$v = $conf->get($v);
		} 		
		parent::__construct();
	}
	
	function log($msg) {
		echo "{$msg}\n";
		parent::log($msg);
	}
	
	function notified() {
		if ($this->download()){
			$this->importData();
		}
	}
	
	private function importData() {
		if ($this->sqlFile) {
            $cmd = "gzip -d {$this->localBackupDir}{$this->sqlFile}";
            exec($cmd);
			$cmd = "mysql -u{$this->db_user} -p{$this->db_pwd} {$this->db_name} < {$this->localBackupDir}" . str_replace('.gz', '', $this->sqlFile);
			exec($cmd);
		}
	}
	
	private function download() {
		$options = array (
			'hostname' => '118.123.8.171',
			'username' => 'dengjing',
			'password' => '6210245',
			'passive' => false,
		);
		$ftp = new Ftp($options);
		$ftp->connect();
		$ftp->_login();
		if (!$ftp->_is_conn()){
			$this->log('ftp server login failed');
			return false;
		}
		$this->log("ftp server login successed");
		if (!$ftp->changedir($this->backupDir)) {
			$this->log("changedir {$this->backupDir} faild");
			return false;
		}
		$this->log("changedir {$this->backupDir} successed");
		$files = $ftp->list_files();
		if (empty($files)){
			$this->log('no data archive right now');
			return false;
		}
		rsort($files);
        if (is_file($this->localBackupDir . str_replace('.gz', '', $files[0])) || is_file($this->localBackupDir . $files[0])){
            $this->log("{$files[0]} has been downloaded");
            return;
        }
		if ($ftp->download($files[0], $this->localBackupDir . $files[0])) {
			$this->log("dowload {$files[0]} success");
			$this->sqlFile = $files[0];
			return true;
		} else {
			$this->log("dowload {$files[0]} failed");
			return false;
		}		
	}
}
?>
