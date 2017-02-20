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
		
		<div class="addr-box">
			<div class="addr-line">				
				<div class="addr-ad use_new_addr">
					<img src="/views/default/wap/mobile/version/img/app-addr.png" />
					<span>添加新地址</span>
				</div>				
			</div>
			
			<ul class="addr-list">
			<?php if( !$this->address){?>
			<div class="default" id="1">				
				<span class="rece-span use_new_addr" style="display: none;">请点击添加</span>
			</div>
			<?php }else{?> <?php $address = $this->address?> 
			<?php foreach($address as $k => $item){?> 
			<?php  $area = area::name($item['province'], $item['city'], $item['area'])?> <?php if($item["default"]==1){?>
			<input type="hidden" id="hidden_item_id" value="<?php echo isset($item['id'])?$item['id']:"";?>" /> <?php }?>
				<li class="no_default" id="<?php echo isset($item['id'])?$item['id']:"";?>">
					<p>收货人：<span><?php echo isset($item["accept_name"])?$item["accept_name"]:"";?></span></p>
					<p>联系电话：<span><?php if($item["mobile"]){?><?php echo isset($item['mobile'])?$item['mobile']:"";?><?php }else{?><?php echo isset($item['telphone'])?$item['telphone']:"";?><?php }?></span></p>
					<p>收货地址：<span><?php echo isset($area[$item['province']])?$area[$item['province']]:"";?> <?php echo isset($area[$item['city']])?$area[$item['city']]:"";?> <?php echo isset($area[$item['area']])?$area[$item['area']]:"";?> <?php echo isset($item['address'])?$item['address']:"";?></span></p>
					<h2>
						<!--设为默认-->
						<input <?php if($item['default'] == '1'){?>checked='checked'<?php }?>name="addressauto" type="radio" id="set_default_<?php echo isset($item['id'])?$item['id']:"";?>" /><label class="texts" for="set_default_<?php echo isset($item['id'])?$item['id']:"";?>"><?php if($item['default'] == '1'){?>默认地址<?php }else{?>设为默认<?php }?></label>
						<input type="hidden" name="myid" value="<?php echo isset($item['id'])?$item['id']:"";?>" />			
						
						<a class="button_edit"><img src="/views/default/wap/mobile/version/img/app-bj.png"><span style="vertical-align: middle;">编辑</span></a>
						<a class="button_delete"><img src="/views/default/wap/mobile/version/img/app-de.png"><span style="vertical-align: middle;">删除</span></a>
						<input type="hidden" name="aid" value="<?php echo isset($item['id'])?$item['id']:"";?>" />
						<input type="hidden" name="iaccept_name" value="<?php echo isset($item['accept_name'])?$item['accept_name']:"";?>" />				
						<input type='hidden' name='itelphone' value="<?php echo isset($item['telphone'])?$item['telphone']:"";?>" />
						<input type='hidden' name='iprovince' value="<?php echo isset($item['province'])?$item['province']:"";?>" />
						<input type='hidden' name='icity' value="<?php echo isset($item['city'])?$item['city']:"";?>" />
						<input type='hidden' name='iarea' value="<?php echo isset($item['area'])?$item['area']:"";?>" />
						<input type='hidden' name='iaddress' value="<?php echo isset($item['address'])?$item['address']:"";?>" />
						<input type='hidden' name='imobile' value="<?php echo isset($item['mobile'])?$item['mobile']:"";?>" />
						<input type='hidden' name='iaccept_time' value="<?php echo isset($item['accept_time'])?$item['accept_time']:"";?>" />
						<input type='hidden' name='idefault' value="<?php echo isset($item['default'])?$item['default']:"";?>" />
					</h2>
				</li>
				<?php }?>
	          <?php }?>
			</ul>
		</div>
		
		<!--新增地址-->
		
		<div class="addr-rape" style="display: none;">
			<form action="<?php echo IUrl::creatUrl("/wapcenter/address_edit");?>" method="POST" id="address_form">
			<div class="add_address addr-tab">
				<div class="content">
				    <div class="ac_body">
						<ul class="addr-ul acb_left">
							<input type="hidden" name="accept_time" value="">
		                    <input type="hidden" name="id" value="">
		                    <input type="hidden" name="default" value="">     
							<li>
								<div class="addr-in">
									<label>收货人</label>
									<div class="addr-input"><input type="text" name="accept_name" value="" placeholder="请填写" /></div>
								</div>
							</li>
							<li>
								<div class="addr-in">
									<label>联系电话</label>
									<div class="addr-input"><input type="text" name="mobile" value="" placeholder="请填写" /></div>
								</div>
							</li>
							
							<li>
								<div class="addr-in">
									<p>省份</p>
									<div class="addr-input">
										<select name="province" child="city,area" onchange="areaChangeCallback(this);"></select>							
									</div>
								</div>
							</li>
							<li>
								<div class="addr-in">
									<p>城市</p>
									<div class="addr-input">
										<select name="city" child="area" parent="province" onchange="areaChangeCallback(this);"></select>							
									</div>
								</div>
							</li> 
							<li>
								<div class="addr-in">
									<p>区/县</p>
									<div class="addr-input">
										<select name="area" parent="city"></select>							
									</div>
								</div>
							</li>
							<li>
								<div class="addr-in">
									<label>街道地址</label>
									<div class="addr-input"><input maxlength="30" type="text" name="address" value="" placeholder="请填写" /></div>
								</div>
							</li>
						</ul>
				</div>
			</div>
			<div class="addr-but">
				<input type="button" name="" id="sure_add" value="保存" />
			</div>
		</div>
		</form>
	</div>
		
		<form id="delete_form" action="<?php echo IUrl::creatUrl("/wapcenter/address_del");?>" method="post">
		    <input type="hidden" name="id" value="" />
		</form>
		
		<script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate.js"></script><script type="text/javascript" charset="UTF-8" src="/runtime/_systemjs/artTemplate/artTemplate-plugin.js"></script>
