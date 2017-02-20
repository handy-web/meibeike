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
		
		<script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate.js"></script><script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate-plugin.js"></script>
<script type='text/javascript' src='<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/artTemplate/area_select.js";?>'></script>
<script type='text/javascript' src='<?php echo IUrl::creatUrl("")."views/".$this->theme."/wap/mobile/version/js/orderFormClass.js?20160720";?>'></script>


<script type='text/javascript'>
//创建订单表单实例
orderFormInstance = new orderFormClass();

//DOM加载完毕
jQuery(function(){
	//使用红包按钮
	$('#ticket_a').click(function()
	{
		//第一次打开时生成缓存数据
		if($.trim($('#ticket_show_box').text()) == '')
		{
			var ticketList = <?php echo JSON::encode($this->prop);?>;
			for(var index in ticketList)
			{
				var ticketHtml = template.render('ticketTrTemplate',{item:ticketList[index]});
				$('#ticket_show_box').append(ticketHtml);
			}
		}

		$(this).toggleClass('fold');
		$(this).toggleClass('unfold');
		$('#ticket_box').toggle('slow');
	});

	//初始化地域联动JS模板
	template.compile("areaTemplate",areaTemplate);

	//收货地址数据
	orderFormInstance.addressInit("<?php echo isset($this->defaultAddressId)?$this->defaultAddressId:"";?>");

	//配送方式初始化
	orderFormInstance.deliveryInit("<?php echo isset($this->custom['delivery'])?$this->custom['delivery']:"";?>");
	
	//设置是否免运费
	orderFormInstance.freeFreight = <?php echo $this->freeFreight ? 1 : 0;?>;

	//支付方式
	orderFormInstance.paymentInit("<?php echo isset($this->custom['payment'])?$this->custom['payment']:"";?>");

	//商品价格
	orderFormInstance.goodsSum = "<?php echo isset($this->final_sum)?$this->final_sum:"";?>";

});

/**
 * 生成地域js联动下拉框
 * @param name
 * @param parent_id
 * @param select_id
 */
function createAreaSelect(name,parent_id,select_id)
{
	//生成地区
	$.getJSON("<?php echo IUrl::creatUrl("/block/area_child");?>",{"aid":parent_id,"random":Math.random()},function(json)
	{
		$('[name="'+name+'"]').html(template.render('areaTemplate',{"select_id":select_id,"data":json}));
	});
}

//[address]保存到常用的收货地址
function address_default_save()
{
	if(orderFormInstance.addressCheck())
	{
		$.getJSON('<?php echo IUrl::creatUrl("/ucenter/address_add2");?>',$('form[name="order_form"]').serialize(),function(content){
			if(content.isError == false)
			{	$('.acc').trigger('click');
				var addressLiHtml = template.render('addressLiTemplate',{"item":content.data});
				$('#address_often').show();
				$('.addr_list').prepend(addressLiHtml);
				$('input:radio[name="radio_address"]:first').trigger('click');
			}
			else
			{
				alert(content.message);
			}
		});
	}
}


function address_fisrt_save(){
	if(orderFormInstance.addressCheck())
	{
		$.getJSON('<?php echo IUrl::creatUrl("/ucenter/address_add2");?>',$('form[name="order_form"]').serialize(),function(content){
			if(content.isError == false)
			{	$('#address_form').hide();
				$('#address_show_box').show();
				var addressLiHtml = template.render('addressLiTemplate',{"item":content.data});
				$('.addr_list').prepend(addressLiHtml);
				$('input:radio[name="radio_address"]:first').trigger('click');
			}
			else
			{
				alert(content.message);
			}
		});
	}
}


function address_modify(){
	if(orderFormInstance.addressCheck())
	{
		$.getJSON('<?php echo IUrl::creatUrl("/ucenter/address_modify");?>',$('form[name="order_form"]').serialize(),function(content){
			if(content.isError == false)
			{	
				$('.acc').trigger('click');
				alert(content.message);
				$('.alert_close_img').click(function(){
					window.location.reload();
				});
			}
			else
			{
				alert(content.message);
			}
		});
	}
}


