<?php
/**
 * @author dengjing <dengjing34@vip.qq.com>
 */
/* @var $conf Conf */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo $conf->get('web_keywords');?>">
<meta name="description" content="<?php echo $conf->get('web_description');?>">
<title><?php echo $conf->get('web_name');?></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<script language="javascript">var SitePath='/';var SiteMid='';var SiteCid='';var SiteId='';</script>
<script language="JavaScript" type="text/javascript" src="/views/js/jquery.js"></script>
<link rel='stylesheet' type='text/css' href='/template/default/template__135.css'>
<link rel="shortcut icon" href="/favicon.ico" />
<style type="text/css">
#subMenu{border-left:1px solid #C5DDF6;border-right:1px solid #C5DDF6;padding:10px 5px;}
#subMenu a:link,#subMenu a:visited,#subMenu a:hover,#subMenu a:active{margin:0 5px;padding:3px;border:1px solid #fff;}
#subMenu a:hover,#subMenu a.cur{color:whitesmoke;background:#82CE11;border:1px solid #69B007; -moz-border-radius:3px;-webkit-border-radius:3px;text-decoration:none;text-shadow:0 1px 0 #333;-moz-transition:background .4s linear;-webkit-transition:background .4s linear;}
</style>

</head>
<body>  
<div id="wrapper">

<div class="header">
  <div class="top">
    <a title="84影视网-大量高清电影电视剧动漫在线观看" class="logo" href="/" style="text-indent:-999px;">84影视网-大量高清电影电视剧动漫在线观看</a>
    <div id="Login" class="login"></div>    
    <div class="control">
        <a href="/map/lists/id/rss.html">RSS订阅</a>&nbsp;|&nbsp;
        <a href="javascript:void(0)" id="fav">收藏本站</a>&nbsp;|&nbsp;
        <a href="/guestbook/lists.html">留言反馈</a>&nbsp;|&nbsp;
        <span class="his"  id="ggg" onmouseover="fnDisplayMenu(this,'mnuArtStyles');" onmouseout="fnHideMenu('mnuArtStyles'); fnRemoveHighlight('mnuArtStyles');" >
        <a class="headerMnuLink" id="mnuArtStylesLink" href="javascript:void(0);">播放记录</a></span></div>
    <div class="popup1" id="mnuArtStyles"  style="display:none" onmouseover="fnDisplayMenu2($('#mnuArtStylesLink'),'mnuArtStyles');" onmouseout="fnHideMenu('mnuArtStyles'); fnRemoveHighlight('mnuArtStyles');" >
      <div id="history">
      </div>
   </div>

  </div>
  <div class="nav"><a href="/" class="cur">首页</a>
    <a onfocus="this.blur();" href="/list/1.html" >电影</a><a onfocus="this.blur();" href="/list/2.html" >电视剧</a><a onfocus="this.blur();" href="/list/3.html" >动漫</a><a onfocus="this.blur();" href="/list/4.html" >综艺</a><a onfocus="this.blur();" href="/list/5.html" >体育</a><a onfocus="this.blur();" href="/list/6.html" >纪录片</a><a onfocus="this.blur();" href="/newslist/7.html" >资讯</a><span>|</span>
    <a href="/top10/lists.html" >排行</a>
    <a href="/special/lists.html" >专题</a> 
  </div>
    <div id="subMenu">
        <a href="/list/8.html" title="动作片">动作片</a>
        <a href="/list/9.html" title="喜剧片">喜剧片</a>
        <a href="/list/10.html" title="爱情片">爱情片</a>
        <a href="/list/11.html" title="科幻片">科幻片</a>
        <a href="/list/12.html" title="剧情片">剧情片</a>
        <a href="/list/13.html" title="恐怖片">恐怖片</a>
        <a href="/list/14.html" title="战争片">战争片</a>
        <a href="/list/15.html" title="国产剧">国产剧</a>
        <a href="/list/16.html" title="台湾剧">台湾剧</a>
        <a href="/list/17.html" title="香港剧">香港剧</a>
        <a href="/list/19.html" title="日本剧">日本剧</a>
        <a href="/list/20.html" title="欧美剧">欧美剧</a>
        <a href="/list/18.html" title="韩国剧">韩国剧</a>
        <a href="/list/21.html" title="海外剧">海外剧</a>   
    </div>     
  <div class="query"> 
    <span class="query_l"></span>     
    <form action="<?php echo $conf->get('web_path') . 'search/'?>" onsubmit="$('#q').val($('#q').val().replace('输入演员,影视剧名字或拼音', ''));" method="get" name="search" id="search" >
      <input onblur="if($(this).val() == '') $(this).val('输入演员,影视剧名字或拼音');" onfocus="if($(this).val() == '输入演员,影视剧名字或拼音') $(this).val('');" placeholder="输入演员,影视剧名字或拼音" x-webkit-speech="x-webkit-speech" type="text" value="<?php echo $q?>" id="q" name="q" autocomplete="off" maxlength="35">      
      <button type="submit" class="search_bt"><span>搜索</span></button>
    </form>
    <div class="hot_kw">热门：
    <?php 
    if (($hotkey = $conf->get('web_hotkey'))) {
        $hotLink = null;
        foreach (explode('|', $hotkey) as $word) {
            $encodeWord = urlencode($word);
            $hotLink .= <<<EOT
<a href="/search/?q={$encodeWord}">{$word}</a>   
EOT;
        }
        echo $hotLink;
    }
    ?>
    </div>
    <span class="query_r"></span> 
  </div>      
</div>
    