<script type='text/javascript' src='<?php echo IUrl::creatUrl("")."views/".$this->theme."/javascript/artTemplate/area_select.js";?>'></script>
		<script type="text/javascript">
                        $("#a_modify_info").click(function () {
                            var dont_show_this = $(".dont_show_account_manager").val();
                            if (dont_show_this) {
                                return false;
                            }

                            var pagesize = getPageSize();

                            var height = $("body").eq(0).height();
                            var width = $(window).width();

                            var show_top = 200;
                            var show_left = (width - 700) / 2;

                            $(".modify_info_all").css("left", show_left);
                            $(".modify_info_all").css("top", show_top);

                            $(".modify_info_bg_all").height(pagesize[1]);
                            $(".modify_info_bg_all").css("display", "block");
                            $(".modify_info_all").css("display", "block");
                            //$("body").css("overflow", "hidden");
                            return false;
                        });


                        $(".button_delete").click(function () {                       	
                            //alert("确认删除?")                                
                            var aid = $(this).nextAll("[name='aid']").val();
                            $("#delete_form").children("[name='id']").val(aid);
                            $("#delete_form").submit();
                             
                        });

                        $(".button_edit").click(function () {
                        	//$(".lable2").css("display","block");
                        	//$(".lable1").css("display","none");
                            //address_box数据加载
                            var aid = $(this).nextAll("[name='aid']").val();
                            var accept_name = $(this).nextAll("[name='iaccept_name']").val();
//                          var zip = $(this).nextAll("[name='izip']").val();
                            var telphone = $(this).nextAll("[name='itelphone']").val();
                            var province = $(this).nextAll("[name='iprovince']").val();
                            var city = $(this).nextAll("[name='icity']").val();
                            var area = $(this).nextAll("[name='iarea']").val();
                            var address = $(this).nextAll("[name='iaddress']").val();
                            var mobile = $(this).nextAll("[name='imobile']").val();
                            var accept_time = $(this).nextAll("[name='iaccept_time']").val();
                            var isdefault = $(this).nextAll("[name='idefault']").val();
                            var father = $(".add_address").children(".content");
                            var left = father.children(".ac_body").children(".acb_left");
                           
                            $("div").find("[name='id']").val(aid);
                            $("div").find("[name='accept_name']").val(accept_name);
                           // left.children("[name='accept_name']").val(accept_name);
//                          left.children("[name='zip']").val(zip);
                            $("div").find("[name='telphone']").val(telphone);
                            $("div").find("[name='address']").val(address);
                            $("div").find("[name='mobile']").val(mobile);
                            $("div").find("[name='accept_time']").val(accept_time);
                            $("div").find("[name='default']").val(isdefault);

                            //初始化地区选择
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
                            $(".addr-rape").css("display","block");
                           // show_address_box();
                        });

                        /*$(".order_required").change(function(){
                         if($(this).val() == '' || $(this).val() == 0){
                         return;
                         }else{
                         $(this).removeClass("alert_input");
                         }
                         })*/

                        $("#sure_add").click(function () {
                            //检查地址是否留空
                            /*$(".order_required").each(function () {
                             if ($(this).val() == '' || $(this).val() == 0) {
                             window.scrollTo(0, 100);
                             $(this).addClass("alert_input");
                             }
                             })
                             */
                            var address = $("[name=address]").val();
                            var accept_name = $("[name=accept_name]").val();
                            var accept_time = $("[name=accept_time]").val();
                            var mobile = $("[name=mobile]").val();
                            var telphone = $("[name=telphone]").val();
                            var province = $("[name=province]").val();
                            var city = $("[name=city]").val();
                            var area = $("[name=area]").val();
//                          var zip = $("[name=zip]").val();
                            if (!address) {
                                alert("请填写详细地址！");
                                return 0;
                            }
//                          if (!accept_time) {
//                              alert("请选择送货时间！");
//                              return 0;
//                          }
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

//                          if (validatemobilephone(mobile) == false) {
//                              alert("请填写正确的手机号码！");
//                              return 0;
//                          }
                            if ((!province) || (!city) || (!area)) {
                                alert("请选择省市信息");
                                return 0;
                            }
//                          if (!zip || zip == "邮编（必填）") {
//                              alert("请填写邮编信息");
//                              return 0;
//                          }
//
//                          if (zip && zip != $("[name=zip]").attr("placeholder")) {
//                              var pattern = /^[1-9][0-9]{5}$/;
//                              if (zip.search(pattern) == -1) {
//                                  alert("邮编格式错误");
//                                  return 0;
//                              }
//                          }

                            //固定电话检查
                            var phone = $("input[name=telphone]").val();
                            if (phone == $("input[name=telphone]").attr("placeholder")) {
                                $("input[name=telphone]").val("");
                                phone = "";
                            }
                            if (phone && phone != $("input[name=telphone]").attr("placeholder")) {
                                var pattern = /^[\d-]{8,13}$/i;
                                if (phone.search(pattern) == -1) {
                                    alert("固定电话格式错误，正确格式如:0001-20110101");
                                    $("input[name=telphone]").addClass("alert_input");
                                    failed = 1;
                                    return;
                                }
                            }


                            $("#address_form").submit();
                           // $("body").css("overflow", "");
                            $(".addr-rape").css("display","none");
                            $(".add_address_bg").css("display", "none");
                            $(".add_address").css("display", "none");
                        })

							$("input[name=telphone]").change(function() {
								$(this).removeClass("alert_input");	
							});

                        $("#cancel_add").click(function () {
                            $("body").css("overflow", "");
                            $(".add_address_bg").css("display", "none");
                            $(".add_address").css("display", "none");
                        })

                        $("#close_img").click(function () {
                            $("body").css("overflow", "");
                            $(".add_address_bg").css("display", "none");
                            $(".add_address").css("display", "none");

                        })


                        $(".sh_time").click(function () {
                            $(this).css("border-color", "rgb(255, 72, 0)");
                            $(this).css("background-color", "rgb(255, 255, 255)");

                            $(this).siblings(".sh_time").css("border-color", "rgb(223, 223, 223)");
                            $(this).siblings(".sh_time").css("background-color", "rgb(250, 250, 250)");
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
                                $('[name="' + name + '"]').html(template.render('areaTemplate', {"select_id": select_id, "data": json}));
                            });
                        }