//[delivery]根据省份地区ajax获取配送方式
function get_delivery(province)
{	
	$.getJSON("<?php echo IUrl::creatUrl("/block/order_delivery");?>",{"province":province,"total_weight":"<?php echo isset($this->weight)?$this->weight:"";?>","goodsSum":"<?php echo isset($this->sum)?$this->sum:"";?>"},function(content){

		//清空数据
		$('#deliveryFormTrBox').empty();

		for(var index in content)
		{	
			var deliveryTrHtml = template.render('deliveryTrTemplate',{item:content[index]});
			$('#deliveryFormTrBox').append(deliveryTrHtml);
		}

		if($.trim($('#deliveryFormTrBox').text()) == '')
		{
			alert('需要从后台添加配送方式才能下单');
			return;
		}

		//是否选中无法送达的配送方式
		if(orderFormInstance.deliveryActiveId)
		{
			var defaultDeliveryItem = $('input[type="radio"][name="delivery_id"][value="'+orderFormInstance.deliveryActiveId+'"]');
			if(defaultDeliveryItem.length > 0)
			{
				//不能送达省份时
				if(defaultDeliveryItem.attr('disabled'))
				{
					defaultDeliveryItem.attr('checked',false);

					tips('您选择的省份当前的配送方式不能送达！请重新选择配送方式');

					//切换视图方式
					if(orderFormInstance.deliveryMod == 'exit')
					{
						orderFormInstance.deliveryModToggle();
					}
					return;
				}

				defaultDeliveryItem.trigger('click');

				//默认配送方式
				if($('#paymentBox:hidden').length == 1 && orderFormInstance.paytype == 0)
				{
					orderFormInstance.deliverySave();
				}
			}
		}
	});
}

//添加代金券
function add_ticket()
{
	var ticket_num = $('#ticket_num').val();
	var ticket_pwd = $('#ticket_pwd').val();

	if(ticket_num == '' || ticket_pwd == '')
	{
		alert('请填写卡号和密码');
		return '';
	}

	$.getJSON('<?php echo IUrl::creatUrl("/block/add_download_ticket");?>',{"ticket_num":ticket_num,"ticket_pwd":ticket_pwd},function(content){
		if(content.isError == false)
		{
			is_success = true;
			$('[name="ticket_id"]').each(
				function()
				{
					if($(this).val() == content.data.id)
					{
						alert('代金券已经存在，不要重复添加');
						is_success = false;
					}
				}
			);

			if(is_success)
			{
				var ticketHtml = template.render('ticketTrTemplate',{item:content.data});
				$('#ticket_show_box').append(ticketHtml);
				$('[name="ticket_id"]').attr('checked',true);
				$('[name="ticket_id"]:last').trigger('click');
			}
			$('#ticket_num').val('');
			$('#ticket_pwd').val('');
		}
		else
		{
			alert(content.message);
		}
	});
}

//取消红包
function cancel_ticket()
{	
	$('#ticket_a').trigger('click');
	$('[name="ticket_id"]').attr('checked',false);
	orderFormInstance.doAccount();
}

