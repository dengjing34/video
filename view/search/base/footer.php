<?php
/**
 * @author dengjing <dengjing34@vip.qq.com>
 */
?>

<div id="footer">
	<?php $conf->get('web_copyright')?>Copyright © 2011 - <?php echo date('Y')?> <a href="<?php echo $conf->get('web_url')?>"><?php echo $conf->get('web_name')?></a> <?php echo pathinfo($conf->get('web_url'), PATHINFO_BASENAME)?> All Rights Reserved   <a href="/map/lists/id/baidu/limit/500.xml" target="_blank">baidu网站地图</a> <a href="/map/lists/id/google/limit/500.xml" target="_blank">google网站地图</a><br />
</div>
<?php
if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'www.84fun.com') {
?>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F27539860c637c3f8958f44ab54cc45b2' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-23785848-1']);
    _gaq.push(['_trackPageview']);
    _gaq.push(
        ['_addOrganic', "sogou", "query"],
        ['_addOrganic', "baidu", "word"],
        ['_addOrganic', "soso", "w"]
    );     
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  	})();
</script>
<?php
}
?>
</div>
</body>
</html>