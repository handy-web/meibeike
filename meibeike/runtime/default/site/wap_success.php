<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />		
		<title>预约成功</title>
		<script type="text/javascript" src="http://o8nj7d5m4.bkt.clouddn.com/jquery-1.9.0.min.js?20160715"></script>
		<style>.top-1{width:100%;height:auto;text-align:center;margin:0;padding:0;}.top-1 img{margin-top:30px;margin-bottom:20px;}.tip-p{width:100%;height:auto;font-size:24px;text-align:center;font-family:"微软雅黑";}.div-p{width:100%;height:auto;font-size:20px;text-align:center;font-family:"微软雅黑";margin-top:15px;}.div-p1{text-align:center;color:red;margin-top:5px;font-family:"微软雅黑"}.div-but{width:100%;text-align:center;margin-top:20%;}.div-but a{width:40%;padding:0.7rem 15%;border:1px solid red;text-decoration:none;color:red;font-family:"微软雅黑"}</style>
		<style>
			@media only screen and (max-width:320px ) {
				html,body{font-size: 13px}
			}
			body{margin: 0;padding: 0;}
		</style>
	</head>
<body>
<div class="top-1"><img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/wap_index/success.png";?>"></div>
<div class="tip-p">您已成功免费预约!</div>
<div class="tip-p" style="font-size: 1.4rem;margin-top: 8px;">预约号:<span id="tipcontent" style="color:rgb(13,150,220)"></span></div>
<div class="div-p" style="font-size: 1.3rem;"><span style="color:red;">8月6号上午11点开抢</span>，请别忘记哦！</div>

<div class="div-but">
	<a href="/site/index_wap">返回首页</a>
</div>
<!--<div class="bottom"><div style="width:40%;height:30px;margin:0 auto;border:1px  solid red;"><a style="color:red" href="/site/index_wap">返回首页</a></div></div>-->
<script type="text/javascript">
$(document).ready(function(){
	var aid = <?php echo isset($aid)?$aid:"";?>;
	$('.header').hide();
	$('.footer').hide();
	$('#header').hide();
	$('.fott').hide();
	$('#tipcontent').html(aid);
});
</script>
</body>
</html>