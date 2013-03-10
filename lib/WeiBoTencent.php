<?php
//dengjing34@vip.qq.com
class WeiBoTencent extends WeiBo{
    const SIGNATURE_METHOD = 'HMAC-SHA1';
    const VERSION = '1.0';
    const HTTP_METHOD_GET = 'GET';
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';
    const WBVERSION = 1;
    public $oauth_token, $oauth_token_secret, $oauth_verifier, $openid, $openkey, $appObject;
    private $apiAddr = "http://open.t.qq.com/api/";
    public static $options = array(
        'appId',
        'appKey',
        'appSecret',
        'appUrl',
    );
    public $authReturnParams = array(
        'oauth_token',
        'oauth_verifier',
        'openid',
        'openkey',
    );
    
    public $tokenArray = array(
        'oauth_token',
        'oauth_token_secret',
    );
    
    function __construct($o = array()) {
        parent::__construct();
        foreach (self::$options as $key) {
            if (!is_null($o->{$key})) $this->{$key} = $o->{$key};
            else throw new Exception("option['{$key}'] must be set when construct");
        }
        $this->appObject = $o;        
        $this->baseUrl = "http://open.t.qq.com/cgi-bin/";
        foreach ($this->authReturnParams as $val) $this->{$val} = Cookie::get ($val);
    }
    
    public function request_token() {
        $method = $this->baseUrl . __FUNCTION__;
        $params = array(
            'oauth_consumer_key' => $this->appKey,
            'oauth_signature_method' => self::SIGNATURE_METHOD,
            'oauth_timestamp' => time(),
            'oauth_nonce' => $this->nonceHash(),
            'oauth_callback' => $this->appUrl,
            'oauth_version' => self::VERSION
        );
        $params['oauth_signature'] = $this->signature($method, $params);
        $url = "{$method}?" . http_build_query($params);
        $res = array();
        if ($response = $this->request($url)) {
            parse_str($response, $res);            
            foreach ($this->tokenArray as $val) {
                $this->{$val} = $res[$val];
                Cookie::set($val, $res[$val]);
            }            
        }
        return $this;         
    }
    
    public function access_token() {
        $method = $this->baseUrl . __FUNCTION__;
        $params = array(
            'oauth_consumer_key' => $this->appKey,
            'oauth_token' => $this->oauth_token,
            'oauth_signature_method' => self::SIGNATURE_METHOD,
            'oauth_timestamp' => time(),
            'oauth_nonce' => $this->nonceHash(),
            'oauth_verifier' => $this->oauth_verifier,
            'oauth_version' => self::VERSION,
        );
        $params['oauth_signature'] = $this->signature($method, $params);
        $url = "{$method}?" . http_build_query($params);
        if ($response = $this->request($url)) {
            parse_str($response, $res);
            foreach ($res as $key => $val) {
                $this->{$key} = $val;
                Cookie::set($key, $val);
            }
        }
        return $this;
    }
    
    public function user_info() {
        $method = "user/info";
        try {
            return $this->requestByOpen($method);
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }    
    
    public function checkCallBack($params = array()) {        
        $res = false;
        if (count($this->authReturnParams) == count(array_filter($params))) {
            $res = true;
            foreach ($this->authReturnParams as $key) {
                $this->{$key} = $params[$key];
                Cookie::set($key, $params[$key]);
            }
            $this->oauth_token_secret = Cookie::get('oauth_token_secret');
        }        
        return $res;
    }        
    
    public function deleteToken() {
        foreach (array_merge($this->authReturnParams, $this->tokenArray) as $val) Cookie::delete ($val);
    }
    
    public function authorizeUrl() {
        return "{$this->baseUrl}authorize?oauth_token={$this->oauth_token}";
    }           
    
    private function nonceHash() {
        return md5(mt_rand(1, 100000) . microtime(true));
    }

    private function signature($appMethod, $params, $httpMethod = self::HTTP_METHOD_GET) {
        $methods = array(
            strtoupper($httpMethod),
            $this->urlencode_rfc3986($appMethod),            
        );
        $baseString = implode('&', array_merge($methods, array($this->build_http_query($params))));
        $keyString = implode('&', array($this->appSecret, $this->oauth_token_secret));
        $sign = base64_encode(hash_hmac('sha1', $baseString, $keyString, true));
        return $sign;        
    }

    private function sig($appMethod, $params = array(), $httpMethod = self::HTTP_METHOD_GET) {
        $methods = array(
            strtoupper($httpMethod),
            $this->urlencode_rfc3986($appMethod),            
        );
        $baseString = implode('&', array_merge($methods, array($this->build_http_query($params))));        
        return base64_encode(hash_hmac('sha1', $baseString, $this->appSecret, true));
    }
    
    private function standardOpenParams($appMethod, $params = array()) {
        $standardParams = array(
            'appid' => $this->appId,
            'openid' => $this->openid,
            'openkey' => $this->openkey,
            'reqtime' => time(),
            'wbversion' => self::WBVERSION,
            'format' => self::FORMAT_JSON,
        );
        $params = array_merge($standardParams, $params);
        $params['sig'] = $this->sig($appMethod, $params);
        return $params;
    }
    
    private function requestByOpen($method, $params = array()) {
        $finalParams = $this->standardOpenParams($method, $params);
        $url = "{$this->apiAddr}{$method}?" . http_build_query($finalParams);
        $res = new stdClass();
        $i = 1;
        do {
            if ($i >= 3) break;//最多三次
            if ($response = $this->request($url)) {
                $res = json_decode($response);
                if ($res->ret != 0) throw new Exception("get userinfo failed");
            }
            $i++;
        } while ($response == false);        
        if (!isset($res->ret)) throw new Exception('connect failed');
        return $res->data;        
    }    
}
?>
