<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"> 
	    <title>预约成功</title>
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-touch-fullscreen" content="yes">
	    <meta name="full-screen" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <meta name="format-detection" content="address=no">
	    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="/views/default/wap/mobile/version/css/main.css" />
		<script type="text/javascript" src="/views/default/wap/mobile/version/js/jquery.js" ></script>
		
	</head>
	<body style="background: #fff;">
<style>.top-1{width:100%;height:auto;text-align:center;margin:0;padding:0;}.top-1 img{width:26%;margin-top:25%;margin-bottom:20px;}.tip-p{width:100%;height:auto;font-size:1.4rem;margin-top:8px;text-align:center;}.div-p{width:100%;height:auto;font-size:1.1rem;text-align:center;margin-top:15px;}.div-p span{color:#FF0000;}.div-p1{text-align:center;color:red;margin-top:5px;}.div-but{padding:0 20%;text-align:center;margin-top:20%;}.div-but a{display:block;padding:0.7rem 10%;border:1px solid red;text-decoration:none;color:red;}.tip-p span{color:rgb(13,150,220)}</style>

<div class="top-1"><img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/wap_index/success.png";?>"></div>
<div class="tip-p">您已成功免费预约!</div>
<div class="tip-p">预约号:<span id="tipcontent"></span></div>
<div class="div-p"><span>8月6号上午11点开抢</span>，请别忘记哦！</div>

<div class="div-but">
	<a href="<?php echo IUrl::creatUrl("/wap/index");?>">返回首页</a>
</div>
<?php $aid = $_GET['aid']?>
<script>
	$(document).ready(function() {
		var aid = <?php echo isset($aid)?$aid:"";?>;
		//$('#header').hide();
		$('#tipcontent').html(aid);
	});
</script>
</body>
</html>