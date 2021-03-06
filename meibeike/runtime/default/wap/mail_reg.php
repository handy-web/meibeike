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
						<?php }else{?><li><a href='javascript:void(0)' id='loginbtn' class="gwc-a"><img class="gwc-img" src="/views/default/wap/mobile/version/img/app-gwc-tc.png" />登录</a></li>
					<?php }?>
				</ul>
			</div>
		<!--登录框-->
		<div class="loginwap" style="display: none;">
			<div id="login_notice" style='text-align:center;visibility:hidden;margin-top: 20%;'></div>		
			<form method="post" action="<?php echo IUrl::creatUrl("/wap/login_act");?>" id="login_form">
			<input type='hidden' name='callback' value='<?php echo isset($callback)?$callback:"";?>' />
            <input type='hidden' name='orderCallback' value='<?php echo isset($orderCallback)?$orderCallback:"";?>' />
            <input type="hidden" name="showerror" value="<?php echo isset($this->message)?$this->message:"";?>"/>
			<div class="login-c">
				<div class="login-input"><input type="text" maxlength="16" name="login_info" placeholder="请输入MeiID（邮箱或手机）" /> </div>
				<div class="login-input"><input type="password" maxlength="16" name="password" value="" placeholder="请输入密码" /> </div>				
				<div class="login-but"><input type="button" id='loginsubmit' value="登录" /> </div>
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
		$('#loginbtn').click(function(){
			$('.loginwap').show();
		});
        $("#loginsubmit").click(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo IUrl::creatUrl("simple/check_login");?>",
                data: "login_info=" + $("input[name=login_info]").val() + "&password=" + $("input[name='password']").val(),
                dataType: "Json",
                success: function (data_ret) {
                    if (data_ret['code'] == 0) {
                         alert(data_ret['message']);
						 if (data_ret["valid_required"]) {
                            $(".login_content").css("height", "auto");
                            $("#failed_check").css("display", "block");
                         }
                         return false;
                    } else {
                        $("#login_form").submit();
                    }
                }
            });
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
		<ul class="reg-c">
			<li><a href="<?php echo IUrl::creatUrl("/wap/phone_reg");?>">手机注册</a></li>
			<li><a class="reg-act" href="<?php echo IUrl::creatUrl("/wap/mail_reg");?>">邮箱注册</li></a>
		</ul>	
		<form action="<?php echo IUrl::creatUrl("/wap/reg_act");?>" method="post" id="reg_form">
		<input type="hidden" name="type" value='1' />
		<div class="reg-line">
			<div class="reg-input"><input type="tel" name="email"  placeholder="请输入邮箱" /> </div>				
			<div class="reg-input"><input type="password" name="password"  placeholder="请输入密码" /> </div>
			<div class="reg-input"><input type="password" name="repassword"  placeholder="再次输入密码" /> </div>
			<div class="reg-go"><input type="button" id="to_submit" value="立即注册" /></div>
			<div class="reg-wr">
				<div class="login-ck"><input type="checkbox" name="id_ck" id="ck_1" value="" checked /><label for="ck_1"><span>同意并愿意遵守<a>《用户协议》</a></span></label></div>					
			</div>
		</div>
		</form>
		<div class="reg-mid"><a href="<?php echo IUrl::creatUrl("/wap/login");?>">已有Mei ID,直接登录</a></div>
		<div id="embed-captcha"></div>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<script>
    var handlerPopup = function (captchaObj) {
        $("#to_submit").click(function () {
            var validate = captchaObj.getValidate();
            if (!validate) {
                alert('请先完成验证');
                return ;
            } 
            
            if ($("#ck_1").attr('checked') != 'checked') {
                alert("请同意用户协议");
                return;
            } 
            
    		if($("input[name=email]").val().indexOf("mail.bccto.me")>1)
    		{
    			alert("请输入正确的邮箱");
    			return;
    		}
            // 去后台验证邮箱和密码
            jQuery.ajax({
                type: "POST",
                url: "<?php echo IUrl::creatUrl("wap/verify_email_captcha");?>",
               	data:{"email":$("input[name=email]").val(),
                    "geetest_challenge": validate.geetest_challenge,
                    "geetest_validate": validate.geetest_validate,
                    "geetest_seccode": validate.geetest_seccode},
                dataType: "Json",
                success: function (d) {
                    if (d.status == 0) {
                        alert(d.message);
                    } else {
						$('#reg_form').submit();
                    }
                }
            });

        });
        captchaObj.appendTo("#embed-captcha");
        captchaObj.bindOn("#to_submit");
    };
    $.ajax({
        // 获取id，challenge，success（是否启用failback）
        url: "<?php echo IUrl::creatUrl("/wap/create_hkyz/t/");?>"+(new Date()).getTime(), // 加随机数防止缓存
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


		
		
