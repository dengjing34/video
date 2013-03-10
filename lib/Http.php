<?php
//dengjing34@vip.qq.com
class Http {
	public static $lastProxy = null;
	public static $uas = array( //可使用的UA
		'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)',
		'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
		'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 627; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)',
		'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.8.0.6) Gecko/20060728 Firefox/1.5.0.6',
		'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
		'Mozilla/5.0 (X11; U; Linux xw3m i686; zh-CN; rv:1.9.0.4) Ubuntu/9.10 (intrepid) Firefox/4.0.1',
	);
	public static $useProxy = false;
    public static $cookie, $postData = false, $sendCookie = false, $receiveCookie = false, $http_code;   
	public static function crawl($url, $force200 = false, $timeout = 3, $refer = '') {
        self::$cookie = BASEDIR . 'temp/Temp/_cookie.txt';
        if (!is_file(self::$cookie) && self::$receiveCookie && self::$sendCookie) file_put_contents (self::$cookie, '');
		$body = false;
		$ch = curl_init();
		$proxystr = self::$lastProxy;
		if (self::$useProxy == true) {
			$uas = self::$uas;			
			if (is_null($proxystr)) {
			    $content = file_get_contents(BASEDIR . "temp/proxy/proxy.txt");
			    $proxies = explode("\n", $content);
			    $proxy = $proxies[array_rand($proxies)];
			    $proxy = explode("\t", $proxy);
			    $proxystr = implode(":", $proxy);
			}
			$ip = current(explode(':', $proxystr));
			$header = array("X-FORWARDED-FOR:{$ip}", "CLIENT-IP:{$ip}", "REMOTE_ADDR:{$ip}", "HTTP_X_FORWARDED_FOR:{$ip}");			
			$ua = $uas[array_rand($uas)];
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_PROXY, $proxystr);
			curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		} else {
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux xw3m i686; zh-CN; rv:1.9.0.4) Ubuntu/9.10 (intrepid) Firefox/4.0.1');
		}
        if (self::$postData) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, self::$postData);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转 登录需要
		}
        if (self::$receiveCookie) curl_setopt($ch, CURLOPT_COOKIEJAR, self::$cookie);//保存cookie
		if (self::$sendCookie) curl_setopt($ch, CURLOPT_COOKIEFILE, self::$cookie); //发送cookie
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);//if get binary data need to set header false
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_REFERER, $refer);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // times out after 1s
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$body = curl_exec($ch);
		$info = curl_getinfo($ch);
		//echo $info['http_code'] . "\n";
        self::$http_code = $info['http_code'];
		if ($body === false || ($force200 && $info['http_code'] != 200) || (curl_error($ch) != '')) {
			curl_close($ch);
			self::$lastProxy = null;
			return false;
		}
		self::$lastProxy = $proxystr;
		curl_close($ch);
		return $body;
	}
	
	public static function remote_addr() {
		// dnion CDN IP : static.baixing.net or img.baixing.net
		if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'static.baixing.net' && isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			$pos = strpos($ip, ',');
			if($pos !== false) $ip = substr($ip, 0, $pos);
			if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
				return $ip;
			} //if X-Forwarded-For header is not correct use NetScaler IP
		}

		// NetScaler IP
		if (isset($_SERVER["HTTP_X_EBAY_CLIENT_IP"])) {
			$ip = $_SERVER["HTTP_X_EBAY_CLIENT_IP"];
			$pos = strpos($ip, ',');
			return ($pos !== false) ? $ip = substr($ip, 0, $pos) : $ip;
		}

		if (isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];	//Direct IP
		} else {
			return "127.0.0.1";	//for CLI
		}
	}	
	
	public static function getHtmlUseProxy($url, $times = 100) {
		self::$useProxy = true;
		for ($i = 0; $i < $times; $i++) {
			if ($htmlStr = self::crawl($url, true)) {
				return $htmlStr;
			}			
		}
		return false;
	}	
}
?>