<?php
//dengjing34@vip.qq.com
require_once(strtr(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR, "\\", '/') . 'config/init.php');
class Cli {
    private $config;
    
    function __construct() {
        // 间隔单位是分钟
        $this->config = array(
            array('jobName' => 'IndexedJob', 'interval'=> 60 * 24, 'startTime' => '2012-01-18 01:30'),
            array('jobName' => 'VideoUpdateJob', 'interval'=> 60 * 3, 'startTime' => '2012-01-26 23:50'),
			array('jobName' => 'DataArchiveJob', 'interval'=> 60 * 24 * 3, 'startTime' => '2012-01-29 23:30'),
            //array('jobName' => 'SiteMapJob', 'interval'=> 60 * 3, 'startTime' => '2012-01-26 23:10'),
            //array('jobName' => 'TestJob', 'interval'=> 1, 'startTime' => '2012-01-17 23:55'),
        );
    }
    
    function run() {
        $currTime = mktime(date('H'), date('i'), 0, date('m'), date('d'), date('Y'));
        foreach ($this->config as $job) {
            $startTime = strtotime($job['startTime']);
            if ($currTime == $startTime || ($currTime - $startTime > 0 && ($currTime - $startTime) % ($job['interval'] * 60) == 0) ){                
                shell_exec("php " . BASEDIR . "schedules/scheduler.php {$job['jobName']}");
            }
        }
    }
}
$cli = new Cli();
$cli->run();
?>
