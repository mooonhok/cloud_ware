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

function logout(){
	Confirm.confirm({message:"确定退出登录？"}).on(function(e){
		if(!e){
			return;
		}
		window.location.href=link+"login.html";
	});
}