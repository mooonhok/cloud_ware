<?php
require_once "jssdk.php";
$str=$_SERVER["QUERY_STRING"];
$arr=explode("=",$str);
$tenant_id=substr($arr[1],0,6);
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
		<title>运单详情</title>
		<link rel="stylesheet" href="css/wodeyundan2.css">
		<script type="text/javascript"></script>
	</head>

	<body>
		<div class="box">
			<!-- top -->
			<div class="top"></div>
			<div class="xian1"></div>
			<!-- top1 -->
			<div class="top1">
				<div class="top1-1">
					<h4>地图模式</h4>
				</div>
				<div class="top1-2">
					<h4>文字模式</h4>
				</div>
			</div>
			<div class="top1-3"></div>
			<div class="top1-4"></div>
			<!-- foot -->
			<div class="foot">
				<div class="foot1">
					<div class="foot1-1">
						<div class="syuan1"></div>
						<div class="sxian1"></div>
						<div class="syuan2"></div>
						<div class="sxian2"></div>
						<div class="syuan3"></div>
						<div class="sxian3"></div>
						<div class="syuan4"></div>
						<div class="sxian4"></div>
						<div class="syuan5"></div>
						<div class="sxian5"></div>
						<div class="syuan6"></div>
						<div class="sxian6"></div>
						<div class="syuan7"></div>
						<div style="clear:both;"></div>
					</div>
					<div style="clear:both;"></div>
				</div>

				<div class="foot2"></div>

				<div class="xian"></div>

				<div class="foot3"></div>
				<div class="xian"></div>

				<div class="foot4"> </div>

				<div class="xian"></div>

				<div class="foot5"></div>

				<div class="xian"></div>

				<div class="foot6"></div>

				<div class="xian"></div>

				<div class="foot7"></div>

				<div class="xian"></div>
				<div class="tianchong"></div>
				<div class="foot8"></div>
				<div style="clear:both;"></div>
			</div>
			<div class="center">
				<div class="center1-1"></div>
				<div class="center1-2"></div>
				<div class="xian1"></div>
				<div class="ditu">
				   <div id="map"></div>
				</div>
				<div style="clear:both;"></div>
			</div>

		</div>
	</body>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		$(".top1-3").hide();
		$(".center").css("display", "none");
		$(".top1-1").on("click", function() {
			$(".top1-4").hide();
			$(".foot").css("display", "none");
			$(".top1-3").show();
			$(".center").css("display", "block");
		})
		$(".top1-2").on("click", function() {
			$(".top1-3").hide();
			$(".center").css("display", "none");
			$(".top1-4").show();
			$(".foot").css("display", "block");
		})
	</script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script>
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
		if(openid != null) {
			$.ajax({
				url: "http://api.uminfo.cn/customer.php/wx_openid?wx_openid=" + openid,
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id", tenant_id);
				},
				dataType: 'json',
				type: 'get',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({
				}),
				success: function(msg) {
					//	alert("用户注册成功" + msg.result + "/////" + msg.desc + "//////" + msg.customer);
					if(msg.result == 0) {
						window.location.href = "http://api.uminfo.cn/weixin/wx_register.php?tenant_id="+tenant_id;
					}
				},
				error: function(xhr) {
					bootbox.setLocale("zh_CN");
					bootbox.alert({
						message: "获取后台失败！",
						size: "small"
					})
				}
			});
		}
		//获取order——id
		$(function() {
			var order_id = $.getUrlParam('order_id');
			//alert(order_id);   
			$.ajax({
				url: "http://api.uminfo.cn/order.php/wx_order_z",
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id",tenant_id);
				},
				dataType: 'json',
				type: 'post',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({
					order_id: order_id
				}),
				success: function(msg) {
					//alert(tenant_id+"////"+order_id);
					if(msg.orders.order_status == -2) {
						var a = "<p>运单号 :<span>" + order_id + "</span></p>";
						$(".top").html(a);
						if(msg.orders.sendcity == null) {
							msg.orders.sendcity = "";
						}
						var sendcity = "发站:<span>" + msg.orders.sendcity + "</span>";
						$(".center1-1").html(sendcity);
						if(msg.orders.receivercity == null) {
							msg.orders.receivercity = "";
						}
						var acceptcity = "到站:<span>" + msg.orders.receivercity + "</span>";
						$(".center1-2").html(acceptcity);
						var xiadan = "<div class='foot2-1'>订单状态</div><div class='foot2-2'>" +
							"未受理" + "</div>";
						$(".foot2").html(xiadan);
						$(".syuan1").css("background", "#02F78E");
						var ruku = "<div class='foot3-1'>入库</div><div class='foot3-2'>" +
							"</div>";
						$(".foot3").html(ruku);
						var chuku = "<div class='foot4-1'>出库</div><div class='foot4-2'>" +
							"</div>";
						$(".foot4").html(chuku);
						var zaitu = "<div class='foot5-1'><p>在途 <span>" + "</span></p></div><div class='foot5-2'>" +
							"</div>";
						$(".foot5").html(zaitu);
						var daoda = "<div class='foot6-1'>到达</div><div class='foot6-2'>" +
							"</div>";
						$(".foot6").html(daoda);
						var shouhuo = "<div class='foot7-1'>收货</div><div class='foot7-2'>" +
							"</div>";
						$(".foot7").html(shouhuo);
					//	var wc = "<div class='foot8-1'>运输完成</div>";
						$(".foot8").html(wc);

					} else {
						var a = "<p>运单号 :<span>" + order_id + "</span></p>";
						$(".top").html(a);
						if(msg.orders.sendcity == null) {
							msg.orders.sendcity = "";
						}
						var sendcity = "发站:<span>" + msg.orders.sendcity + "</span>";
						$(".center1-1").html(sendcity);
						if(msg.orders.receivercity == null) {
							msg.orders.receivercity = "";
						}
						var acceptcity = "到站:<span>" + msg.orders.receivercity + "</span>";
						$(".center1-2").html(acceptcity);

						if(msg.orders.plate_number == null) {
							msg.orders.plate_number = "";
						}
						if(msg.orders.order_time0 != null) {
							var xiadan = "<div class='foot2-1'>下单成功</div><div class='foot2-2'>" +
								msg.orders.order_time0 + "</div>";
							$(".foot2").html(xiadan);
							$(".syuan1").css("background", "#02F78E");
							if(msg.orders.order_time1 != null) {
								var ruku = "<div class='foot3-1'>入库</div><div class='foot3-2'>" +
									msg.orders.order_time1 + "</div>";
								$(".foot3").html(ruku);
								$(".syuan2").css("background", "#02F78E");
								$(".sxian1").css("background", "#02F78E");
								if(msg.orders.order_time2 != null) {
									var chuku = "<div class='foot4-1'>出库</div><div class='foot4-2'>" +
										msg.orders.order_time2 + "</div>";
									$(".foot4").html(chuku);
									$(".syuan3").css("background", "#02F78E");
									$(".sxian2").css("background", "#02F78E");
									if(msg.orders.order_time3 != null) {
										var zaitu = "<div class='foot5-1'><p>在途 <span>" + msg.orders.plate_number + "</span></p></div><div class='foot5-2'>" +
											msg.orders.order_time3 + "</div>";
										$(".foot5").html(zaitu);
										$(".syuan4").css("background", "#02F78E");
										$(".sxian3").css("background", "#02F78E");
										if(msg.orders.order_time4 != null) {
											var daoda = "<div class='foot6-1'>到达</div><div class='foot6-2'>" +
												msg.orders.order_time4 + "</div>";
											$(".foot6").html(daoda);
											$(".syuan5").css("background", "#02F78E");
											$(".sxian4").css("background", "#02F78E");
											if(msg.orders.order_time5 != null) {
												var shouhuo = "<div class='foot7-1'>收货</div><div class='foot7-2'>" +
													msg.orders.order_time5 + "</div>";
												$(".foot7").html(shouhuo);
												$(".syuan6").css("background", "#02F78E");
												$(".sxian5").css("background", "#02F78E");
												$(".sxian6").css("background", "#02F78E");
												$(".syuan7").css("background", "#02F78E");
												var wc = "<div class='foot8-1'>运输完成</div>";
												$(".foot8").html(wc);
											}
										}
									}
								}
							}
						}
					};
				},
				error: function(xhr) {
					alert("获取后台失败");
				}
			});
		});
	</script>
	<script type="text/javascript">
       对浏览器的UserAgent进行正则匹配，不含有微信独有标识的则为其他浏览器
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
<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
<script>
 /*
     * 注意：
     * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
     * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
     * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
     *
     */
    wx.config({
        debug: true,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'openLocation', 'getLocation'
        ]
    });	
    wx.ready(function () {
  document.getElementById("map").innerHTML=function(){	
	wx.openLocation({
    latitude: 120.840000, // 纬度，浮点数，范围为90 ~ -90
    longitude: 83.000000, // 经度，浮点数，范围为180 ~ -180。
    name: '1', // 位置名
    address: '1', // 地址详情说明
    scale: 1, // 地图缩放级别,整形值,范围从1~28。默认为最大
    infoUrl: '1' // 在查看位置界面底部显示的超链接,可点击跳转
});
};
 });
	wx.error(function (res) {
  //alert(res.errMsg);
});
</script>
</html>