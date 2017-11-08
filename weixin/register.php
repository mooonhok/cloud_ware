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
		<style>
			* {
				margin: 0;
				padding: 0;
			}
			 .box{
            width: 100%;
            height: 100%;
            position: absolute;
            background: url('images/come.png');
            background-size: 100% 100%;
        }
			.box2 {
				width: 100%;
				height: 100%;
				position: absolute;
				z-index: 100;
				background: rgba(0, 0, 0, 0.3);
			}
			.box2_1 {
				width: 80%;
				height: 150px;
				float: left;
				margin-left: 10%;
				text-align: center;
				margin-top: 50px;
				font: 24px "微软雅黑";
				text-align: center;
                color:white;
			}
			.box3 {
				width: 70%;
				float: left;
				margin-left: 15%;
				/* border: 1px solid red; */
				margin-top: 50px;
			}
			.box3_1 {
				width: 100%;
				height: 40px;
				float: left;
				background: rgba(0, 0, 0, 0.7);
			}
			.box3_2 {
				width: 100%;
				height: 40px;
				float: left;
				margin-top: 20px;
				background: rgba(0, 0, 0, 0.7);
			}
			.box3_1 input {
				width: 100%;
				height: 40px;
				line-height: 40px;
				background: rgba(0, 0, 0, 0.0);
				color: white;
				outline-style: none;
				list-style: none;
				border: 0;
			}
			.box3_2 input {
				width: 100%;
				height: 40px;
				line-height: 40px;
				background: rgba(0, 0, 0, 0.0);
				color: white;
				outline-style: none;
				list-style: none;
				border: 0;
			}
			.kon {
				width: 5%;
				height: 40px;
				float: left;
			}
			.in {
				width: 95%;
				height: 40px;
				float: left;
			}
			.login {
				width: 80%;
				float: left;
				margin-left: 10%;
				height: 25px;
				border-radius: 50px;
				background: #FECD07;
				color: white;
				font-size: 18px;
				text-align: center;
				margin-top: 30px;
				line-height: 25px;
			}
			.box4 {
				width: 100%;
				height: 20px;
				text-align: center;
				float: left;
				margin-top: 20px;
			}
			.box4 a {
				color: white;
				font-size: 14px;
			}
			.xian {
				width: 100%;
				background: white;
				height: 1px;
			}
::-webkit-input-placeholder{
  color: white;
}
:-moz-placeholder {/* Firefox 18- */
   color: white;
}
::-moz-placeholder{/* Firefox 19+ */
  color: white;
}
:-ms-input-placeholder {
   color: white;
}
 .box3_5{
        	width: 100%;
        	height: 40px;
        	float: left;
        	margin-top: 20px;
        	/* background: rgba(0, 0, 0, 0.7); */
        	line-height: 40px;
        }
        .b1{
        	width: 50%;
        	float: left;
        	height: 50px;
        	line-height: 50px;
        	color: white;
        	text-align: center;
        }
        .b2{
        	width: 50%;
        	float: left;
        	height: 50px;
        	line-height: 50px;
        	color: white;
        	text-align: center;
        }
		</style>
	</head>
	<body>
		<div class="box">
			<div class="box2_1">
			    您可否留下您的姓名和电话
			</div>
			<div class="box3">
				<div class="box3_1">
					<div class="kon"></div>
					<div class="in"><input type="tel" id="customername" placeholder="姓名"></div>
				</div>
				<div class="box3_2">
					<div class="kon"></div>
					<div class="in"><input type="text" id="customertel" placeholder="手机号"></div>
				</div>
				<div class="login" id="submit">
					注册
				</div>
				<div style="clear: both;"></div>
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