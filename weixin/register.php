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
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Access-Control-Allow-Origin" content="*">
		<meta content="width=device-width,initial-scale=1.0,maximum-scale=1,minimum-scale=0.1,user-scalable=0" name="viewport">
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
		<link rel="stylesheet" href="css/zhuce.css">
		<title>注册</title>
		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="layer/layer.js"></script>
		<script type="text/javascript">
			$(function() {
				//改变div的高度
				$(".content").css("height", $(window).height());
			});
		</script>
	</head>
	<body>
		<div class="content">
			<div class="box_top">
				<div class="box_top1">Welcome</div>
			</div>
			<div class="box_center">

				<div class="box_center1"><span>注<span class="kong"></span>册</span>
				</div>

				<div class="box_center1"><input id="customername" placeholder="姓名"></div>

				<div class="box_center1"><input id="customertel" placeholder="手机号码" pattern="[0-9]*" type="tel"></div>

			</div>
			<div class="box_buttom">
				<div id="submit">注<span class="kong"></span>册</div>
			</div>
		</div>
	</body>
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
		var openid = $.cookie('openid'+tenant_id);
	//	alert(tenant_id)
	//	alert(openid)
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
					if(msg.result == 1) {
						layer.msg("你已经注册过了");
						$("#submit").css("background", "gray");
						$("#customername").val(msg.customer.customer_name);
						$("#customertel").val(msg.customer.customer_phone);
						$("#customername").attr('disabled','true');
						$("#customertel").attr('disabled','true');
					} else {
						(function($) {
							$.getUrlParam = function(name) {
								var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
								var r = window.location.search.substr(1).match(reg);
								if(r != null) return unescape(r[2]);
								return null;
							}
						})(jQuery);
						$(function() {
							var page = parseInt($.getUrlParam('page'));
							$("#submit").on('click', function() {
								var customername = $("#customername").val();
								var customertel = $("#customertel").val();
								var openid = $.cookie('openid'+tenant_id);
								if(!/^1[34578]\d{9}$/.test(customertel)) {
									layer.msg("手机号码格式不对")
									return false;
								} else {
									$.ajax({
										url: "http://api.uminfo.cn/customer.php/wx_customer",
										beforeSend: function(request) {
											request.setRequestHeader("tenant-id", tenant_id);
										},
										dataType: 'json',
										type: 'post',
										ContentType: "application/json;charset=utf-8",
										data: JSON.stringify({
											customer_name: customername,
											customer_phone: customertel,
											wx_openid: openid
										}),
										success: function(msg) {
											layer.msg("用户注册成功");
											$("#submit").removeAttr('onclick');
												window.location.href = "http://api.uminfo.cn/weixin/test.html?tenant_id="+tenant_id+"&page=1";
										},
										error: function(xhr) {
											alert("获取后台失败！");
										}
									});
								}
							});
						});
					}
				},
				error: function(xhr) {
					alert("获取后台失败！");
				}
			});
		}
	</script>
	<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
	<script>
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
</html>