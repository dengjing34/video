<?php
class LatestVideoJob extends Job {
	function __construct() {
		parent::__construct();
	}

	function notified() {
		foreach (Video::latestVideo() as $video) {
			try {
				$video->save();
			} catch (Exception $e) {
				$this->log($e->getMessage());
			}
		}
	}
}
?>
