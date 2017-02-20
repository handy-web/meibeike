<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"> 
	    <title>预约</title>
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-touch-fullscreen" content="yes">
	    <meta name="full-screen" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <meta name="format-detection" content="address=no">
	    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="/views/default/wap/mobile/version/css/main.css" />
		<script type="text/javascript" src="/views/default/wap/mobile/version/js/jquery.js" ></script>
		<!--<script>
			$(document).ready(function(){
				$('#header').hide(0);
			})
		</script>-->
	</head>
<body style="background: #F9F9F9;">

   <div class="mu-box">
      <div class="container">
          <div class="mu-tab">
          	<a href="/wap/index">
	          	<img src="http://o8nj7d5m4.bkt.clouddn.com/app-logo.png">
	          	<span class="text-mbk">美贝壳</span>
          	</a>
          	<span class="text-gw"><a href="/wap/index">进入官网</a></span>
          </div>
          <div class="mu-bax">
	          <h1>预约<a href="<?php echo IUrl::creatUrl("/site/index_wap_first");?>">云棒</a></h1>
	          <h2>皆有机会获得1T存储碟</h2>         
	          <h5>9月3日上午11点开抢</h5>
	          <p><a href="<?php echo IUrl::creatUrl("/wap/advert_video");?>">云棒广告</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="/wap/yuyue_first">云棒介绍</a></p>
    	 </div> 
      </div>
      <!-- div class="container" style="margin: 20px 0;">
          <p id="timer" class="headline hh">仅剩<span>20</span>天<span>12</span>时<span>33</span>分<span>59</span>秒<p>
      </div--> 

      <div class="m-box">
      	<ul class="m-ul"> 
      		<li>
      			<div class="m-line">
      				<div class="m-tab">
      					<img src="http://o8nj7d5m4.bkt.clouddn.com/product_1.png" />
      				</div>      				
      				<p>(母棒+1T存储碟)</p>
      				<p>银组合1979元</p>
      			</div>
      		</li> 
      		<li>
      			<div class="m-line m-re">
      				<div class="m-tab1">
      					<img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/act/images/productgold.png";?>" />
      				</div>     				
      				<p>(母棒+两个120GB存储棒)</p>
      				<p>金组合3000元</p>
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

      <div class="mu-line" id="success">
      	<ul class="mu-ul">
      		<li>
      			<div class="mu-in">
      				<input id="telphone"  maxlength ='11' type="tel" name="" placeholder="请输入手机号" />
      			</div>
      			<div class="mu-rt" >
      				<em id="msg_box"></em>
      				<a id="send_code">获取验证码</a>    				
      			</div>
      		</li>
      		
      		<li>
      			<div class="mu-in">
      				<input id="verify_code"  maxlength ='6' type="tel" name="" placeholder="请输入验证码" />
      				<input id="vcode" value="" type="hidden" />
      			</div>
      			<div class="mu-bom">
      				<a id="telphone_appoint">免费预约</a>
      			</div>
      		</li>     		
      	</ul>
      </div>
      <div class="mu-p">
      	<p><span>共<span><em><?php echo isset($order_num)?$order_num:"";?></em></span>预约&nbsp;</span><span>官网<?php echo isset($order_num1)?$order_num1:"";?></span><span>微信<?php echo isset($order_num2)?$order_num2:"";?></span><span>其它<?php echo isset($order_num3)?$order_num3:"";?></span></p>     
      </div>
      <div class="foot">
			<div class="foot-nav">
				<ul class="foot-line">
					<li>
						<div class="foot-le"><i></i><span>400-772-8320</span></div>
						<div class="foot-ft"><i></i><span>9:00-18:00(工作日)</span></div>
					</li>					
				</ul>
				<div class="foot-box">
					<p>Copyright©2014-2020</p>
					<p>深圳市美贝壳科技有限公司All Rights Reserved.</p>
					<p>粤ICP备14098187号-1</p>
			</div>
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
         data: "mobile="+$('#telphone').val()+"&purchase_time=20160903",
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
            	 window.location.href='/wap/yuyue_success'+'/aid/'+"'"+d.aid+"'";
             }
         }
     });	
     
 }
 function refreshCount() {
     count--;
     if (count == 0) {
         var mobile = $("#telphone").val();      
         $("#msg_box").html("<a id ='review' onclick='sendVerify(" + mobile + ")'>重新发送</a>");
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
                	 "verify_code":$('#verify_code').val(),"purchase_time":'20160903',
                     "geetest_challenge": validate.geetest_challenge,
                     "geetest_validate": validate.geetest_validate,
                     "geetest_seccode": validate.geetest_seccode},
                     dataType: "Json",
    	         dataType: "json",
    	         success: function (d,s,r) {
    	             if (d.code==1) {
    					window.location.href='/wap/yuyue_success'+'/aid/'+"'"+d.aid+"'";
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