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
    <link rel="stylesheet" href="/views/default/wap/mobile/version/css/main.css?08-08-01" />
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
		
		<style>
	body{background: #F9F9F9;}
</style>
		<div class="prod-header">
		<input type='hidden' id='product_id_3' alt='货品ID' value='' />
		<input type='hidden' id='product_id_7' alt='货品ID' value='' />
		<input type='hidden' id='product_id_4' alt='货品ID' value='' />
			<ul class="prod-tab">
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_collo/id/10");?>">金甲组合</a></li>
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_collo/id/9");?>">银霸组合</a></li>
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_yin/id/16");?>">金组合</a></li>
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_yin/id/14");?>">银组合</a></li>
				<li class="act"><a href="<?php echo IUrl::creatUrl("/wap/product_diy");?>">DIY</a></li>
			</ul>
		</div>
		
		<div class="prod-shop">
			<div class="prod-title">
				<h3>DIY说明</h3>
			</div>
			<div class="prod-nav">
				<h4>云棒为组件式智能产品，像搭积木一样，搭完即可用</h4>
				<p>1.一套云棒中必须有母棒，母棒负责主体运算和中心控制，母棒也可以直接做无线投射服务；
				<p>2.作为私有云服务器必须选择存储组件，存储组件有存储碟和存储棒两类；</p>
				<p>3.存储棒使用高速轻便的固态硬盘，最多可以同时连接8个到云棒母棒上，每个存储棒又有120G，250G，512G，1000G四个规格两个相同的存储棒可以作为镜像备份；</p>
				<p>4.存储碟使用大容量高性价比、可7*24小时开机的安防级机械硬盘，有1T，2T两个规格，每套云棒系统中只能装1个存储碟。</p>
			</div>
		</div>
		
		<div class="prod-p">		
			<em>●</em>您是否已有母棒，如果没有，请选择一个
		</div> 
		<div class="prod-box" id="goods_3">
			<div class="prod-sc">
				<ul class="prod-ul"> 
					<li>
						<div class="prod-ck">
							<input name="ck-radio" type="checkbox" id="chek_a" value="3"><label for="chek_a"></label>
						</div>
					</li>
					<li><img class="img-mb" src="/views/default/wap/mobile/version/img/001.jpg"/> </li>
					<li>
						<div class="prod-jg">
							<p class="p1"><?php echo isset($goods_info_3['name'])?$goods_info_3['name']:"";?></p>
							<p class="p2 goods3_price">&#65509;<span><?php echo isset($goods_info_3['sell_price'])?$goods_info_3['sell_price']:"";?></span></p>
						</div>
					</li>
				</ul>
			</div>
			<div class="prod-md" >
				<div class="flt prod-ber">数量</div>
				<div class="number-box">
					<a class="reduce" onclick="modified(-1,'buynums3')"><img src="/views/default/wap/mobile/version/img/app-red.png"></a>
					<input type="text" class="numer buynums3" onchange="checkBuyNums('buynums3')" onblur="checkBuyNums('buynums3')"  maxlength="5" value="1">
					<a class="add" onclick="modified(1,'buynums3')"><img src="/views/default/wap/mobile/version/img/app-add.png"></a>
				</div>	
			</div>
		</div>
		
		<div class="prod-box" id="goods_7">
			<?php if(empty($productsList7)){?>
			该商品已售完
			<?php }else{?>
			<div class="prod-sc">
				<ul class="prod-ul"> 
					<li>
						<div class="prod-ck">
							<input name="ck-radio" type="checkbox" id="chek_b" value="7"><label for="chek_b"></label>
						</div>
					</li>
					<li><img class="img-mb" src="/views/default/wap/mobile/version/img/002.jpg"/> </li>
					<li>
						<div class="prod-jg">
							<h5>请选择存储碟的规格（每套限一个）</h5>
							<div class="prod-cin"></div>
							<p class="p1 prod-top"><?php echo isset($goods_info_7['name'])?$goods_info_7['name']:"";?></p>
							<p class="p2 goods7_price">&#65509;<span></span></p>
						</div>
					</li>
				</ul>
			</div>			
			<div class="prod-md">
				<div class="prod-bot">
					<div class="flt prod-ber">选择规格</div>
					<div class="prod-bott">
						<ul class="prod-li">
						<?php foreach($productsList7 as $key => $item){?>
						<?php $k = $key; $specArray = JSON::decode($item['spec_array']);?>	
							<?php foreach($specArray as $key => $item){?>
								<?php if($item['type'] == 1){?>
								<li class="item <?php if($k=='0'){?><?php echo  'acb';?><?php }?>"><a href="javascript:void(0);"  value='{"id":"<?php echo isset($item['id'])?$item['id']:"";?>","type":"<?php echo isset($item['type'])?$item['type']:"";?>","value":"<?php echo isset($item['value'])?$item['value']:"";?>","name":"<?php echo isset($item['name'])?$item['name']:"";?>"}' ><?php echo isset($item['value'])?$item['value']:"";?></a></li>
								<?php }else{?>
								<li class="item"><a href="javascript:void(0);"  value='{"id":"<?php echo isset($item['id'])?$item['id']:"";?>","type":"<?php echo isset($item['type'])?$item['type']:"";?>","value":"<?php echo isset($item['value'])?$item['value']:"";?>","name":"<?php echo isset($item['name'])?$item['name']:"";?>"}' ><img src="<?php echo IUrl::creatUrl("")."$spec_value";?>" width='30px' height='30px' /><span></span></a></li>
								<?php }?>
							<?php }?>
						<?php }?>
						</ul>
					</div>
				</div>
				<div class="prod-mot">
					<div class="flt prod-ber">数量</div>
					<div class="number-box">
						<a class="reduce" onclick="modified(-1,'buynums7')"><img src="/views/default/wap/mobile/version/img/app-red.png"></a>
						<input type="text" class="numer buynums7" onchange="checkBuyNums('buynums7')" onblur="checkBuyNums('buynums7')" value="1" maxlength="5" />
						<a class="add" onclick="modified(1,'buynums7')"><img src="/views/default/wap/mobile/version/img/app-add.png"></a>
					</div>	
				</div>
			</div>
			<?php }?>
		</div>
		
		<div class="prod-box" id="goods_4">
			<?php if(empty($productsList4)){?>
			该商品已售完
			<?php }else{?>
			<div class="prod-sc">
				<ul class="prod-ul"> 
					<li>
						<div class="prod-ck">
							<input name="ck-radio" type="checkbox" id="chek_c" value="4"><label for="chek_c"></label>
						</div>
					</li>
					<li><img class="img-mb" src="/views/default/wap/mobile/version/img/003.jpg"/> </li>
					<li>
						<div class="prod-jg">
							<h5>请选择存储碟的规格（每套限一个）</h5>
							<div class="prod-cin"></div>
							<p class="p1 prod-top"><?php echo isset($goods_info_4['name'])?$goods_info_4['name']:"";?></p>
							<p class="p2 goods4_price">&#65509;<span></span></p>
						</div>
					</li>
				</ul>
			</div>			
			<div class="prod-md">
				<div class="prod-bot">
					<div class="flt prod-ber">选择规格</div>
					<div class="prod-bott">
						<ul class="prod-li">
						<?php foreach($productsList4 as $key => $item){?>
						<?php $k = $key; $specArray = JSON::decode($item['spec_array']);?>	
							<?php foreach($specArray as $key => $item){?>
								<?php if($item['type'] == 1){?>
								<li class="item <?php if($k=='0'){?><?php echo  'acb';?><?php }?>"><a href="javascript:void(0);"  value='{"id":"<?php echo isset($item['id'])?$item['id']:"";?>","type":"<?php echo isset($item['type'])?$item['type']:"";?>","value":"<?php echo isset($item['value'])?$item['value']:"";?>","name":"<?php echo isset($item['name'])?$item['name']:"";?>"}' ><?php echo isset($item['value'])?$item['value']:"";?></a></li>
								<?php }else{?>
								<li class="item"><a href="javascript:void(0);"  value='{"id":"<?php echo isset($item['id'])?$item['id']:"";?>","type":"<?php echo isset($item['type'])?$item['type']:"";?>","value":"<?php echo isset($spec_value)?$spec_value:"";?>","name":"<?php echo isset($item['name'])?$item['name']:"";?>"}' ><img src="<?php echo IUrl::creatUrl("")."$spec_value";?>" width='30px' height='30px' /><span></span></a></li>
								<?php }?>
							<?php }?>
						<?php }?>
						</ul>
					</div>
				</div>
				<div class="prod-mot">
					<div class="flt prod-ber">数量</div>
					<div class="number-box">
						<a class="reduce" onclick="modified(-1,'buynums4')"><img src="/views/default/wap/mobile/version/img/app-red.png"></a>
						<input type="text" class="numer buynums4" onchange="checkBuyNums('buynums4')" onblur="checkBuyNums('buynums4')" value="1" maxlength="5" />
						<a class="add" onclick="modified(1,'buynums4')"><img src="/views/default/wap/mobile/version/img/app-add.png"></a>
					</div>	
				</div>
			</div>
		<?php }?>
		</div>
		
		<div class="prod-subnav"> 
			<div class="peod-sub">
				<div class="prod-vo" id="goods_selected">您已选择：<span></span>&nbsp;&nbsp;<span></span>&nbsp;&nbsp;<span></span></div>
				<div class="prod-vo" id="totalPrice">合计金额：&#65509;<span></span></div>
			</div>
			<div class="prod-goto">
				<input type="submit" onclick="buy_now()" value="前去付款" />
			</div>
		</div>
		<!--<style>
			.prod-jg li .acb{
				background: url(../version/img/app-ss.png) right bottom no-repeat;
			    border: 2px solid #ff2222;
			    background-size: 21%;
			}
		</style>-->
		<script>
			$(function(){
				$(".prod-li li").click(function(){
					var index = $(this).index();
					$(this).addClass("acb").siblings().removeClass("acb");
					//$(".hide").eq(index).addClass("show").siblings().removeClass("show")
					
				})
			})
		</script>
		
		
			<script type="text/javascript">
			function toDecimal2 (x){ 
				   var f = parseFloat(x); 
				   if (isNaN(f)) { 
				    return false; 
				   } 
				   var f = Math.round(x*100)/100; 
				   var s = f.toString(); 
				   var rs = s.indexOf('.'); 
				   if (rs < 0) { 
				    rs = s.length; 
				    s += '.'; 
				   } 
				   while (s.length <= rs + 2) { 
				    s += '0'; 
				   } 
				   return s; 
			} 
			
			$(document).ready(function(){				
				if($('#chek_a').is(':checked')){
					var count3 = $('#goods_3 .buynums3').val();
					var name = $('#goods_3 .p1').html();
					var str = name+'*'+count3;
					$('#goods_selected span:eq(0)').html(str);
					//var totalPrice = parseFloat(<?php echo isset($goods_info_3['sell_price'])?$goods_info_3['sell_price']:"";?>*count3);
					//$('#totalPrice span').html(totalPrice);
				}
				$('#chek_a,#chek_b,#chek_c').bind("click",function(){
					if($('#chek_a').is(':checked')){
						var count3 = $('#goods_3 .buynums3').val();
						var name = $('#goods_3 .p1').html();
						$('#goods_3 .buynums3').attr('disabled','disabled');
						$('#goods_3 .add').css('visibility','hidden');
						$('#goods_3 .reduce').css('visibility','hidden');
						var str = name+'*'+count3;
						$('#goods_selected span:eq(0)').html(str);
					}else{
						$('#goods_3 .buynums3').attr('disabled',false);
						$('#goods_3 .add').css('visibility','visible');
						$('#goods_3 .reduce').css('visibility','visible');
						$('#goods_selected span:eq(0)').html("");
					}
					
					if($('#chek_b').is(':checked')){		
						var count7= $('#goods_7 .buynums7').val();
						var name = $('#goods_7 .p1').html();
						var rule = $('#goods_7 .acb a').html();
						var str = rule+name+'*'+count7;
						$('#goods_7 .acb').siblings().css('visibility','hidden');
						$('#goods_7 .buynums7').attr('disabled','disabled');
						$('#goods_7 .add').css('visibility','hidden');
						$('#goods_7 .reduce').css('visibility','hidden');
						$('#goods_selected span:eq(1)').html(str);
					}else{
						$('#goods_7 .acb').siblings().css('visibility','visible');
						$('#goods_7 .buynums7').attr('disabled',false);
						$('#goods_7 .add').css('visibility','visible');
						$('#goods_7 .reduce').css('visibility','visible');
						$('#goods_selected span:eq(1)').html("");
					}
					
					if($('#chek_c').is(':checked')){
						var count4 = $('#goods_4 .buynums4').val();
						var name = $('#goods_4 .p1').html();
						var rule = $('#goods_4 .acb a').html();
						var str = rule+name+'*'+count4;
						$('#goods_4 .acb').siblings().css('visibility','hidden');
						$('#goods_4 .buynums4').attr('disabled','disabled');
						$('#goods_4 .add').css('visibility','hidden');
						$('#goods_4 .reduce').css('visibility','hidden');
						$('#goods_selected span:eq(2)').html(str);
					}else{
						$('#goods_4 .acb').siblings().css('visibility','visible');
						$('#goods_4 .buynums4').attr('disabled',false);
						$('#goods_4 .add').css('visibility','visible');
						$('#goods_4 .reduce').css('visibility','visible');
						$('#goods_selected span:eq(2)').html("");
					}
					
					var count3 = typeof count3=='undefined' ? 0 : count3;
					var count7 = typeof count7=='undefined' ? 0 : count7;
					var count4 = typeof count4=='undefined' ? 0 : count4;
					var totalPrice = parseFloat(<?php echo isset($goods_info_3['sell_price'])?$goods_info_3['sell_price']:"";?>*count3) + parseFloat($('.goods7_price span').html())*count7 + parseFloat($('.goods4_price span').html())*count4;
					totalPrice = toDecimal2(totalPrice);
					$('#totalPrice span').html(totalPrice);
				});
			
			});
			</script>
			
			
			<script>
				//存储碟选择

				
				//存储棒选择
				$(function() {
					$(".diy-but1").bind("click", function(e) {
						e = e || window.event;
						var tar = e.srcElement || e.target;
						if (tar.nodeName.toLowerCase() == "li" && !$(tar).hasClass('bg-img')) {
							$(tar).addClass("bg-img");
							$(tar).css("color", "#FF0000");
//							$(".").css("display", "none");
						} else {
							$(tar).removeClass("bg-img");
							$(tar).css("color", "#999");
			
						}
					})
				})
			</script>
			
			
<script type="text/javascript">
$(document).ready(function(){ 

	$(".gold-ul li a").removeClass("gold-act");
	$(".gold-ul li a").each(function (){ 
		// alert($(this).text());
	//if($("a",$(this)).attr("href") == window.location.href){ 		
		if($($(this)).attr("href") == window.location.href){ 		
	    $(this).addClass("gold-act");
	}
});

//城市地域选择按钮事件
$('.sel_area').hover(
	function(){
		$('.area_box').show();
	},function(){
		$('.area_box').hide();
	}
);
$('.area_box').hover(
	function(){
		$('.area_box').show();
	},function(){
		$('.area_box').hide();
	}
);


//按钮绑定
$('[name="showButton"]>label').click(function(){
	$(this).siblings().removeClass('current');
	if($(this).hasClass('current') == false)
	{
		$(this).addClass('current');
	}
	$('[name="showBox"]>div').addClass('hidden');
	$('[name="showBox"]>div:eq('+$(this).index()+')').removeClass('hidden');

	switch($(this).index())
	{
		case 1:
		{
			comment_ajax();
		}
		break;

		case 2:
		{
			history_ajax();
		}
		break;

		case 3:
		{
			refer_ajax();
		}
		break;

		case 4:
		{
			discuss_ajax();
		}
		break;
	}
});

});

//禁止购买
function closeBuy()
{
	if($('#buyNowButton').length > 0)
	{
		$('#buyNowButton').attr('disabled','disabled');
		$('#buyNowButton').addClass('disabled');
	}

	if($('#joinCarButton').length > 0)
	{
		$('#joinCarButton').attr('disabled','disabled');
		$('#joinCarButton').addClass('disabled');
	}
}


//开放购买
function openBuy()
{
	if($('#buyNowButton').length > 0)
	{
		$('#buyNowButton').removeAttr('disabled');
		$('#buyNowButton').removeClass('disabled');
	}

	if($('#joinCarButton').length > 0)
	{
		$('#joinCarButton').removeAttr('disabled');
		$('#joinCarButton').removeClass('disabled');
	}
}

//加载根据地域获取城市
function getAddress()
{
	//根据IP查询所在地
	var ipAddress = $.cookie('ipAddress');
	if(ipAddress)
	{
		searchDelivery(ipAddress);
	}
	else
	{
		$.getScript('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js',function(){
			ipAddress = remote_ip_info['province'];
			$.cookie('ipAddress',ipAddress);
			searchDelivery(ipAddress);
		});
	}
}


function history_ajax(page)
{
	if(!page && $.trim($('#historyBox').text()))
	{
		return;
	}
	page = page ? page : 1;
	var url = '<?php echo IUrl::creatUrl("/site/history_ajax/page/@page@/goods_id/$id");?>';
	url = url.replace("@page@",page);
	$.getJSON(url,function(json)
	{
		//清空购买历史记录
		$('#historyBox').empty();
		$('#historyBox').parent().parent().find('.pages_bar').remove();

		for(var item in json.data)
		{
			var historyHtml = template.render('historyRowTemplate',json.data[item]);
			$('#historyBox').append(historyHtml);
		}
		$('#historyBox').parent().after(json.pageHtml);
	});
}

/**
 * 规格的选择
 * @param _self 规格本身
 */


/**
 * 监测库存操作
 */
function checkStoreNums()
{
	var storeNums = parseInt($.trim($('#data_storeNums').text()));
	if(storeNums > 0)
	{
		openBuy();
	}
	else
	{
		closeBuy();
	}
}

/**
 * 检查规格选择是否符合标准
 * @return boolen
 */
function checkSpecSelected()
{	
	if($('[name="specCols"]').length === $('[name="specCols"] .current').length)
	{
		return true;
	}
	return false;
}



//检查购买数量是否合法
function checkBuyNums(element)
{	
	//购买数量小于0
	var buyNums = parseInt($.trim($('.'+element).val()));

	if(buyNums <= 1||buyNums==null)
	{
		$('.'+element).val(1);
		return;
	}
	if($.isNumeric(parseInt($.trim($('.'+element).val())))==true)
	{
		//var pi = element.length - 1;
		//var index = element[pi];
		//var price = parseFloat($('.goods'+index+'_price em:eq(1)').html())*buyNums;
		//$('.goods'+index+'_price em:eq(1)').html(price);
	}
	else
{
		$('.'+element).val(1);
		return;
	}


	//购买数量大于库存
	var storeNums = parseInt($.trim($('#data_storeNums').text()));
	if(buyNums >= storeNums)
	{
		$('.'+element).val(storeNums);
		return;
	}
}

/**
 * 购物车数量的加减
 * @param code 增加或者减少购买的商品数量
 */
function modified(code,element)
{	
	var buyNums = parseInt($.trim($('.'+element).val()));
	switch(code)
	{
		case 1:
		{
			buyNums++;
		}
		break;

		case -1:
		{
			buyNums--;
		}
		break;
	}

	$('.'+element).val(buyNums);
	checkBuyNums(element);
}


//立即购买按钮
function buy_now()
{	var chek_a = $('#chek_a').attr('checked');
	var chek_b = $('#chek_b').attr('checked');
	var chek_c = $('#chek_c').attr('checked');
	//if(chek_a != 'checked'){
		//alert("请至少选择一个母棒");
		//$('.alert_close_img').click(function(){
			//window.location.href='<?php echo IUrl::creatUrl("/tool/diy#chek_a");?>';
		//});
		//return;
	//}	

	//设置必要参数
	if($('#chek_a').is(':checked')){
		var id3 = $('#product_id_3').val();
		var buyNums3  = parseInt($.trim($('.buynums3').val()));
	} 
	
	if($('#chek_b').is(':checked')){
		var id7 = $('#product_id_7').val();
		var buyNums7  = parseInt($.trim($('.buynums7').val()));
	}
	
	if($('#chek_c').is(':checked')){
		var id4 = $('#product_id_4').val();
		var buyNums4  = parseInt($.trim($('.buynums4').val()));
	}
	
	if(!$('#chek_a').is(':checked') && !$('#chek_b').is(':checked') && !$('#chek_c').is(':checked')){
		alert('您至少选择一种商品');
		return false;
	}
	type = 'product';
	//普通购买
	var url = '<?php echo IUrl::creatUrl("/wap/cart_diy_2/id3/@id3@/num3/@buyNums3@/id7/@id7@/num7/@buyNums7@/id4/@id4@/num4/@buyNums4@/type/@type@");?>';
	url = url.replace('@id3@',id3).replace('@buyNums3@',buyNums3).replace('@id7@',id7).replace('@buyNums7@',buyNums7).replace('@id4@',id4).replace('@buyNums4@',buyNums4).replace('@type@',type);
	
	if ($("input#hidden_user_id").val() == "" || $("input#hidden_user_id").val() == 0) {
        $(".loginwap").css("display", "block");
        $('.loginwap #login_notice').css('visibility','visible');
        $('.loginwap #login_notice').html("购买需要登录MeiID");
        $('input[name="orderCallback"]').val(url);
        return false;
	} 	
	//页面跳转
	window.location.href = url;
}

$(function() {
	//$(".diy-but li").click(function() {
		//var index = $(this).index();
		//$(this).addClass("butt-1").siblings().removeClass("butt-1");
		
		//sele_spec(<?php echo isset($goods_info_4['id'])?$goods_info_4['id']:"";?>,4,'#goods_4 .butt-1 a');
		
	//})
		
		$("#goods_7 .prod-li li").click(function() {
			$(this).addClass("acb").siblings().removeClass("acb");
			sele_spec(<?php echo isset($goods_info_7['id'])?$goods_info_7['id']:"";?>,7,'#goods_7 .acb a');
		})
		
		$("#goods_4 .prod-li li").click(function() {
			$(this).addClass("acb").siblings().removeClass("acb");
			sele_spec(<?php echo isset($goods_info_4['id'])?$goods_info_4['id']:"";?>,4,'#goods_4 .acb a');
		})
	
})

function sele_spec(goods_id,index,spec)
{		
		var specObj = $(spec).attr('value');
		var specJSON = '['+specObj+']';
		if(index == '3'){
			var specJSON = '[{"id":"9","type":"1","value":"母棒","name":"母棒"}]';
		}
		$.getJSON('<?php echo IUrl::creatUrl("/tool/getProduct");?>',{"goods_id":goods_id,"random":Math.random(),"specJSON":specJSON},function(json){
			if(json.flag == 'success')
			{	
				$('#product_id_'+index).val(json.data.id);
				$('.goods'+index+'_price span').html(json.data.sell_price);
			}
			else
			{
				alert(json.message);
				closeBuy();
			}
		});
}

$(document).ready(function(){
	sele_spec(<?php echo isset($goods_info_3['id'])?$goods_info_3['id']:"";?>,3,'#goods_3 .acb a');
	sele_spec(<?php echo isset($goods_info_7['id'])?$goods_info_7['id']:"";?>,7,'#goods_7 .acb a');
	sele_spec(<?php echo isset($goods_info_4['id'])?$goods_info_4['id']:"";?>,4,'#goods_4 .acb a');
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


		
		
