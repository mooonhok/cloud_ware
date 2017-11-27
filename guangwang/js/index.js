			           var ua = navigator.userAgent.toLowerCase();
//         alert(ua)
            var isMobile = false;
            var browsers = ["android;", "android ", "android/", "ipod;", "ipad;", "iphone;", "iphone ", "iphone/"];
            for (var _b = browsers.length; _b--; ) {
                if (ua.indexOf(browsers[_b]) != -1) {
                    isMobile = true;
                    break;
                }
            }
            if(isMobile){
            	window.location.href="m_index.html";
            }

			(function($) {
			$.getUrlParam = function(name) {
				var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
				var r = window.location.search.substr(1).match(reg);
				if(r != null) return decodeURI(r[2]);
				return null;
			}
		})(jQuery);
function mouseWheel(){
        if(document.addEventListener){
            document.body.addEventListener('mousewheel',function(e){
                Detail(e);
                e.stopPropagation();
                e.preventDefalut();
            });
            document.body.addEventListener('DOMMouseScroll',function(e){
                Detail(e);
                e.stopPropagation();
                e.preventDefault();
            })
        }else{
            document.body.attachEvent('onmousewheel',function(event){
                Detail(event);
                event.cancelBubble=true;
                event.returnValue=false;
            })
        }
        function Detail(e){
            ((-e.detail || e.wheelDelta)>0)?alert('top'):alert('down');
        }
    }
 
        		var width=$(window).width();
	            var height=$(window).height();
