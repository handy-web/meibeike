<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">    
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="full-screen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- 页面标题,关键字,描述开始-->	
		<?php if($this->menu_key_name!=''){?>
			<title><?php echo isset($this->menu_key_name)?$this->menu_key_name:"";?></title>
			<meta name="description" content="深圳市美贝壳科技有限公司，位于深圳市南山区科技园内，是一家以“保护个人隐私数据”为理念，研发以私有云为基础的智能家居服务器产品的高新科技企业。公司秉承“不将就！创造一款用户极致体验的产品！“的企业文化，怀着”让私有数据真正隐私化“的愿景，至力于真正解决”私人隐私数据自动收集、安全存储、无线分享“的问题。目前，公司已经成功研发云棒1号产品，云棒产品集成了多项核心专利，已经取得实用及发明专利152项，现已面向中国、美国、加拿大三国公开销售。">
			<meta name="keyword" content="美贝壳,贝壳,云棒,云棒1,云棒1号,云棒一号,云服务,私有云,无线存储,无线备份,nas,PC,android,ios,">
		<?php }?>
	<!-- 页面标题,关键字,描述结束-->
    <link rel="stylesheet" href="/views/default/wap/mobile/version/css/main.css?08-03-01" />
    <script type="text/javascript" src="/views/default/wap/mobile/version/js/jquery.js" ></script>
