<?php
/**
 * 视频搜索页面
 *
 * @author dengjing34@vip.qq.com
 */
class Search_Controller extends Controller{
    private static $conf = array();
    function __construct() {        
        parent::__construct();
        header('Content-type: text/html; charset=utf-8');
        $this->fork(2);//use the second segment of uri as contorller's method name
        self::$conf = new Conf();
    }
    
    public function index() {
        $url = $this->url;
        $q = $url->get('q');
        $filter = array(
            'addtime' => 'addtime',
            'cid' => 'cid',
            'year' => 'year',
            'letter' => 'letter',
            'area' => 'area',
        );
        $categories = Category::getSecondCategories();
        $letters = Category::letter();
        $areas = Video::area();
        $years = Category::year();
        $page = (int)$url->get('page', 1);
        $conf = self::$conf;
        $videoSearcher = new VideoSearcher();
        Pager::$pageSize = 20;
        foreach ($filter as $field => $queryString) {
            ${$field} = $url->get($queryString);
        }
        $videos = $videoSearcher->sort(array('addtime' => 'desc'))
                ->query('status', Video::STATUS_ACTIVE)
                ->setRows(Pager::$pageSize)
                ->setPage($page)
                ->defaultQuery($q)
                ->query('cid', $cid)
                ->query('year', $year)
                ->query('letter', $letter)
                ->query('area', $area)
                ->timestampQuery('addtime', $addtime)
                ->search();
        $pager = Pager::showPage($videos['numFound']);
        $view = new View('search/welcome', compact(
                'q', 'videos', 'conf', 'pager', 'categories', 'letters',
                'areas', 'years', 'url'
        ));
        $this->render($view->render());
        }
    
    protected function render($html = null) {
        $conf = self::$conf;
        $q = $this->url->get('q');
        $baseHtml = '';
        $header = new View('search/base/header', compact('conf', 'q'));
        $baseHtml .= $header->render();
        $baseHtml .= $html;
        $footer = new View('search/base/footer', compact('conf'));
        $baseHtml .= $footer->render();
        echo $baseHtml;                
    }
}

?>
