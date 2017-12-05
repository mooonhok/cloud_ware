  var width=$(window).width();
  var height=$(window).height();
                 function sport(){
               $('.back_sport').animate({top:(height-100)+"px"},600);
               $('.back_sport').animate({top:(height-110)+"px"},600);	
               }
  $(document).ready(function(){
  	        	    var width_sport=$('.back_sport').outerWidth();
        	    $('.back_sport').css("left",(width-width_sport)/2);
        	    $('.back_sport').css("top",(height-110)+"px");
        	    var show=setInterval('sport()',1000);
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
  		setTimeout("reload()",50);
  	});
  });
  var reload=function(){
  	window.location.href="lxwm.html";
  }