</head>
<body>
	<!--banner图-->
		<header id="header">
			<div class="menu">
				<ul class="menu-nav">
					<li><img class="img1" src="/views/default/wap/mobile/version/img/app-menu.png" /></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/index");?>'><div class="logo-m"><img class="img2" src="/views/default/wap/mobile/version/img/app-logo.png" /></div></a></li>
					<li><div class="frt"><img class="img3" src="/views/default/wap/mobile/version/img/app-gwc.png" /></div></li>
				</ul>
			</div>
		</header> 		
		<!--购物车-->
		<div class="gwc-c" style="display: none;"></div>
			<div class="gwc-line" style="display: none;">
				<ul class="gwc-ul">
					<li><a href="<?php if($this->user['user_id']){?>/wapcenter/myreser<?php }else{?>/wap/login?callback='/wapcenter/myreser'<?php }?>" class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-yy.png" />我的预约</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/product_yin/id/14");?>' class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-gm.png" />现在购买</a></li>
					<?php if($this->user['user_id']){?><li><a href="<?php echo IUrl::creatUrl("/wapcenter/personal");?>" class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-zx.png" />个人中心</a></li>
					<?php }?>
					
					
					
					<?php if($this->user['user_id']){?><li><a href='<?php echo IUrl::creatUrl("/wap/logout?callback=$callback");?>' class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-zxiao.png" />注销</a></li>
						<?php }else{?><li><a href='<?php echo IUrl::creatUrl("/wap/login?callback=$callback");?>' class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-tc.png" />登录</a></li>
					<?php }?>
				</ul>
			</div>
		<!--登录框-->
		<div class="loginwap" style="display: none;">		
			<form method="post" action="<?php echo IUrl::creatUrl("/wap/login_act");?>">
			<input type='hidden' name='callback' value='<?php echo isset($callback)?$callback:"";?>' />
            <input type='hidden' name='orderCallback' value='<?php echo isset($orderCallback)?$orderCallback:"";?>' />
            <input type="hidden" name="showerror" value="<?php echo isset($this->message)?$this->message:"";?>"/>
			<div class="login-c">
				<div class="login-input"><input type="text" maxlength="16" name="login_info" placeholder="请输入MeiID（邮箱或手机）" /> </div>
				<div class="login-input"><input type="password" maxlength="16" name="password" value="" placeholder="请输入密码" /> </div>				
				<div class="login-but"><input type="submit" value="登录" /> </div>
				<div class="login-te">		
					<div class="flt" style="width: 50%;">
						<div class="login-ck"><input type="checkbox" name="autoLogin" id="ck_1" value="1" checked /><label for="ck_1"><span>下次自动登录</span></label></div>
					</div>
					<span class="frt"><a href='<?php echo IUrl::creatUrl("/wap/phone_reg");?>' class="login-a" href="mobile-reg.html">免费注册</a></span>
				</div>
				
			</div>
			</form>
		</div>
		
		<script type='text/javascript'>
		<?php $callback = IReq::get('callback') ? IFilter::clearUrl(IReq::get('callback')) :IUrl::getRefRoute()?>	
		<?php $orderCallback = IReq::get('callback') ? IFilter::clearUrl(IReq::get('callback')) :IUrl::getRefRoute()?>	
		//DOM加载结束
		$(function(){
			//回调地址设置
			$('input[name="callback"]').val("<?php echo isset($callback)?$callback:"";?>");
			$('input[name="orderCallback"]').val("<?php echo isset($orderCallback)?$orderCallback:"";?>");
			$(".form_table input").focus(function(){$(this).addClass('current');}).blur(function(){$(this).removeClass('current');})
			var errmsg = $("input[name=showerror]").val();
			if (errmsg && errmsg != '') {
				alert(errmsg);
				$("input[name=showerror]").val("");
			}
		});
		</script>
		
		
		
		<!--菜单栏-->
		<div class="nav" style="display: none">
			<div class="box-c">							
				<a href='<?php echo IUrl::creatUrl("/wap/index");?>'><img class="nav-img" src="/views/default/wap/mobile/version/img/app-logo.png" /></a>
				<img class="nav-close" src="/views/default/wap/mobile/version/img/app-close.png" />
			</div>
			<div class="nav-line">
				<ul class="nav-ul">					
					<li><a href='<?php echo IUrl::creatUrl("/wap/overview");?>'>云棒详情</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/software");?>'>软件中心</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/customer");?>'>客服中心</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/news");?>'>新闻中心</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/product_yin/id/14");?>'>现在购买</a></li>
					<li><a href="<?php echo IUrl::creatUrl("/wap/yuyue");?>">现在预约</a></li>
					
				</ul>
			</div>
		</div>
		
		<style>
	body{background: #F9F9F9;}
</style>
		<?php if($this->user['user_id']){?>
		<?php $tmpId=$this->order_id;?>
		<div class="pay-title">
			订单成交成功，请支付！
		</div>
		<div class="pay-line">
			<div class="pay-tab">
				<p>订单名称：<?php echo isset($this->order_name)?$this->order_name:"";?></p>
				<p>订单编号：<?php echo isset($this->order_num)?$this->order_num:"";?></p>
				<p>应付金额：<span>&#65509;<?php echo sprintf('%.2f',$this->final_sum);?></span></p>
				<div>请在48小时内付款，过期订单将自动取消</div>
			</div>
		</div>
		<div class="pay-list">
			<ul class="pay-box">
				<li>选择支付方式</li>
				<li>
					<span><img class="pay-zfb" src="/views/default/wap/mobile/version/img/app-zfb.png"/></span><span class="pay-t">支付宝支付</span>
					<div class="pay-ck">
						<input type="checkbox" name="ck-c" id="cke_1"  checked='checked' /><label for="cke_1"></label>
					</div>
				</li>
			</ul>
		</div>
		<?php }?>
		<?php if($this->deliveryType == 0 && $this->paymentType == 1){?>
		<?php $tmpId=$this->order_id;?>
		<div class="pay-but">
			<form action='<?php echo IUrl::creatUrl("/wap/doPay/order_id/$tmpId");?>' method='post' name='payform' target='_blank'>
				<input type="button"  id="paynow" value="立即支付" />
			</form>
		</div>
		<?php }?>	
<script type="text/javascript">
$(document).ready(function(){
	$('#paynow').click(function(){
		if(!$('input[name="ck-c"]').is(':checked')){
			alert('请选择支付方式');
			return false;
		}
		$('form[name="payform"]').submit();
	});
});

</script>
</body>
</html>

		
		<script type="text/javascript">
		//菜单
		$(document).ready(function(){
			$(".img1").click(function(event) {			
				$(".nav").slideToggle(800);
				event.stopImmediatePropagation();
				$('.gwc-c').hide();
			});
			
			$(".nav-close").click(function(event) {
				$(".nav").hide();
			});
			//购物车
			$(".img3").click(function(event) {			
				$(".gwc-c,.gwc-line").slideToggle(0);
				event.stopImmediatePropagation();
			});
			
			$("body").click(function(event) {
				$(".gwc-c,.gwc-line").hide();
			}); 
		});
		</script>


		
		