//                      function show_address_box() {
//                          var pagesize = getPageSize();
//                          var height = $(window).height();
//                          var width = $(window).width();
//
//                          var show_top = ($("body").eq(0).height() - 570) / 2;
//                          if (show_top < 0)
//                              show_top = 100;
//                          var show_left = (width - 500) / 2;
//
//                          $(".add_address").css("left", show_left);
//                          $(".add_address").css("top", show_top);
//
//                          $(".add_address_bg").height(pagesize[1]);
//                          $(".add_address_bg").css("display", "block");
//                          $(".add_address").css("display", "block");
//                          //$("body").css("overflow", "hidden");
//
//                          checkplaceholder();
//                      }



                        $(".use_new_addr").click(function () {
                        	
                        	$(".lable1").css("display","block");
                        	$(".lable2").css("display","none");
                            //address_box数据清空
                            var father = $(".add_address").children(".content");
                            var left = father.children(".ac_body").children(".acb_left");
                            left.children("[name='id']").val('');
                            left.children("[name='accept_name']").val('');
//                          left.children("[name='zip']").val('');
                            left.children("[name='telphone']").val('');
                            left.children("[name='address']").val('');
                            left.children("[name='mobile']").val('');
                            //初始化收货时间
                            left.children("[name='accept_time']").val('');
                            $(".sh_time").css("border-color", "rgb(223, 223, 223)");
                            $(".sh_time").css("background-color", "rgb(250, 250, 250)");
                            left.children("[name='default']").val(0);

                            //初始化发货时间 默认不限时间
                            var sh_time = $("[name='sh_time_value'][value='不限送货时间']").parents(".sh_time");
                            sh_time.css("border-color", "rgb(255, 72, 0)");
                            sh_time.css("background-color", "rgb(255, 255, 255)");
                            sh_time.siblings(".sh_time").css("border-color", "rgb(223, 223, 223)");
                            sh_time.siblings(".sh_time").css("background-color", "rgb(250, 250, 250)");
                            $("input[name='accept_time']").val("不限送货时间");

                            //初始化地区选择
                            createAreaSelect('province', 0, '');
                            $('[name="city"]').empty();
                            $('[name="area"]').empty();
                            template.compile("areaTemplate", areaTemplate);
							$(".addr-rape").show();
                           // show_address_box();
                        });

                        $(".menu_a").click(function () {
                            $(".menu_a").removeClass("selected");
                            $(this).addClass("selected");
                        });

//                      $(".no_default").mouseover(function () {
//                          var id = $(this).attr("id");
//                          $("#set_default_" + id).css("display", "block");
//                      }).mouseout(function () {
//                          var id = $(this).attr("id");
//                          $("#set_default_" + id).css("display", "none");
//                      });

                        function register_default() {
                            //设置默认
                            $(":radio[name='addressauto']").click(function () { 
                            	//$('input[name="addressauto"]').attr(checked,true);
                            	var index = $(":radio[name='addressauto']").index($(this));
  
                            	if($('input[name="addressauto"]').is(':checked')){                         		
                            		$('.texts').eq(index).html('默认地址');    
                            		
                            		var address_id = $('input[name="myid"]').eq(index).val();
                            		//alert(address_id);
                            	}
                                
                                $.post("<?php echo IUrl::creatUrl("/ucenter/address_set_default");?>", {address_id: address_id}, function (ret) {
                                    if (ret == 'success') {
                                        //成功
                                        
                                        alert("修改成功");                                        
                                        window.location.reload();
     
                                    } else {
                                        alert("修改失败");
                                        $(window).reload();
                                    }
                                })

                            });
                        }

                        register_default();
                        
                        $().ready(function () {
   
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


		
		
