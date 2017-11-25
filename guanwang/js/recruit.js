$(document).ready(function(){
	$(".salesman_t").on("click",function(){
        window.location.href="yindex.html";
            });
     $(".first_page").on("click",function(){
     	window.location.href="index.html";
     });
     $(".down_load").on("click",function(){
     	window.location.href="index.html";
     });
     $(".assist_t").on("click",function(){
     	window.location.href="assist.html";
     });
    $(".recruit_t").on("click",function(){

     	window.location.reload();
     });
     

var c=document.getElementById("myCanvas");
var ctx=c.getContext("2d");
ctx.font="80px txbdxk";
ctx.strokeStyle = 'green';
ctx.strokeText("大众创业，万众创新",30,200);

});