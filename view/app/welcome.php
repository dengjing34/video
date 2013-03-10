<?php
//dengjing34@vip.qq.com
?>
<ul>
    <?php
    $appLink = array();
    foreach ($apps as $app) {
        $appLink[] = "<li><a href=\"" . Url::siteUrl("app/{$app->name}") . "\">{$app->title}</a></li>";
    }
    echo implode("\n", $appLink);
    ?>
</ul>

