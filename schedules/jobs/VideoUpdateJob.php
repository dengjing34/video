<?php
//dengjing34@vip.qq.com
class VideoUpdateJob extends Job {
    private $http, $html, $login_name, $login_pwd, $mailBody, $host,$receiver;
    
    function __construct() {
        parent::__construct();
        $this->http = new Http();
        Http::$sendCookie = Http::$receiveCookie = true;
        $this->login_name = 'dengjing';
        $this->login_pwd = 'dengjing4234';
        Http::$postData = array(
            'login_name' => $this->login_name,
            'login_pwd' => $this->login_pwd,
        );
        $this->host = "http://www.84fun.com";
        $this->receiver = "dengjing34@vip.qq.com";
    }
        
    function notified() {        
        $this->login();      
        //$url = "{$this->host}/index.php?s=Admin/Collect/Gxcms/ziyuan/a/fid/3/action/day/h/24/xmlurl/http:@@www.baduzy.com@xml@01dysp.asp?ac=videolist/reurl/http:@@www.baduzy.com@detail@?";
		$url = "{$this->host}/index.php?s=Admin/Collect/Gxcms/ziyuan/a/fid/8/action/day/h/24/xmlurl/http:@@www.bdzy.cc@xml@caiji.asp?ac=videolist/reurl/http:@@www.bdzy.cc@detail@?";
        $this->crawlMovies($url);        
	$this->latestVideoUpdate();
        $email = new Email();        
        $email->send($this->receiver, date('Y-m-d H:i:s') . "自动采集视频任务完成", $this->mailBody);
    }

	private function latestVideoUpdate() {
		$videos = Video::latestVideo();
		foreach ($videos as $video) {
			try {
				$video->save();
			} catch (Exception $e) {
				$this->log($e->getMessage());
			}
		}
		return $this;
	}
    
    private function crawlMovies($url) {
        if ($this->html = $this->http->crawl($url, true, 360)) {
            if (preg_match("/<div id=\"show\">([\s|\S]*)(<\/div><div><span>2<\/span>秒后将自动采集下一页！|<\/div><div>所有采集任务已经完成，返回)/U", $this->html, $m1)){
                $this->mailBody .= $m1[1];
            }
            if (preg_match("/<meta http-equiv=\"refresh\" content=2;url=(.*)>/U", $this->html, $m2)) {
                $this->crawlMovies("{$this->host}{$m2[1]}");
            }            
        } else {
            $this->log('crawl failed');
        }                
    }
    
    private function login() {
        $url = "{$this->host}/index.php?s=Admin/Login/Check";        
        if ($this->html = $this->http->crawl($url, true, 60)) {
            $this->log('login successed');
        } else {
            $this->log('login failed');
            $this->login();
        }
        Http::$postData = false;//only login need to post some data        
    }
}
?>
