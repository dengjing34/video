<?php
//dengjing34@vip.qq.com
?>
<div id="aside">
    <div class="ads_300_250"></div>
    <h3 class="message info">热门微博应用<a href="<?=Url::siteUrl('app/hot')?>">更多 &gt;&gt;</a></h3>    
    <?php
    $appBlock = array();
    foreach ($apps as $app) {
        $href = Url::siteUrl("app/{$app->name}");
        $src = Url::siteUrl($app->appIcon);            
        $appBlock[] = <<<EOB
<dl>
    <dt><a title="{$app->description}" href="{$href}"><img alt=\"{$app->title}\" src="{$src}" /></a></dt>
    <dd><a href="{$href}">{$app->title}</a><p>{$app->description}</p><a class="button button-green" href="{$href}"><span class="add"></span>马上试试</a></dd>
</dl>
EOB;
    
    }
    echo implode("\n", $appBlock)
    ?>
</div>

