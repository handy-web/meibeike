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
		
		<?php $status = $_GET['status']?>
<div class="fcode">
			<ul class="fcode-line"> 
				<li onclick = "window.location.href='/wapcenter/myfcode/status/0'" <?php if($status == '0'){?>class="act"<?php }?>>可用F码</li>
				<li onclick = "window.location.href='/wapcenter/myfcode/status/1'" <?php if($status == '1'){?>class="act"<?php }?>>已用F码</li>
				<li onclick = "window.location.href='/wapcenter/myfcode/status/2'" <?php if($status == '2'){?>class="act"<?php }?>>已过期</li>
			</ul>
		</div> 
		
		<div class="fcode-bog">
			<span><a href="<?php echo IUrl::creatUrl("/wapcenter/myfcode_rule");?>">F码使用规则</a></span>
			<span><a class="sf">申领F码</a></span>
		</div>
		<div class="fcode-list" style="clear: both;">
			<!--可用F码-->
			<div class="hide show">
				<?php foreach($flist as $key => $item){?>
				<div class="fcode-tab">
					<div class="fcode-bg">
						<div class="fm">F码：<?php echo isset($item['f_code'])?$item['f_code']:"";?></div>
						<div class="fx">
							<div class="fut1"><a>分享给朋友</a></div>
							<?php if($item['is_shared'] == '1'){?><div class="fut2"><a>已分享</a></div><?php }else{?><div class="fut2"><a onclick="mark('<?php echo isset($item["f_code"])?$item["f_code"]:"";?>')">标记已分享</a></div>	<?php }?>					
						</div>
					</div>
					<div class="fcode-time">
						<p><?php echo isset($item['start_time'])?$item['start_time']:"";?>至<?php echo isset($item['end_time'])?$item['end_time']:"";?></p>
						<p>状 态：<?php if(empty($item['use_time'])){?><?php echo  '未使用';?><?php }else{?><?php echo  '已使用';?><?php }?></p>
					</div>
				</div>
				<?php }?> 
			</div>
			<!--已用F码-->
			<div class="hide">
				<?php foreach($flist as $key => $item){?>
				<div class="fcode-tab">
					<div class="fcode-bg">
						<div class="fm">F码：<?php echo isset($item['f_code'])?$item['f_code']:"";?></div>
						<div class="fx">
							<div class="fut3"><a>删除</a></div>											
						</div> 
					</div>
					<div class="fcode-time">
						<p>使用时间：</p>
						<p>使 用 人：</p>
						<p>兑现状 态：未兑奖</p>
					</div>
				</div>
				<?php }?>
			</div>
			<!--已过期-->
			<div class="hide">
				<?php foreach($flist as $key => $item){?>
				<div class="fcode-tab">
					<div class="fcode-close-bg">
						<div class="fm">F码：<?php echo isset($item['f_code'])?$item['f_code']:"";?></div>
						<div class="fx">
							<div class="fut3"><a href="<?php echo IUrl::creatUrl("/wapcenter/delfcode/id/$item[id]");?>">删除</a></div>									
						</div>
					</div>
					<div class="fcode-time">
						<p>有效期：<?php echo isset($item['start_time'])?$item['start_time']:"";?>至<?php echo isset($item['end_time'])?$item['end_time']:"";?></p>
						<p>状 态：</p>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
		
		
		<!--申请F码-->
		
		<div class="fcode-box" style="display: none;"> 
			<div class="fcode-foot">
				<textarea rows="8" name="apply_comment"  cols="10" onblur="if(this.innerHTML==''){this.innerHTML='请在这里填写您申领说明';this.style.color='#666'}" onfocus="if(this.innerHTML=='请在这里填写您申领说明'){this.innerHTML='';this.style.color='#666'}">请在这里填写您申领说明</textarea>
			</div>
			<div class="fcode-in">
				<input type="text" name="contact_way" placeholder="请留下您的联系方式或邮箱地址（选填）" />
			</div>
			<div class="fcode-but">			
				<input type="button" name="" value="保 存" />
			</div>
		</div>
		
		
		 <script type="text/javascript">
		 	$(document).ready(function(){
		 		$('.fcode-box .fcode-but ').bind("click",function(){
		 			var apply_comment = $('textarea').val();
		 			var waitReviewCount = <?php echo isset($waitReviewCount)?$waitReviewCount:"";?>;
		 			var contact_way = $('input[name="contact_way"]').val();
		 			if(apply_comment == '' || apply_comment == '请在这里填写您申领说明'){
		 				alert('请输入申领说明');
		 				return false;
		 			}
		 			if(waitReviewCount>0){
		 				alert("您的F码申请正在处理中,请稍候再试");
		 				return false;
		 			}
		 			$.ajax({
		 				url:"<?php echo IUrl::creatUrl("wapcenter/do_apply_fcode");?>",
		 				type:"post",
		 				dataType:"json",
		 				data:{"apply_comment":apply_comment,"contact_way":contact_way},
		 				success:function(d,s,r){
		 					if(d.status == '1'){
		 						alert(d.message);	
		 						$('.alert_close_img').bind("click",function(){
		 							window.location.reload();
		 						});
		 					}else{
		 						alert(d.message);
		 						$('.alert_close_img').bind("click",function(){
		 							window.location.reload();
		 						});
		 					}
		 				}
		 			});
		 		});
		 	});
		 </script>
		 
		<script>
			//申领F码
			$(".sf").click(function(event) {
			$(".fcode-box").slideToggle(0);
				event.stopImmediatePropagation();
			});
			
			
		</script>
		<script>
		//菜单切换		
		$(function(){
			if(<?php echo isset($status)?$status:"";?> == '0'){
				$(".hide").eq(0).addClass("show").siblings().removeClass("show")
			}else if(<?php echo isset($status)?$status:"";?> == '1'){
				$(".hide").eq(1).addClass("show").siblings().removeClass("show")
			}else if(<?php echo isset($status)?$status:"";?> == '2'){
				$(".hide").eq(2).addClass("show").siblings().removeClass("show")
			}

		})
		</script>
				<script type="text/javascript">			
			function sharing(fcode){
				$(".share-m").show();
				$('input[name="fcode"]').val(fcode);
			}
			
			function mark(fcode){
				$.ajax({
					url:'<?php echo IUrl::creatUrl("/tool/mark");?>',
					type:'post',
					dataType:'json',
					data:{"fcode":fcode},
					success:function(d){
						if(d.status == '1'){
							alert(d.message);
							$('.alert_close_img').click(function(){
								window.location.reload();
							});
						}else{
							alert(d.message);
							$('.alert_close_img').click(function(){
								window.location.reload();
							});
						}
					}
				});
			}
			
			$(".ioc").click(function(){
				$(".share-m").hide();
			})		
			$(".wx-img").click(function(){
				$(".share-box").hide();
			})
		</script>
		<!-- 新浪微博分享开始 -->
		<script type="text/javascript">
			$('.share-m .shareimg1').bind('click',function(){
				 var fcode = $('input[name="fcode"]').val();
				 var title = '我分享了F码:'+fcode+',使用F码购买云棒优惠多多哦!';//分享标题
				 var url = 'http://www.meibeike.com/tool/newbuy';//分享链接
				 var pic = 'http://www.meibeike.com/views/default/skin/default/lottery/images/b_logo.png';//分享图片
				 window.open('http://v.t.sina.com.cn/share/share.php?title=' + title + '&url=' + url + '&pic=' + pic, '_blank');
			});
		</script>
		<!-- 新浪微博分享结束 -->
		<!--QQ分享 开始-->
		<script type="text/javascript">
		        $('.share-m .shareimg2').click(function(){
			 		var fcode = $('input[name="fcode"]').val();
					var p = {
			        url: 'http://www.meibeike.com/tool/newbuy',/*获取URL，可加上来自分享到QQ标识，方便统计*/
			        title : '我分享了F码:'+fcode+',使用F码购买云棒优惠多多哦!',/*分享标题(可选)*/
			        summary : '云棒官网:http://www.meibeike.com',/*分享描述(可选)*/
			        desc: '我分享了F码:'+fcode+',使用F码购买云棒优惠多多哦!', /*分享理由(风格应模拟用户对话),支持多分享语随机展现（使用|分隔）*/
			        pics : 'http://www.meibeike.com/views/default/skin/default/lottery/images/b_logo.png',/*分享图片(可选)*/
			        flash : '',	/*视频地址(可选)*/
			        commonClient : true, /*客户端嵌入标志*/
			        site: 'QQ分享',/*分享来源 (可选) ，如：QQ分享*/
			        };
			        var s = [];
			        for (var i in p) {
			       		s.push(i + '=' + encodeURIComponent(p[i] || ''));
			        }
		        	window.open("http://connect.qq.com/widget/shareqq/index.html?" + s.join('&'));
		        });	  
		</script>
		<!-- QQ分享结束 -->
		<!-- 微信分享开始 -->
		<script type='text/javascript'>
			$('.share-m .shareimg3').click(function(){
				$('#qrcode').show();
			});
		</script>
		<!--  微信分享结束 -->
		<script> 
			       /* wx.config({    
			            debug: false,    
			            appId: '${shakeMap.appId}',    
			            timestamp: '${shakeMap.timestamp}',    
			            nonceStr: '${shakeMap.nonceStr}',    
			            signature: '${shakeMap.signature}',    
		            	jsApiList: [    
			                'checkJsApi',    
			                'onMenuShareTimeline',    
			                'onMenuShareAppMessage'    
			            ]    
			        }); */
			        $('#wxsharess').click(function(){
			        	var pageUrl = window.location.href.split('#')[0];  
			        	pageUrl = pageUrl.replace(/\&/g, '%26');
			        	$.ajax({
			        		url:'<?php echo IUrl::creatUrl("/tool/test2");?>',
			        		type:'post',
			        		dataType:'json',
			        		data:{'url':pageUrl},
			        		success:function(d){
			        			if(d.status == '1'){
							        wx.config({    
							            debug: true,    
							            appId: d.appId,    
							            timestamp: d.timestamp,    
							            nonceStr: d.noncestr,    
							            signature: d.signature,    
						            	jsApiList: [    
							                'checkJsApi',    
							                'onMenuShareTimeline',    
							                'onMenuShareAppMessage'    
							            ]    
							        });  
			        			}else{
			        				alert('签名失败');
			        			}
			        		}
			        	});

				        wx.ready(function () {    
			                   
					           /* var shareData = {    
					                title: '${title}',    
					                desc: '${description}',    
					                link: '${url}',    
					                imgUrl: '${headImgUrl}',    
					                success: function (res) {    
					                    //alert('已分享');    
					                },    
					                cancel: function (res) {    
					                }    
					            }; */   
					                  
					            wx.onMenuShareAppMessage({    
					                title: '微信分享好友测试',    
					                desc: '微信分享好友测试',    
					                link: 'http://www.meibeike.com',    
					                imgUrl: '',    
					                trigger: function (res) {    
					                            alert('用户点击发送给朋友');    
					                },    
					                success: function (res) {    
					                    alert('已分享');    
					                },    
					                cancel: function (res) {    
					                    alert('已取消');    
					                },    
					                fail: function (res) {    
					                    alert(JSON.stringify(res));    
					                }    
					            });    
					              
					            //wx.onMenuShareTimeline(shareData);    
					        });    
			  
					        wx.error(function (res) {    
					            alert("error: " + res.errMsg);    
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


		
		
