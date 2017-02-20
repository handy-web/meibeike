
;(function($){
	
/*--------------------
----------------------
依附于JQ对象的插件
----------------------
----------------------*/



$.fn.extend({
	
	
//图片轮播
soChange:function(b) {
	b = $.extend({
		thumbObj: null, //导航对象，默认为空
		botPrev: null, //按钮上一个，默认为空
		botNext: null, //按钮下一个。默认为空
		changeType: "fade", //切换方式，可选：fade,slide，默认为fade
		thumbNowClass: "now", //导航对象当前的class,默认为now
		thumbOverEvent: true, //鼠标经过thumbObj时是否切换对象，默认为true，为false时，只有 鼠标点击thumbObj才切换对象
		slideTime: 1000, //平滑过渡时间，默认为1000ms，为0或负值时，忽略changeType方式，切换效果为直接显示隐藏
		autoChange: true, //是否自动切换，默认为true
		clickFalse: true, //导航对象如果有链接，点击是否链接无效，即是否返回return false，默认是return false链接无效，当thumbOverEvent为false时，此项必须为true，否则鼠标点击事件冲突
		overStop: true, //鼠标经过切换对象时，切换对象是否停止切换，并于鼠标离开后重启自动切换，前提是已开启自动切换，默认开启鼠标经过对象停止切换
		changeTime: 5000, //对象自动切换时间，默认为5000ms，即5秒
		delayTime: 300 //鼠标经过时对象切换迟滞时间，推荐值为300ms，同时防止鼠标快速切换
	},
	b||{});
	var h = $(this);
	var i;
	var k = h.size();
	var e = 0;
	var g;
	var c;
	var f;
	function d() {
		if (e != g) {
			if (b.thumbObj != null) {
				$(b.thumbObj).removeClass(b.thumbNowClass).eq(g).addClass(b.thumbNowClass);
			}
			if (b.slideTime <= 0) {
				h.eq(e).hide();
				h.eq(g).show();
			} else {
				if (b.changeType == "fade") {     //切换方式，可做改进和添加
					//h.eq(e).stop(true,false).fadeTo(b.slideTime,0);
					//h.eq(g).stop(true,false).fadeTo(b.slideTime,1);
					h.eq(e).stop(true,true).fadeOut(b.slideTime);  //stop(true,true)立即清空动画列表，并完成当前动画，初始化状态。
					h.eq(g).stop(true,true).fadeIn(b.slideTime);
						
				} else {
					h.eq(e).stop(true,true).slideUp(b.slideTime);
					h.eq(g).stop(true,true).slideDown(b.slideTime);
				}
			}
			e = g;
			if (b.autoChange == true) {
				clearInterval(c);
				c = setInterval(j, b.changeTime);
			}
		}
	}
	function j() {
		g = (e + 1) % k;
		d();
	}
	h.hide().eq(0).show();
	if (b.thumbObj != null) {
		i = $(b.thumbObj);
		i.removeClass(b.thumbNowClass).eq(0).addClass(b.thumbNowClass);
		i.click(function() {
			g = i.index($(this));
			d();
			if (b.clickFalse == true) {
				return false;
			}
		});
		if (b.thumbOverEvent == true) {
			i.mouseenter(function() {
				g = i.index($(this));
				f = setTimeout(d, b.delayTime);
			});
			i.mouseleave(function() {
				clearTimeout(f);
			});
		}
	}
	if (b.botNext != null) {
		$(b.botNext).click(function() {
			if (h.queue().length < 1) {
				j();
			}
			return false;
		});
	}
	if (b.botPrev != null) {
		$(b.botPrev).click(function() {
			if (h.queue().length < 1) {
				g = (e + k - 1) % k;
				d();
			}
			return false;
		});
	}
	if (b.autoChange == true) {
		c = setInterval(j, b.changeTime);
		if (b.overStop == true) {
			h.mouseenter(function() {
				clearInterval(c);
			});
			h.mouseleave(function() {
				c = setInterval(j, b.changeTime);
			});
		}
	}
	return this;
},




//背景向上滚动，需要.bg_div来模拟背景
bgScroll:function(a,b){
	var ele=$(this);    //注意，这里只接受一个元素，有待改善
	var scH = a||400;//背景要滚动的距离，可自定义
	var sp=b||80; //滚动速度
	$(window).scroll(function(){
		//获取数据
		var scHeight = parseFloat($(document).scrollTop());//取得滚动总距离
		//var bgHeight = parseFloat($(".bg_div").height());//取背景高度
		var bodyHeight = parseFloat($(window).height());//当前窗口高度
		var htHeight = parseFloat($(document).height());//文档总高度		
		ele.find(".bg_div").height(htHeight+scH);//给背景赋值高度，达到滚动效果
		//文档滚动百分比
		var scPCT = Math.floor(scHeight/(htHeight-bodyHeight)*100);
		//背景图滚动比例
		var bgSc = -1*scH*scPCT/100;
		//背景图片缓动
		ele.find(".bg_div").animate({"top":bgSc},sp);
	});
	
	return this;
},



//模拟自定义select框
selectBox : function(opt) {
	opt = $.extend({
		input:'selectbox',
		com: 'select_ul',
		hover: 'hover', 
		active:'selected',
		change:function(){}
	},
	opt||{});
	
	var hasF = false;
	var ele = $(this);
	var id = ele.attr("id")||ele.attr("class")||"select";
	var ul = $('<ul id="'+id+'_container" class='+opt.com+'></ul>');
	var input = $('<input type="text" id="'+id+'_input" class='+opt.input+' autocomplete="off"  readonly="readonly" >');
	
	ele.children('option').each(function() {
		var li =$('<li id='+id+ '_' + $(this).attr('value')+' >'+$(this).html()+'</li>');
		if ($(this).is(':selected')) {
			input.val($(this).html());
			li.addClass(opt.active);
		}
		li.attr("val",$(this).attr('value'));
		ul.append(li);
		li
		.hover(
			function () {
				hasF = true;
				$(this).addClass(opt.hover);
			},
			function () {
				hasF = false;
				$(this).removeClass(opt.hover);
			}
		)
		.click(function() {
			$(this).addClass(opt.active).siblings().removeClass(opt.active);
			var val_t=$(this).attr("val");
			ele.val(val_t);
			input.val($(this).html());
			hideMe();
			opt.change(val_t);
		});
	});
	ele.hide().before(input).before(ul);
	ul.hide();
	
	function hideMe() { 
		hasF = false;
		ul.hide(); 
	}
	input
	.focus(function(){
		ul.show();
	})
	.blur(function() {
		if (ul.is(':visible') && !hasF) {
			hideMe();
		}
	});	
},




//图片完全加载时调用callback，并把本身作为JQ对象传给callback
imgLoad:function(callback,each){
	var obj=$(this);
	var _obj=obj.eq(0).is("img")?obj:obj.find("img");
	var j=_obj.toArray();
	_obj.each(function(i,d){
		var img= new Image();	
		function fn(){
			//var _i=j.toString().indexOf(d);    //重新获取元素对应的索引值
			var _i=$.inArray(d,j);
			j.splice(_i,1);
			if((each+"")=="true"){
				callback($(d));
			}else if(!each && j.length==0){
				callback(obj);
			}
		}
		img.onload=function(){
			img.onload=null;		//重要！
			fn();
		};
		img.onerror=function(){
			img.onerror=null;
			fn();
		};
		img.src = d.src;		//地址写在最后，避免IEbug，重要！
	});
	return this;
},





//图片尺寸限制
imgAuto : function(w,h) {
	var ele = $(this).eq(0).is("img")?$(this):$(this).find("img");
	var r;
	var or =w/h;
	function imgR(o){    //直接用Image对象的width和height属性，以免图片隐藏时获取不到。
		o.each(function(i,d){
			r =d.width/d.height;   
			if(d.width>w || d.height>h){
				if (r >=or) {
					$(d).width(w);
					$(d).height(w/r);
					//$(d).css("margin-top",Math.round((h-w/r)/2));    //尽量使用css来控制图片居中，要考虑小图片也能居中对齐
				}else{
					$(d).width(h*r);
					$(d).height(h);
					//$(d).css("margin-left",(w-h*r)/2);
				}
			}
		});
	}
	ele.imgLoad(imgR);
	
	return this;
},




//自动顶部定位
boxScroll : function(h) {
	var topbox = $(this);
	var oldtop = topbox.offset().top;
	var scrolls = $(document).scrollTop();
	var fn;
	h=h||0;
	fn=function() {
		scrolls = $(document).scrollTop();
		if (scrolls > oldtop-h) {
			topbox.css({
				top: (!-[1,]&&!window.XMLHttpRequest)?scrolls+h:h,
				position: (!-[1,]&&!window.XMLHttpRequest)?"absolute":"fixed"
			});
		}else {
			topbox.css({
				top: oldtop,
				position: "absolute"
			});    
		}
	};
	$(window).bind("scroll",fn);
	fn();
	
	return this;
},


//瀑布流布局
waterFall:function(m){
	m=m||0;
	var o=$(this);
	var w=o.width();
	var li=o.find("li");
	var li_w = li.outerWidth()+m;
	var h=[];
	var n = w/li_w|0;
	function callback(e){
		e.each(function(i,d){
			var li_h = $(d).outerHeight()+m;
			if(i < n) {     //n是一行最多的li，所以小于n就是第一行了
				h[i]=li_h;  //把每个li的高度放到数组里面
				$(d).css({"top":m,"left":i * li_w+m});
			}else{
				var min_h =Math.min.apply(null,h);    //取得数组中的最小值，区块中高度值最小的那个
				var minKey = $.inArray(min_h, h);      //最小的值对应的指针
				h[minKey] += li_h ;    //加上新高度后更新高度值
				if(o.height()){     //如果父级限定高度
					var max_h =Math.max.apply(null,h);
					if(o.height()<max_h+m){$(d).hide().nextAll().hide();return false;}
					else{
						$(d).show();
					}
				}
				$(d).css({"top":min_h+m,"left":minKey * li_w+m});   //注意第一行top值加上了m，所以后面的也都要加。
			}
		});
		if(!o.height()){
			var max_h =Math.max.apply(null,h);
			o.height(max_h+m);
		}
	}
	li.has("img").length>0?li.imgLoad(callback):callback(li);
	
	
	return this;
},


//上下左右滚动
marquee:function(o){
	var a = $.extend({
		dt:"left",
		n: 1,
		time: 500,
		stime: 2000,
		pre:".pre",
		next:".next",
		num:".num",
		numclass:"now",
		auto:true,
		easing:"swing"
	},
	(typeof o ==="object")?o:{});
	var cur=1;
	var d= (typeof o ==="string")?o:a.dt;
	var _d = (d=="left" || d=="right")?true:false;
	var box=$(this).children("ul");
	var li=box.children("li");
	var _w=li.outerWidth();
	var _h=li.outerHeight();
	var $pre = $(a.pre);
    var $next = $(a.next);
	var $num = $(a.num);
	
	var _i=minNum= Math.min(li.length,a.n),maxNum = Math.max(li.length,a.n),vper=i=0;
	if(minNum!=maxNum){
		for(;_i<=maxNum;_i++){
			vper = minNum * _i;
			if(vper % maxNum === 0){break;}
		}
	}else{
		vper = _i;
	}
	
	for(;i<vper/li.length;i++){li.clone().appendTo(box)};



	function decFn(or){
		var dObj = {};
		dObj["margin"+(_d?"Left":"Top")]= (or?"+":"-")+"=" + (_d?_w:_h)*a.n;
		if (!box.is(':animated')){ 
			if(or){
				if(cur==1){
					box.css("margin-"+(_d?"left":"top"),-vper*(_d?_w:_h));
					box.animate(dObj,a.time,a.easing,function(){
						$num.eq(cur - 1).addClass(a.numclass).siblings().removeClass(a.numclass);
					});	
					cur=vper/a.n;
				}else{
					box.animate(dObj,a.time,a.easing,function(){
						$num.eq(cur - 1).addClass(a.numclass).siblings().removeClass(a.numclass);
					});	
					cur--;
				}
			}else{
				cur++;
				box.animate(dObj,a.time,a.easing,function(){
					if(vper/a.n==cur-1){box.css("margin-"+(_d?"left":"top"),0);cur=1;};
					$num.eq(cur - 1).addClass(a.numclass).siblings().removeClass(a.numclass);
				});
			}
		}
	}
		
	$num.click(function(){
		if (!box.is(':animated')) {
			var $index = $num.index(this);
			var _dObj = {};
			_dObj["margin"+(_d?"Left":"Top")]= "-" + (_d?_w:_h)*$index*a.n;
			box.animate(_dObj,a.time,a.easing); 
			cur = $index+1;
			$(this).addClass(a.numclass).siblings().removeClass(a.numclass);
		}
	});
	
	$pre.click(function(){decFn(1)});
	$next.click(function(){decFn()});
	
//		function $preFn(){   //避免点击过快
//			decFn(1);
//			var _preT=setTimeout( function(){$pre.one("click",$preFn);},a.time+a.stime/4);
//		};
//		function $nextFn(){
//			decFn();
//			var _nextT=setTimeout( function(){$next.one("click",$nextFn);},a.time+a.stime/4);
//		};
//		
//		$pre.one("click",$preFn);
//		$next.one("click",$nextFn);
	
	
	if(a.auto){
		function autoClick(){
			if(d=="bottom" || d=="right"){
				decFn(1);
			}else{decFn();}
		}
		var t=setInterval(autoClick,a.stime==0?50:a.time+a.stime);
		function clearFun(ele){
			ele.hover(
				function () {
					clearInterval(t);
				},
				function () {
					t=setInterval(autoClick,a.stime==0?50:a.time+a.stime);
				}
			);
		}
		$num.eq(0).addClass(a.numclass);
		clearFun(box);
		clearFun($pre);
		clearFun($next);
		clearFun($num);	
	}
	
		
	return this;
}




});














/*--------------------
----------------------
静态方法
----------------------
----------------------*/



$.extend({




//图片完全加载时调用callback，只接受数组格式
imgLoad:function(list,callback,each){
	var n=list.length;
	$.map(list,function(x){		//用map简写，效果等同于下面的for循环
		var img= new Image();
		img.onerror=img.onload=function(){
			img.onerror=img.onload=null;
			n--;				//用n记录数组长度，避免数组使用pop、splice操作时，循环错误。
			if((each+"")=="true"){
				callback();
			}else if(!each && n==0){
				callback();
			}
		};
		img.src = x;
	});
//	for(var i=0;i<list.length;i++){		//不直接将onload事件写在for循环中，onload事件在回调的时候会形成闭包，让每个img都是不同的Image！
//		loadImg(list[i]);
//	};
//	function loadImg(x){
//		var img= new Image();
//		img.onerror=img.onload=function(){
//			img.onerror=img.onload=null;
//			n--;			
//			if((each+"")=="true"){
//				callback();
//			}else if(!each && n==0){
//				callback();
//			};
//		};
//		img.src = x;
//	}
		
	
},




	

//微博、QQ分享
share:function(tag,obj) {	
	obj = $.extend({
		stitle:'', //也可不填写,保留空 分享的文字
		surl: '', //分享的链接
		spic: '', //也可不填写,保留空 分享的图片
		objW:700, //窗口默认宽
		objH:600 //窗口默认高
	},
	obj||{});
	var shareTitle=encodeURIComponent(obj.stitle)||encodeURIComponent(document.title);
	var shareURL=encodeURIComponent(obj.surl)||encodeURIComponent(top.location.href)||encodeURIComponent(d.location.href);
	var sharePIC=obj.spic||'';
	var param='toolbar=no,status=no,menubar=no,scrollbars=no,location=yes,resizable=yes,width='+obj.objW+',height='+obj.objH+',left='+(screen.width-obj.objW)/2+',top='+(screen.height-obj.objH)/2;
	//var param=['toolbar=no,status=no,menubar=no,scrollbars=no,location=yes,resizable=yes,width='+obj.objW+',height='+obj.objH+',left=',(screen.width-obj.objW)/2,',top=',(screen.height-obj.objH)/2].join('');
	switch (tag){
		case "tx":window.open('http://share.v.t.qq.com/index.php?c=share&a=index&title='+shareTitle+'&url='+shareURL+'&pic='+sharePIC,'',param);
		break;
		case "sn":window.open('http://service.weibo.com/share/share.php?url='+shareURL+'&title='+shareTitle+'&pic='+sharePIC,'',param);
		break;
		case "rr":window.open('http://widget.renren.com/dialog/share?resourceUrl='+shareURL+'&srcUrl='+shareURL+'&title='+shareTitle+'&pic='+sharePIC+'&description='+shareTitle,'',param);
		break;
		case "qz":window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+shareURL+'&title='+shareTitle+'&pics='+sharePIC+'&summary='+shareTitle,'',param);	
		break;
		default:window.open('http://www.kaixin001.com/rest/records.php?content='+shareTitle+'&url='+shareURL+'&starid=&aid=100018706&style=11&pic='+sharePIC,'',param);
	}
},



//弹窗
showDialog:function(a){
	var longPop=false,
	objOverLay=ele=o=_scroll=null,
	isIE6=!-[1,]&&!window.XMLHttpRequest;
	
	o = $.extend({
		id: null,
		bg: '#111',
		op: 0.8,
		sMsg: null,
		sStyle: 'padding:10px;border:4px solid #dedede;background-color:#fff',
		sTime: null,
		noclose:false,
		PopupCall: null,
		CloseCall: null
	},
	(typeof(a) === 'object') ? a: {});	
	ele=(typeof(a) === 'object') ? (o.sMsg?"#_PopupMsg_":o.id) : a;
	
	//添加方法
	$.extend($.showDialog,{
		arr:$.showDialog.arr?$.showDialog.arr:[],
		
		//弹窗位置
		adjust: function() {
			var b = $(ele),
			_h = $(window).height(),
			_st = null;
			if (b.length>0) {
				var _iw = b.outerWidth(),
				_ih = b.outerHeight();
				if (_ih >= _h) {
					longPop = true;
					b.css({
						"position": "absolute",
						"top": "100px",
						"margin-left": "-" + _iw / 2 + "px",
						"margin-bottom": "100px"
					});
					$(window).scrollTop(0);
				} else {
					longPop = false;
					isIE6 ? (b.css("position","absolute")) : (b.css("position","fixed"));
					b.css({
						"margin-top": "-" + _ih / 2 + "px",
						"margin-left": "-" + _iw / 2 + "px"
					});
				}
				if (isIE6) {
					$.showDialog.fixIE6_Center();
					_scroll=function(){
						if (_st) {
							clearTimeout(_st);
						}
						_st = setTimeout(function() {
							$.showDialog.fixIE6_Center();
						},
						250);
					};
					$(window).bind("scroll",_scroll);
				}
			}
		},
		//IE6弹窗兼容
		fixIE6_Center: function() {
			var b = $(window).scrollTop();
			if (!longPop) {
				$(ele).css({
					"margin-top": parseInt(b - $(ele).outerHeight() / 2) + "px",
					"top": '50%'
				});
			}
		},
		
		//隐藏
		hide: function() {
			if (o.CloseCall != null && typeof o.CloseCall === "function") {
				o.CloseCall();
			}
			if (ele !== "#_PopupMsg_") {
				$(ele).hide();
			} else {
				$(ele).remove();
			}
			$.showDialog.arr = [];
			objOverLay.remove();
			if(_scroll){$(window).unbind("scroll",_scroll);}
		},
		
		//遮罩
		overlay: function() {
			$('#_overlay_').remove();
			objOverLay = $("<div id='_overlay_'></div>");
			objOverLay.css({
				"background-color": o.bg,
				"position": "absolute",
				"height": $(document).height() + "px",
				"width": "100%",
				"z-index": "989",
				"left": "0",
				"top": "0",
				"opacity": o.op
			});
			if (isIE6) {
				objOverLay.width($(document).width());
				objOverLay.html('<iframe style="position:absolute;top:0;left:0;width:100%;height:100%;filter:alpha(opacity=0);z-index:988" src="javascript:void(0)"></iframe>');
			}
			$("body").append(objOverLay);
			if (o.sTime != null && typeof o.sTime === "number") {
				window.setTimeout(function() {
					$.showDialog.hide();
				},
				o.sTime);
			} else if(o.noclose||o.sMsg) {
				objOverLay.one("click", function(){
					$.showDialog.hide();
				});
			}
		}
	});
	
	
	
	if (o.PopupCall != null && typeof o.PopupCall  === 'function') {
		o.PopupCall();
	}
	$.showDialog.arr.push(ele);
	$($.showDialog.arr[$.showDialog.arr.length - 2]).hide();
	//简易对话框
	if (o.sMsg != null) {
		var e;
		if (!$('#_PopupMsg_').length>0) {
			e = $("<div id=_PopupMsg_></div>");
			e.attr("style",o.sStyle);
			$("body").append(e);
		} else {
			e = $('#_PopupMsg_');
		}
		e.html(o.sMsg);
		e.show();
	}
	//弹窗
	if (a != null && $(ele).length>0 ) {
		$(ele).css({
			"visibility" : "visible",
			"z-index": "999",
			"position": "absolute",
			"left": "50%",
			"top": "50%"
		});
		$(ele).show();
	}
	$.showDialog.adjust();
	$.showDialog.overlay();
	
	
}



});




//easing插件，缓动函数  参考http://code.ciaoca.com/jquery/easing/ ，http://gsgd.co.uk/sandbox/jquery/easing/
$.easing['jswing'] = $.easing['swing'];
$.extend($.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		return $.easing[$.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - $.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return $.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return $.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});




})(jQuery);


