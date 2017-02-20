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
		
		<?php $item = $this->order_info;?>
<?php $orderStatus = Order_Class::getOrderStatus($item)?>
<div class="state-tol">
			<div class="state-zoom">
				<p>订单状态：<?php echo Order_Class::orderStatusText($orderStatus);?></p>
				<p>订单编号：<?php echo isset($item['order_no'])?$item['order_no']:"";?></p>
			</div>
		</div>
		
		<div class="state-bom">
			<a>
				<div class="state-bom-p">
					<div class="state-kd"><img src="/views/default/wap/mobile/version/img/app-kd.png"/></div>					
					<div class="state-qs">
						<p>快件正常签收， 签收人是：李晓明</p>
						<?php $orderStep = Order_Class::orderStep($item)?>
						<?php foreach($orderStep as $eventTime => $stepData){?>
						<p>下单时间：<?php echo isset($eventTime)?$eventTime:"";?></p>
						<?php }?>
					</div>
					<div class="state-rig"><i></i></div>
				</div>
			</a>
		</div>
		
		<div class="state-box">
			<div class="state-box-cen">
				<div class="state-addr"><img src="/views/default/wap/mobile/version/img/app-addr.png"/></div>
				<div class="state-addr-p">
					<p>收货人：<span><?php echo isset($item['accept_name'])?$item['accept_name']:"";?></span></p>
					<p>联系人电话：<span><?php echo isset($item['mobile'])?$item['mobile']:"";?></span></p>
					<p>收货地址：<span><?php echo isset($this->area[$item['province']])?$this->area[$item['province']]:"";?> <?php echo isset($this->area[$item['city']])?$this->area[$item['city']]:"";?> <?php echo isset($this->area[$item['area']])?$this->area[$item['area']]:"";?> <?php echo isset($item['address'])?$item['address']:"";?></span></p>
				</div>
			</div>
		</div>
	
		<div class="state-list">
			<?php $order_id=$item['id']?>
            <?php $query = new IQuery("order_goods as og");$query->join = "left join goods as go on og.goods_id = go.id";$query->where = "order_id = $order_id";$query->field = "og.*,go.point";$items = $query->find(); foreach($items as $key => $good){?>
            <?php $good_info = JSON::decode($good['goods_array'])?>
            <?php $totalWeight = $good['goods_nums'] * $good['goods_weight']?>
			<div class="state-list-l">
				<div class="state-lim">
					<div class="state-cp"><img src="/<?php echo isset($good['img'])?$good['img']:"";?>"/> </div>
					<div class="state-w">
						<p><?php echo isset($good_info['name'])?$good_info['name']:"";?></p>
						<div class="state-w-r">
							<span>&#65509;<?php echo isset($good['goods_price'])?$good['goods_price']:"";?></span><span>x<?php echo isset($good['goods_nums'])?$good['goods_nums']:"";?></span>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
		
		<div class="state-tab">
			<ul class="state-ul"> 
				<li><p>配送方式：<span>公司包邮</span></p></li>
				<li><?php if($item['invoice']==1){?>
					<p>发票：<span><?php echo isset($item['invoice_title'])?$item['invoice_title']:"";?></span></p>
					<?php }else{?><p>发票：无</p>
					<?php }?>
				</li>
				<li><?php if(!empty($item['postscript'])){?><p>备注：<span><?php echo isset($item['postscript'])?$item['postscript']:"";?></span></p>
					<?php }else{?><p>备注：无</p>
					<?php }?>
				</li>
				<!--  <li><p>F码：<span>F码已经使用</span></p></li>-->
			</ul> 
		</div>
		<div class="state-tom">
			<div class="state-tb">
				<p>商品价格：<span>&#65509;<?php echo isset($item['real_amount'])?$item['real_amount']:"";?></span></p>
				<p>现金劵抵扣：<span>&#65509;<?php echo isset($item['promotions'])?$item['promotions']:"";?></span></p>
				<p>运费：<span>&#65509;<?php echo isset($item['real_freight'])?$item['real_freight']:"";?></span></p>
				<p>应付总额：<span>&#65509;<?php echo isset($item['order_amount'])?$item['order_amount']:"";?></span></p>
			</div>
		</div>
		
		<div class="state-foter">
			<div class="state-foot">
				<div class="state-line">
					<?php if(in_array($orderStatus,array(1,2))){?>	
						<div class="state-line-l"><input onclick = "window.location.href='<?php echo IUrl::creatUrl("/wap/order_status/order_id/$item[id]/op/cancel");?>" type="submit" value="取消订单" /></div>	
					<?php }?>
					<?php if($orderStatus == 2){?>
						<div class="state-line-r"><input onclick="window.location.href='<?php echo IUrl::creatUrl("/wap/doPay/order_id/$item[id]");?>'" type="submit" value="现在付款" /> </div>
					<?php }?>
					<?php if($item['status'] == '3'){?>
						<div class="state-line-r"><input onclick="window.location.href='<?php echo IUrl::creatUrl("/wap/delete_order/order_id/$item[id]");?>'" type="submit" value="删除订单" /> </div>
					<?php }?>
					<?php if($orderStatus == 3){?>
						<div class="state-line-r"><input onclick="exdelievey()" type="submit" value="删除订单" /> </div>
					<?php }?>
				</div>
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


		
		
