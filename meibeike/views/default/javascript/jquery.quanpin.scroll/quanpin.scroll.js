	
	$().ready(function() {
		var hidden_menu_name = $("#hidden_menu_name").val();
		if (hidden_menu_name == "overview" || hidden_menu_name == "design" || hidden_menu_name == "feature" || hidden_menu_name == "design") {
			$('body,html').animate({scrollTop:0},1000);
			$("#is_top_show").val(1);
			$("#is_bottom_show").val(0);
			$("#has_show").val(0);
		}
		
	})

	function isExitsFunction(funcName) {
		try {
			if (typeof(eval(funcName)) == "function") {
				return true;
			}
		} catch(e) {}
		return false;
	}
	// 调整顶部的显示
	function adjust_show(delta) {
		var is_in_scroll = $('.is_in_scroll').val();
		if (is_in_scroll == 1) {
			return;
		}
		
		if (delta < 0) {
			if ($("#is_top_show").val() == 1 && $('.cur_disappear').val() == 0) {
				$(".header").animate({"margin-top": "-140"}, 700);
				$("#is_top_show").val(0);
				
				// 表示第一次进入 index=0 的全屏
				if(isExitsFunction("onPreEnter")) {
					// 进入回调，编号+进入方向（0向下，1向上）
					onPreEnter(0, 0);
				}
				if(isExitsFunction("onEnter")) {
					// 进入回调，编号+进入方向（0向下，1向上）
					setTimeout(function () {
						onEnter(0, 0);
					}, 600);
				}
				return ;
			}
		} else if (delta > 0) {
			if ($("#is_top_show").val() == 0 && $('.cur_disappear').val() == 0) {
				$(".header").animate({"margin-top": "0"}, 700);
				$("#is_top_show").val(1);
				// 表示离开 index=0 的全屏
				if(isExitsFunction("onExit")) {
					// 进入回调，编号+进入方向（0向下，1向上）
					setTimeout(function () {
						onExit(0, 1);
					}, 600);
				}
				return ;
			}
		}
		qpgd(delta);
	}
	
	$(function () {
		$('body').mousewheel(function (event, delta) {
			adjust_show(delta);
		});
			
		$('.num_box').mousewheel(function (event, delta) {
			
		});
		
		$('.cur_disappear').val(0);
		$('.is_in_scroll').val(0);
		$('.fixed_r li').eq(0).addClass('on');
		quanp_call();
	});

	// 全屏滚动
	function qpgd(delta) {		
		var cur_disappear = $('.cur_disappear').val();
		var cur_disappear_count = parseInt(cur_disappear);
		var all_count = $('.num').length;
		var index_to_exit = -cur_disappear;
		if (delta < 0) {
			if (-cur_disappear_count >= all_count - 1) {
				/*
				 * 到达底部，要重现footer
				 */
				if ($("#is_bottom_show").val() == 0) {
					var footer_height = $(".footer").height();
					var top_new_pos = cur_disappear_count * $(window).height();
					//$(".num_box").animate({top: top_new_pos}, 1000);
					
					$(".num_box").css("top", top_new_pos + "px");
					$(".footer").css("top", top_new_pos + "px");
					
					// 模拟wrapper的top
					$(".wrapper_all").css("top", "0px");
					$(".wrapper_all").animate({top: -footer_height}, 1000);
					
					$("#is_bottom_show").val(1);
				}
				return;
			}
			cur_disappear_count -= 1;
			$('.is_in_scroll').val(1);
		} else if (delta > 0) {
			if (-cur_disappear_count == 0) {
				return;
			}
			if ($("#is_bottom_show").val() == 1) {
				var top_new_pos = cur_disappear_count * $(window).height();
				$(".num_box").animate({top: top_new_pos}, 1000);
				$(".footer").css("top", top_new_pos + "px");
				$("#is_bottom_show").val(0);

				// 模拟wrapper的top
				$(".wrapper_all").animate({top: "0px"}, 1000);
				
				setInterval('var footer_height = $(".footer").height();var top_new_pos = cur_disappear_count * $(window).height();\n\
							$(".footer").css("top", (top_new_pos - footer_height) + "px");', 1200);
				return;
			}
			cur_disappear_count += 1;
			$('.is_in_scroll').val(1);
		}
		
		$('.cur_disappear').val(cur_disappear_count);
		$('.fixed_r li').eq(-cur_disappear_count).addClass('on').siblings('li').removeClass('on');
		var single_hh = $(window).height();
		var click_hh = single_hh * cur_disappear_count;
		
		// 表示第一次进入 index=0 的全屏
		if(isExitsFunction("onPreEnter")) {
			// 进入回调，编号+进入方向（0向下，1向上）
			onPreEnter(-cur_disappear_count, (delta > 0 ? 1 : 0));
		}
		if(isExitsFunction("onEnter")) {
			// 进入回调，编号+进入方向（0向下，1向上）
			setTimeout(function () {
				onEnter(-cur_disappear_count, (delta > 0 ? 1 : 0));
			}, 1000);
		}
		if(isExitsFunction("onExit")) {
			// 离开回调，编号+离开方向（0向下，1向上）
			setTimeout(function () {
				onExit(-index_to_exit, (delta > 0 ? 1 : 0));
			}, 1000);
		}
		$('.num_box').animate({
			'top' : click_hh
		}, 1000);
		setTimeout(function () {
			$('.is_in_scroll').val(0);
		}, 1400);
	}

	$().ready(function() {
		$('.fixed_r li').click(function () {
			var cur_disappear = $('.cur_disappear').val();
			var cur_disappear_count = parseInt(cur_disappear);
			var cur_index = $(this).index();
			if (cur_index == -cur_disappear_count) {
				return;
			}
			
			$(this).addClass('on').siblings('li').removeClass('on');
			// 隐藏顶部
			if ($('.cur_disappear').val() == 0) {
				$(".header").animate({"margin-top": "-140"}, 1000);
				$("#is_top_show").val(0);
			}
			$('.cur_disappear').val(-cur_index);

			// 底部调整
			if ($("#is_bottom_show").val() == 1) {
				var top_new_pos = cur_index * $(window).height();
				$(".footer").css("top", top_new_pos + "px");
				$("#is_bottom_show").val(0);
				// 模拟wrapper的top
				$(".wrapper_all").animate({top: "0px"}, 1000);
				
				setInterval('var footer_height = $(".footer").height();var top_new_pos = cur_disappear_count * $(window).height();\n\
							$(".footer").css("top", (top_new_pos - footer_height) + "px");', 1200);
			}	
			
			// 显示隐形菜单
			/*$( ".product-nav-slide" ).animate({top: "0"}, 700);
			$("#has_show").val(1);
			*/
			// 回调
			if(isExitsFunction("onPreEnter")) {
				// 进入回调，编号+进入方向（0向下，1向上）
				onPreEnter(cur_index, (-cur_index > -cur_disappear_count ? 0 : 1));
			}
			if(isExitsFunction("onEnter")) {
				// 进入回调，编号+进入方向（0向下，1向上）
				setTimeout(function () {
					onEnter(cur_index, (-cur_index > -cur_disappear_count ? 0 : 1));
				}, 1000);
			}
			if(isExitsFunction("onExit")) {
				// 离开回调，编号+离开方向（0向下，1向上）
				setTimeout(function () {
					onExit(-cur_disappear_count, (-cur_index > -cur_disappear_count ? 0 : 1));
				}, 1000);
			}

			// 设置滚动框的top
			var single_hh = $(window).height();
			var click_hh = -single_hh * cur_index;
			$('.num_box').animate({
				'top' : click_hh
			}, 1000);
		});
	});
	
	function quanp() {		
		var single_hh = $(window).height();
		$('.num').height(single_hh);
		var cur_disappear = $('.cur_disappear').val();
		var cur_disappear_count = parseInt(cur_disappear);
		$(".num_box").css("top", cur_disappear_count * single_hh);
		
		// 判断当前是否已经显示出了底部菜单，是的话，要调整
		if ($("#is_bottom_show").val() == 1) {
			$(".footer").css("top", cur_disappear_count * single_hh);
		}
	}
	
	function quanp_call() {
//		if (typeof indexSlides != 'undefined' && indexSlides.reformat)
//			indexSlides.reformat();
		quanp();
	}
	
	$(window).resize(function () {
		quanp_call();
		if(isExitsFunction("onResizeWin")) {
			var cur_disappear = $('.cur_disappear').val();
			var cur_disappear_count = parseInt(cur_disappear);
			// 屏幕变化回调，编号
			setTimeout(function () {
				onResizeWin(-cur_disappear_count, $(window).height());
			}, 1000);
		}
	});