get_delivery("广东");
</script>
		<form action='<?php echo IUrl::creatUrl("/wap/cart3");?>' method='post' name='order_form' callback='orderFormInstance.isSubmit();'>
		<input type='hidden' name='timeKey' value='<?php echo time();?>' />
		<input type='hidden' name='direct_gid' value='<?php echo isset($this->gid)?$this->gid:"";?>' />
		<input type='hidden' name='direct_type' value='<?php echo isset($this->type)?$this->type:"";?>' />
		<input type='hidden' name='direct_num' value='<?php echo isset($this->num)?$this->num:"";?>' />
		<input type='hidden' name='direct_promo' value='<?php echo isset($this->promo)?$this->promo:"";?>' />
		<input type='hidden' name='direct_active_id' value='<?php echo isset($this->active_id)?$this->active_id:"";?>' />
		<input type="hidden" name="taxes"  value="<?php echo isset($this->goodsTax)?$this->goodsTax:"";?>" />
		<div class="state-box"> 
		<?php if(count($this->addressList)<1){?>
			<div class="cart-in" style="display: block;">
				<a id='add_address'>
					<div class="cart-addr"><img src="/views/default/wap/mobile/version/img/app-addr.png"/></div>
					<div class="cart-t">您还没有添加新地址，请点击添加</div>
					<div class="cart-rig"><i></i></div>
				</a>
			</div>
		<?php }?>
				<?php if(count($this->addressList)>0){?>
				<div class="cart-on" id="address_show_box" style='display:none'>
						<div id="addressShowBox"></div>
						<script type='text/html' id='addressShowTemplate'>
						<a>
						<div class="cart-addr-1"><img src="/views/default/wap/mobile/version/img/app-addr.png"/></div>
						<div class="cart-t-1">
						<p>收货人：<span><%=accept_name%></span></p>
						<p>联系电话：<span><%=mobile%></span></p>
						<div>收货地址：<span><%=province_val%> <%=city_val%> <%=area_val%>&nbsp;<%=address%></span></div>
						</div>
						<div class="cart-rig1" style="float: right;"><i></i></div>
				    	</a>				
						</script>				
				</div>
				<?php }?>
					<!--保存完显示的地址end-->
					
					<!--收货表单信息 开始-->
					<div class="prompt_4 m_10" id='address_often' style='display:none'>
						<div class="addr-dz"></div>
						<ul class="addr_list" style="margin-left: 10px;font-size: 16px;">
							<?php foreach($this->addressList as $key => $item){?>
							<li>
								<label style="cursor: pointer;"><input class="radio" name="radio_address" type="radio" value="<?php echo isset($item['id'])?$item['id']:"";?>" onclick='orderFormInstance.addressSelected(<?php echo JSON::encode($item);?>);' /><?php echo isset($item['accept_name'])?$item['accept_name']:"";?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo isset($item['province_val'])?$item['province_val']:"";?> &nbsp;<?php echo isset($item['city_val'])?$item['city_val']:"";?>&nbsp; <?php echo isset($item['area_val'])?$item['area_val']:"";?>&nbsp; <?php echo isset($item['address'])?$item['address']:"";?>&nbsp;<?php echo isset($item['mobile'])?$item['mobile']:"";?></label>
							</li>
							<?php }?>
							<input style="display:none" type='radio' name='radio_address' onclick='orderFormInstance.addressEmpty();' value='' />
						</ul>
						<!--收货地址项模板-->
						<script type='text/html' id='addressLiTemplate'>
						<li>
							<label style='cursor: pointer;'><input class="radio" name="radio_address" type="radio" value="<%=item['id']%>" onclick='orderFormInstance.addressSelected(<%=jsonToString(item)%>);' /><%=item['accept_name']%>&nbsp;&nbsp;&nbsp;&nbsp;<%=item['province_val']%>&nbsp;<%=item['city_val']%>&nbsp;<%=item['area_val']%>&nbsp;<%=item['address']%>&nbsp;<%=item['mobile']%></label>
						</li>
						</script>
					</div>
					
				<ul class="pla-ul" id='address_form' style="width: 700px;">
					<li><em>*</em>姓&nbsp;&nbsp;名：<span class="pla-input"><input type="text" name="accept_name" /></span> </li>
					<li><em>*</em>手&nbsp;&nbsp;机：<span class="pla-input"><input type="text"  name='mobile' /> </span></li>
					<select name="province" child="city,area" onchange="areaChangeCallback(this);"></select>&nbsp;
					<select name="city" child="area" parent="province" onchange="areaChangeCallback(this);"></select>&nbsp;
					<select name="area" parent="city" pattern="required" alt="请选择收货地区"></select>
					<li><span class="pla-input pla-left"><input type="text" name='address' /> </span> </li>
				</ul>	
				
		</div>
		
		<div class="cart-tab">
			<?php foreach($this->productList as $key => $item){?>
				<?php if(intval(IReq::get('num')) > $item['store_nums'] || intval(IReq::get('num')) < 0){?>
					<?php IError::show(403,'购买的商品数量不正确或者大于库存量');?>
				<?php }?>						
			<div class="cart-line">
				<div class="cart-l"><img src="<?php echo IUrl::creatUrl("")."";?><?php echo Thumb::get($item['img'],66,66);?>"/> </div>
				<div class="cart-r">
					<h2><?php echo isset($item['name'])?$item['name']:"";?></h2>
					<p>价格：<span>&#65509;<?php echo isset($item['sell_price'])?$item['sell_price']:"";?></span></p>
					<p>数量：<span><?php echo isset($item['count'])?$item['count']:"";?></span></p>												
						<!-- <div class="cart-m">
							<a class="min"><img src="../version/img/app-red.png"></a>
							<input type="text" value="1">
							<a class="add"><img src="../version/img/app-add.png"></a>
						</div>-->		
				</div>
			</div>
			<ul class="cart-zd">
				<li>
					<div class="cart-zdli">商品总价：<span>&#65509;<?php echo isset($item['sum'])?$item['sum']:"";?></span></div>					
				</li>				
			</ul>
			<?php }?>
		</div>
		  
		<div class="cart-list">
			<ul class="cart-ul">
				<li>
					<div class="cart-wl"><img src="/views/default/wap/mobile/version/img/app-kd.png"/><span>配送方式</span></div>
					<div class="cart-wr" id="deliveryFormTrBox"><span>公司包邮</span><i></i></div>				
						<script type='text/html' id='deliveryTrTemplate'>
							<span><input style="display:none" checked="checked" type="radio" name="delivery_id" paytype="<%=item['type']%>" alt="<%=item['price']%>" value="<%=item['id']%>" <%if(item['if_delivery'] == 1){%>disabled="disabled" title="无法送达"<%}%> onclick='orderFormInstance.deliverySelected(<%=jsonToString(item)%>);' />公司包邮<span><i></i>
						</script>
						<label class='attr' style='display:none'><input type='radio' name='accept_time' checked="checked" value='任意' />任意</label>
				</li>
				<li style="display:none">
					<div class="wrap_box" id='paymentBox' style='display:block;margin-top: -50px;'>
					<div class="pla-p1">支付方式</div>					
					<div class="pla-cin1" style="margin-bottom: 15px;"></div>

					<table width="100%" class="border_table" id='payment_form'>
						<col width="200px" />
						<col />
						<?php if($this->user['user_id']){?>
						<?php $query = new IQuery("payment");$query->where = "status = 0";$paymentList = $query->find();?>
						<?php }else{?>
						<?php $query = new IQuery("payment");$query->where = "class_name  !=  'balance' and status = 0";$paymentList = $query->find();?>
						<?php }?>
						<?php foreach($paymentList as $key => $item){?>
						<?php $paymentPrice = CountSum::getGoodsPaymentPrice($item['id'],$this->sum);?>
						<tr>
							<th><label><input class="radio" checked="checked" name="payment" alt="<?php echo isset($paymentPrice)?$paymentPrice:"";?>" onclick='orderFormInstance.paymentSelected(<?php echo JSON::encode($item);?>);' title="<?php echo isset($item['name'])?$item['name']:"";?>" type="radio" value="<?php echo isset($item['id'])?$item['id']:"";?>" /><?php echo isset($item['name'])?$item['name']:"";?></label></th>
							<td><?php echo isset($item['note'])?$item['note']:"";?> 支付手续费：￥<?php echo isset($paymentPrice)?$paymentPrice:"";?></td>
						</tr>
						<?php }?>
					</table>

					<table class="form_table" id="payment_show_box" style='display:none;margin-top: -15px;overflow: hidden;margin-left: 41px;font-size: 16px;'>
						<col width="120px" />
						<col />
						<tbody id="paymentShowBox"></tbody>
					</table>

					<!--支付方式模板-->
					<script type='text/html' id='paymentShowTemplate'>
						<tr>
							<th style='font-size: 16px;'>支付方式：</th>
							<td><%=name%></td>
						</tr>
					</script>

					<!--  <label class="btn_orange3" id='payment_save_button'><input type="button" onclick="orderFormInstance.paymentSave();" value="保存支付方式" /></label>-->
				</div>
				</li>
				
				<li>
					<div class="cart-wl"><img src="/views/default/wap/mobile/version/img/app-juan.png"/><span>优惠券</span></div>
					<div class="cart-wr"><span>0.00元</span><i></i></div>
				</li>
				<li>
					<div class="cart-wd"><img src="/views/default/wap/mobile/version/img/app-fm.png"/><span>F码</span></div>
					<!--输入F码验证-->
					<div class="cart-wc" style="display: block">
						<div class="cart-ipu"><input type="tel" name="fcode" value="" alt = '0.00' placeholder="如有F码，请在这填写" /></div>
						<div class="cart-yz"><input type="button" id="fcode_sure"  value="验证" /></div>
					</div>
					<!--F码可用-->
					<div class="cart-fm" style="display: none;">F码已使用，获得1T存储碟</div>
				</li>
				
				<li>   
					<div class="cart-ak">
						<div class="cart-wl"><img src="/views/default/wap/mobile/version/img/app-fp.png"/><span>发票</span></div>
						<div class="cart-wt"><span>不开发票</span><i></i></div>
					</div>
					<div style="clear: both;display: none;" class="cart-km">
						<ul class="cart-kt">
							<li><input type="radio" name="is_note" id="ra_b1" checked="checked" value="0" alt='不开发票'/><label for="ra_b1">不开发票</label></li>
							<li><input type="radio" name="is_note" id="ra_b2" value="1" alt='需要发票'/><label for="ra_b2">需要发票</label></li>
						</ul>
						<div class="cart-kc">
							<input type="text" name="tax_title" id="" value="" placeholder="请输入个人姓名或者公司名称" />
						</div>
					</div>
				</li>
				<li>
					<textarea rows="3" cols="10" name='message' onblur="if(this.innerHTML==''){this.innerHTML='有特殊请求请留言（限30字）';this.style.color='#a3a3a3'}" onfocus="if(this.innerHTML=='有特殊请求请留言（限30字）'){this.innerHTML='';this.style.color='#a3a3a3'}">有特殊请求请留言（限30字）</textarea>
				</li>
			</ul>
		</div>
		
		<div class="cart-pm"> 
			<div class="cart-pl">
				<ul class="cart-pw">
					<li>商品总价格：<span>&#65509;<?php echo isset($this->final_sum)?$this->final_sum:"";?></span></li>
					<?php foreach($this->productList as $key => $item){?>
					<li>现金劵抵扣：<span>&#65509;<?php echo isset($item['reduce'])?$item['reduce']:"";?></span></li>
					<?php }?>
					<li>运费：<span>&#65509;0.00</span></li>
					<li>应付总额：<span>&#65509;<span id="final_sum_first"></span></span></li>
				</ul>
			</div>
		</div>
		<div class="cart-tm">
			<div class="cart-te">应付总额：<span>&#65509;<span id="final_sum"></span></span></div>
			<div class="cart-ti"><input type="submit"  value="提交订单" /> </div>
		</div>
		</form>
		<?php if(count($this->addressList)<1){?>
		<div class="addr-rape" style="display: none;">
			<form action="" method="POST" name='address_form'>
			<div class="add_address addr-tab">
				<div class="content">
				    <div class="ac_body">
						<ul class="addr-ul acb_left">
		                    <input type="hidden" name="id" value="">
		                    <input type="hidden" name="default" value="">     
							<li>
								<div class="addr-in">
									<label>收货人</label>
									<div class="addr-input"><input type="text" id='address_accept_name' name="accept_name" value="" placeholder="请填写" /></div>
								</div>
							</li>
							<li>
								<div class="addr-in">
									<label>联系电话</label>
									<div class="addr-input"><input type="text" id='address_mobile' name="mobile" value="" placeholder="请填写" /></div>
								</div>
							</li>
							<li>
								<div class="addr-in">
									<p>省份</p>
									<div class="addr-input">
										<select name="province" id='address_province' child="city,area" onchange="areaChangeCallback(this);"></select>							
									</div>
								</div>
							</li>
							<li>
								<div class="addr-in">
									<p>城市</p>
									<div class="addr-input">
										<select name="city" id='address_city' child="area" parent="province" onchange="areaChangeCallback(this);"></select>							
									</div>
								</div>
							</li> 
							<li>
								<div class="addr-in">
									<p>区/县</p>
									<div class="addr-input">
										<select name="area" id='address_area' parent="city"></select>							
									</div>
								</div>
							</li>
								<div class="addr-in">
									<label>街道地址</label>
									<div class="addr-input"><input maxlength="30" type="text" id="address_detail" name="address" value="" placeholder="请填写" /></div>
								</div>
							</li>
						</ul>
				</div>
			</div>
			<div class="addr-but">
				<input type="button"  id="sure_add" value="保存" />
			</div>
		</div>
		</form>
	</div>
	<?php }?>
		
		<script type='text/javascript'>
			$('#add_address').click(function(){
				$('.addr-rape').show();
				orderFormInstance.provinceMenuInit();
				$('#sure_add').click(function(){
					var accept_name = $('#address_accept_name').val();
					var mobile = $('#address_mobile').val();
					var province = $('#address_province').val();
					var city = $('#address_city').val();
					var area = $('#address_area').val();
					var address_detail = $('#address_detail').val();
					var patt = /^(13|14|15|17|18)\d{9}$/;
					if(accept_name == ''){
						alert('请填写收货人姓名');
						return false;
					}else if(!patt.test(mobile)){
						alert('联系电话输入不正确');
						return false;
					}else if(province == ''){
						alert('请选择省份');
						return false;
					}else if(city == ''){
						alert('请选择城市');
						return false;
					}else if(area == ''){
						alert('请选择地区');
						return false;
					}else if(address_detail == ''){
						alert('请填写收货地址');
						return false;
					}
					
					$.getJSON('<?php echo IUrl::creatUrl("/wap/address_add2");?>',$('form[name="address_form"]').serialize(),function(content){
						if(content.isError == false)
						{	
							window.location.reload();
						}
						else
						{
							alert(content.message);
						}
					});
				});
			});
		</script>
		
		<script type="text/javascript">
			$(document).ready(function(){
				$('#fcode_sure').click(function(){
					if($('input[name="fcode"]').val() == ''){
						alert('请输入F码');
						return false;
					}
					$.ajax({
						url:"<?php echo IUrl::creatUrl("/wap/checkfcode");?>",
						data:{"fcode":$('input[name="fcode"]').val()},
						dataType:"json",
						success:function(d){
							if(d.status == '0'){
								alert(d.message);
								return false;
							}else{
								$('.cart-fm').empty();
								$('input[name="fcode"]').attr("alt",d.message);
								orderFormInstance.doAccount();
							}
						}
					});
				});
			});
		</script>
		<script>
			$(document).ready(function(){
				$(".cart-ak").click(function(){
					$(".cart-km").toggle(500);
				});
			
			 
			$(".cart-kc").hide();
			$(":radio[name='is_note']").click(function() {
				var obj = $('input:radio[name="is_note"]:checked').attr('alt');
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


		
		
