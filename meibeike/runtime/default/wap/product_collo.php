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
		
		<style>
	body{background: #F9F9F9;}
</style>

<script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate.js"></script><script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate-plugin.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/jquery.jqzoom/css/jquery.jqzoom.css";?>" />
<script type="text/javascript" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/jquery.jqzoom/js/jquery.jqzoom-core-pack.js";?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/jquery.bxSlider/jquery.bxslider.css";?>" />
<link rel="stylesheet" href="/views/default/wap/mobile/version/css/swipe.css" />
<script type="text/javascript" src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/jquery.bxSlider/jquery.bxSlider.min.js";?>"></script>

<script type="text/javascript" src="/views/default/wap/mobile/version/js/zepto.min.js" ></script>	

		<div class="prod-header">
			<ul class="prod-tab">
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_collo/id/10");?>">金甲组合</a></li>
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_collo/id/9");?>">银霸组合</a></li>
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_yin/id/16");?>">金组合</a></li>
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_yin/id/14");?>">银组合</a></li>
				<li><a href="<?php echo IUrl::creatUrl("/wap/product_diy");?>">DIY</a></li>
			</ul>
		</div>
	<div class="slider-box">
		<div class="J_top_slider index-slider">
			<ul class="top-slider" style="width: 700%; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); -webkit-transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; -webkit-transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
				<?php foreach($photo as $key => $item){?>
				<li style="width:14.285714285714%">
					<a>
						<img class="" src="/<?php echo isset($item['img'])?$item['img']:"";?>" ks_mark="y">
					</a>
				</li>
				<?php }?>
			</ul>
			<div class="top-slider-indicator">
				<?php foreach($photo as $key => $item){?>
				<span class="dot <?php if($key == '0'){?>on<?php }?>"></span>
				<?php }?>
			</div>
		</div>
	</div>	
		
		<div class="prod-center">
			<div class="prod-line">
				<div class="prod-cor"><?php echo isset($name)?$name:"";?></span></div>
				<p >&#65509;<span id="totalPrice"><?php echo isset($sell_price)?$sell_price:"";?></span>
				<input type="hidden" value="<?php echo isset($sell_price)?$sell_price:"";?>" id="price" />
			</div>
			
			<div class="prod-num">	 
				<div class="flt prod-ber">数量</div>
				<div class="number-box">
					<a onclick="numDec()" class="min"><img src="/views/default/wap/mobile/version/img/app-red.png"></a>
					<input type="text" id="buyNums" value="1" maxlength="5">
					<a onclick="numAdd()" class="adds"><img src="/views/default/wap/mobile/version/img/app-add.png"></a>
				</div>		
				<div class="prod-go">
					<input type="submit" onclick="buy_now();" value="前去购买" />
				</div>
			</div>
		</div>
		
		<div class="prod-table"> 
			<h4 id="text1" style="display: none;">金甲组合介绍</h4>
			<h4 id="text2" style="display: none;">银霸组合介绍</h4>
			<div>
				<img src="http://o8nj7d5m4.bkt.clouddn.com/product_2016072501.jpg" alt="b_img.jpg">
				<img src="http://o8nj7d5m4.bkt.clouddn.com/product_2016072502.jpg" alt="b_imgb.jpg">
				<img src="http://o8nj7d5m4.bkt.clouddn.com/product_2016072503.jpg" alt="b_imgc.jpg">
				<img src="http://o8nj7d5m4.bkt.clouddn.com/product_2016072504.jpg" alt="b_imgd.jpg">
				<img src="http://o8nj7d5m4.bkt.clouddn.com/product_2016072505.jpg" alt="b_imge.jpg">
			</div>
		</div>
		<input type='hidden' id='product_id' alt='货品ID' value='' />
		
	<script src="/views/default/wap/mobile/version/js/iscroll5.min.js"></script>
	<script src="/views/default/wap/mobile/version/js/mdd_index.min.js"></script>	
	<script type="text/javascript">
			$(function() {
					$("#buyNums").keyup(function() {
						if(isNaN($(this).val()) || parseInt($(this).val()) < 1) {
							$(this).val("1");
							$("#totalPrice").html($("#price").val());
							return;
						}
						var total = parseFloat($("#price").val()) * parseInt($(this).val());
						$("#totalPrice").html(total.toFixed(2));
					})

				})
			/*商品数量+1*/
			function numAdd() {
				var num_add = parseInt($("#buyNums").val()) + 1;
				if($("#buyNums").val() == "") {
					num_add = 1;
				}
				$("#buyNums").val(num_add);
				//alert($("#buyNums").val())
				var total = parseFloat($("#price").val()) * parseInt($("#buyNums").val());
				$("#totalPrice").html(total.toFixed(2));
				//parseInt($.trim($('#data_storeNums').text()))
//				var storeNums = 5;
//				if($("#buyNums").val() >= storeNums)
//				{
//					alert('库存有限！');
//					return false;
//				}
			}
			/*商品数量-1*/
			function numDec() {
				var num_dec = parseInt($("#buyNums").val()) - 1;
				if(num_dec < 1) {
					//购买数量必须大于或等于1
					alert("数量选择大于0");
				} else {
					$("#buyNums").val(num_dec);
					var total = parseFloat($("#price").val()) * parseInt($("#buyNums").val());
					$("#totalPrice").html(total.toFixed(2));
				}
			}
		</script>


<script type="text/javascript">
$(function(){
	<?php $id = $_GET['id']?>
	if(<?php echo isset($id)?$id:"";?> == '10'){
		$(".prod-tab li:nth-child(1)").addClass("act");
		$('#text1').css('display','block');
	}else{
		$(".prod-tab li:nth-child(2)").addClass("act");
		$('#text2').css('display','block');
}
//图片初始化
var goodsSmallPic = "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/nopic_435_435.gif";?>";
var goodsBigPic   = "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/nopic_435_435.gif";?>";

//存在图片数据时候
<?php if(isset($photo) && $photo){?>
goodsSmallPic = "<?php echo IUrl::creatUrl("")."";?><?php echo Thumb::get($photo[0]['img'],435,435);?>";
goodsBigPic   = "<?php echo IUrl::creatUrl("")."";?><?php echo isset($photo[0]['img'])?$photo[0]['img']:"";?>";
<?php }?>

//初始化商品轮换图
var bxObj = $('#thumblist').bxSlider({
	infiniteLoop: false,
	hideControlOnEnd: true,
	pager:false,
	minSlides: 5,
	maxSlides: 5,
	slideWidth: 72,
	slideMargin: 15,
	controls:true,
	onSliderLoad:function(currentIndex){
		//设置图片
		$('#smallPicBox').attr('src',goodsSmallPic);
		$('#bigPicBox').attr('href',goodsBigPic);

		//开启放大镜
		$('.jqzoom').jqzoom({
			title:false,
			lens:false,
			preloadText:'加载中...',
			zoomWidth:0,
			zoomHeight:0,
			xOffset:15,
		    zoomType: 'standard',
		    preloadImages: false
		});
	}
});

//如果抢购或团购过期则不许立即购买
<?php if($promo!='' && !isset($promotion) && !isset($regiment)){?>
	closeBuy();
<?php }?>

//如果当前是团购
<?php if(isset($regiment)){?>
	<?php $reg_id = IFilter::act(IReq::get('active_id'),'int');?>

	//团购检查,1,人数已满 2,已经参加过
	<?php if(Regiment::isFull($reg_id) || (isset($this->user['user_id']) && Regiment::hasJoined($reg_id,$this->user['user_id'])) || (ICookie::get("regiment_".$reg_id) && Regiment::hasJoined($reg_id,ICookie::get("regiment_".$reg_id)))){?>
		closeBuy();
	<?php }?>
<?php }?>

//开启倒计时功能


//限时抢购倒计时
<?php if(isset($promotion)){?>
cd_timer.add('promotiona');
<?php }?>

//团购倒计时
<?php if(isset($regiment)){?>
cd_timer.add('promotionb');
<?php }?>

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



//生成商品价格
var priceHtml = template.render('priceTemplate',{"group_price":"<?php echo isset($group_price)?$group_price:"";?>","minSellPrice":"<?php echo isset($minSellPrice)?$minSellPrice:"";?>","maxSellPrice":"<?php echo isset($maxSellPrice)?$maxSellPrice:"";?>","sell_price":"<?php echo isset($sell_price)?$sell_price:"";?>"});
$('#priceLi').replaceWith(priceHtml);

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

//发表讨论
function sendDiscuss()
{
	var userId = "<?php echo isset($this->user['user_id'])?$this->user['user_id']:"";?>";
	if(userId)
	{
		$('#discussTable').show('normal');
		$('#discussContent').focus();
	}
	else
	{
		alert('请登陆后再发表讨论!');
	}
}

/**
 * 根据省份获取运费信息
 * @param province 省份名称
 */
function searchDelivery(province)
{
	var url = '<?php echo IUrl::creatUrl("/block/searchPrivice/random/@random@");?>';
	url = url.replace("@random@",Math.random);

	$.getJSON(url,{'province':province},function(json)
	{
		if(json.flag == 'success')
		{
			delivery(json.area_id,province);
		}
	});
}

/**
 * 计算运费
 * @param provinceId
 * @param provinceName
 */

/**
 * 获取评论数据
 * @page 分页数
 */
function comment_ajax(page)
{
	if(!page && $.trim($('#commentBox').text()))
	{
		return;
	}
	page = page ? page : 1;
	var url = '<?php echo IUrl::creatUrl("/site/comment_ajax/page/@page@/goods_id/$id");?>';
	url = url.replace("@page@",page);
	$.getJSON(url,function(json)
	{
		//清空评论数据
		$('#commentBox').empty();

		for(var item in json.data)
		{
			var commentHtml = template.render('commentRowTemplate',json.data[item]);
			$('#commentBox').append(commentHtml);
		}
		$('#commentBox').append(json.pageHtml);
	});
}

/**
 * 获取购买记录数据
 * @page 分页数
 */
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
 * 获取购买记录数据
 * @page 分页数
 */
function refer_ajax(page)
{
	if(!page && $.trim($('#referBox').text()))
	{
		return;
	}
	page = page ? page : 1;
	var url = '<?php echo IUrl::creatUrl("/site/refer_ajax/page/@page@/goods_id/$id");?>';
	url = url.replace("@page@",page);
	$.getJSON(url,function(json)
	{
		//清空评论数据
		$('#referBox').empty();

		for(var item in json.data)
		{
			var commentHtml = template.render('referRowTemplate',json.data[item]);
			$('#referBox').append(commentHtml);
		}
		$('#referBox').append(json.pageHtml);
	});
}

/**
 * 获取购买记录数据
 * @page 分页数
 */
function discuss_ajax(page)
{
	if(!page && $.trim($('#discussBox').text()))
	{
		return;
	}
	page = page ? page : 1;
	var url = '<?php echo IUrl::creatUrl("/site/discuss_ajax/page/@page@/goods_id/$id");?>';
	url = url.replace("@page@",page);
	$.getJSON(url,function(json)
	{
		//清空购买历史记录
		$('#discussBox').empty();
		$('#discussBox').parent().parent().find('.pages_bar').remove();

		for(var item in json.data)
		{
			var historyHtml = template.render('discussRowTemplate',json.data[item]);
			$('#discussBox').append(historyHtml);
		}
		$('#discussBox').parent().after(json.pageHtml);
	});
}

//发布讨论数据
function sendDiscussData()
{
	var content = $('#discussContent').val();
	var captcha = $('[name="captcha"]').val();

	if($.trim(content)=='')
	{
		alert('请输入讨论内容!');
		$('#discussContent').focus();
		return false;
	}
	if($.trim(captcha)=='')
	{
		alert('请输入验证码!');
		$('[name="captcha"]').focus();
		return false;
	}

	var url = '<?php echo IUrl::creatUrl("/site/discussUpdate/id/$id/captcha/@captcha@/random/@Math@");?>';
	url = url.replace("@captcha@",captcha).replace("@Math@",Math.random);

	$.getJSON(url,{'content':content},function(json)
	{
		if(json.isError == false)
		{
			var discussHtml = template.render('discussRowTemplate',json);
			$('#discussBox').prepend(discussHtml);

			//清除数据
			$('#discussContent').val('');
			$('[name="captcha"]').val('');
			$('#discussTable').hide('normal');
			changeCaptcha('<?php echo IUrl::creatUrl("/site/getCaptcha");?>');
		}
		else
		{
			alert(json.message);
		}
	});
}

/**
 * 规格的选择
 * @param _self 规格本身
 */
function sele_spec(_self)
{	
	var specObj = $.parseJSON($(_self).attr('value'));

	//清除同规格下已选数据
	$('#selectedSpan'+specObj.id).remove();

	//已经为选中状态时
	if($(_self).attr('class') == 'current')
	{
		$(_self).removeClass('current');
		$('#selectedSpan'+specObj.id).remove();
	}
	else
	{
		//清除同行中其余规格选中状态
		$('#specList'+specObj.id).find('a.current').removeClass('current');

		$(_self).addClass('current');
		var newSpecHtml = template.render('selectedSpecTemplate',specObj);
		$('#specSelected').append(newSpecHtml);
	}

	//检查规格是否选择符合标准
		//整理规格值
		var specArray = [];
		$('[name="specCols"]').each(function(){
			specArray.push($(this).find('a.current').attr('value'));
		});
		var specJSON = '['+specArray.join(",")+']';
		//获取货品数据并进行渲染
		$.getJSON('<?php echo IUrl::creatUrl("/site/getProduct");?>',{"goods_id":"<?php echo isset($id)?$id:"";?>","specJSON":specJSON,"random":Math.random},function(json){
			if(json.flag == 'success')
			{
				//普通商品购买方式(非团购，抢购等),商品价格渲染
				if($('#priceLi').length > 0)
				{
					var priceHtml = template.render('priceTemplate',json.data);
					$('#priceLi').replaceWith(priceHtml);
				}
				//非普通商品购买方式，商品价格渲染
				else if($('#data_sellPrice').length > 0)
				{
					$('#data_sellPrice').text(json.data.sell_price);
				}

				//普通货品数据渲染
				$('#data_goodsNo').text(json.data.products_no);
				$('#data_storeNums').text(json.data.store_nums);
				$('#data_marketPrice').text("￥"+json.data.market_price);
				$('#data_weight').text(json.data.weight);
				$('#product_id').val(json.data.id);

				//库存监测
				checkStoreNums();
			}
			else
			{
				alert(json.message);
				closeBuy();
			}
		});
}

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
//function checkBuyNums()
//{
//	//购买数量小于0
//	var buyNums = parseInt($.trim($('#buyNums').val()));
//	if(buyNums <= 1||buyNums==null)
//	{
//		$('#buyNums').val(1);
//		return;
//	}
//	if($.isNumeric(parseInt($.trim($('#buyNums').val())))==true)
//	{
//		
//
//	}
//	else
//{
//		$('#buyNums').val(1);
//		return;
//	}
//
//
//	//购买数量大于库存
//	var storeNums = parseInt($.trim($('#data_storeNums').text()));
//	if(buyNums >= storeNums)
//	{
//		$('#buyNums').val(storeNums);
//		return;
//	}
//}

/**
 * 购物车数量的加减
 * @param code 增加或者减少购买的商品数量
 */
//function modified(code)
//{
//	var buyNums = parseInt($.trim($('#buyNums').val()));
//	switch(code)
//	{
//		case 1:
//		{
//			buyNums++;
//		}
//		break;
//
//		case -1:
//		{
//			buyNums--;
//		}
//		break;
//	}
//
//	$('#buyNums').val(buyNums);
//	checkBuyNums();
//}

//立即购买按钮
function buy_now()
{
	//设置必要参数

	var buyNums  = parseInt($.trim($('#buyNums').val()));
	var id = <?php echo isset($id)?$id:"";?>;
	var type = 'goods';

	if($('#product_id').val())
	{
		id = $('#product_id').val();
		type = 'product';
	}
	<?php if($promo){?>
	
	//有促销活动（团购，抢购）
	var url = '<?php echo IUrl::creatUrl("/wap/cart2/id/@id@/num/@buyNums@/type/@type@/promo/$promo/active_id/$active_id");?>';
	url = url.replace('@id@',id).replace('@buyNums@',buyNums).replace('@type@',type);
	<?php }else{?>
	//普通购买
	var url = '<?php echo IUrl::creatUrl("/wap/cart2/id/@id@/num/@buyNums@/type/@type@'");?>;
	url = url.replace('@id@',id).replace('@buyNums@',buyNums).replace('@type@',type);
	<?php }?>

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

$(document).ready(function(){ 
	$.getJSON('<?php echo IUrl::creatUrl("/wap/getProduct");?>',{"goods_id":"<?php echo isset($id)?$id:"";?>","random":Math.random},function(json){
		if(json.flag == 'success')
		{
			$('#product_id').val(json.data.id);
		}
		else
		{
			alert(json.message);
			closeBuy();
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


		
		
