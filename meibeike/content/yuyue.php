﻿

<?php


$key = $_GET["key"];

if($key == "")

{ $key = "所有";}


$fr = $_GET["fr"];

if($fr == "")

{ $fr = "direct";}


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:wb="http://open.weibo.com/wb">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title><?php echo $key;?>网盘用户可免费获得1T存储盘</title>
<link rel="stylesheet" href="images/index_new.css">
<link rel="stylesheet" type="text/css" href="images/default.css">
<link rel="stylesheet" href="images/red.css">
<script type="text/javascript" charset="UTF-8" src="images/jquery-1.9.0.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="images/validate.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=8">
</head>
<body style="margin: 0px auto; zoom: 1;">


<div class="wrapper_all">
<div class="header" style="display: none;">
  <div class="header_content"> <a href="http://www.meibeike.com/site/" class="logo" style="padding-top:5px;overflow:visible;"> <img src="images/logo.png" style="border: 0px;"> </a>

   
   
  </div>
  <style type="text/css">
                </style>
  <div class="header-menu-wrapper" style="height: 40px; line-height: 40px; border: 0px;">
    <div class="header-container">
      <div class="account">
        <div class="not_login"> <a id="login" class="normal-link" href="http://www.meibeike.com/simple/login?callback=/site/index_3/">登录</a> &nbsp;|&nbsp; <a id="register" class="normal-link" href="http://www.meibeike.com/simple/reg_phone?callback=/site/index_3/">注册</a> </div>
      </div>
    </div>
    <div class="header-shadow"></div>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="images/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="images/default_wap.css">

<div id="wrapper" style="max-width: 640px; min-width: 320px; margin: 0 auto;overflow: hidden;">
<div class="container">
 
    </div>
  <p class="headline h3" style="margin-top:20px;"><span style=" color:red;"><?php echo $key;?></span>用户</p>
  <p style="margin:15px;color:#000;font-size:20px;" class="headline h2" >预约都有机会免费得1T存储盘</p>
 
  <p class="headline">
   
    <a href="#detail" style="text-decoration:underline">了解活动详情</a></p>
</div>

<div class="container">
  <div class="content" style="margin:5px;">
    <div class="col-item"> <a href="#detail">
      <div class="item-img"><img src="images/product_1.png" border="0" width="95%" height="95%"></div>
      <p style="font-size:8px;margin-bottom:0px">银霸组合</p>
      <p style="font-size:12px;margin-top:1%">(母棒+2T存储碟)</p>
      </a> </div>
    <div class="col-item"> <a href="#detail">
      <div class="item-img"><img src="images/product_3.png" border="0" width="95%" height="95%"></div>
      <p style="font-size:8px;margin-bottom:0px">金甲组合</p>
      <p style="font-size:12px;margin-top:1%">(母棒+250GB存储棒)</p>
      </a> </div>
  </div>
</div>
<div class="container" id="success">

  <div class="input_content" style="margin:1%">
    <div class="left">
	<form name="f" id="f" action="success.php" method="post">
      <input style="margin-left:10%;width:90%" class="input_text" id="tel" name="tel" maxlength="11" type="number" placeholder="请输入手机号" onclick="this.value=''">
      <input type="hidden" value="<?php echo $fr;?>" id="fr" name="fr" />
      <input type="hidden" value="<?php echo $key;?>" id="key" name="key" />
  </div>
	  
    <div class="right" id="telphone_appoint">
      
      <button type="submit" value="立即预约" style="height:36px;width:100px;color:#fff;background:#da302c" id="yuyue_button">免费预约</button>
      
      
    </div>
</form>  


</div>
</div>
<div style="text-align: center;" class="container">
  <p class="yuyue">共<span class="red">12.3</span>万人成功领取价值680元存储盘</p>
</div>
<div class="container" id="detail">
  <div class="row"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_1.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_2.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_3.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_4.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_5.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_6.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_8.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_9.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_10.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_11.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_12.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_mm.jpg" class="img-responsive"> <img src="http://o8nj7d5m4.bkt.clouddn.com/col_end.jpg" class="img-responsive">
    <div class="row"> </div>
  </div>

  
  <script type="text/javascript">
$(document).ready(function(){
  $("#yuyue_button").click(function(){
  
  var telphone = document.getElementById("tel").value;
  var patt = /^(13|14|15|17|18)[0-9]{9}$/;	
   
  if(telphone == ''){
  alert('请输入手机号吗');
  //alert(telphone);
   return false;
  }else if(!patt.test(telphone)){
  alert('请输入正确的手机号');
  //alert(telphone);
   return false;
  }
  
  
  
  });
  

  
  
});
</script>



  <!--style="position: relative; float: left; display: block;"-->
  <div class="footer">

    <div class="publish_info">
     
        <div class="publish_content" style="font-align:center;"> <p style="font-size: 12px;">Copyright © 2014-2020,深圳市美贝壳科技有限公司 All Rights Reserved.</p>
          <p style="font-size: 12px; color: rgb(191, 191, 191)">粤ICP备 14098187号-1</p>
           </div>
           
    </div>
  </div>
</div>

<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?dd6ea620e2361b9d88ebaa9a60eed24b";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>


</body>
</html>
