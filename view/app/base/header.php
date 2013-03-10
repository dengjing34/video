<?php
//dengjing34@vip.qq.com
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$opt['title']?></title>
<meta name="description" content="<?=$opt['description']?>" />    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type='text/css' rel='stylesheet' href="<?=BASEURL?>css/app/base.css" />
<script type="text/javascript" src="<?=BASEURL?>js/jquery-1.4.2.min.js"></script>
</head>
    
<body>
    <div id="nav-wrapper">
        <div id="nav">
            <ul>
                <?php
                $navs = array();
                foreach ($nav as $key => $val) {
                    $curr = $key == $controller ? " class=\"curr\"" : null;
                    $navs[] = "<li><a{$curr} href=\"" . Url::siteUrl("app/{$key}") . "\">{$val}</a></li>";
                }
                echo implode("\n", $navs);
                ?>
            </ul>            
        </div>
        <div class="clear"></div>
    </div>    
    <div id="wrapper" class="clear">
