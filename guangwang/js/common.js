//var link="http://www.uminfo.cn/";
var link="";

$(document).ready(function(){
	$('#main-menu').metisMenu();
	$(window).bind("load resize",function(){
		if($(this).width()<768){
			$('div.sidebar-collapse').addClass('collapse')
		}else{
			$('div.sidebar-collapse').removeClass('collapse')
		}
	});
});

function size(){
	var windowWidth=$(window).width(); 
	var windowHeight=$(window).height();
	var bodyHeight=$("body").height();
	var headerHeight=$(".navbar-top").outerHeight();
	var footerHeight=$("#footer").outerHeight();
	if(windowWidth>=768){
		if(windowHeight>bodyHeight+headerHeight+footerHeight){
			$(".navbar-side").css("height",windowHeight-headerHeight-footerHeight);
			$("#view").css("height",windowHeight-headerHeight-footerHeight);
		}else{
			$(".navbar-side").css("height",$("#view").outerHeight());
		}
	}else{
		$(".navbar-side").css("height","auto");
		$("#view").css("height","auto");
	}
}
window.onload=size;
setInterval(size,100);

function logout(){
	Confirm.confirm({message:"确定退出登录？"}).on(function(e){
		if(!e){
			return;
		}
		window.location.href=link+"login.html";
	});
}