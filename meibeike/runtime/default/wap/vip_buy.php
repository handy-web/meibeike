<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
		<title>预约抢购</title>		
		<link rel="stylesheet" href="/views/default/wap/mobile/version/css/main.css" />
		<link rel="stylesheet" href="/views/default/wap/mobile/version/css/style.css" />
		<script type="text/javascript" src="/views/default/wap/mobile/version/js/jquery.js" ></script>		
		<script src="/views/default/wap/mobile/version/js/awardRotate.js"></script>
		<script src="/views/default/wap/mobile/version/js/pack.js"></script>
	</head>
	<body style="background: #fff;">
		<!--登录-->
		<div class="mask-box good-box" style="display:none">
		  <div class="coupon-2">
		  	<div class="box-b"><img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/corico.png";?>"/> </div>
		    <h2>&nbsp;<a class="close">&nbsp;</a></h2>
		    <h1 class="login-title">MeiID 登录</h1>
		    <ul class="regist-list">
		        <li><input type="text" placeholder="请输入MeiID (邮箱或手机号）" class="regist-input"></li>
		        <li><input type="password" placeholder="请输入密码" class="regist-input" id="password"></li>
		    </ul>
		    <div class="regist-sub">
		        <a href="javascript:void(0)" class="btn-a btn-login">登 录</a>
		    </div>
		  </div>
		</div>

		<div class="vip-logo">
			<div class="vip-le"><a href="/"></a><img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/lottery/lottery_images/logo-icon.png";?>" /></a></div>			
			<div class="vip-rt">美贝壳</div>
			<div class="vip-login">
				<?php if($this->user['user_id']){?>
				<a href="<?php echo IUrl::creatUrl("/wap/logout?callback=$callback");?>" target="_self">注销</a>
				<?php }else{?><a class="login_btn_new" >登录</a>
				<?php }?>
			</div>
		</div>
		<div class="vip-ot" style="display: block;">
			<h2>抢购倒计时</h2>
			<div class="timerbox">
				<h4> <p id="timer" class="headline hh">仅剩<span>00</span>天<span>00</span>时<span>00</span>分<span>00</span>秒<p></h4>
			</div>
		</div>
		<div class="vip-tt" style="display: none;">开始抢购</div>
		<div class="vip-kf">如有任何疑问，请随时在微信公众号上勾搭客服MM或致电400－772－8320</div>
		
		<section class="pu-box">
			<ul class="bd">
				<li style="margin-right: 3%;" class="on" onclick="checkgood(1)">
					<img class="vip-img" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/lottery/lottery_images/index-imga.png";?>">
					<h2>银组合：<span class="ren">¥1979</span></h2>
					<p>（母棒+1T存储碟)</p>
				</li>
				<li onclick="checkgood(2)">
					<img class="vip-img" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/lottery/lottery_images/index-imgb.png";?>">
					<h2>金组合：<span class="ren">¥3000</span></h2>
					<p>(母棒+两个120GB存储棒)</p>
				</li>
			</ul> 
			<div class="hd">
				<h3 class="on">您已经选择：<span>￥1979</span> 银组合</h3>
			    <h3>您已经选择：<span>￥3000 </span>金组合</h3>
			</div>
			
			<div class="box-line" style="margin-top: 1rem;">				
				<input class="pay" type='button' onclick="paynow()" value="立即抢购" />
				<input type="hidden" id="v_goodid" value="14"/>
			</div>
		</section>


		<script type="text/javascript">
		$(".box-b img").click(function(){
			$(".mask-box").hide();
		});
		$(document).ready(function(){
			$('.coupon-2 .btn-login').click(function(){
				var login_info = $('.coupon-2 .regist-input').val();
				var password = $('#password').val();
				if(login_info == ''){
					alert('请输入用户名');
					return false;
				}else if(password == ''){
					alert('请输入密码');
					return false;
				}
				$.ajax({
				    type: "POST",
				    url: "<?php echo IUrl::creatUrl("/simple/login_act_ajax");?>",
				    data: {"login_info":login_info,"password":password},
				    dataType: "json",
				    success: function (d,s,r) {
				        if (d.code==0) {
				       	 	 alert(d.message);
							 return false;
				        }else if(d.code==1){
		       				 var jump = function(){
		       					 window.location.reload();
		       				 }
		       				setInterval(jump,1000);
				        }
				    }
				});			
			});
		});
		</script>

		<script type="text/javascript">
		$(".login_btn_new").click(function(){
				$(".good-box").show();
			});
			function login_window(){
				
			}
			
			function checkgood(t){
				if(t==1) {$("#v_goodid").val("14");}
				if(t==2) {$("#v_goodid").val("16");}
			}
		
			function paynow()
			{
				$.ajax({
			         type: "POST",
			         url: "<?php echo IUrl::creatUrl("site/check_user_power");?>",
			         data: "userid="+$('#hidden_user_id').val()+"&goodid="+$('#v_goodid').val(),
			         dataType: "json",
			         success: function (d,s,r) {
			             if (d.code==1) {
			            	 alert(d.message);
							 return false;
			             }else if(d.code==4){
			            	 fadebox('.good-box','.close');
							 return false;             
			             }else if(d.code==0){
			        		 window.location.href="/wap/product_yin/id/"+$("#v_goodid").val();
							 return false;             
			             }
			         }
			     });	
			   
			}
		</script>


		<script>
			$(function() {
				clickbox('.pu-box .bd','.pu-box .hd')
				animateleft('.notice ul');
			})
		</script>
<script>
var EndTime= <?php echo isset($end_time)?$end_time:"";?>; 
var NowTime = <?php echo isset($current_time)?$current_time:"";?>;
var t =EndTime - NowTime;
var intDiff = t;//倒计时总秒数量，60*60*24*n=N天
function timer(intDiff){

	window.setInterval(function(){

	var day=0,

		hour=0,

		minute=0,

		second=0;//时间默认值		

	if(intDiff > 0){

		day = Math.floor(intDiff / (60 * 60 * 24));

		hour = Math.floor(intDiff / (60 * 60)) - (day * 24);

		minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);

		second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);

		$('.pay').attr('disabled','disabled');
	}else{
		$('.pay').attr('disabled',false);
		$('.vip-ot').hide();
		$('.vip-tt').show();
		$('.pay').css('background','#FF0000').css('cursor','pointer');
	}
		
	if (day <= 9) day = '0' + day;	
		
	if (hour <= 9) hour = '0' + hour;
		
	if (minute <= 9) minute = '0' + minute;

	if (second <= 9) second = '0' + second;

	document.getElementById("timer").innerHTML = '仅剩<span>'+day+'</span>天<span>'+hour+'</span>时<span>'+minute+'</span>分<span>'+second+'</span>秒';
	
	intDiff--;

	}, 1000);

} 



$(function(){

	timer(intDiff);

});	
$(function() {
				clickbox('.pu-box .bd','.pu-box .hd')
});
</script>	

	</body>
</html>