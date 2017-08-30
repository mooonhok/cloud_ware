/*
* @Author: Administrator
* @Date:   2017-08-29 12:00:01
* @Last Modified by:   Administrator
* @Last Modified time: 2017-08-30 10:02:14
*/

$(function(){

var num = 1;
var width = $(".banner_box .imgs img").width();
var count = $(".banner_box .imgs img").length
var timer = null;

points(0);
$(".banner_box .imgs img").eq(0).css({'display':'block'});	
function points(index){
		$(".banner_box .points span").eq(index).addClass("current").siblings('span').removeClass('current');
		}
	
	$(".right_btn").click(function(){
	if(num == count){
		$(".banner_box .imgs img").css({'display':'none'});
		$(".banner_box .imgs img").eq(0).css({'display':'block'});
		num=1;
	}else{
		$(".banner_box .imgs img").css({'display':'none'});
		$(".banner_box .imgs img").eq(num).css({'display':'block'});
		num++
	}
	points(num-1);
})
$(".left_btn").click(function(){
	if(num == 1){
		$(".banner_box .imgs img").css({'display':'none'});
		$(".banner_box .imgs img").eq(3).css({'display':'block'});
		num=count;
	}else{
		$(".banner_box .imgs img").css({'display':'none'});
		$(".banner_box .imgs img").eq(num-2).css({'display':'block'});
		num--
	}
	points(num-1);



})
	
	// 4.自动轮播	
	timer=setInterval("$('.right_btn').click()",2000);
	$(".banner_box").mouseover(function(){
		clearInterval(timer);
	}).mouseout(function(){
		timer=setInterval("$('.right_btn').click()",2000);
	})
	
	$(".banner_box .points span").mouseover(function(){
		var index = $(this).index();
	console.log(index);
	$('.banner_box .imgs img').eq(index).css('display','block').siblings('img').css('display','none')
	points(index);
	num=index+1;
	});

})