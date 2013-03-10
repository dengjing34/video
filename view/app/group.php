<?php
//dengjing34@vip.qq.com
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ACTIVE USER</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type='text/css' rel='stylesheet' href="<?=BASEURL?>css/app/base.css" />
<script type="text/javascript" src="<?=BASEURL?>js/jquery-1.4.2.min.js"></script>
<style>
.form{margin:20px;}
.form input{vertical-align:middle;height:20px;line-height: 20px;border:1px solid #C8C8C8;padding:2px;border-radius:5px;color:#000;}
.form input:focus{border: 1px solid #5690CB; outline: medium none;box-shadow:0 0 4px rgba(86, 144, 203,0.7);}
.form button{vertical-align:middle;}
</style>
</head>
		
<body>
	<form method="post" class="form" autocomplete="off">
		<label for="qq">您的QQ号码：</label><input type="text" name="qq" id="qq" placeholder="输入你的QQ号码" x-webkit-speech="x-webkit-speech" /><button class="button button-green" type="submit">提交</button><span class="red"><?=$error?></span>
	</form>
	<div style="margin:20px;"><?=$list?></div>
</body>
</html>
