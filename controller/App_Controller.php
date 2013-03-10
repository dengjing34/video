<?php
//dengjing34@vip.qq.com
class App_Controller extends Controller {
    protected $appObject;    
    protected $twitterUser;    
    protected $nav = array(
        '' => '首页',
        'hot' => '人气排行',
        'all' => '所有应用',
        'help' => '常见问题',
        'about' => '关于我们',
    );
    function __construct() {
        parent::__construct();
        $this->noCache();
        header("Content-type:text/html; charset=utf-8");
    }
    
    function index() {
        $this->branch();
    }
    
    function checklogin() {        
        $this->getApp();//获取db中的 app 信息        
        try{
           $weibo = new WeiBoTencent($this->appObject); 
        }catch (Exception $e) {
            ErrorHandler::show_404('有点儿问题', $e->getMessage()); 
        }
        $returnParams = array();
        foreach ($weibo->authReturnParams as $val) {
            $returnParams[$val] = $this->url->get($val);
        }
        //先检测是否是登录后回调带参数的情况
        if ($weibo->checkCallBack($returnParams)) {
            Url::redirect(Url::siteUrl("app/{$this->appObject->name}"));// 腾讯微博登录后callback的url会带上oauth_token, oauth_verifier, openid, openkey四个GET参数
        }        
        $flag = true;
        //没有回调参数检查cookie是否登录过
        foreach ($weibo->authReturnParams as $val) {
            if (is_null($weibo->{$val} = Cookie::get($val))) {
                $flag = false;continue;
            } 
        }
        if (!$flag)  {
            //没有 cookie 信息;
            $weibo->oauth_token = null;
            do {
                $oauth_token = $weibo->request_token()->oauth_token;
            }while (is_null($oauth_token));
            $aside = $this->aside();
            $app = $this->appObject;
            $view = new View('app/base/login', compact('weibo', 'app','aside'));
            $this->render($view->render());
            exit;
        } else {
            //有cookie 信息 尝试获取用户信息, 针对不同的应用需要不同的openkey和openid
            try {
                $this->twitterUser = $weibo->user_info();
            }catch (Exception $e) {
                foreach ($weibo->authReturnParams as $val) Cookie::delete ($val);//清空cookie;
                Url::redirect(Url::siteUrl("app/{$this->appObject->name}"));
            }            
        }
    }
    
    function aside() {
        $apps = WeiBoApp::hotApps();        
        $view = new View('app/base/aside', compact('apps'));
        return $view->render();
    }
    
    function getApp() {
        $appName = strtolower($this->url->segment(2));       
        if (!($this->appObject = WeiBoApp::loadByName($appName))) ErrorHandler::show_404 ('有点问题', "应用:[{$app}] 不存在");
        return $this->appObject;
    }
    
    function render($html = null, $option = array()) {
        $opt = array(
          'title' => '好玩有趣的腾讯微博应用平台',
          'description' => '84影视网的微博应用平台是集合了各种好玩有趣的性格测试,IQ测试,微博游戏的一个娱乐平台',  
        );
        $nav = $this->nav;
        if (!empty($option)) {
            $opt = array_merge($opt, $option);
        } elseif (!is_null($this->appObject)) {
            $opt['title'] = $this->appObject->title ? $this->appObject->title : $opt['title'];
            $opt['description'] = $this->appObject->description ? $this->appObject->description : $opt['description'];            
        }
        $opt['title'] .= "_84影视网微博应用";
        $controller = $this->url->segment(2, '');
        $baseHtml = '';
        $header = new View('app/base/header', compact('opt', 'nav', 'controller'));
        $baseHtml .= $header->render();
        $baseHtml .= $html;
        $footer = new View('app/base/footer');
        $baseHtml .= $footer->render();
        echo $baseHtml;        
    }
}
?>
