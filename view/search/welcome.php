<?php
/**
 * @author dengjing <dengjing34@vip.qq.com>
 */
?>
<style>
.mov_list li{width:900px;}
.mov_list li a em{color:orangered;}
.mov_list li p{width:760px;}
.mov_list li p.intro{height:70px;white-space:normal;}
.mov_list li p em{color:orangered;}
/*.pager*/
.pager{clear:both;padding:0;height:30px;line-height:30px;margin:5px 0 5px 20px;}
.pager span{border:1px solid #CCC;color:black;border-radius:2px;background: #E6E6E6;padding:4px 8px;}
.pager span.dots{padding:4px 0;background:none;border:none;}
.pager a:link,.pager a:visited,.pager a:hover,.pager a:active{border:1px solid #CCC;padding:4px 8px;border-radius:2px;text-decoration:none;}
.pager a:hover{background:#E6E6E6;color:black;}
.pager i{color:orangered;}
/*filter*/
.mov_index dl{padding:10px 5px;}
.mov_index dd{margin-left:6px;}
.mov_index dd a.curr{color:orangered;}
</style>
<div class="box">
    <span>您现在所在的位置：</span>
    <a href="/">84影视网</a> &gt; <span>搜索页</span>
</div>
<div class="box">        
    <div class="video_search_box bd">
    <span class="tl"></span>
    <span class="tr"></span>    
    <div class="ct">        
        <div class="ct">
            <div class="hd">
                <h3>筛选条件</h3>
            </div>
            <div class="mov_index">
                <dl>
                    <dt>按类型</dt>
<?php
$filter = null;
if (!empty($categories)) {   
    $filter .= '<dd><a href="' . Url::siteUrl('search/' . Url::filterParams('cid')) .'">全部</a></dd>';
    /* @var $category Category*/
    /* @var $url Url*/
    foreach ($categories as $category) {
        $params = Url::filterParams('cid', $category->id);
        $style = $url->get('cid') == $category->id ? ' class="curr"' : '';
        $filter .= <<<EOT
<dd><a href="{$params}"{$style}>{$category->cname}</a></dd>   
EOT;
    }
}
echo $filter;
?>
                </dl>
                <dl>
                    <dt>按地区</dt>
<?php
$filter = null;
if (!empty($areas)) {   
    $filter .= '<dd><a href="' . Url::siteUrl('search/' . Url::filterParams('area')) .'">全部</a></dd>';
    /* @var $url Url*/
    foreach ($areas as $area) {
        $areaName = current($area);
        $areaCount = next($area);        
        $params = Url::filterParams('area', $areaName);
        $style = $url->get('area') == $areaName ? ' class="curr"' : '';
        $filter .= <<<EOT
<dd><a href="{$params}" title="共{$areaCount}部视频"{$style}>{$areaName}</a></dd>   
EOT;
    }
}
echo $filter;
?>
                </dl>
                <dl>
                    <dt>按字母</dt>
<?php
$filter = null;
if (!empty($letters)) {   
    $filter .= '<dd><a href="' . Url::siteUrl('search/' . Url::filterParams('letter')) .'">全部</a></dd>';
    /* @var $category Category*/
    /* @var $url Url*/
    foreach ($letters as $letter) {
        $params = Url::filterParams('letter', $letter);
        $style = $url->get('letter') == $letter ? ' class="curr"' : '';
        $filter .= <<<EOT
<dd><a href="{$params}"{$style}>{$letter}</a></dd>   
EOT;
    }
}
echo $filter;
?>
                </dl>
                <dl>
                    <dt>按年份</dt>
<?php
$filter = null;
if (!empty($years)) {   
    $filter .= '<dd><a href="' . Url::siteUrl('search/' . Url::filterParams('year')) .'">全部</a></dd>';
    /* @var $category Category*/
    /* @var $url Url*/
    foreach ($years as $year) {
        $params = Url::filterParams('year', $year);
        $style = $url->get('year') == $year ? ' class="curr"' : '';
        $filter .= <<<EOT
<dd><a href="{$params}"{$style}>{$year}</a></dd>   
EOT;
    }
}
echo $filter;
?>
                </dl>                
            </div>
        </div>        
        <div class="video_search_total">
            共找到<span class="kw"><?php echo $videos['numFound']?></span>条
            <?php
            if ($q) echo "与<span class=\"kw\">{$q}</span> 的相关";            
            ?>
            结果
        </div>
        <div class="pager"><?php echo $pager?></div>
        <ul class="mov_list">
            <?php
            /* @var $video Video*/
            $videoHtml = null;
            $uploadPath = $conf->get('upload_path');
            foreach ($videos['docs'] as $video) {
                $content = $video->highLight($q, mb_strimwidth($video->content(), 0, 500, '...', 'UTF-8'));
                $videoHtml .= <<<EOT
<li>
    <a href="/movie/{$video->id}.html" title="{$video->title}"><img src="/{$uploadPath}/{$video->picurl}" width="119" height="170" border="0" onerror="this.src='/views/images/nophoto.jpg'" alt="{$video->title}"/></a>
    <a href="/movie/{$video->id}.html" class="title" title="{$video->title}">{$video->highLight($q, $video->title)}</a>
    <p>
        导演：{$video->director} <br />
        主演：{$video->actorLink($q)}<br />
        地区：<a href="/search/?q={$video->area}">{$video->area}</a>
        年份：<a href="/search/?q={$video->year}">{$video->year}</a>
	语言：<a href="/search/?q={$video->language}">{$video->highLight($q, $video->language)}</a>
        评分：<span class="score">{$video->score}</span>
        更新：{$video->addtime()}
    </p>
    <p class="intro">剧集介绍：{$content}</p>
    <p class="bar">
        <a href="/movie/{$video->id}.html" class="view">详细资料&gt;&gt;</a>
        <a href="/player/{$video->id}-1.html" class="watch">立即观看</a> 
    </p>    
</li>
EOT;
            }
            echo $videoHtml;
            ?>
        </ul>
        <div class="pager"><?php echo $pager?></div>   
    </div>
    <span class="bl"></span>
    <span class="br"></span>     
</div>    
