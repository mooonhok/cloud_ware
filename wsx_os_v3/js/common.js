$(document).ready(function(){
	$(".subnav-items").eq(0).children(".subnav-item").eq(0).css("margin-left","661px");
	$(".subnav-items").eq(1).children(".subnav-item").eq(0).css("margin-left","799px");
});

function navOver(index){
	$(".nav-item").removeClass("nav-active");
	$(".nav-item").eq(parseInt(sessionStorage.getItem("aindex"))).addClass("nav-active");
	$(".subnav-items").css("display","none");
	if(index==1||index==3){
		$(".subnav").css("display","block");
		if(index==1){
			$(".subnav-items").eq(0).css("display","inline-block");
		}else if(index==3){
			$(".subnav-items").eq(1).css("display","inline-block");
		}
	}else{
		$(".subnav").css("display","none");
	}
}
function navOut(index){
	if(index==1||index==3){
		if($(".nav-item").eq(index).is('.nav-item.nav-visit')){
			$(".subnav").css("display","none");
		}
	}
}
function navTo(index){
	sessionStorage.setItem("oindex",0);
	sessionStorage.setItem("psindex",0);
	sessionStorage.setItem("awindex",0);
	if(index==0){
		window.location.href="index.html";
	}else if(index==1){
		window.location.href="product_service.html";
	}else if(index==2){
		window.location.href="operate.html";
	}else if(index==3){
		window.location.href="about_wsx.html";
	}
}

function subnavEnter(index){
	$(".nav-item").removeClass("nav-active");
	$(".nav-item").eq(parseInt(sessionStorage.getItem("aindex"))).addClass("nav-active");
	$(".nav-item").eq(index).addClass("nav-active");
}
function subnavLeave(){
	$(".nav-item").removeClass("nav-active");
	$(".nav-item").eq(parseInt(sessionStorage.getItem("aindex"))).addClass("nav-active");
	$(".subnav").css("display","none");
}
function subnavTo(index){
	if(index==0||index==1||index==2){
		if(index==0){
			sessionStorage.setItem("psindex",0);
		}else if(index==1){
			sessionStorage.setItem("psindex",5);
		}else if(index==2){
			sessionStorage.setItem("psindex",8);
		}
		if(window.location.href.lastIndexOf("product_service.html")==-1){
			window.location.href="product_service.html";
		}else{
			$(".smalltitle-item").removeClass("smalltitle-active");
			$(".intro-subtitle").removeClass("subtitle-active");
			$(".smalltitle-view").removeClass("titleview-visible");
			$(".subtitle-view").removeClass("titleview-visible");
			if(index==0){
				$(".smalltitle-item").eq(0).addClass("smalltitle-active");
				$(".smalltitle-view").eq(0).addClass("titleview-visible");
			}else if(index==1){
				$(".smalltitle-item").eq(5).addClass("smalltitle-active");
				$(".smalltitle-view").eq(5).addClass("titleview-visible");
			}else if(index==2){
				$(".intro-subtitle").eq(2).addClass("subtitle-active");
				$(".subtitle-view").eq(2).addClass("titleview-visible");
			}
		}
	}else{
		sessionStorage.setItem("awindex",index-3);
		if(window.location.href.lastIndexOf("about_wsx.html")==-1){
			window.location.href="about_wsx.html";
		}else{
			$(".intro-subtitle").removeClass("subtitle-active");
			$(".subtitle-view").removeClass("titleview-visible");
			$(".intro-subtitle").eq(index-3).addClass("subtitle-active");
			$(".subtitle-view").eq(index-3).addClass("titleview-visible");
		}
	}
}