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
				<div class="login-input"><input type="text"  name="login_info" placeholder="请输入MeiID（邮箱或手机）" /> </div>
				<div class="login-input"><input type="password" maxlength="20" name="password" value="" placeholder="请输入密码" /> </div>				
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
		
		<div class="wap">
			<div class="cur-center">
				<h2>我们非常重视您的意见！</h2>
				<h3>我们准备了多种渠道，随时恭候您的垂询！</h3>
			<div class="cur-wt">
				<div class="wt1">常见问题</div>
				<div class="wt2"><a href="<?php echo IUrl::creatUrl("/wap/customer_more");?>">更多</a></div>
			</div>
			<ul class="cur-ul">
				<?php foreach($list as $key => $item){?>
				<li>	
					<a href="<?php echo IUrl::creatUrl("/wap/customer_content/id/");?><?php echo isset($item['id'])?$item['id']:"";?>">				
						<span class="flt cur-m"><i>●</i> <?php echo isset($item['name'])?$item['name']:"";?></span>
						<span class="frt"><img class="cur-r" src="/views/default/wap/mobile/version/img/app-rigth.png" /></span>
					</a>
				</li>
				<?php }?>
			</ul>					
			</div>
			
			<div class="cur-line">
				<div class="cur-title">
					<h3>服务渠道</h3>
				</div>
				
				<!--提交问题-->
				<div class="tomer-m" style="display: none;">
					<div class="tomer-tab-1"> 
						<div class="tomer-line-1">
							<div class="h1">请选择问题类型</div>
							
							<ul class="radiobox">
								<li>
									<input name="ck-radio" type="radio" id="radio_1" checked />
									<label for="radio_1">购买</label>
								</li>
								<li>
									<input name="ck-radio" type="radio" id="radio_2" />
									<label for="radio_2">硬件</label>
								</li>
								<li>
									<input name="ck-radio" type="radio" id="radio_3" />
									<label for="radio_3">使用</label>
								</li>
								<li>
									<input name="ck-radio" type="radio" id="radio_4" />
									<label for="radio_4">售后</label>
								</li>
								<li>
									<input name="ck-radio" type="radio" id="radio_5" />
									<label for="radio_5">其他</label>
								</li>
							</ul>
							<div class="tomer-te">
								<textarea rows="6" cols="10" name="content" onblur="if(this.innerHTML==''){this.innerHTML='请在这里描述您遇到的问题或建议';this.style.color='#666'}" onfocus="if(this.innerHTML=='请在这里描述您遇到的问题或建议'){this.innerHTML='';this.style.color='#666'}">请在这里描述您遇到的问题或建议</textarea>
							</div>
							<div class="tomer-in">
								<input type="text" name="contact_way" placeholder="请留下你的联系方式或邮箱地址（选填）" />
							</div>
							<div class="tomer-but">
								<input class="in1" type="button" name="" value="关闭" />
								<input class="in2" type="submit" name="" value="提交" />
							</div>
						</div>
						
					</div>
				</div>
				
				
				<!--关注微信二维码-->
				<div class="tomer-bg" style="display: none;">
					<div class="tomer-box">											
						<div class="tomer-line">
							<div class="to-img"><img id="to-img" src="/views/default/wap/mobile/version/img/app-closes.png"/></div>
							<div class="imgs">
								<img src="/views/default/wap/mobile/version/img/app-weewm.jpg" />
							</div>
							<p>扫一扫关注“美贝壳科技”公众号</p>
						</div>							 			
					</div>
				</div>
				
				<ul class="tomer-list">
					<li>
						<div class="tomer-l1"><img src="/views/default/wap/mobile/version/img/app-wxkf.png"/></div>
						<div class="tomer-l2">
							<h3>关注公众号“美贝壳科技”</h3>
							<p>服务时间：7x24小时</p>
						</div>
						<div class="tomer-l3">
							<a id="look_w">查看二维码</a>
						</div>
					</li>
					<li>
						<div class="tomer-l1"><img src="/views/default/wap/mobile/version/img/app-dhkf.png"/></div>
						<div class="tomer-l2" style="width: 74%;">
							<h3>400 772 8320</h3>
							<p>服务时间：9:00-18:00（周一至周五）</p>
						</div>		
					</li>
					<li>
						<div class="tomer-l1"><img src="/views/default/wap/mobile/version/img/app-yjkf.png"/></div>
						<div class="tomer-l2">
							<h3>kf@meibeike.com</h3>
							<p>服务时间：7x24小时</p>
						</div>		
					</li>
					<li>
						<div class="tomer-l1"><img src="/views/default/wap/mobile/version/img/app-zxkf.png"/></div>
						<div class="tomer-l2">
							<h3>在线提交您的问题</h3>
							<p>服务时间：7x24小时</p>
						</div>
						<div class="tomer-l3">
							<a id="look_k">提交问题</a>
						</div>
					</li>
				</ul>
				
				<script type="text/javascript">
				//提交表单弹框
				$("#look_k").click(function(event) {				
					if ($("input#hidden_user_id").val() == "" || $("input#hidden_user_id").val() == 0){
						 $(".loginwap").css("display", "block");
				        $('.loginwap #login_notice').css('visibility','visible');
				        $('.loginwap #login_notice').html("登录MeiID");
				        $('input[name="orderCallback"]').val(url);

						return false;
					}
					$(".tomer-m").slideToggle(0);
					event.stopImmediatePropagation();					
				});

				$('.in2').bind("click",function(){
					var content = $('textarea[name="content"]').val();
					var contact_way = $('input[name="contact_way"]').val();
					var suggestion_type = $('input[name="ck-radio"]').val();
					$.ajax({
						url:"<?php echo IUrl::creatUrl("/tool/suggestion");?>",
						type:"post",
						dataType:"json",
						data:{"content":content,"contact_way":contact_way,"suggestion_type":suggestion_type},
						success:function(d){
							if(d){
								alert(d.message);
								window.location.reload();
//								$('.alert_close_img').click(function(){
//									window.location.reload();
//								});
							}
							//$('.tomer-bg,.tomer-box-1').hide();
						}
					});
				});
				
			
		</script>		
		
		<script>
			$(".tomer-in input").focus(function(){
			  $(".tomer-tab-1").css("margin-top","0");
			});
		</script>
		
				<script>
					$('#look_w').click(function(){
						$('.tomer-bg').show();
					})
					$('#to-img').click(function(){
						$('.tomer-bg').hide();
					})

					$('.in1').click(function(){
						$('.tomer-m').hide();
					})
				</script>
				
				<!--<div class="cur-box">
					<div class="cur-wx">
						<div class="cur-wxkf"><img src="/views/default/wap/mobile/version/img/app-wxkf.png" /><p>微信客服</p></div>
						<div class="cur-wxewm">
							<p>服务时间：7*24h</p>
							<div class="cur-c">
								<img src="/views/default/wap/mobile/version/img/app-weewm.jpg" />
							</div>							
							<p>微信搜索“美贝壳科技”</p>
						</div>
					</div>
					<div class="cur-wx">
						<div class="cur-wxkf"><img src="/views/default/wap/mobile/version/img/app-dhkf.png" /><p>电话客服</p></div>
						<div class="cur-dhkf">
							<p>400 772 8320</p>
							<div>服务时间：9:00-18:00</div>
							<div>（周一到周五）</div>
						</div>
					</div>
					<div class="cur-wx">
						<div class="cur-wxkf"><img src="/views/default/wap/mobile/version/img/app-yjkf.png" /><p>邮件客服</p></div>
						<div class="cur-yjkf">
							<p>kf@meibeike.com</p>
							<p>服务时间：7*24h</p>
						</div>
					</div>
					
					<div class="cur-wx">
						<div class="cur-wxkf"><img src="/views/default/wap/mobile/version/img/app-zxkf.png" /><p>在线客服</p></div>
						<div class="cur-zxkf">
							<div class="cur-but"><input type="submit" value="提交表单" /> </div>
							<p>服务时间：7*24h</p>
						</div>
					</div>					
				</div>			-->
			</div>
		</div>
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


		
		
