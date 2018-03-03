<?php
require_once "jssdk.php";
$str=$_SERVER["QUERY_STRING"];
$arr=explode("=",$str);
$tenant_id=substr($arr[1],0,10);
$appid=substr($arr[2],0,18);
$secret=substr($arr[3],0,32);
$jssdk = new JSSDK($appid,$secret);
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Access-Control-Allow-Origin" content="*">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
		<title>选择专线</title>
		<style>
			#cneter{
				width:100%;
				height:100%;
			}
			.tet{
				width:90%;
				height:80px;
				margin-top:20px;
				margin-left:5%;
				border-style: line;
				border-radius: 5px;
			}
			.but{
				width:75%;
				text-align:center;
				margin-left:5%;
				height:80px;
				line-height:40px;
				/*background-color:#24D9C9;*/
				color: white;
				border-radius: 5px;
				float:left;
			}
			.picl{
				width:15%;
				height:60px;
				margin-top:10px;
				/*background-color:#24D9C9;*/
				border-radius: 5px;
			}
		</style>
	</head>
	<body>
	<div id="center"></div>
   </body>
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script type="text/javascript">
		(function($) {
			$.getUrlParam = function(name) {
				var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
				var r = window.location.search.substr(1).match(reg);
				if(r != null) return decodeURI(r[2]);
				return null;
			}
		})(jQuery);
		var tenant_id=$.getUrlParam('tenant_id');
		//判断openid是否已经被注册
		var openid = $.cookie('openid'+tenant_id);
	    window.alert = function(name){
			 var iframe = document.createElement("IFRAME");
			iframe.style.display="none";
			iframe.setAttribute("src", 'data:text/plain,');
			document.documentElement.appendChild(iframe);
			window.frames[0].window.alert(name);
			iframe.parentNode.removeChild(iframe);
		}
	   var appid=$.getUrlParam('appid');
	   var secret=$.getUrlParam('secret');
	     $.ajax({
			url: "http://api.uminfo.cn/wx_test.php/sontenants?tenant-id="+tenant_id,
			dataType: 'json',
			type: 'get',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({}),
			success: function(msg) {
			    for(var i=0;i<msg.tenants.length;i++){
			    	$("#center").append("<div class='tet'><div class='but' id='center"+i+"'>"+msg.tenants[i].jcompany+"</br>"+msg.tenants[i].telephone+"</div><img src='images/lefttwo.png' class='picl'></div>");
//			    	alert(msg.tenants[i].tenantimg);
                    if(i%3==0){
                        $(".tet").css("background-color","#227e69");
                    }else if(i%3==1){
                    	$(".tet").css("background-color","#4ea039");
                    }else{
                    	$(".tet").css("background-color","#0689b0");
                    }
			    
			    	$("#center"+i).on("click",function(){
			    		var a=$(this).attr('id').substring(6);
			    		$.cookie("openid"+msg.tenants[a].tenant_id,openid);
			    		window.location.href="http://api.uminfo.cn/weixin/sendtwo.php?tenant_id="+msg.tenants[a].tenant_id+'&appid='+appid+'&secret='+secret+'&tenantname='+msg.tenants[a].jcompany;
			    	});
			    }
			},
			error: function(e) {
				layer.msg("获取后台失败!");
			}
		});
	
</script>

<script>
    /*
     * 注意：
     * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
     * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
     * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
     *
     */
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'checkJsApi', 'scanQRCode'
        ]
    });
    wx.ready(function () {
    
    });
pushHistory(); 
   window.addEventListener("popstate", function(e) { 
        wx.closeWindow();
   }, false); 
   function pushHistory() { 
     var state = { 
       title: "title", 
       url: "#"
     }; 
     window.history.pushState(state, "title", "#"); 
   }

wx.error(function (res) {
  //alert(res.errMsg);
});
</script>
<script type="text/javascript">
    // 对浏览器的UserAgent进行正则匹配，不含有微信独有标识的则为其他浏览器
    var useragent = navigator.userAgent;
    if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
        // 这里警告框会阻塞当前页面继续加载
        alert('已禁止本次访问：您必须使用微信内置浏览器访问本页面！');
        // 以下代码是用javascript强行关闭当前页面
        var opened = window.open('http://www.uminfo.cn', '_self');
        opened.opener = null;
        opened.close();
    }
</script>
</html>