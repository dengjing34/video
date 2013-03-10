<?php
//dengjing34@vip.qq.com
require_once(strtr(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR, "\\", '/') . 'config/init.php');
define('JOBS_DIR', BASEDIR . 'schedules/jobs/');
set_include_path(get_include_path() . PATH_SEPARATOR . JOBS_DIR);
class Cmd {
    private $config;
    
    function __construct() {
        // 间隔单位是分钟
        $this->config = array(
            array('jobName' => 'IndexedJob', 'interval'=> 60 * 24, 'startTime' => '2012-01-18 01:30'),
            array('jobName' => 'VideoUpdateJob', 'interval'=> 60 * 6, 'startTime' => '2012-01-26 23:50'),
            array('jobName' => 'PicJob', 'interval'=> 60 * 6, 'startTime' => '2012-01-26 23:55'),
            //array('jobName' => 'TestJob', 'interval'=> 1, 'startTime' => '2012-01-17 23:55'),
        );
    }
    
    function run() {
        $gid = getmypid();
        $currTime = mktime(date('H'), date('i'), 0, date('m'), date('d'), date('Y'));
        foreach ($this->config as $job) {
            $startTime = strtotime($job['startTime']);
            if ($currTime == $startTime || ($currTime - $startTime > 0 && ($currTime - $startTime) % ($job['interval'] * 60) == 0) ){
                if (function_exists('pcntl_fork')) {
                    $job['pid'] = pcntl_fork();//for linux only
                    if ($job['pid'] == -1) {
                        continue;//die('could not fork');
                    } elseif ($job['pid']) {
                         // we are the parent
                        $status = 0;
                        pcntl_wait($status, WNOHANG); //WNOHANG不阻塞父进程 //Protect against Zombie children                      
                    } elseif ($job['pid'] === 0) {                        
                        // we are the child                          
                        if (is_file(JOBS_DIR . "{$job['jobName']}.php")) {
                            $jobClass = new $job['jobName']();
                            $jobClass->log($gid ." => " .getmypid(). " {$job['jobName']}\tStart @ ".date('Y-m-d H:i:s ')."\n");
                            $jobClass->notified();
                            $jobClass->log($gid ." => " .getmypid(). " {$job['jobName']}\tStop @ ".date('Y-m-d H:i:s ') . "\tMemory(now/top): " . memory_get_usage() . '/' . memory_get_peak_usage() . "\n");
                            unset($jobClass);
                            exit;
                        } 
                    }                    
                } else {
                    shell_exec("php " . BASEDIR . "schedules/scheduler.php {$job['jobName']}");//for windows
                }                
            }
        }
    }
}
$cli = new Cmd();
$cli->run();
?>
