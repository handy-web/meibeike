<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />		
		<title>预约首页</title>
		<script type="text/javascript" src="http://o8nj7d5m4.bkt.clouddn.com/jquery-1.9.0.min.js?20160714"></script>
		<style>#embed-captcha{width:300px;margin:0 auto;}.show{display:block;}.hide{display:none;}#notice{color:red;}.row div{margin-top:2%;text-align:center;font-size:12px;}.row_mid{font-size:12px;}.row_bottom{font-size:12px;color:rgb(191,191,191);}a{text-decoration: none;}</style>
	<style> body {
		font-family: "Microsoft YaHei", 微软雅黑;
	}
	
	
	@media only screen and (max-width:640px) {
		html,
		body {
			font-size: 20px;
		}
	}
	
	@media only screen and (max-width:540px) {
		html,
		body {
			font-size: 16.875px;
		}
	}
	
	@media only screen and (max-width:480px) {
		html,
		body {
			font-size: 15px;
		}
	}
	
	@media only screen and (max-width:414px) {
		html,
		body {
			font-size: 13px;
		}
	}
	
	@media only screen and (max-width:400px) {
		html,
		body {
			font-size: 12.5px;
		}
	}
	
	@media only screen and (max-width:360px) {
		html,
		body {
			font-size: 11.25px;
		}
	}
	
	@media only screen and (max-width:320px) {
		html,
		body {
			font-size: 10px;
		}
	}
	
	body,
	div,
	dl,
	dt,
	dd,
	ul,
	ol,
	li,
	h1,
	h2,
	h3,
	h4,
	h5,
	h6,
	pre,
	code,
	form,
	fieldset,
	legend,
	input,
	button,
	textarea,
	p,
	blockquote,
	th,
	td {
		margin: 0;
		padding: 0
	}
	
	article,
	aside,
	details,
	figcaption,
	figure,
	footer,
	header,
	hgroup,
	main,
	nav,
	section,
	summary {
		display: block
	}
	
	body {
		background-color: #fff;
		color: #666;
		height: 100%;
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif
	}
	
	fieldset,
	img {
		border: 0px
	}
	
	address,
	cite,
	dfn,
	em,
	var,
	i {
		font-style: normal
	}
	
	:focus {
		outline: 0 none
	}
	
	ul,
	ol,
	dl,
	li {
		list-style: none
	}
	
	body {
		font-family: "微软雅黑"
	}
	
	#wrapper {
		max-width: 640px;
		min-width: 320px;
		margin: 0 auto;
		overflow: hidden;
		background: #fff;
	}
	
	.container {
		width: 100%;
	}
	
	.col {
		margin-top: 40px;
	}
	
	.ci img {
		display: block;
		margin: 0 auto;
	}
	
	.col1 {
		margin-top: 20px;
	}
	
	.wcol {
		width: 500px;
		margin: 0 auto;
		padding-top: 70px;
	}
	
	.content {}
	
	.col-item {
		position: relative;
		font-size: 1.2rem;
		background-color: #FFF;
		letter-spacing: normal;
		vertical-align: top;
		display: inline-block;
		width: 48%;
	}
	
	.col-item a {
		display: block;
		text-decoration: none;
	}
	
	.col-item a p {
		color: #555;
		text-align: center;
		font-size: 1.1rem;
	}
	
	.item-img {
		display: block;
		max-width: 172px;
		min-width: 120px;
		max-height: 240px;
		min-height: 120px;
		margin: 0.5rem auto 0 auto;
	}
	
	.input_content {
		margin: 1.5rem;
		overflow: hidden;
		height: 3.6rem;
	}
	
	.input_content .left {
		float: left;
		width: 60%;
	}
	
	.input_content .right {
		float: right;
		width: 35%;
	}
	
	.input_content .right a {
		background: url('../images/wap/btn.png') no-repeat;
		background-size: contain;
		height: 45px;
	}
	
	.right a {
		display: block;
	}
	
	.input_text {
		width: 100%;
		border: 1px solid #A09B9B;
		height: 3.4rem;
		text-indent: 1em;
		vertical-align: middle;
		font-weight: normal;
		font-size: 1rem;
		border-radius: 3px;
		-webkit-appearance: none;
	}
	
	.input_text:hover {
		box-shadow: 0 0 2px #46B8DA;
		border: 1px solid #46B8DA;
	}
	
	.yuyue {
		font-size: 1.1rem;
		color: #999;
		margin-bottom: 1.5rem;
	}
	
	.yuyue span {
		margin: 0 0.5em;
	}
	
	.red {
		color: #f22;
		margin: 0!important;
	}
	
	.headline {
		text-align: center;
		margin-top: 0.3rem;
		color: #424141;
		font-size: 1.8rem
	}
	
	.text-col {
		color: #e51626
	}
	
	.h4 {
		margin-top: 30px;
		color: #e51626;
	}
	
	.hh {
		font-size: 1.3em;
	}
	
	.hh span {
		border: 1px solid #e51626;
		margin-left: 10px;
		margin-right: 3px;
		font-size: 0.8em;
		padding: 5px;
		color: #e51626;
	}
	
	.but-1 {
		height: 3.4rem;
		width: 8.5rem;
		font-size: 1.1rem;
		margin-left: -0.5rem;
		z-index: 999;
		border-radius: 0;
	}
	
	.but-2 {
		height: 3.4rem;
		width: 8.5rem;
		color: #fff;
		background: #da302c;
		font-size: 1.1rem;
		border: 0 none;
		margin-left: -0.5rem;
		z-index: 999;
	}
	
	.but-3 {
		height: 3.4rem;
		width: 8.5rem;
		color: #fff;
		background: #da302c;
		font-size: 1.1rem;
		border: 0 none;
		margin-left: -0.5rem;
		z-index: 999;
	}
	
	.top {
		margin-top: 0.8rem;
		padding-bottom: 1.2rem;
	}
	
	.top a {
		font-size: 1.3rem;
		display: block;
		float: right;
		color: red;
		padding-top: 0.4rem;
	}
	
	.text-mbk {
		font-size: 1.2rem;
		margin-left: 0.4rem;
	}
	
	</style>
	</head>
