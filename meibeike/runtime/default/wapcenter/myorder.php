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
		
		<script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate.js"></script><script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate-plugin.js"></script>
<style>
body{background: #F1F1F1;}
.order-pay input{cursor:pointer;}
</style>
		<div class="order-nav" style="display: block;">
			<?php  $user_id = $this->user['user_id']?>
									<?php  $order_cnt = Api::run('getMyOrderCnt', $user_id)?>
									<?php if( $order_cnt == 0){?>
							        <div class="no_order" style="display: none;">
							            <div class='content'>
							                <label>您目前还没有订单，赶紧购买云棒吧！</label>
							                <!--<a href='<?php echo IUrl::creatUrl("/site/book");?>'>马上去购物</a>-->
							                <a href='<?php echo IUrl::creatUrl("/wap/product_collo/id/10");?>' target="_blank">马上去购物</a>
							            </div>
							        </div>
							        <?php }else{?>
							        <?php  $orders = Api::run('getMyOrderList', $user_id)?>
							        <?php foreach($orders as $key => $item){?>
							        <?php  $i_attr = JSON::decode(str_replace('母棒:母棒','母棒*1',$item['goods_array']))?>
							        <?php $orderStatus = Order_Class::getOrderStatus($item)?>
							        <!--商品详情-->
							        <?php  $goods_info = Api::run('getGoodsList',$item['goods_id'])?>
							        <?php  $delivery_list = Api::run('getDeliveryList',array("#oid#",$item['id']))?>
     								 <input type="hidden" name="totalmoney" value="<?php echo isset($item['order_amount'])?$item['order_amount']:"";?>">
						            <input type="hidden" name="order_id" value="<?php echo isset($item['id'])?$item['id']:"";?>">
						            <input type="hidden" name="invoice_type" value="<?php echo isset($item['invoice_type'])?$item['invoice_type']:"";?>">
						            <input type="hidden" name="invoice_title" value="<?php echo isset($item['invoice_title'])?$item['invoice_title']:"";?>">
						            <input type="hidden" name="accept_name" value="<?php echo isset($item['accept_name'])?$item['accept_name']:"";?>" />
						            <input type="hidden" name="postcode" value="<?php echo isset($item['postcode'])?$item['postcode']:"";?>" />
						            <input type='hidden' name='telphone' value="<?php echo isset($item['telphone'])?$item['telphone']:"";?>" />
						            <input type='hidden' name='province' value="<?php echo isset($item['province'])?$item['province']:"";?>" />
						            <input type='hidden' name='city' value="<?php echo isset($item['city'])?$item['city']:"";?>" />
						            <input type='hidden' name='area' value="<?php echo isset($item['area'])?$item['area']:"";?>" />
						            <input type='hidden' name='address' value="<?php echo isset($item['address'])?$item['address']:"";?>" />
						            <input type='hidden' name='mobile' value="<?php echo isset($item['mobile'])?$item['mobile']:"";?>" />
						            <input type='hidden' name='accept_time' value="<?php echo isset($item['accept_time'])?$item['accept_time']:"";?>" />
						            <input type='hidden' name='goods_name' value="<?php echo isset($i_attr['name'])?$i_attr['name']:"";?>" />
						            <input type='hidden' name='goods_attr' value="<?php echo isset($i_attr['value'])?$i_attr['value']:"";?>" />
						            <input type='hidden' name='goods_price' value="<?php echo isset($item['goods_price'])?$item['goods_price']:"";?>" />
						            <input type='hidden' name='goods_nums' value="<?php echo isset($item['goods_nums'])?$item['goods_nums']:"";?>" />
						            <input type='hidden' name='order_amount' value="<?php echo isset($item['order_amount'])?$item['order_amount']:"";?>" />
						            <input type='hidden' name='goods_img' value="<?php echo isset($item['img'])?$item['img']:"";?>" />
						            <input type='hidden' name='goods_no' value='<?php echo isset($goods_info[0]["goods_no"])?$goods_info[0]["goods_no"]:"";?>' />
						
						            <!--售后表单-->
						            <form name="service_order" method="post" action="<?php echo IUrl::creatUrl("/ucenter/apply_service");?>">
						                <input type="hidden" name="order_id" value="<?php echo isset($item['id'])?$item['id']:"";?>" />
						            </form>
									 <?php $progress = 1?>
						            <?php if($item['status']==2 && $item['pay_status']==1 && $item['distribution_status'] == 0){?>
						            <?php $progress = 2?>
						            <?php }elseif($item['status']==2 && $item['pay_status']==1 && $item['distribution_status'] == 1){?>
						            <?php $progress = 3?>
						            <?php }elseif($item['status']==2 && $item['pay_status']==1 && $item['distribution_status'] == 2){?>
						            <?php $progress = 4?>
						            <?php }elseif($item['status']==5){?>
						            <?php $progress = 5?>
						            <!--订单取消状态-->
						            <?php }elseif($item['status']==3){?>
						            <?php $progress = 5?>
						            <!--订单售后状态-->
						            <?php }elseif($item['status']==7){?>
						            <!--取服务订单信息-->
						            <?php $service_info = Api::run('getServiceOrder', $item['id'])?>
						            <?php if($service_info['status'] <= 3){?>
						            <?php $progress = 1?>
						            <?php }elseif($service_info['status'] == 4){?>
						            <?php $progress = 2?>
						            <?php }elseif($service_info['status'] == 5){?>
						            <?php $progress = 3?>
						            <?php }elseif($service_info['status'] == 6){?>
						            <?php $progress = 4?>
						            <?php }elseif($service_info['status'] == 7){?>
						            <?php $progress = 5?>
						            <?php }?>
						            <?php }?>
						            <input type="hidden" order_id="<?php echo isset($item['id'])?$item['id']:"";?>" class="order_p_class" name="order_progress" value="<?php echo isset($progress)?$progress:"";?>"/>
						            
			<div class="order-tab">
				<div class="order-title">
					<p>订单编号：<?php echo isset($item['order_no'])?$item['order_no']:"";?></p>
					<h3><?php if($item['status'] == '1'){?>待付款<?php }elseif($item['status'] == '2'){?>支付订单<?php }elseif($item['status'] == '3'){?>已取消<?php }elseif($item['status'] == '4'){?>作废订单<?php }elseif($item['status'] == '5'){?>完成订单<?php }elseif($item['status'] == '7'){?>申请售后<?php }?></h3>
				</div>
				<div class="order-box">
					<a href='<?php echo IUrl::creatUrl("/wapcenter/orderstate/id/$item[id]");?>'>
					<div class="order-list">
						<div class="order-line">
							<div class="order-padding">
								<div class="order-img"><img src="<?php echo IUrl::creatUrl("")."";?><?php echo isset($item['img'])?$item['img']:"";?>" /></div>
								<div class="order-flt">
									<h3><?php echo isset($i_attr['name'])?$i_attr['name']:"";?>&nbsp;<?php echo str_replace(',存储碟:无','',(str_replace(',存储棒:无','',str_replace('母棒:母棒','母棒*1',$i_attr['value']))));?></h3>
									<p>
										<span>&#65509;<?php echo isset($item['order_amount'])?$item['order_amount']:"";?></span>
										<span>x<?php echo isset($item['goods_nums'])?$item['goods_nums']:"";?></span>
									</p>
								</div>
							</div>
						</div>
					</div>
					</a>											
				</div>
				<div class="order-ce">
					<div>应付金额：<span>&#65509;<?php echo isset($item['order_amount'])?$item['order_amount']:"";?></span></div>
				</div>
				<div class="order-inc">
					<?php if($orderStatus == 2){?>
					<p class='timer'>付款剩余：<span><?php echo strtotime($item['create_time'])+$diff;?></span></p>
					<div class="order-pay"><input type="submit" onclick="window.location.href='<?php echo IUrl::creatUrl("/wap/doPay/order_id/$item[id]");?>'" name="" id="" value="现在付款" /></div>
					<?php }?>
					<?php if(in_array($orderStatus,array(1,2))){?>	
					<div class="order-kk"><input type="submit" onclick="window.location.href='<?php echo IUrl::creatUrl("/wap/order_status/order_id/$item[id]/op/cancel");?>'" name="" id="" value="取消订单" /></div>
					<?php }?>
					<?php if($item['status'] == '3'){?>
					<div class="order-kk"><input type="submit" onclick="window.location.href='<?php echo IUrl::creatUrl("/wap/delete_order/order_id/$item[id]");?>'" name="" id="" value="删除订单" /></div>
					<?php }?>
					<?php if($orderStatus == 3){?>
					<div class="order-kk"><input type="submit" onclick="exdelievey()" name="" id="" value="查看物流" /></div>
					<?php }?>
				</div>
			</div>
			<?php }?>
       		<?php }?>
			
		</div>

		<div class="order-no" style='<?php if(empty($orders)){?>{echo "display:block"}<?php }else{?><?php echo  "display:none";?><?php }?>;'>
			<img src="/views/default/wap/mobile/version/img/app-nodd.png" />
			<p>暂无订单</p>
		</div>
	</body>
</html>
<script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate.js"></script><script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate-plugin.js"></script>
<script type='text/javascript' src='<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/artTemplate/area_select.js";?>'></script>
<script>
var NowTime = <?php echo isset($current)?$current:"";?>;

$('.timer span').each(function(){
	var CreateTime = $(this).html();
	var intDiff = CreateTime - NowTime;
	timer(intDiff,$(this));
});

function timer(intDiff,element){

	window.setInterval(function(){

	var day=0,

		hour=0,

		minute=0,

		second=0;//时间默认值		

	if(intDiff > 0){

		day = Math.floor(intDiff / (60 * 60 * 24));

		hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
		
		hours = hour + (day * 24)

		minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);

		second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);

	}
		
	if (day <= 9) day = '0' + day;	
		
	if (hours <= 9) hours = '0' + hours;
		
	if (minute <= 9) minute = '0' + minute;

	if (second <= 9) second = '0' + second;
	
	$(element).html('<span>'+hours+'</span>时<span>'+minute+'</span>分<span>'+second+'</span>秒');

	intDiff--;

	}, 1000);

} 




