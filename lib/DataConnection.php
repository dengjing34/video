<?php
//dengjing34@vip.qq.com
class DataConnection {
    
	private static $connection = null;
    
	public static function getConnection() {
        $conf = new Conf();        
		if (self::$connection == null) {
			self::$connection = mysql_connect($conf->get('db_host'), $conf->get('db_user'), $conf->get('db_pwd')) or die(mysql_error());
			mysql_select_db($conf->get('db_name')) or die(mysql_error());
			mysql_query('set names utf8') or die(mysql_error());
		}
		return self::$connection;
	}

}
?>