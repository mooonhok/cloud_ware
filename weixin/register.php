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
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Access-Control-Allow-Origin" content="*">
		<meta content="width=device-width,initial-scale=1.0,maximum-scale=1,minimum-scale=0.1,user-scalable=0" name="viewport">
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
		<title>个人信息</title>
		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="layer/layer.js"></script>
		<script type="text/javascript" src="js/config.js"></script>
		   <script type="text/javascript">
    	$(function() {
				//改变div的高度
				$(".content").css("height", $(window).height());
			});
    </script>
		<style>
			*{
				margin: 0;
				padding: 0;
				font-family: "微软雅黑";
			}
			body{
				width: 100%;
				height: 100%;
			}
			.box{
				width: 100%;
				height: 100%;
				position: absolute;
				background: white;
			}
			.box1{
				width: 80%;
				float: left;
				margin-top: 50px;
				margin-left: 10%;
				text-align: center;
			}
			.box1_1{
				width: 100%;
				height: 50px;
				text-align: center;
				 
			}
			.box1_2{
				width: 100%;
				height: 50px;
				line-height: 50px;
				/*border-radius: 50px;*/
				/*background: #00DDDD;*/
				color:#999999 ;
				border-bottom-style:solid;
				margin-top: 30px;
			}
			.sp{
				display: inline-block;
				float: left;
				margin-left: 10px;
			}
			input{
				list-style: none;
				outline-style: none;
				border: none;
				background: none;
				display: inline-block;
				height: 30px;
				line-height: 30px;
				float: left;
				margin-top: 10px;
				margin-left: 10px;
			}
			.submit{
				border-radius:50px;
				width: 50%;
				height:40px;
				margin-top: 22px;
                font-size: 25px;
                line-height: 40px;
                text-align: center;
                 /*float: left;*/
                 margin-left:25%;
                 color:white;
                /*border-style: solid;*/
               background: #2fb8c3;
			}
			.kong{
				display: inline-block;
				width: 20px;
			}
			.sp1{
				display: inline-block;
				width:1px;
				height:50px;
				margin-left:4%;
				float:left;
				background: white;
			}
			.pic{
			    width:70%;
			    height:70px;
			}
            .beizhu
                width:100%;
                margin-top: 40px;
                font-size:12px;
                /*float: left;*/
            }
		</style>
    </head>
    <body>
		<div class="box">
			<div class="box1">
				<div class="box1_1"><img class="pic" src="images/5.png"></div>
				<div class="box1_2"><span class="sp">姓名:</span><span class="sp1"></span><input type="text" id="customername" placeholder="请输入您的姓名"></div>
				<div class="box1_2"><span class="sp">电话:</span><span class="sp1"></span><input type="tel" id="customertel" placeholder="请输入您的手机号码"></div>
			<div id="submit" class="submit">确<span class="kong"></span>认</div>
<!--            <div class="beizhu">注：查询运单，需要留下您的姓名和电话</div>-->
                <div class="beizhu">注：下单，查单需要绑定您的姓名和电话</div>
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
				url: p_url+"customer.php/wx_openid?wx_openid=" + openid,
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
										url: p_url+"customer.php/wx_customer",
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
												window.location.href = p_url+"weixin/test.html?tenant_id="+tenant_id+"&page=1";
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
////     对浏览器的UserAgent进行正则匹配，不含有微信独有标识的则为其他浏览器
//  var useragent = navigator.userAgent;
//  if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
//      // 这里警告框会阻塞当前页面继续加载
//      alert('已禁止本次访问：您必须使用微信内置浏览器访问本页面！');
//      // 以下代码是用javascript强行关闭当前页面
//     var opened = window.open('http://www.uminfo.cn', '_self');
//      opened.opener = null;
//      opened.close();
//  }
</script>
</html>