</script>	
<script type="text/javascript">
                        $().ready(function () {
							
							$("#top_a").click(function() {
								
							});

                            $(".zf_title").click(function () {
                                var obj = $(".banks_" + $(this).attr("id"));
                                if ($(obj).css("display") == "block")
                                    $(obj).css("display", "none");
                                else
                                    $(obj).css("display", "block");
                            });

                            $(".div_control_show").click(function () {
                                var is_show = $(this).attr("is_show");
                                var order_id = $(this).attr("order_id");
                                if (is_show == "1") {
                                    $("#order_all_" + order_id).css("display", "none");
                                    $(this).attr("is_show", "0");
                                    $(this).children("label.control_show").html("显示订单详情");

									$(".tanchuang").css("display", "none");
                                } else {
                                    $("#order_all_" + order_id).css("display", "block");
                                    $(this).attr("is_show", "1");
                                    $(this).children("label.control_show").html("隐藏订单详情");
                                }
                            });

                            $("#not_cancel_order").click(function () {
                                $(".cancel_box").css("display", "none");
                            })

                            $("#sure_cancel_order").click(function () {
                                $(".cancel_box").css("display", "none");
                                $("#cancel_form").submit();
                            })
                            $("#not_confirm_order").click(function () {
                                $(".confirm_box").css("display", "none");
                            })

                            $("#sure_confirm_order").click(function () {
                                $(".confirm_box").css("display", "none");
                                $("#confirm_form").submit();
                            })


                            $(".a_order_info").click(function () {
                                //数据
                                //收货信息
                                var accept_name = $(this).parents("div.has_order").children("input[name='accept_name']").val();
                                var postcode = $(this).parents("div.has_order").children("input[name='postcode']").val();
                                var province = $(this).parents("div.has_order").children("input[name='province']").val();
                                var city = $(this).parents("div.has_order").children("input[name='city']").val();
                                var area = $(this).parents("div.has_order").children("input[name='area']").val();
                                var daddress = $(this).parents("div.has_order").children("input[name='address']").val();
                                var telphone = $(this).parents("div.has_order").children("input[name='telphone']").val();
                                var mobile = $(this).parents("div.has_order").children("input[name='mobile']").val();
                                
                                var accept_time = $(this).parents("div.has_order").children("input[name='accept_time']").val();
                                $.getJSON("<?php echo IUrl::creatUrl("/block/area_name");?>", {province: province, city: city, area: area}, function (json) {
                                    //alert(json);
                                    province = json.province;
                                    city = json.city;
                                    area = json.area;

                                   
                                    var address = '收货人：' + accept_name + ", 手机号：" + mobile + "<br />"
                                            + "邮编：" + postcode + (telphone? (", 固定电话："+telphone ):'') + "<br />"
                                            + "收货地区：" + province + " " + city + " " + area + ", 详细地址：" + daddress + "<br />" 
                                            + "送货方案：" + accept_time + " 收货";
                                    $("#check_address").html(address);
                                });

                                //发票信息
                                var invoice_type = $(this).parents("div.has_order").children("input[name='invoice_type']").val();
                                var invoice_title = $(this).parents("div.has_order").children("input[name='invoice_title']").val();
                                if (invoice_type == 1) {
                                    $("#check_invoice").html("个人发票");
                                } else {
                                    $("#check_invoice").html("公司：" + invoice_title);
                                }

                                //商品信息
                                var goods_name = $(this).parents("div.has_order").children("input[name='goods_name']").val();
                                var goods_attr = $(this).parents("div.has_order").children("input[name='goods_attr']").val();
                                var goods_price = $(this).parents("div.has_order").children("input[name='goods_price']").val();
                                var goods_nums = $(this).parents("div.has_order").children("input[name='goods_nums']").val();
                                var order_amount = $(this).parents("div.has_order").children("input[name='order_amount']").val();
                                var goods_no = $(this).parents("div.has_order").children("input[name='goods_no']").val();
                                var goods_img = $(this).parents("div.has_order").children("input[name='goods_img']").val();
                                $("#check_goods_img").attr("src", "<?php echo IUrl::creatUrl("")."";?>" + goods_img);
                                $("#check_goods_name").html(goods_name + " " + goods_attr);
                                $("#check_goods_price").html("¥&nbsp;" + goods_price);
                                $("#check_goods_nums").html(goods_nums);
                                $("#check_order_amount").html("¥&nbsp;" + order_amount);
                                $("#check_order_amount_2").html("订单总计:&nbsp;&nbsp;&nbsp;&nbsp;¥&nbsp;&nbsp;" + order_amount);
                                $("#check_goods_no").html("商品编号:&nbsp;" + goods_no);

                                var win_width = parseInt($(window).width());
                                var box_left = (win_width - 1120) / 2;
                                $(".check_order_info").css("left", box_left);
                                var box_top = document.body.scrollTop + 160;
                                $(".check_order_info").css("top", box_top);
                                $(".check_order_info").css("display", "block");
                            });

                            $(".a_cancel_order").click(function () {
                                $(".tanchuang").css("display", "none");

                                var win_width = parseInt($(window).width());
                                var box_left = (win_width - 480) / 2;
                                var win_height = parseInt($(window).height());
                                var sh = $(window).scrollTop();

                                var box_top = sh + 160;
                                $(".cancel_box").css("top", box_top);
                                $(".cancel_box").css("left", box_left);
                                $(".cancel_box").css("display", "block");
                                var order_id = $(this).parents("div.has_order").children("input[name='order_id']").val();
                                $("#cancel_form").children("input[name='order_id']").val(order_id);


                            });
                            $("a.confirm_recieved").click(function () {
//                                var win_width = parseInt($(window).width());
//                                var box_left = (win_width - 480) / 2;
//                                var win_height = parseInt($(window).height());
//                                var box_top = document.body.scrollTop + 160;
                                $(".tanchuang").css("display", "none");

                                var win_width = parseInt($(window).width());
                                var box_left = (win_width - 480) / 2;
                                var win_height = parseInt($(window).height());
                                var sh = $(window).scrollTop();

                                var box_top = sh + 160;
                                $(".confirm_box").css("top", box_top);
                                $(".confirm_box").css("left", box_left);
                                $(".confirm_box").css("display", "block");
                                var order_id = $(this).parents("div.has_order").children("input[name='order_id']").val();
                                $("#confirm_form").children("input[name='order_id']").val(order_id);
                                $("#confirm_form").children("input[name='op']").val("confirm");
                            });
                            $("a.confirm_service_recieved").click(function () {
//                                var win_width = parseInt($(window).width());
//                                var box_left = (win_width - 480) / 2;
//                                var win_height = parseInt($(window).height());
//                                var box_top = document.body.scrollTop + 160;
                                $(".tanchuang").css("display", "none");

                                var win_width = parseInt($(window).width());
                                var box_left = (win_width - 480) / 2;
                                var win_height = parseInt($(window).height());
                                var sh = $(window).scrollTop();

                                var box_top = sh + 160;
                                $(".confirm_box").css("top", box_top);
                                $(".confirm_box").css("left", box_left);
                                $(".confirm_box").css("display", "block");
                                var order_id = $(this).parents("div.has_order").children("input[name='order_id']").val();
                                $("#confirm_form").children("input[name='order_id']").val(order_id);
                                $("#confirm_form").children("input[name='op']").val("service");
                            });

                            //修改发票
                            $(".a_modify_fapiao").click(function (event) {
                                $(".tanchuang").css("display", "none");
                                var order_id = $(this).parents("div.has_order").children("input[name='order_id']").val();
                                var invoice_type = $(this).parents("div.has_order").children("input[name='invoice_type']").val();
                                var invoice_title = $(this).parents("div.has_order").children("input[name='invoice_title']").val();
                                $("#invoice_form").children("input[name='order_id']").val(order_id);
                                $("#invoice_form").children("input[name='invoice_type']").val(invoice_type);
                                $("#personal_info").val(invoice_title);
                                $("#personal").attr("src", "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/selected.png";?>");
                                //$("#personal").siblings(".img_select").attr("src", "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/unselected.png";?>");
                                $("#personal_info").css("display", "block");
                                /*if (invoice_type == 1) {
                                    //个人
                                    $("#personal").attr("src", "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/selected.png";?>");
                                    $("#personal").siblings(".img_select").attr("src", "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/unselected.png";?>");
                                    $("#company_info").css("display", "none");
                                } else {
                                    //公司
                                    $("#company").attr("src", "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/selected.png";?>");
                                    $("#company").siblings(".img_select").attr("src", "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/unselected.png";?>");
                                    $("#company_info").css("display", "block");
                                }*/

                                var win_width = parseInt($(window).width());
                                var box_left = (win_width - 1200) / 2 + 705;
                                $(".fapiao").css("left", box_left);
                                var box_top = event.pageY - 200;
                                $(".fapiao").css("top", box_top);
                                $(".fapiao").css("display", "block");

                            });
                            
                            $(".a_modify_address").click(function (event) {
                                $(".tanchuang").css("display", "none");
                                //address_box数据加载
                                var order_id = $(this).parents("div.has_order").children("input[name='order_id']").val();
                                var accept_name = $(this).nextAll("[name='iaccept_name']").val();
                                var postcode = $(this).nextAll("[name='ipostcode']").val();
                                var telphone = $(this).nextAll("[name='itelphone']").val();
                                var province = $(this).nextAll("[name='iprovince']").val();
                                var city = $(this).nextAll("[name='icity']").val();
                                var area = $(this).nextAll("[name='iarea']").val();
                                var address = $(this).nextAll("[name='iaddress']").val();
                                var mobile = $(this).nextAll("[name='imobile']").val();
                                var accept_time = $(this).nextAll("[name='iaccept_time']").val();
                                //alert(2222222);
                                var father = $(".address").children(".content");
                                var left = father.children(".ac_body").children(".acb_left");
                                left.children("[name='order_id']").val(order_id);
                                left.children("[name='accept_name']").val(accept_name);
                                left.children("[name='postcode']").val(postcode);
                                left.children("[name='telphone']").val(telphone);
                                left.children("[name='address']").val(address);
                                left.children("[name='mobile']").val(mobile);
                                left.children("[name='accept_time']").val(accept_time);

                                //初始化地区选择
                                //alert(province);

                                createAreaSelect('province', 0, province);
                                createAreaSelect('city', province, city);
                                createAreaSelect('area', city, area);
                                template.compile("areaTemplate", areaTemplate);

                                //初始化发货时间
                                var sh_time = $("[name='sh_time_value'][value='" + accept_time + "']").parents(".sh_time");
                                sh_time.css("border-color", "rgb(255, 72, 0)");
                                sh_time.css("background-color", "rgb(255, 255, 255)");
                                sh_time.siblings(".sh_time").css("border-color", "rgb(223, 223, 223)");
                                sh_time.siblings(".sh_time").css("background-color", "rgb(250, 250, 250)");
                                //alert("aaaaaaaaaaaaaaaa");
                                var win_width = parseInt($(window).width());
                                var box_left = (win_width - 1200) / 2 + 70;
                                $(".address").css("left", box_left);
                                var box_top = event.pageY - 400;
                                $(".address").css("top", box_top);
                                $(".address").css("display", "block");

                                checkplaceholder();

                            });

                            $("#addr_cancel").click(function () {
                                $(".address").css("display", "none");
                            });

                            //修改订单地址
                            $("#addr_sure").click(function () {
                                //检查参数
                                var father = $(".address").children(".content");
                                var left = father.children(".ac_body").children(".acb_left");
                                var address = left.children("[name=address]").val();
                                var accept_name = left.children("[name=accept_name]").val();
                                var accept_time = left.children("[name=accept_time]").val();
                                var mobile = left.children("[name=mobile]").val();
                                var telphone = left.children("[name=telphone]").val();
                                var province = left.children("[name=province]").val();
                                var city = left.children("[name=city]").val();
                                var area = left.children("[name=area]").val();
                                var zip = left.children("[name=postcode]").val();
                                if (!address) {
                                    alert("请填写详细地址！");
                                    return 0;
                                }
                                if (!accept_time) {
                                    alert("请选择送货时间！");
                                    return 0;
                                }
                                if (!accept_name) {
                                    alert("请填写收货人！");
                                    return 0;
                                }
                                /*if ((!mobile) && (!telphone)) {
                                 alert("请填至少一种联系方式！");
                                 return 0;
                                 }*/
                                if (!mobile) {
                                    alert("请填写您的手机号码！");
                                    return 0;
                                }

                                if (validatemobilephone(mobile) == false) {
                                    alert("请填写正确的手机号码！");
                                    return 0;
                                }
                                if ((!province) || (!city) || (!area)) {
                                    alert("请选择省市信息");
                                    return 0;
                                }
                                if (!zip || zip == "邮编（必填）") {
                                    alert("请填写邮编信息");
                                    return 0;
                                }

                                if (zip && zip != left.children("[name=postcode]").attr("placeholder")) {
                                    var pattern = /^[1-9][0-9]{5}$/;
                                    if (zip.search(pattern) == -1) {
                                        alert("邮编格式错误");
                                        return 0;
                                    }
                                }
                                //固定电话检查
                                var phone = left.children("input[name=telphone]").val();
                                if (phone == left.children("input[name=telphone]").attr("placeholder")) {
                                    left.children("input[name=telphone]").val("");
                                    phone = "";
                                }
                                if (phone && phone != left.children("input[name=telphone]").attr("placeholder")) {
                                    var pattern = /^[\d-]{8,13}$/i;
                                    if (phone.search(pattern) == -1) {
                                        alert("固定电话格式错误，正确格式如:0001-20110101");
                                        left.children("input[name=telphone]").addClass("alert_input");
                                        return;
                                    }
                                }


                                $("#address_form").submit();
                                $(".address").css("display", "none");
                            });

									$("input[name=telphone]").change(function() {
										$(this).removeClass("alert_input");	
									});

                            $("#invoice_cancel").click(function () {
                                $(".fapiao").css("display", "none");
                            });

                            //修改订单地址
                            $("#invoice_sure").click(function () {
                                $("#invoice_form").submit();
                                $(".fapiao").css("display", "none");
                            });

                            $(".sh_time").click(function () {
                                //设置送货值
                                var accept_time = $(this).children("input[name='sh_time_value']").val();
                                $("input[name='accept_time']").val(accept_time);

                                $(this).css("border-color", "rgb(255, 72, 0)");
                                $(this).css("background-color", "rgb(255, 255, 255)");

                                $(this).siblings(".sh_time").css("border-color", "rgb(223, 223, 223)");
                                $(this).siblings(".sh_time").css("background-color", "rgb(250, 250, 250)");
                            });

                            $(".img_select").click(function () {
                                $(this).attr("src", "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/selected.png";?>");
                                $(this).siblings(".img_select").attr("src", "<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/unselected.png";?>");

                                if ($(this).attr("id") == "personal") {
                                    $("#company_info").css("display", "none");
                                    $("#invoice_type").val(1);
                                } else if ($(this).attr("id") == "company") {
                                    $("#company_info").css("display", "block");
                                    $("#invoice_type").val(2);
                                }


                            });

                            $("a#now_give_money").click(function () {
                                $(".tanchuang").css("display", "none");
                                var totalmoney = $(this).parents("div.has_order").children("[name='totalmoney']").val();
                                $("#totalmoney").text("¥ " + totalmoney);
                                var order_id = $(this).parents("div.has_order").children("[name='order_id']").val();
                                $("#payorder").val(order_id);

                                var win_height = parseInt($(window).height());
                                var win_width = parseInt($(window).width());

                                var sh = $(window).scrollTop();
                                var box_top = sh + 200;
                                var box_left = (win_width - 700) / 2;

                                $(".fukuan_box").css("top", box_top);
                                $(".fukuan_box").css("left", box_left);


                                $(".fukuan_box").css("display", "block");

                            });

                            $("label#close_fukuan_box").click(function () {
                                $(".fukuan_box").css("display", "none");
                            });

                            $("label#close_order_info").click(function () {
                                $(".check_order_info").css("display", "none");
                            });

                            $("label#close_fapiao").click(function () {
                                $(".fapiao").css("display", "none");
                            });

                            $("label#close_cancel_box").click(function () {
                                $(".cancel_box").css("display", "none");
                            });
                            $("label#close_confirm_box").click(function () {
                                $(".confirm_box").css("display", "none");
                            });

                            var order_ps = $(".order_p_class");
                            for (var i = 0; i < order_ps.length; i++) {
                                var order_id = $(order_ps[i]).attr("order_id");
                                var order_progress = parseInt($(order_ps[i]).val());

                                if (order_progress > 1) {
                                    progress_change(order_progress, order_id);
                                }
                            }
                        });
                        /**
                         * 生成地域js联动下拉框
                         * @param name
                         * @param parent_id
                         * @param select_id
                         */
                        function createAreaSelect(name, parent_id, select_id)
                        {

                            //生成地区
                            $.getJSON("<?php echo IUrl::creatUrl("/block/area_child");?>", {"aid": parent_id, "random": Math.random()}, function (json)
                            {
                                $('select[name="' + name + '"]').html(template.render('areaTemplate', {"select_id": select_id, "data": json}));
                            });
                        }
                        function progress_change(cur_progress, order_id) {
                            cur_progress = parseInt(cur_progress);

                            //$("#a_progress").attr("progress", cur_progress);

                            // 往后走
                            var img_obj = $(".progress_" + order_id + "> .image");
                            if (cur_progress == 2)
                                $(img_obj).css("background", "url(<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/p002.png";?>)");
                            else if (cur_progress == 3)
                                $(img_obj).css("background", "url(<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/p003.png";?>)");
                            else if (cur_progress == 4)
                                $(img_obj).css("background", "url(<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/p004.png";?>)");
                            else if (cur_progress == 5)
                                $(img_obj).css("background", "url(<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/p005.png";?>)");

                            var margin_left = 233 * (cur_progress - 1) + 3;
                            $("#img_progress_" + order_id).css("margin-left", margin_left.toString() + "px");

                            margin_left = 233 * (cur_progress - 1) + 50;
                            $("#p_pup_" + order_id).css("margin-left", margin_left.toString() + "px");

                            for (var i = 1; i <= cur_progress.toString(); i++) {
                                $("label.date_" + order_id + "_" + i).css("display", "block");
                            }

                            $(".state_title_" + order_id).css("display", "none");
                            $(".state_detail_" + order_id).css("display", "none");

                            //如果是已发货，则取发货信息
                            if (cur_progress == 4) {
                                $.post("<?php echo IUrl::creatUrl("/block/getExpressInfo");?>", {"id": order_id}, function (data)
                                {
                                    $("#detail_" + order_id + "_" + cur_progress.toString()).children("p").html(data);
                                });
                            }

                            $("#title_" + order_id + "_" + cur_progress.toString()).css("display", "block");
                            $("#detail_" + order_id + "_" + cur_progress.toString()).css("display", "block");
                        }

                        $("#a_progress").click(function () {
                            /*alert(1);
                             var cur_progress = parseInt($(this).attr("progress"));
                             cur_progress += 1;
                             progress_change(cur_progress);
                             if (cur_progress >= 5) {
                             $("#a_progress").css("display", "none");
                             return;
                             }*/
                        });

                        $("#paynow").click(function () {
                            $(".fukuan_box").css("display", "none");
                            dopay();
                            $("#pay_form").submit();

                        });

                        $("a[name='apply_service']").click(function () {
                            $(this).parents("div.has_order").children("form[name='service_order']").submit();
                        });

                        //支付类型选择
                        function choose_me($id) {
                            $("input[name='pay_type']").val($id);
                        }
                        // 支付等待
                        function dopay()
                        {
                            confirm('支付是否成功', "<?php echo IUrl::creatUrl("/ucenter/order");?>");
                        }
</script>

<script type="text/javascript">
//快递跟踪
function freightLine(doc_id)
{
    var urlVal = "<?php echo IUrl::creatUrl("/block/freight/id/@id@");?>";
    urlVal = urlVal.replace("@id@",doc_id);
    art.dialog.open(urlVal,{'title':'轨迹查询',width:'600px',height:'500px'});
}
</script>

		
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


		
		
