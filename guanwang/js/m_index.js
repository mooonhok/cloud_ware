  var width=$(window).width();
  var height=$(window).height();
  $(document).ready(function(){
  	$(".bg_img_a").css("width",width);
  	$(".bg_img_a").css("height",height);
  	  	$(".bg_img_b").css("width",width);
  	$(".bg_img_b").css("height",height);
  	  	$(".bg_img_c").css("width",width);
  	$(".bg_img_c").css("height",height);
  	  	$(".bg_img_d").css("width",width);
  	$(".bg_img_d").css("height",height);
  	  	$(".bg_img_e").css("width",width);
  	$(".bg_img_e").css("height",height);
  	  	$(".bg_img_f").css("width",width);
  	$(".bg_img_f").css("height",height);
  	$(".join").on("click",function(){
  		setTimeout("reload()",500);
  	});
  });
  var reload=function(){
  	window.location.href="lxwm.html";
  }
