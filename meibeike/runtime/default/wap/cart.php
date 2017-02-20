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
    <link rel="stylesheet" href="/views/default/wap/mobile/version/css/main.css" />
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
		<div class="gwc-c" style="display: none;">
			<div class="gwc-line">
				<ul class="gwc-ul">
					<li><a href="<?php if($this->user['user_id']){?>/wapcenter/myreser<?php }else{?>/wap/login<?php }?>" class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-yy.png" />我的预约</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/product_yin");?>' class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-gm.png" />现在购买</a></li>
					<?php if($this->user['user_id']){?><li><a href="<?php echo IUrl::creatUrl("/wapcenter/personal");?>" class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-zx.png" />个人中心</a></li>
					<?php }?>
					
					
					
					<?php if($this->user['user_id']){?><li><a href='<?php echo IUrl::creatUrl("/wap/logout?callback=$callback");?>' class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-tc.png" />注销</a></li>
						<?php }else{?><li><a href='<?php echo IUrl::creatUrl("/wap/login?callback=$callback");?>' class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-tc.png" />登录</a></li>
					<?php }?>
				</ul>
			</div>
		</div>
		<!--菜单栏-->
		<div class="nav" style="display: none">
			<div class="box-c">							
				<img class="nav-img" src="/views/default/wap/mobile/version/img/app-logo.png" />	
				<img class="nav-close" src="/views/default/wap/mobile/version/img/app-close.png" />
			</div>
			<div class="nav-line">
				<ul class="nav-ul">					
					<li><a href='<?php echo IUrl::creatUrl("/site/index_wap_first");?>'>云棒详情</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/software");?>'>软件中心</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/customer");?>'>客服中心</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/news");?>'>新闻中心</a></li>
					<li><a href='<?php echo IUrl::creatUrl("/wap/product_yin");?>'>现在购买</a></li>
					<li><a href="<?php echo IUrl::creatUrl("/site/index_wap");?>">现在预约</a></li>
					
				</ul>
			</div>
		</div>
		
		<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"> 
	    <title>提交订单</title>
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
	<body>
		<style>
			.cart-bc{position: fixed;background: #F1F1F1;top: 0;left: 0;right: 0;bottom: 0;z-index: 999;}
			.cart-bcl{background: #fff;margin-top: .6rem;overflow: hidden;}
			.cart-bul{width: 100%;}
			.cart-bul li{width: 100%;padding: .6rem;list-style: none;border-bottom: 1px solid #E8E8E8;}
			.cart-bul li input{margin-right: .3rem;}
			.cart-bul li label{font-size: .8rem}
		</style>
		<div class="cart-bc">
			<div class="cart-bcl">
				<ul class="cart-bul">
					<li><input type="radio" name="" id="" value="" /><label>广东省深圳市南山区兰光科技园Ajsjsskskssk</label></li>
				</ul>
			</div>
		</div>
		
		
		<div class="state-box"> 
			<div class="cart-in" style="display: block;">
				<a>
					<div class="cart-addr"><img src="../version/img/app-addr.png"/></div>
					<div class="cart-t">您还没有添加新地址，请点击添加</div>
					<div class="cart-rig"><i></i></div>
				</a>
			</div>
			<div class="cart-on" style="display: none;">
				<a>
					<div class="cart-addr-1"><img src="../version/img/app-addr.png"/></div>
					<div class="cart-t-1">
						<p>收货人：<span>小米</span></p>
						<p>联系电话：<span>13654789856</span></p>
						<div>收货地址：<span>广东省深圳市南山区话说就是就是就是计算机</span></div>
					</div>
					<div class="cart-rig1" style="float: right;"><i></i></div>
				</a>
			</div>
		</div>
		
		<div class="cart-tab"> 
			<div class="cart-line">
				<div class="cart-l"><img src="../version/img/001.jpg"/> </div>
				<div class="cart-r">
					<h2>银霸组合（母棒+2T存储碟）</h2>
					<p>价格：<span>&#65509;1900.00</span></p>											
						<div class="cart-m">
							<a class="min"><img src="../version/img/app-red.png"></a>
							<input type="text" value="1">
							<a class="add"><img src="../version/img/app-add.png"></a>
						</div>		
				</div>
			</div>
			<ul class="cart-zd">
				<li>
					<div class="cart-zdli">商品总价：<span>&#65509;1900.00</span></div>					
				</li>				
			</ul>
		</div>
		  
		<div class="cart-list">
			<ul class="cart-ul">
				<li>
					<div class="cart-wl"><img src="../version/img/app-kd.png"/><span>配送方式</span></div>
					<div class="cart-wr"><span>公司包邮</span><i></i></div>
				</li>
				<li>
					<div class="cart-wl"><img src="../version/img/app-juan.png"/><span>优惠券</span></div>
					<div class="cart-wr"><span>满1898减10元</span><i></i></div>
				</li>
				<li>
					<div class="cart-wd"><img src="../version/img/app-fm.png"/><span>F码</span></div>
					<!--输入F码验证-->
					<div class="cart-wc" style="display: block">
						<div class="cart-ipu"><input type="tel" name="" value="" placeholder="如有F码，请在这填写" /></div>
						<div class="cart-yz"><input type="submit" name="" value="验证" /></div>
					</div>
					<!--F码可用-->
					<div class="cart-fm" style="display: none;">F码已使用，获得1T存储碟</div>
				</li>
				
				<li>   
					<div class="cart-ak">
						<div class="cart-wl"><img src="../version/img/app-fp.png"/><span>发票</span></div>
						<div class="cart-wt"><span>不开发票</span><i></i></div>
					</div>
					<div style="clear: both;display: none;" class="cart-km">
						<ul class="cart-kt">
							<li><input type="radio" name="is_note" id="ra_b1" checked value="不开发票" /><label for="ra_b1">不开发票</label></li>
							<li><input type="radio" name="is_note" id="ra_b2" value="需要发票" /><label for="ra_b2">需要发票</label></li>
						</ul>
						<div class="cart-kc">
							<input type="text" name="" id="" value="" placeholder="请输入个人姓名或者公司名称" />
						</div>
					</div>
				</li>
				<li>
					<textarea rows="3" cols="10"  onblur="if(this.innerHTML==''){this.innerHTML='有特殊请求请留言（限30字）';this.style.color='#666'}" onfocus="if(this.innerHTML=='有特殊请求请留言（限30字）'){this.innerHTML='';this.style.color='#666'}">有特殊请求请留言（限30字）</textarea>
				</li>
			</ul>
		</div>
		
		<div class="cart-pm"> 
			<div class="cart-pl">
				<ul class="cart-pw">
					<li>商品总价格：<span>&#65509;1900.00</span></li>
					<li>现金劵抵扣：<span>&#65509;20.00</span></li>
					<li>运费：<span>&#65509;0.00</span></li>
					<li>应付总额：<span>&#65509;1900.00</span></li>
				</ul>
			</div>
		</div>
		<div class="cart-tm">
			<div class="cart-te">应付总额：<span>&#65509;1900.00</span></div>
			<div class="cart-ti"><input type="submit" name="" id="" value="提交订单" /> </div>
		</div>
		<script>
			$(document).ready(function(){
				$(".cart-ak").click(function(){
					$(".cart-km").toggle(500);
				});
			
			 
			$(".cart-kc").hide();
			$(":radio[name='is_note']").click(function() {
				var obj = $('input:radio[name="is_note"]:checked').val();
				$(".cart-wt span").text(obj);				
				var index = $(":radio[name='is_note']").index($(this));
				if (index == 1)
					$(".cart-kc").show(500);
				else
					$(".cart-kc").hide(500);
			});
		})
		</script>
	</body>
</html>

		
		<script type="text/javascript">
		//菜单
		$(document).ready(function(){
			$(".img1").click(function(event) {			
				$(".nav").slideToggle(800);
				event.stopImmediatePropagation();
			});
			
			$(".nav-close").click(function(event) {
				$(".nav").hide();
			});
			//购物车
			$(".img3").click(function(event) {			
				$(".gwc-c").slideToggle(0);
				event.stopImmediatePropagation();
			});
			
			$("body").click(function(event) {
				$(".gwc-c").hide();
			}); 
		});
		</script>


		
		
