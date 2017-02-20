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
	<input type="hidden" value="<?php echo $this->user['user_id'];?>" id="hidden_user_id"/>
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
						<?php }else{?><li><a href='<?php echo IUrl::creatUrl("/wap/login?callback=$callback");?>' id='loginbtn' class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-tc.png" />登录</a></li>
					<?php }?>
				</ul>
			</div>
				
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
body{background: #f5f5f5;}
</style>
		<div class="pers-center">
			<div class="pers-admin">
				<div class="flt"><img class="" src="<?php if(empty($this->user['head_ico'])){?><?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/img/pc-img.png";?><?php }else{?><?php echo $this->user['head_ico'];?><?php }?>" /> </div>
				<div class="ptext">
					<?php if($this->user['user_id']){?>
					<p><?php echo  ISafe::get('username');?></p>
					<p><?php if(empty($this->user['email'])){?><?php echo $this->user['mobile'];?><?php }else{?><?php echo $this->user['email'];?><?php }?></p>
					<?php }?>
				</div>
			</div>
			<ul class="pers-zhcz">
				<li><a id="nickname">修改昵称<i></i></a></li>
				<li><a id="password1">修改密码<i></i></a></li>
			</ul>
		</div>
		
		<!--修改昵称-->
		<div class="acc-line" style="display: none;">
			<form>
			<div class="acc-in manage-lable">
				<input type="text" name="username" />			
				<div class="manage-bc"><a>保存</a></div>		
			</div>
			</form>
		</div>
		<!--修改密码-->
		<div class="acc-tab" style="display: none;">
			<form method='post' action='<?php echo IUrl::creatUrl("wapcenter/change_password");?>'>
				<ul class="acc-list">
					<li>
						<input type="hidden" autocomplete="off"  name="fpassword" id="modify_password_1"/>
						<input type="password" autocomplete="off" class="no_chinese password_input" target_input="modify_password_1" placeholder="请输入原密码" value="" />
					</li>
					<li>
						<input type="hidden" autocomplete="off"  name="password" id="modify_password_2"/>
						<input type="password" autocomplete="off" class="no_chinese password_input" target_input="modify_password_2" value="" placeholder="请输入新密码" />
					</li>
					<li>
						<input type="hidden" autocomplete="off" name="repassword" id="modify_password_3"/>
						<input type="password" autocomplete="off" class="no_chinese password_input" target_input="modify_password_3" value="" placeholder="请确认密码" />
					</li>
				</ul>
				<div class="acc-box"> 
					<input class="submit_button" type="button" name="" value="保存" />
				</div>
			</form>
		</div>		
		
		<script>
			$(document).ready(function(){
				$("#nickname").click(function(){					
					$(".acc-line").css("display","block");
				});
				
				$("#password1").click(function(){
					$(".acc-tab").css("display","block");
				});				
				
			})
		</script>
		<script type='text/javascript'>
		var input_just_change = 0;
		
		function passwordchange(obj) {
			
			var value = $(obj).val();
			value = value.replace(/\s/g,"");
			var target = $(obj).attr("target_input");
			var valuebk = $("#" + target).val();
			
			var placeholder = $(obj).attr("placeholder");
			if (placeholder == value) {
				return true;
			}
												
			value=value.replace(/[\u4e00-\u9fa5]/g,'');

			if (valuebk == "") {
				valuebk = value;
			} else {
				if (valuebk.length < value.length) {
					var count = value.length - valuebk.length;
					valuebk = valuebk + value.substring(value.length-count, value.length);
				} else if (valuebk.length > value.length) {
//					var count = valuebk.length - value.length
//					valuebk = valuebk.substring(0, valuebk.length-count);
					valuebk = value;
				}
			}

			$("#" + target).val(valuebk);

			var length = value.length;
			var value_show = "";
			for (var i = 0 ; i < length; i++) {
				value_show = value_show + "*";
			}
			
			$(obj).val(value_show);	
			$(obj).attr("realvalue", valuebk);
		}
		
		$('.password_input').bind('input propertychange', function(event) {
			if (input_just_change == 1) {
				return true;
			}
			input_just_change = 1;
			passwordchange($(this));
			input_just_change = 0;
		});  
	
	
	function checkplaceholder() {
		if(!placeholderSupport()){   // 判断浏览器是否支持 placeholder
			function show_place(obj) {
				obj.addClass('placeholder');
				obj.val(obj.attr('placeholder'));
				obj.css("color", "#aaa");
			}
			function hide_place(obj) {
				obj.val('');
				obj.removeClass('placeholder');
				obj.css("color", "#000");
			}
			
			jQuery(':input[placeholder]').each(function(index, element) {
				if ($(this).attr("type") != "password" && $(this).attr("type") != "repassword") { 
					$(this).focus(function() {
						var input = $(this);
						if (input.val() == input.attr('placeholder')) {
							hide_place(input);
						}
					}).blur(function() {
						var input = $(this);
						if (input.val() == '' || input.val() == input.attr('placeholder')) {
							show_place(input);
						}
					});

					if ($(this).val() == '' || $(this).val() == $(this).attr('placeholder')) {
						var input = $(this);
						show_place(input);
					} else {
						$(this).css("color", "#000");
					}
				}
			});
		};
	}
	
	function checkplaceholderchange(obj) {
		if (obj.attr("placeholder") == obj.val()) {
			return false;
		}
		return true;
	}
	</script>
		 <script type="text/javascript">				
				
				$('.submit_button').click(function(){
					//alert('text')
					var fpassword = $('input[name="fpassword"]').val();
					var password = $('input[name="password"]').val();
					var repassword = $('input[name="repassword"]').val();
					var patt = /^\w{6,32}$/;
					if(!patt.test(fpassword)){
						alert('原始密码格式不正确');
						$('input[target_input="modify_password_1"]').focus();
						return false;
					}
					
					if(!patt.test(password)){
						alert('请输入6到32位由数字字母下划线组成的新密码');
						$('input[target_input="modify_password_2"]').focus();
						return false;
					}
					
					if(password != repassword){
						alert('两次密码输入不一致');
						$('input[target_input="modify_password_3"]').focus();
						return false;
					}
					
					$.ajax({
						url:'<?php echo IUrl::creatUrl("/wapcenter/change_password");?>',
						type:'get',
						dataType:'json',
						data:{'fpassword':fpassword,'password':password,'repassword':repassword},
						success:function(d){
							if(d.status == '1'){
								alert(d.message);
								window.location.reload();
							}else{
								alert(d.message);
								$('input[target_input="modify_password_1"]').val("");
								$('input[target_input="modify_password_2"]').val("");
								$('input[target_input="modify_password_3"]').val("");
							}
						}
					});
				});
		</script>
		<script type="text/javascript">
				
				$('.manage-bc a').click(function(){
					
					var username = $('input[name="username"]').val();
					var patt = /^[\w\u0391-\uFFE5]{2,20}$/;
					$('.manage-lable').trigger('submit');
					if(username == ''){
						alert('请输入用户名');
						return false;
					}else if(!patt.test(username)){
						alert('请填写2-20个由字母，数字下划线和中文组成的昵称');
						return false;
					}
					$.ajax({
						url:'<?php echo IUrl::creatUrl("/wapcenter/modify_username");?>',
						type:'post',
						dataType:'json',
						data:{"username":username},
						success:function(d){
							if(d.status == '1'){
								//alert(d.message);
								//$('.alert_close_img').click(function(){
									window.location.href='/wapcenter/account?'+Math.random();
								//});
							}else{
								alert(d.message);
							}
						}
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


		
		
