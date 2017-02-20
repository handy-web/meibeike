// JavaScript Document\

function autobox(){
	
	var width=$(window).width();
	if(width>=720){
		width=720
		};
	var auto=width*10/720;	
	//$('html').css('font-size',auto);
}//字体大小自适应
	
autobox();
	

$(window).resize(function(){
	autobox();
	});

function touch(){	
	var a=document.getElementsByTagName('a');
for(var i=0;i<a.length;i++){
  a[i].addEventListener('touchstart',function(){},false);
}	
	};//ios模拟点击事件
	

function number(boxnname,addname,minname,inputname){
	var value;
	$(addname).click(function(){
		value=$(inputname).val();
		$(this).prev($(inputname)).val(parseInt(value)+1);
	});
	$(minname).click(function(){
		value=$(inputname).val();
		if(value<=1){
			$(this).next($(inputname)).val('1');
			}else{
				$(this).next($(inputname)).val(parseInt(value)-1);
		};
		
	});
	
	$(inputname).blur(function(){
		value=$(inputname).val();
		if(value!=null && value!=""){
			$(inputname).val('1');
			}
		
	});
	
	

	
	
};

function animateleft(boxname){
	var w=0;
	var i=0;	
	var node=$(boxname).children();
	
	node.each(function(){		
		w+= parseInt($(this).width());
	});



function show1(){
    
	if(i==w){
		i=0;		
		}
		else{
			i=i+1;	
		$(boxname).animate({'left':'-'+i},1);
		}
		//console.log(i);
	
}

setInterval(show1,1);
	
	
	
	
}

function clickbox(bdname,hdname){
	var num;
	$(bdname).children().click(function(){
		var num=$(this).index();
		$(this).addClass('on').siblings().removeClass('on');
		$(hdname).children().eq(num).addClass('on').siblings().removeClass('on');
	});
};


function fadebox(boxname,closename){
	
	$(boxname).fadeIn(150);
	$(boxname).find($(closename)).click(function(){
		$(boxname).fadeOut();
		});
	
};//遮罩弹出封装函数..
  //boxname = 遮罩的class名称
  //closename = 遮罩class里面的关闭按钮


	
	
$(function(){
	
	touch();
	})	
	
	