//	            var height_sport=$('.back_sport').outerHeight();
               function sport(){
               $('.back_sport').animate({top:(height-100)+"px"},600);
               $('.back_sport').animate({top:(height-110)+"px"},600);	
               }
            var nowPage = 0;
            $(document).ready(function(){
            var nowPage=$.getUrlParam('nowPage');
//          	$(window).resize(function() {
//                window.location.reload();
//                  });
            window.onresize = eload;
            function eload(){
            	window.location.href="index.html?nowPage="+nowPage;
            }
//          function resize()
//          {
//          setTimeout("eload()",50);
//          }

                var show=setInterval('sport()',1000);
            	var width_img_a=(width/1920)*792;
	            var height_img_a=(height/1080)*436;
//	            alert(width_img_a)
	            $(".img_a_abus").css('left',(width-width_img_a)/2);
	            $(".img_a_abus").css('top',(height-height_img_a)/2);
	            $(".img_a_abus").css('width',width_img_a);
	            $(".img_a_abus").css('height',height_img_a);
        	    $(".img_a").css("width",width);
        	    $(".img_a").css("height",height);
        	    $(".intra_a").css("height",height);
        	    $(".intra_a").css("top",height);
        	    $(".img_b").css("width",width);
        	    $(".img_b").css("height",height);
        	    var height_a_k=height-430;
        	    $(".intra_a_s").css("height",height_a_k);
        	    $(".intra_a_k").css("height",'300px');
        	    $(".img_c").css("width",width);
        	    $(".img_c").css("height",height);
        	    var width_sport=$('.back_sport').outerWidth();
        	    $('.back_sport').css("left",(width-width_sport)/2);
        	    $('.back_sport').css("top",(height-110)+"px");
//      	    $(".img_fade_a").css("width",(width/1920)*550+"px");
        	    $(".img_fade_b").css("width",(width/1920)*800+"px");
//      	    $(".img_fade_c").css("width",(width/1920)*300+"px");
        	    $(".img_fade_d").css("width",(width/1920)*550+"px");
        	    $(".img_fade_e").css("width",(width/1920)*550+"px");
        	    $(".img_fade_f").css("width",(width/1920)*450+"px");
//      	    $(".img_fade_a").css("margin-top",(100+(height/1080)*164/2)+'px');
//      	    alert($(".img_fade_a").position().top)
        	    $(".img_fade_b").css("margin-top",(height/1080)*164*5/4+100+'px');
//      	    $(".img_fade_c").css("margin-top","300px");
        	    $(".img_fade_d").css("margin-top",(100+(height/1080)*266)+(height/1080)*164*3/4+(height/1080)*164/4+'px');
        	    $(".img_fade_e").css("margin-top",(height/1080)*164/2+100+'px');
//      	    $(".img_fade_f").css("margin-top","500px");
        	    $(".img_fade_b").css("right","0px");
        	    $('.img_fade_d').css("left","40%");
        	    $('.img_fade_c').css("left","20%");
        	    $(".intrb_a").css("height",height);
        	    $(".intrb_a").css("top",height*2);
        	    $(".img_c").css("width",width);
        	    $(".img_c").css("height",height);
        	    var height_b_k=height-430;
        	    $(".intrb_a_s").css("height",height_b_k);
        	    $(".intrb_a_k").css("height",'300px');
        	    $(".img_fade_g").css("width",(width/1920)*348+"px");
        	    $(".img_fade_h").css("width",(width/1920)*348+"px");
        	    $(".img_fade_j").css("width",(width/1920)*348+"px");
        	    $(".img_fade_g").css("margin-top",(height/1920)*600/4+100+"px");
//      	    $(".img_fade_g").css("margin-top","200px");
        	    $(".img_fade_h").css("margin-top",(height/1920)*600*2/4+100+"px");
        	    $(".img_fade_j").css("margin-top",(height/1920)*600/6+100+"px");
        	    $(".intrc_a").css("height",height);
        	    $(".intrc_a").css("top",height*3);
        	    $(".img_c").css("width",width);
        	    $(".img_c").css("height",height);
        	    var height_c_k=height-430;
        	    $(".intrc_a_s").css("height",height_c_k);
        	    $(".intrc_a_k").css("height",'300px');
        	    $(".img_d").css("width",width);
        	    $(".img_d").css("height",height);
        	    $(".img_fade_i").css("width",(width/1920)*516+"px");
        	    $(".img_fade_i").css("margin-top","120px");
                $(".img_fade_i").css("left","48%");
                  var first_g_w=$(".first_goods").width();
                $(".two_goods").css("height",(height-300)*0.85);
                $(".first_goods").css("height",(height-300)*0.5);
                $(".second_goods").css("height",(height-300)*0.5);
                $(".sqsy").css("width",$(".w_p_plus").outerHeight());
//              alert($(".first_goods img").outerHeight());
//              $(".first_goods div").css("line-height",$(".first_goods img").outerHeight()+"px");
//                $(".third_goods").css("height",first_g_w);
//                $(".iphone_a").css("left",(first_g_w-width/1920*81)/2);
//                $(".iphone_a").css("top",(first_g_w-height/1080*89)/2);
//                $(".iphone_b").css("top",(first_g_w-height/1080*89)/2);
////                $(".iphone_b").css("left",(first_g_w-width/1920*81)/2);
//                $(".iphone_a").css("width",width/1920*81);
//                $(".iphone_a").css("height",height/1080*89);
//                $(".android_a").css("left",(first_g_w-width/1920*81)/2);
//                $(".android_a").css("top",(first_g_w-height/1080*89)/2);
//                $(".android_a").css("width",width/1920*81);
//                $(".android_a").css("height",height/1080*89);
////                $(".android_b").css("left",(first_g_w-width/1920*81)/2);
//                $(".android_b").css("top",(first_g_w-height/1080*89)/2);
//      	      $(".pc_a").css("margin-top",(first_g_w-height/1080*89)/2);
//      	      $(".weixin_a").css("margin-top",(first_g_w-height/1080*89)/2);
//      	      var third_goods_w=$(".third_goods").outerWidth();
//      	      $(".pc_a").css("margin-left",third_goods_w*0.2);
//      	      $(".weixin_a").css("margin-left",(first_g_w-width/1920*81)/4);
//      	      $(".plus_a").css("margin-top",(first_g_w-height/1080*89)/2+height/1080*89/3);
//      	      $(".plus_a").css("margin-left",(first_g_w-height/1080*89)/4);
//      	      $(".plus_a").css("height",height/1080*89/3);
//      	      $(".pc_a").css("height",height/1080*89);
//                $(".weixin_a").css("height",height/1080*89);
//                $(".pc_b").css("margin-left",third_goods_w*0.2);
//                $(".pc_b").css("width",width/1920*81);
//      	      $(".weixin_b").css("margin-left",(first_g_w-height/1080*89)/4+height/1080*89/3+(first_g_w-width/1920*81)/4);
//      	      $(".zs").css("top",height*4);
        	      
//      	    alert($(".container").outerHeight())
                if(nowPage==1){
//              	$(".img_fade_a").fadeIn("fast");
//              	$('.img_fade_a').animate({left:"65%"},600);
//              	$(".img_fade_a").css("z-index","1999");	
                	$(".img_fade_d").fadeIn("fast");
                	$(".img_fade_d").css("z-index","1999");	
                	$('.img_fade_d').animate({left:"75%"},600);
                	$(".img_fade_b").fadeIn("fast");
                	$('.img_fade_b').animate({left:"45%"},600);
                	$(".img_fade_b").css("z-index","9999");	
                	$(".img_fade_e").fadeIn("fast");
                	$(".img_fade_e").css("z-index","999");	
                	$('.img_fade_e').animate({left:"75%"},600);
//              	$(".img_fade_f").fadeIn("fast");
//              	$('.img_fade_f').animate({right:"20%"},600);
                }else{
//              	$(".img_fade_a").fadeOut("slow");
//              	$('.img_fade_a').animate({left:"0"},600);
                	$(".img_fade_b").fadeOut("slow");
                	$('.img_fade_b').animate({left:width},600);
//              	$(".img_fade_c").fadeOut("slow");
                	$(".img_fade_d").fadeOut("slow");
                	$('.img_fade_d').animate({left:width},600);
                	$(".img_fade_e").fadeOut("slow");
                	$('.img_fade_e').animate({left:"0"},600);
//              	$(".img_fade_f").fadeOut("slow");
//              	$('.img_fade_f').animate({right:"0"},600);
                }
                if(nowPage==2){
                	$(".img_fade_g").fadeIn("fast");
                	$('.img_fade_h').animate({left:"50%"},600);
                	$(".img_fade_h").fadeIn("fast");
                	$('.img_fade_g').animate({left:"45%"},600);
                	$(".img_fade_j").fadeIn("fast");
                	$('.img_fade_j').animate({left:"55%"},600);
                	$(".img_fade_g").css("z-index",'999');
        	        $(".img_fade_h").css("z-index","2999");
        	        $(".img_fade_j").css("z-index","1999");
                }else{
                	$(".img_fade_g").fadeOut("slow");
                	$(".img_fade_h").fadeOut("slow");
                	$(".img_fade_h").animate({left:"0"},600);
                	$('.img_fade_g').animate({left:width},600);
                	$(".img_fade_j").fadeOut("slow");
                	$(".img_fade_j").animate({left:"0"},600);
                }
                if(nowPage==3){
                    $(".img_fade_i").fadeIn("slow");
                    var img_fade_i_t=$(".img_fade_i").position().top; 
                    var img_fade_i_l=$(".img_fade_i").position().left; 
                    var img_fade_i_h=height/1080*724;
                    var img_fade_i_w=$(".img_fade_i").outerWidth();
//                  var img_fade_i_talka_top=img_fade_i_t+(218/547)*img_fade_i_h+120-(109/393)*img_fade_i_w;
//                  var img_fade_i_talka_left=img_fade_i_l+img_fade_i_w*(84/393)-(width/1920)*530*0.4/2;
                    var img_fade_i_talka_top=img_fade_i_t+(218/547)*img_fade_i_h+120+(img_fade_i_w*(84/393)/2-553*(height/1080)*0.4);
//                  var img_fade_i_talka_left=img_fade_i_l+img_fade_i_w*(84/393)-(109/393)*img_fade_i_w/2;
                    var img_fade_i_talka_left=img_fade_i_l+img_fade_i_w*(84/393)/2-(109/393)*img_fade_i_w;
//                  var canvas_a='<canvas id="myCanvas_a" style="z-index:1111;position:absolute;display:none;"></canvas>';
                    var img_talk_a='<img src="img/1.png" id="myCanvas_a" style="z-index:1111;position:absolute;display:none;"/>';
//                  $(".page3 .page_bag").append(canvas_a);
                    $(".page3 .page_bag").append(img_talk_a);
                    $("#myCanvas_a").css('width',(width/1920)*530*0.4+"px");
                    $("#myCanvas_a").css('height',553*(height/1080)*0.4+"px");
                    $("#myCanvas_a").css('top',img_fade_i_talka_top);
                    $("#myCanvas_a").css('left',img_fade_i_talka_left);
//                  alert((img_fade_i_w*(84/393))/2)
//                  alert(img_fade_i_h)
//                  var c_a=document.getElementById("myCanvas_a");
//                  var ctx_a=c_a.getContext("2d");
//                  ctx_a.moveTo(0,(img_fade_i_w*(84/393))/2);
//                  ctx_a.lineTo((109/393)*img_fade_i_w,img_fade_i_w*(84/393));
//                  ctx_a.lineTo(((109/393)*img_fade_i_w)/2,0);
//                  ctx_a.moveTo(0,(img_fade_i_w*(84/393))/2);
//                  ctx_a.fillStyle="rgb(1,202,255)";
//                  ctx_a.fill();
//                  ctx_a.lineWidth=1;
//                  ctx_a.strokeStyle="rgb(1,202,255)";
//                  ctx_a.stroke();
//                  var div_a='<div id="myDiv_a" style="background-color:rgb(1,202,255);position:absolute;z-index:1999;display:none;text-align:center;vertical-align:middle;">司机扫码</div>';
//                  $(".page3 .page_bag").append(div_a);
//                  $("#myDiv_a").css('width',(109/393)*img_fade_i_w+"px");
//                  $("#myDiv_a").css('height',img_fade_i_w*(84/393)+"px");
//                  $("#myDiv_a").css('line-height',img_fade_i_w*(84/393)+"px");
//                  var myDiv_a_w=$("#myDiv_a").outerWidth();
//                  var myDiv_a_h=$("#myDiv_a").outerHeight();
//                  $("#myDiv_a").css("border-radius",(84/393)*img_fade_i_w/2+"px");
//                  $("#myDiv_a").css('top',img_fade_i_talka_top+img_fade_i_w*(84/393)/2-myDiv_a_h);
//                  $("#myDiv_a").css('left',img_fade_i_talka_left-myDiv_a_w+(109/393)*img_fade_i_w/2);
                    
                    var img_fade_i_talkb_top=img_fade_i_t+(310/547)*img_fade_i_h+120+(img_fade_i_w*(84/393))/2;
//                  var img_fade_i_talkb_left=img_fade_i_l+img_fade_i_w*(84/393)-(width/1920)*530*0.4/2;
                    var img_fade_i_talkb_left=img_fade_i_l+img_fade_i_w*(84/393)/2-(109/393)*img_fade_i_w;
//                  var canvas_b='<canvas id="myCanvas_b" style="z-index:1111;position:absolute;display:none;"></canvas>';
                    var img_talk_b='<img src="img/2.png" id="myCanvas_b" style="z-index:1111;position:absolute;display:none;"/>';
//                  $(".page3 .page_bag").append(canvas_b);
                    $(".page3 .page_bag").append(img_talk_b);
//                  $("#myCanvas_b").attr('width',(109/393)*img_fade_i_w+"px");
//                  $("#myCanvas_b").attr('height',img_fade_i_w*(84/393)+"px");
                    $("#myCanvas_b").css('width',(width/1920)*530*0.4+"px");
                    $("#myCanvas_b").css('height',553*(height/1080)*0.4+"px");
                    $("#myCanvas_b").css('top',img_fade_i_talkb_top);
                    $("#myCanvas_b").css('left',img_fade_i_talkb_left);
//                  alert((img_fade_i_w*(84/393))/2)
//                  alert(img_fade_i_h)
//                  var c_b=document.getElementById("myCanvas_b");
//                  var ctx_b=c_b.getContext("2d");
//                  ctx_b.moveTo(0,(img_fade_i_w*(84/393))/2);
//                  ctx_b.lineTo((109/393)*img_fade_i_w,0);
//                  ctx_b.lineTo(((109/393)*img_fade_i_w)/2,img_fade_i_w*(84/393));
//                  ctx_b.moveTo(0,(img_fade_i_w*(84/393))/2);
//                  ctx_b.fillStyle="rgb(1,202,255)";
//                  ctx_b.fill();
//                  ctx_b.lineWidth=1;
//                  ctx_b.strokeStyle="rgb(1,202,255)";
//                  ctx_b.stroke();
//                  var div_b='<div id="myDiv_b" style="background-color:rgb(1,202,255);position:absolute;z-index:1999;display:none;text-align:center;vertical-align:middle;">交付货主</div>';
//                  $(".page3 .page_bag").append(div_b);
//                  $("#myDiv_b").css('width',(109/393)*img_fade_i_w+"px");
//                  $("#myDiv_b").css('height',img_fade_i_w*(84/393)+"px");
//                  $("#myDiv_b").css('line-height',img_fade_i_w*(84/393)+"px");
//                  var myDiv_a_w=$("#myDiv_b").outerWidth();
//                  var myDiv_a_h=$("#myDiv_b").outerHeight();
//                  $("#myDiv_b").css("border-radius",(84/393)*img_fade_i_w/2+"px");
//                  $("#myDiv_b").css('top',img_fade_i_talkb_top+img_fade_i_w*(84/393)*3/2-myDiv_a_h);
//                  $("#myDiv_b").css('left',img_fade_i_talkb_left-myDiv_a_w+(109/393)*img_fade_i_w/2);
                    var img_fade_i_talkc_top=img_fade_i_t+(218/547)*img_fade_i_h+120+(img_fade_i_w*(84/393)/2-553*(height/1080)*0.4);
//                  var img_fade_i_talkc_left=img_fade_i_l+img_fade_i_w/2+(109/393)*img_fade_i_w/2;
                    var img_fade_i_talkc_left=img_fade_i_l+img_fade_i_w/2+(109/393)*img_fade_i_w;
//                  var canvas_b='<canvas id="myCanvas_b" style="z-index:1111;position:absolute;display:none;"></canvas>';
                    var img_talk_c='<img src="img/33.png" id="myCanvas_c" style="z-index:1111;position:absolute;display:none;"/>';
//                  $(".page3 .page_bag").append(canvas_b);
                    $(".page3 .page_bag").append(img_talk_c);
//                  $("#myCanvas_b").attr('width',(109/393)*img_fade_i_w+"px");
//                  $("#myCanvas_b").attr('height',img_fade_i_w*(84/393)+"px");
                    $("#myCanvas_c").css('width',(width/1920)*530*0.4+"px");
                    $("#myCanvas_c").css('height',553*(height/1080)*0.4+"px");
                    $("#myCanvas_c").css('top',img_fade_i_talkc_top);
                    $("#myCanvas_c").css('left',img_fade_i_talkc_left);
                    var img_fade_i_talkd_top=img_fade_i_t+(310/547)*img_fade_i_h+120+(img_fade_i_w*(84/393))/2;
//                  var img_fade_i_talkd_left=img_fade_i_l+img_fade_i_w/2+(109/393)*img_fade_i_w/2;
                    var img_fade_i_talkd_left=img_fade_i_l+img_fade_i_w/2+(109/393)*img_fade_i_w;
//                  var canvas_b='<canvas id="myCanvas_b" style="z-index:1111;position:absolute;display:none;"></canvas>';
                    var img_talk_d='<img src="img/3.png" id="myCanvas_d" style="z-index:1111;position:absolute;display:none;"/>';
//                  $(".page3 .page_bag").append(canvas_b);
                    $(".page3 .page_bag").append(img_talk_d);
//                  $("#myCanvas_b").attr('width',(109/393)*img_fade_i_w+"px");
//                  $("#myCanvas_b").attr('height',img_fade_i_w*(84/393)+"px");
                    $("#myCanvas_d").css('width',(width/1920)*530*0.4+"px");
                    $("#myCanvas_d").css('height',553*(height/1080)*0.4+"px");
                    $("#myCanvas_d").css('top',img_fade_i_talkd_top);
                    $("#myCanvas_d").css('left',img_fade_i_talkd_left);
                    

                    $("#myCanvas_a").fadeIn(1500);
//                  $("#myDiv_a").fadeIn(1200);
                    $("#myCanvas_b").fadeIn(1500);
                    $("#myCanvas_c").fadeIn(1500);
                    $("#myCanvas_d").fadeIn(1500);
//                  $("#myDiv_b").fadeIn(1200);
                }else{
                	$(".img_fade_i").fadeOut("slow");
                	$("#myCanvas_a").fadeOut('fast');
                    $("#myCanvas_b").fadeOut('fast');
                    $("#myCanvas_c").fadeOut('fast');
                    $("#myCanvas_d").fadeOut('fast');
                }



                $(".container").animate({"top": nowPage * -100 +"%"},50);  
//              $(".container").css("position", "absolute");
//              $(".container").animate({"top":(nowPage-1) * -height});

                $(".page").eq(nowPage).addClass("current").siblings().removeClass('current');  


            var lock = true;//函数节流的锁  
            if(lock){
            $(document).mousewheel(function(event,delta){  
                if(lock){ 
                lock = false;
                var now=nowPage;	 
                nowPage = nowPage - delta;  
                if(nowPage>=now){
                	now++;
                	nowPage=now;
                	
                }else if(nowPage<now){
                	now--;
                	nowPage=now;
                }
                if(nowPage<0){  
                    nowPage=0;  
                }  
                if(nowPage>5){  
                    nowPage=5;  
                }   
                
                console.log(nowPage)
                if(nowPage==0){
                    show=setInterval('sport()',1000);
                }else{
                	clearInterval(show);
                }
                if(nowPage==1){
//              	$(".img_fade_a").fadeIn("fast");
//              	$('.img_fade_a').animate({left:"65%"},600);
//              	$(".img_fade_a").css("z-index","1999");	
                	$(".img_fade_d").fadeIn("fast");
                	$(".img_fade_d").css("z-index","1999");	
                	$('.img_fade_d').animate({left:"70%"},600);
                	$(".img_fade_b").fadeIn("fast");
                	$('.img_fade_b').animate({left:"45%"},600);
                	$(".img_fade_b").css("z-index","9999");	
                	$(".img_fade_e").fadeIn("fast");
                	$(".img_fade_e").css("z-index","999");	
                	$('.img_fade_e').animate({left:"70%"},600);
//              	$(".img_fade_f").fadeIn("fast");
//              	$('.img_fade_f').animate({right:"20%"},600);
                }else{
//              	$(".img_fade_a").fadeOut("slow");
//              	$('.img_fade_a').animate({left:"0"},600);
                	$(".img_fade_b").fadeOut("slow");
                	$('.img_fade_b').animate({left:width},600);
//              	$(".img_fade_c").fadeOut("slow");
                	$(".img_fade_d").fadeOut("slow");
                	$('.img_fade_d').animate({left:width},600);
                	$(".img_fade_e").fadeOut("slow");
                	$('.img_fade_e').animate({left:"0"},600);
//              	$(".img_fade_f").fadeOut("slow");
//              	$('.img_fade_f').animate({right:"0"},600);
                }
                if(nowPage==2){
                	$(".img_fade_g").fadeIn("fast");
                	$('.img_fade_h').animate({left:"50%"},600);
                	$(".img_fade_h").fadeIn("fast");
                	$('.img_fade_g').animate({left:"45%"},600);
                	$(".img_fade_j").fadeIn("fast");
                	$('.img_fade_j').animate({left:"55%"},600);
                	$(".img_fade_g").css("z-index",'999');
        	        $(".img_fade_h").css("z-index","2999");
        	        $(".img_fade_j").css("z-index","1999");
                }else{
                	$(".img_fade_g").fadeOut("slow");
                	$(".img_fade_h").fadeOut("slow");
                	$(".img_fade_h").animate({left:"0"},600);
                	$('.img_fade_g').animate({left:width},600);
                	$(".img_fade_j").fadeOut("slow");
                	$(".img_fade_j").animate({left:"0"},600);
                }
                if(nowPage==3){
                    $(".img_fade_i").fadeIn("slow");
                    var img_fade_i_t=$(".img_fade_i").position().top; 
                    var img_fade_i_l=$(".img_fade_i").position().left; 
                    var img_fade_i_h=$(".img_fade_i").outerHeight();
                    var img_fade_i_w=$(".img_fade_i").outerWidth();
//                  var img_fade_i_talka_top=img_fade_i_t+(218/547)*img_fade_i_h+120-(109/393)*img_fade_i_w;
//                  var img_fade_i_talka_left=img_fade_i_l+img_fade_i_w*(84/393)-(width/1920)*530*0.4/2;
                    var img_fade_i_talka_top=img_fade_i_t+(218/547)*img_fade_i_h+120+(img_fade_i_w*(84/393)/2-553*(height/1080)*0.4);
//                  var img_fade_i_talka_left=img_fade_i_l+img_fade_i_w*(84/393)-(109/393)*img_fade_i_w/2;
                    var img_fade_i_talka_left=img_fade_i_l+img_fade_i_w*(84/393)/2-(109/393)*img_fade_i_w;
//                  var canvas_a='<canvas id="myCanvas_a" style="z-index:1111;position:absolute;display:none;"></canvas>';
                    var img_talk_a='<img src="img/1.png" id="myCanvas_a" style="z-index:1111;position:absolute;display:none;"/>';
//                  $(".page3 .page_bag").append(canvas_a);
                    $(".page3 .page_bag").append(img_talk_a);
                    $("#myCanvas_a").css('width',(width/1920)*530*0.4+"px");
                    $("#myCanvas_a").css('height',553*(height/1080)*0.4+"px");
                    $("#myCanvas_a").css('top',img_fade_i_talka_top);
                    $("#myCanvas_a").css('left',img_fade_i_talka_left);
//                  alert((img_fade_i_w*(84/393))/2)
//                  alert(img_fade_i_h)
//                  var c_a=document.getElementById("myCanvas_a");
//                  var ctx_a=c_a.getContext("2d");
//                  ctx_a.moveTo(0,(img_fade_i_w*(84/393))/2);
//                  ctx_a.lineTo((109/393)*img_fade_i_w,img_fade_i_w*(84/393));
//                  ctx_a.lineTo(((109/393)*img_fade_i_w)/2,0);
//                  ctx_a.moveTo(0,(img_fade_i_w*(84/393))/2);
//                  ctx_a.fillStyle="rgb(1,202,255)";
//                  ctx_a.fill();
//                  ctx_a.lineWidth=1;
//                  ctx_a.strokeStyle="rgb(1,202,255)";
//                  ctx_a.stroke();
//                  var div_a='<div id="myDiv_a" style="background-color:rgb(1,202,255);position:absolute;z-index:1999;display:none;text-align:center;vertical-align:middle;">司机扫码</div>';
//                  $(".page3 .page_bag").append(div_a);
//                  $("#myDiv_a").css('width',(109/393)*img_fade_i_w+"px");
//                  $("#myDiv_a").css('height',img_fade_i_w*(84/393)+"px");
//                  $("#myDiv_a").css('line-height',img_fade_i_w*(84/393)+"px");
//                  var myDiv_a_w=$("#myDiv_a").outerWidth();
//                  var myDiv_a_h=$("#myDiv_a").outerHeight();
//                  $("#myDiv_a").css("border-radius",(84/393)*img_fade_i_w/2+"px");
//                  $("#myDiv_a").css('top',img_fade_i_talka_top+img_fade_i_w*(84/393)/2-myDiv_a_h);
//                  $("#myDiv_a").css('left',img_fade_i_talka_left-myDiv_a_w+(109/393)*img_fade_i_w/2);
                    
                    var img_fade_i_talkb_top=img_fade_i_t+(310/547)*img_fade_i_h+120+(img_fade_i_w*(84/393))/2;
//                  var img_fade_i_talkb_left=img_fade_i_l+img_fade_i_w*(84/393)-(width/1920)*530*0.4/2;
                    var img_fade_i_talkb_left=img_fade_i_l+img_fade_i_w*(84/393)/2-(109/393)*img_fade_i_w;
//                  var canvas_b='<canvas id="myCanvas_b" style="z-index:1111;position:absolute;display:none;"></canvas>';
                    var img_talk_b='<img src="img/2.png" id="myCanvas_b" style="z-index:1111;position:absolute;display:none;"/>';
//                  $(".page3 .page_bag").append(canvas_b);
                    $(".page3 .page_bag").append(img_talk_b);
//                  $("#myCanvas_b").attr('width',(109/393)*img_fade_i_w+"px");
//                  $("#myCanvas_b").attr('height',img_fade_i_w*(84/393)+"px");
                    $("#myCanvas_b").css('width',(width/1920)*530*0.4+"px");
                    $("#myCanvas_b").css('height',553*(height/1080)*0.4+"px");
                    $("#myCanvas_b").css('top',img_fade_i_talkb_top);
                    $("#myCanvas_b").css('left',img_fade_i_talkb_left);
//                  alert((img_fade_i_w*(84/393))/2)
//                  alert(img_fade_i_h)
//                  var c_b=document.getElementById("myCanvas_b");
//                  var ctx_b=c_b.getContext("2d");
//                  ctx_b.moveTo(0,(img_fade_i_w*(84/393))/2);
//                  ctx_b.lineTo((109/393)*img_fade_i_w,0);
//                  ctx_b.lineTo(((109/393)*img_fade_i_w)/2,img_fade_i_w*(84/393));
//                  ctx_b.moveTo(0,(img_fade_i_w*(84/393))/2);
//                  ctx_b.fillStyle="rgb(1,202,255)";
//                  ctx_b.fill();
//                  ctx_b.lineWidth=1;
//                  ctx_b.strokeStyle="rgb(1,202,255)";
//                  ctx_b.stroke();
//                  var div_b='<div id="myDiv_b" style="background-color:rgb(1,202,255);position:absolute;z-index:1999;display:none;text-align:center;vertical-align:middle;">交付货主</div>';
//                  $(".page3 .page_bag").append(div_b);
//                  $("#myDiv_b").css('width',(109/393)*img_fade_i_w+"px");
//                  $("#myDiv_b").css('height',img_fade_i_w*(84/393)+"px");
//                  $("#myDiv_b").css('line-height',img_fade_i_w*(84/393)+"px");
//                  var myDiv_a_w=$("#myDiv_b").outerWidth();
//                  var myDiv_a_h=$("#myDiv_b").outerHeight();
//                  $("#myDiv_b").css("border-radius",(84/393)*img_fade_i_w/2+"px");
//                  $("#myDiv_b").css('top',img_fade_i_talkb_top+img_fade_i_w*(84/393)*3/2-myDiv_a_h);
//                  $("#myDiv_b").css('left',img_fade_i_talkb_left-myDiv_a_w+(109/393)*img_fade_i_w/2);
                    var img_fade_i_talkc_top=img_fade_i_t+(218/547)*img_fade_i_h+120+(img_fade_i_w*(84/393)/2-553*(height/1080)*0.4);
//                  var img_fade_i_talkc_left=img_fade_i_l+img_fade_i_w/2+(109/393)*img_fade_i_w/2;
                    var img_fade_i_talkc_left=img_fade_i_l+img_fade_i_w/2+(109/393)*img_fade_i_w;
//                  var canvas_b='<canvas id="myCanvas_b" style="z-index:1111;position:absolute;display:none;"></canvas>';
                    var img_talk_c='<img src="img/33.png" id="myCanvas_c" style="z-index:1111;position:absolute;display:none;"/>';
//                  $(".page3 .page_bag").append(canvas_b);
                    $(".page3 .page_bag").append(img_talk_c);
//                  $("#myCanvas_b").attr('width',(109/393)*img_fade_i_w+"px");
//                  $("#myCanvas_b").attr('height',img_fade_i_w*(84/393)+"px");
                    $("#myCanvas_c").css('width',(width/1920)*530*0.4+"px");
                    $("#myCanvas_c").css('height',553*(height/1080)*0.4+"px");
                    $("#myCanvas_c").css('top',img_fade_i_talkc_top);
                    $("#myCanvas_c").css('left',img_fade_i_talkc_left);
                    var img_fade_i_talkd_top=img_fade_i_t+(310/547)*img_fade_i_h+120+(img_fade_i_w*(84/393))/2;
//                  var img_fade_i_talkd_left=img_fade_i_l+img_fade_i_w/2+(109/393)*img_fade_i_w/2;
                    var img_fade_i_talkd_left=img_fade_i_l+img_fade_i_w/2+(109/393)*img_fade_i_w;
//                  var canvas_b='<canvas id="myCanvas_b" style="z-index:1111;position:absolute;display:none;"></canvas>';
                    var img_talk_d='<img src="img/3.png" id="myCanvas_d" style="z-index:1111;position:absolute;display:none;"/>';
//                  $(".page3 .page_bag").append(canvas_b);
                    $(".page3 .page_bag").append(img_talk_d);
//                  $("#myCanvas_b").attr('width',(109/393)*img_fade_i_w+"px");
//                  $("#myCanvas_b").attr('height',img_fade_i_w*(84/393)+"px");
                    $("#myCanvas_d").css('width',(width/1920)*530*0.4+"px");
                    $("#myCanvas_d").css('height',553*(height/1080)*0.4+"px");
                    $("#myCanvas_d").css('top',img_fade_i_talkd_top);
                    $("#myCanvas_d").css('left',img_fade_i_talkd_left);
                    

                    $("#myCanvas_a").fadeIn(1500);
//                  $("#myDiv_a").fadeIn(1200);
                    $("#myCanvas_b").fadeIn(1500);
                    $("#myCanvas_c").fadeIn(1500);
                    $("#myCanvas_d").fadeIn(1500);
//                  $("#myDiv_b").fadeIn(1200);
                }else{
                	$(".img_fade_i").fadeOut("slow");
                	$("#myCanvas_a").fadeOut('fast');
                    $("#myCanvas_b").fadeOut('fast');
                    $("#myCanvas_c").fadeOut('fast');
                    $("#myCanvas_d").fadeOut('fast');
                }
                if(nowPage==4){
//                 setInterval(boom($(".zs")),4000);

                }else{
                      
                }
                if(nowPage==5){
//              	var first_g_w=$(".first_goods").width();
//                $(".first_goods").css("height",first_g_w);
//                $(".second_goods").css("height",first_g_w);
//                $(".third_goods").css("height",first_g_w);
                }else{

                }
                $(".container").animate({"top": nowPage * -100 +"%"});  
//              $(".container").css("position", "absolute");
//              $(".container").animate({"top":(nowPage-1) * -height});
                $(".page").eq(nowPage).addClass("current").siblings().removeClass('current');  
                 lock=false;
                 if(ua.indexOf("mac") != -1){
            	   setTimeout(function(){  
                        lock = true;  
                    },1300);
                    }else{
                    setTimeout(function(){  
                        lock = true;  
                    },500);  
                    }

                }  
            });  
            }
            
            
            $(".first_page").on("click",function(){
            	nowPage=0;
            	if(lock){
            	$(".container").animate({"top": nowPage * -100 +"%"});    
                $(".page").eq(nowPage).addClass("current").siblings().removeClass('current');  
                lock = false;  
                setTimeout(function(){  
                        lock = true;  
                    },1000);  
            	}
            });
            
            $(".down_load").on("click",function(){
            	nowPage=5;
            	if(lock){
            	$(".container").animate({"top": nowPage * -100 +"%"});    
                $(".page").eq(nowPage).addClass("current").siblings().removeClass('current');  
                lock = false;  
                setTimeout(function(){  
                        lock = true;  
                    },500);  
            	}
            });
            $(".salesman_t").on("click",function(){
            	window.location.href="yindex.html";
            });
            $(".assist_t").on("click",function(){
     	        window.location.href="assist.html";
            });
            $(".sqsy").on("click",function(){
     	        window.location.href="joinus.html";
            });
            $(".recruit_t").on("click",function(){
     	        window.location.href="recruit.html";
            });
        });  
        
