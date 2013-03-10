<?php
//dengjing34@vip.qq.com

class Group_Controller extends App_Controller{

	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$url = $this->url;
		$error = null;
		$file = BASEDIR . 'temp/Temp/qq_gourp.csv';
		if ((bool)($qq = $url->post('qq'))) {			
			if (preg_match('/^\d{5,10}$/', $qq)) {						
				$msg = "{$qq}," . date('Y-m-d H:i:s') . "\n";
				if (!file_exists($file)) {					
					file_put_contents($file, $msg);
				} else {
					$content = file_get_contents($file);
					if (preg_match("/^{$qq},(.*)\n/U", $content)) {
						$content = preg_replace("/^{$qq},(.*)\n/U", $msg, $content);
					} else {
						$content .= $msg;
					}
					file_put_contents($file, $content);					
				}
				$error = "thanks!";
			} else {
				$error = '需要您的QQ号码 5-10个数字';
			}
		}
		$list = null;
		if ($url->get('p') == 'sunset' && is_file($file)) {
			$list = nl2br(file_get_contents($file));
		}
		$view = new View('app/group', compact('url', 'error', 'list'));
		$view->render(true);
	}
}

?>
