<?php
//dengjing34@vip.qq.com
?>
<div class="ads_728_90"></div>
<div id="main">    
    <h1 id="title" class="message info"><?=$app->title?></h1>
    <div class="preview">
        <img alt="<?=$app->title?>" src="<?=Url::siteUrl($app->appPreview)?>" />
    </div>    
    <p class="description">        
        <?=$app->description?>
    </p>
    <div class="preview">
        <a class="qq_login_btn" href="<?=$weibo->authorizeUrl()?>" rel="nofollow">登录到腾讯微博</a>
    </div>    
    <div class="clear"></div>
</div>
<?=$aside?>
