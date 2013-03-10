<?php
require_once(strtr(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR, "\\", '/') . 'config/init.php');
define('SCHEDULE_DIR', BASEDIR . 'schedules/');
set_include_path(get_include_path() . PATH_SEPARATOR . SCHEDULE_DIR . 'jobs/');
class Scheduler {

	public $jobName;

	function __construct() {
		if (PHP_SAPI === 'cli' && $_SERVER['argc'] > 1) {
			$this->jobName = $_SERVER['argv'][1]; //第一个参数是job名称
			$this->start();
		} elseif (isset($_GET['job']) && $_GET['job']) {
			$this->jobName = $_GET['job']; //第一个参数是job名称;
			$this->start();
		} else {
			echo "there's no job arguments here \n";
		}
	}

	function start() {
		if (!is_file(SCHEDULE_DIR . "jobs/{$this->jobName}.php")) {
			echo "job file:" . SCHEDULE_DIR . "jobs/{$this->jobName}.php not found";
			return;
		}
		$job = new $this->jobName;
		if (!method_exists($job, 'notified')) {
			echo get_class($job) . "'s method 'notified' not found";return;
		}
		$job->notified();
	}
}
$scheduler = new Scheduler();
?>