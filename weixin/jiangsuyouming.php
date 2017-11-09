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
	<title>靖江万事鑫</title>
	
	<style>
		*{margin: 0 auto;
		 padding:0;
		box-sizing:border-box;}
		html,body{height:100%; width:100%; overflow:hidden; margin:0;
         padding:0;}

		.box{
			width: 100%;
			height:100%;
			background: #64E3DA;
		}
		.box1{
			width: 100%;
			height: 150px;
			
			text-align: center;
			line-height: 200px;
			font-size: 22px;
			
		}
		.box2{
			width: 90%;
			height: 300px;
			background:rgba(0,0,0,0.2); 
			border-radius: 5px;
			font: 15px '黑体';
			line-height: 25px;
	
			
		}
		.box3{
			width: 95%;

		}
	</style>
</head>
<body>
   <div class="box">
   	<div class="box1">靖江万事鑫</div>
	<div class="box2">
	<div class="box3">
		<br>
		<div id="introduction">
			<!--公司介绍: 本公司主要从普通货运、大型物件运输、货物专用运输、综合货运站；水路货物代理、船舶代理；港口货物装卸、仓储和港口驳运服务。-->
		</div>
		<br>
		<div id="address">
			<!--公司地址: 江苏省泰州市靖江经济开发区康桥路2号港城大厦。-->
		</div>
		<br>
		<div id="phone">
			<!--公司地址: 江苏省泰州市靖江经济开发区康桥路2号港城大厦。-->
		</div>
	</div>
</div>
   </div>
   <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
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
//       if(openid != null) {
//           $.ajax({
//               url: "http://api.uminfo.cn/customer.php/wx_openid?wx_openid="+openid,
//               beforeSend: function(request) {
//                   request.setRequestHeader("tenant-id", tenant_id);
//               },
//               dataType: 'json',
//               type: 'get',
//               ContentType: "application/json;charset=utf-8",
//               data: JSON.stringify({
//
//               }),
//               success: function(msg) {
//                   if(msg.result == 0) {
//                       window.location.href = "http://api.uminfo.cn/weixin/test.html?tenant_id="+tenant_id+"&page=5";
//                   }
//               },
//               error: function(e) {
//                   alert("获取后台失败！")
//               }
//           });
//       }
   </script>
   <script type="text/javascript">
       $(document).ready(function(){
       
           $.ajax({
               url: "http://api.uminfo.cn/tenant.php/tenant_introduction?tenant_id="+tenant_id,
               beforeSend: function(request) {
               },
               dataType: 'json',
               type: 'get',
               ContentType: "application/json;charset=utf-8",
               data: JSON.stringify({
               }),
               success: function(msg) {
                   if(msg.tenant.c_introduction==""){
                       msg.tenant.c_introduction='暂无';
				   }   
                 // alert(msg.tenant.c_introduction+"xxx"+msg.tenant.company+"////"+msg.tenant.address+"***"+msg.contact.customer_phone);
                   $('.box1').text(msg.tenant.company);
                   $('#introduction').html('公司介绍：'+msg.tenant.c_introduction);
                   $('#address').html('地址：'+msg.tenant.ad);
                   $('#phone').html('电话：'+msg.contact.customer_phone);
               },
               error: function(e) {
                   alert("获取后台失败！")
               }
           });
       });

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
</body>
</html>