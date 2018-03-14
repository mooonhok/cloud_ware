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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>

	<title>运单号查询</title>
	<style type="text/css">
		*{
			margin: 0;padding: 0;
			font-family: "微软雅黑"
		}
		html,body{height:100%; width:100%;
         overflow:hidden; margin:0;
         padding:0;}
		.box{
			width: 100%;
			height: 100%;
			background:#E7E7E7;
		}
		.box1{
			width: 80%;
			height: 180px;
			background: white;
			float: left;
			margin-left: 10%;
			margin-top: 50px;
			border-radius: 10px;
		}
		.top{
			width: 90%;
			float: left;
			margin-left: 5%;
			/* border: 1px solid red; */
			height: 41px;
		}
		.top1{
			width: 25%;
			height: 1px;
			background: #989898;
			float: left;
			/* margin-left: 5%; */
			margin-top: 20px;
		}
		.top2{
			width: 50%;
			height: 41px;
			text-align: center;
			line-height: 41px;
			float: left;
			font-size: 18px;
		}
		.top3{
			width: 25%;
			height: 1px;
			background: #989898;
			float: right;
			/* margin-right: 5%; */
			margin-top: 20px;
		}
		.center{
			width: 80%;
			height: 46px;
			float: left;
			margin-left: 10%;
			margin-top: 20px;
			border: 1px solid #B0B0B0;
			border-radius: 5px;
		}
		.center1{
			width: 30%;
			float: left;
			height: 46px;
			text-align: center;
			line-height: 46px;
			font-size: 16px;
		}
		.center2{
			float: left;
			width: 50%;
			height: 40px;
			margin-left: 3%;
			margin-top: 3px;
		}
		.center2 input{
			width: 100%;
			height: 40px;
			line-height: 40px;
			border:0;outline-style: none;
			font-size: 14px;
		}
		.center3{
			width: 15%;
			float: left;
			height: 20px;
			margin-top: 10px;
			margin-left: 1%;

		}
		.center3 img{
			width: 20px;
			height: 20px;
		}
		.foot{
			width: 80%;
			height: 40px;
			float: left;
			margin-left: 10%;
			/* border: 1px solid red; */
			margin-top: 20px;
			border-radius: 5px;
			background: #24D9CA;
			text-align: center;
			line-height: 40px;
			color: white;
			font-size: 24px;
		}
	</style>
</head>
<body>
	<div class="box">
		<div class="box1">
		<div class="top">
			<div class="top1"></div>
			<div class="top2">运单号查询</div>
			<div class="top3"></div>
		</div>
		<div class="center">
			<div   class="center1">运单号</div>
			<div class="center2"><input id="order_id" type="number" placeholder="请输入运单号" pattern="[0-9]*"></div>
			<div class="center3" id="saoman"><img src="images/saoma.png" alt=""></div>
		</div>
		<div  id="sumbit"  class="foot">
			查   询
		</div>

		</div>
	</div>

</body>
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script>
//	alert(location.href.split('#')[0]);
		(function($) {
			$.getUrlParam = function(name) {
				var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
				var r = window.location.search.substr(1).match(reg);
				if(r != null) return decodeURI(r[2]);
				return null;
			}
		})(jQuery);
		var tenant_id=$.getUrlParam('tenant_id');
	$("#sumbit").click(function(){
		var order_id=$("#order_id").val();
		if(order_id.length!=0){
			$.ajax({
				url: "http://api.uminfor.cn/order.php/wx_order_z",
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id", tenant_id);
				},
				dataType: 'json',
				type: 'post',
				contentType: "application/json;charset=utf-8",
				data: JSON.stringify({
					order_id: order_id
				}),
				success: function(msg) {
					if(msg.result == 1) {
				       layer.msg("订单不存在");
					}else{
					// alert(order_id);
		            window.location.href="http://api.uminfor.cn/weixintest/waybill_details.html?order_id="+order_id+"&tenant_id="+tenant_id;
					}
				},
				error: function(xhr) {
					alert("获取后台失败");
				}
			});
		}else{
			 layer.msg("没有填写订单号");
		}
	});
</script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script>
		//判断openid是否已经被注册
		var openid = $.cookie('openid'+tenant_id);
	//	alert(openid);
		if(openid != null) {
			$.ajax({
				url: "http://api.uminfor.cn/customer.php/wx_openid?wx_openid="+openid,
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id", tenant_id);
				},
				dataType: 'json',
				type: 'get',
				contentType: "application/json;charset=utf-8",
				data: JSON.stringify({
				}),
				success: function(msg) {
					if(msg.result == 0) {
						 window.location.href = "http://api.uminfor.cn/weixintest/test.html?tenant_id="+tenant_id+"&page=5";
					}
				},
				error: function(xhr) {
					alert("获取后台数据失败")
					}
				})
			};

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
  document.querySelector('#saoman').onclick = function () {
    wx.scanQRCode({
      needResult: 1,
      desc: 'scanQRCode desc',
      success: function (res) {
          var a=new Array();
          a=res.resultStr.split(",");
         // alert(a[1]);
       if(a[1]!=null){
          // alert(a)
			$.ajax({
				url: "http://api.uminfor.cn/order.php/wx_order_z",
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id",tenant_id);
				},
				dataType: 'json',
				type: 'post',
				contentType: "application/json;charset=utf-8",
				data: JSON.stringify({
					order_id: a[1]
				}),
				success: function(msg) {
					if(msg.result == 1) {
				     layer.msg("订单不存在");
					}else{
		            window.location.href="http://api.uminfor.cn/weixintest/waybill_details.html?order_id="+a[1]+"&tenant_id="+tenant_id;
					}
				},
				error: function(xhr) {
					alert("获取后台失败");
				}
			});
		}else{
			 layer.msg("没有扫描到条形码");
	   }
      }
    });
  };
});
pushHistory(); 
   window.addEventListener("popstate", function(e) { 
     	//alert("123456789");
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