<?php
class TestJob extends Job {
    function __construct() {
        parent::__construct();
    }
    
    function notified() {
        //$this->log(date('Y-m-d H:i:s'));
	$this->update();
    }

	function update() {
		$o = new Video();
		$o->area = '';
		foreach ($o->find() as $v) {
			$v->area = '其它';
			try {
				$v->save();
			} catch (Exception $e) {echo $e->getMessage();}
		}
	}
}
?>