<body>

   <div id="wrapper">
      <div class="container">
          <div class="top" style="padding: 0.3rem 1.2rem;"><img style="margin-right:2%;float: left;" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/wap/mbklogo.png";?>"><span class="text-mbk" style="float: left;padding-top: 3.5%;margin-left: 0;font-size: .9rem;">美贝壳</span>
          	<span style="float: right;color: #FF0000;padding-top: 2.5%;font-size: .9rem;"><a href='<?php echo IUrl::creatUrl("/wap/index");?>'>进入官网</a></span>
          </div>
          <p class="headline" style="color:#424141;font-size: 2.2rem;clear: both;margin-top: 15%;">预约<span><a style="text-decoration: none;" href="<?php echo IUrl::creatUrl("/site/index_wap_first");?>"><span class="text-col">云棒</span></a></span></p>
          <p class="headline" style="font-size: 2.2rem;">皆有机会获得1T存储碟</p>         
          <p class="headline" style="color:#e51626;font-size: 1.4rem;">8月6日上午11点开抢</p>
          <p class="headline" style="font-size: 1.4rem;margin-top: 0.6rem;"><a style="text-decoration:underline" href="http://www.meibeike.com/videoplay/advert_video.html">云棒广告</a><a style="margin-left:4%;text-decoration:underline" href="/site/index_wap_first">云棒介绍</a></p>
      </div>
      <!-- div class="container" style="margin: 20px 0;">
          <p id="timer" class="headline hh">仅剩<span>20</span>天<span>12</span>时<span>33</span>分<span>59</span>秒<p>
      </div--> 
      <style>
      	.m-box{padding: 0 6%;margin-top: 1rem;}
      	.m-ul{width: 100%;}
      	.m-ul li{width: 50%;float: left;}
      	.m-line{text-align: center;}      
      	.m-line p{font-size: .8rem;line-height: 1.6rem;}
      	.m-line p:last-child{font-size: 1rem}
      	.m-tab{height: 5.5rem;}
      	.m-tab img{width: 3.5rem;}
      	.m-tab1{height: 5.5rem;}
      	.m-tab1 img{width: 8rem;padding-top: .2rem;}
      	.m-re{margin-left: .6rem;}
      </style>
      <div class="m-box">
      	<ul class="m-ul"> 
      		<li>
      			<div class="m-line">
      				<div class="m-tab">
      					<img src="http://o8nj7d5m4.bkt.clouddn.com/product_1.png" />
      				</div>
      				<p>银组合1979元</p>
      				<p>(母棒+1T存储碟)</p>
      			</div>
      		</li> 
      		<li>
      			<div class="m-line m-re">
      				<div class="m-tab1">
      					<img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/act/images/productgold.png";?>" />
      				</div>
      				<p>金组合3000元</p>
      				<p>(母棒+两个120GB存储棒)</p>
      			</div>
      		</li>
      	</ul>
      </div>
      
      <!--<div class="container">
          <div class="content">
            <div class="col-item">
              <a href="">
                <div  class="item-img"><img  src="http://o8nj7d5m4.bkt.clouddn.com/product_1.png"></div>
                <p style="font-size:0.8rem;margin-bottom:0.4rem">银组合1598元</p>
                <p style="font-size:1.0rem;">(母棒+1T存储碟)</p>
              </a>
            </div>
            <div class="col-item">
              <a href="">
                <div  class="item-img"><img  src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/act/images/product-mgold.png";?>"></div>
                <p style="font-size:0.8rem;margin-bottom:0.4rem">金组合3000元</p>
                <p style="font-size:1.0rem;">(母棒+120GB存储棒x2)</p>
              </a>
            </div>
          </div>
      </div>-->
	  <!--弹出式-->
      <div id="embed-captcha"></div>
	  <!--弹出式-->
      
      <div class="container" id="success" style="margin-top: 1rem;display: inline-block;">
          <div class="input_content" style="margin: 1% 1% 0.8rem">
            <div class="left" >
              <input style="margin-left:10%;width:90%" class="input_text" id="telphone"  maxlength ='11' type="text" placeholder="请输入手机号">
            </div>
            <div  class="right">
              <span id="msg_box" style="line-height: 40px;"><button class="but-1" id="send_code">获取验证码</button></span>
          </div>
        </div>
         <div class="input_content" style="margin:1%">
            <div class="left" >
              <input style="margin-left:10%;width:90%" class="input_text" id="verify_code"  maxlength ='6' type="text" placeholder="请输入验证码">
              <input id="vcode" value="" type="hidden" />
            </div>
            <div class="right" id="telphone_appoint">
              <button class="but-2" style="font-family: 'microsoft yahei';">免费预约</button>
          </div>
        </div>
      </div>  
      <div style="text-align: center;margin-top: 1rem;" class="container">
          <p class="yuyue"><span>共<span class="red"><?php echo isset($order_num)?$order_num:"";?></span>预约&nbsp;</span><span>官网<?php echo isset($order_num1)?$order_num1:"";?></span><span>微信<?php echo isset($order_num2)?$order_num2:"";?></span><span>其它<?php echo isset($order_num3)?$order_num3:"";?></span></p>     
      </div>
      <div class="container">
        <div class="row" style="margin-top: 10%;">
	      	<div>400-772-8320&nbsp;&nbsp; 周一至周五&nbsp;&nbsp;9:00-20:00</div>
	      	<div class="row_mid">Copyright © 2014-2020,深圳市美贝壳科技有限公司 All Rights Reserved.</div>
			<div class="row_bottom">粤ICP备 14098187号-1</div>
      </div>
 <script type="text/javascript">
 $(document).ready(function(){
	 //$('.footer').hide(); 
	 //$('.header').hide();
	 //$('#header').hide();
	 //$('.fott').hide();
	 $('#sina_weibo').click(function(){
         $.ajax({
             type: "POST",
             url: "<?php echo IUrl::creatUrl("simple/third_login");?>",
             data: "argument=rand_num",
             dataType: "json",
             success: function (d,s,r) {
                 if (d.status==1) {
					window.location.href=d.url;
                 }else{
                	alert('您已经授权登录了新浪微博');
                 }
             }
         });		 
	 });
 });
 
 $('#send_code').click(function(){
	 var telphone = $('#telphone').val();
	 var patt = /^(13|14|15|17|18)[0-9]{9}$/;	 
	 if(telphone == ''){
		 alert('请输入手机号吗');
		 return;
	 }else if(!patt.test(telphone)){
		 alert('请输入正确的手机号');
		 return;
	 }
	 sendVerify(telphone);
 });
 
 //预约代码
 


 function sendVerify(telphone) {
	 //alert(telphone);
    /* $.post("<?php echo IUrl::creatUrl("simple/verify_telphone");?>", {mobile: telphone}, function (data_ret) {
    	 alert(data_ret['code']);
    	 if(data_ret['code']=='0'){
    		 alert(d.message);
    	 }else{
    		 $('#send_code').hide();
    		// alert(d['code'] + "zpj验证码");
             $("#vcode").val(data_ret['msg']);
             $("#msg_box").text("校验码已发送到你的手机，请查收");
             count = 60;
             $("#msg_box").html(count + "秒后可重新操作");
             intval = self.setInterval("refreshCount()", 1000);
    	 }
     });*/
     /*var count=$.cookie("mbk_wap_s_count");

		if(count*1>=1) {$.cookie("mbk_wap_s_count", count*1+1); } else {$.cookie("mbk_wap_s_count", "1"); }
		if(count*1>=5)
		{
			alert("");
			return false;
		}*/


     $.ajax({
         type: "POST",
         url: "<?php echo IUrl::creatUrl("simple/verify_telphone");?>",
         data: "mobile="+$('#telphone').val()+"&purchase_time=20160806",
         dataType: "json",
         success: function (d,s,r) {
             if (d.code==0) {
            	 alert(d.message);
             }else if(d.code==1){
        		 $('#send_code').hide();
                  $("#msg_box").text("校验码已发送到你的手机，请查收");
                  count = 60;
                  $("#msg_box").html(count + "秒后可重新操作");
                  intval = self.setInterval("refreshCount()", 1000);
             }else{
            	 window.location.href='/site/wap_success'+'/aid/'+"'"+d.aid+"'";
             }
         }
     });	
     
 }
 function refreshCount() {
     count--;
     if (count == 0) {
         var mobile = $("#telphone").val();      
         $("#msg_box").html("<button id ='review' style='height:42px;width:100px' onclick='sendVerify(" + mobile + ")'>重新发送</button>");
         window.clearInterval(intval);
     } else {
         $("#msg_box").html(count + "秒后可重新操作");
     }
 }
 </script>
 
 <script src="http://static.geetest.com/static/tools/gt.js"></script>
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<script>
    var handlerPopup = function (captchaObj) {
    	 $('#telphone_appoint').click(function(){
             var validate = captchaObj.getValidate();
             if (!validate) {
				 alert('请先完成验证');
                 return ;
             }  	
    		 //alert('test');
    		 var telphone = $('#telphone').val();
    		 var patt = /^(13|14|15|17|18)[0-9]{9}$/;
    		 var verify_code = $('#verify_code').val();
    		 if(telphone == ''){
    			 alert('请输入手机号吗');
    			 return;
    		 }else if(!patt.test(telphone)){
    			 alert('请输入正确的手机号');
    			 return;
    		 }else if(verify_code==''){
    			 alert('请输入短信验证码');
    			 return;
    		 }else if(verify_code.length!='6'){
    			 alert('请输入6位短信验证码');
    			 return;
    		 }


    	     $.ajax({
    	         type: "POST",
    	         url: "<?php echo IUrl::creatUrl("simple/telphone_appoint");?>",
                 data: {"telphone":$('#telphone').val(),
                	 "verify_code":$('#verify_code').val(),"purchase_time":'20160806',
                     "geetest_challenge": validate.geetest_challenge,
                     "geetest_validate": validate.geetest_validate,
                     "geetest_seccode": validate.geetest_seccode},
                     dataType: "Json",
    	         dataType: "json",
    	         success: function (d,s,r) {
    	             if (d.code==1) {
    					window.location.href='/site/wap_success'+'/aid/'+"'"+d.aid+"'";
    	             }else{
    	            	alert(d.msg);
    	             }
    	         }
    	     });	
    	     
    	 });
    	 
         captchaObj.appendTo("#embed-captcha");
         captchaObj.bindOn('#telphone_appoint');
    };
    
    $.ajax({
        // 获取id，challenge，success（是否启用failback）
        url: "<?php echo IUrl::creatUrl("/simple/create_hkyz/t/");?>"+(new Date()).getTime(), // 加随机数防止缓存
        type: "get",
        dataType: "json",
        success: function (data) {
            // 使用initGeetest接口
            // 参数1：配置参数
            // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                product: "popup", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
            }, handlerPopup);
        }
    });
</script>
</body>
</html>