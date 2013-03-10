<?php
//dengjing34@vip.qq.com
class WeiBo {
    protected $appId;
    protected $appKey;
    protected $appSecret;
    protected $appUrl;
    public $baseUrl;   
    public $http_code;
    
    function __construct() {
        
    }
    
    protected function request($url, $force200 = true, $timeout = 2, $refer = '') {
        $body = false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);//if get binary data need to set header false
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_REFERER, $refer);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // times out after 1s
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$body = curl_exec($ch);
		$info = curl_getinfo($ch);        
        $this->http_code = $info['http_code'];
        if (($force200 && $this->http_code != '200') || curl_errno($ch) != 0) $body = false;
        curl_close($ch);
        return $body;
    }
    
    protected function urlencode_rfc3986($input) {
        if (is_array($input)) {
            return array_map(array(__CLASS__, 'urlencode_rfc3986'), $input);
        } else if (is_scalar($input)) {
            return str_replace('%7E', '~', rawurlencode($input));
        } else {
            return '';
        }
    }
    
    protected function build_http_query($params = array()) {
        if (empty($params)) return '';
		// Urlencode both keys and values
		$keys = $this->urlencode_rfc3986(array_keys($params));
		$values = $this->urlencode_rfc3986(array_values($params));
		$params = array_combine($keys, $values);
        uksort($params, 'strcmp');
        $pairs = array();        
        foreach ($params as  $key => $value) {
            if (is_array($value)) {
                // If two or more parameters share the same name, they are sorted by their value
                // Ref: Spec: 9.1.1 (1)
                natsort($value);
                foreach ($value as $eachValue) $pairs[] = "{$key}=$eachValue}";                
            } else {
                $pairs[] = "{$key}={$value}";
            }
        }
        return $this->urlencode_rfc3986(implode('&', $pairs));
    }
}

?>
