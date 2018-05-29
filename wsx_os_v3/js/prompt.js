function a_show(){
	$(".mask").css("display","block");
	$("#alert-view").css("display","block");
}
function a_know(){
	$("#alert-view").css("display","none");
	$(".prompt-center").text("");
	$(".mask").css("display","none");
}
function c_show(){
	$(".mask").css("display","block");
	$("#confirm-view").css("display","block");
}
function c_cancel(){
	$("#confirm-view").css("display","none");
	$(".prompt-center").text("");
	$(".mask").css("display","none");
}