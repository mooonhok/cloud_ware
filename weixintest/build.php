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
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<style>
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
         }
         .box img{
         	width: 100%;
         	height: 100%;
         }
	</style>
</head>
<body>
	<div class="box">
		<img src="images/build.jpg" alt="">
	</div>
</body>
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
 <script>
//		window.alert = function(name){
//			 var iframe = document.createElement("IFRAME");
//			iframe.style.display="none";
//			iframe.setAttribute("src", 'data:text/plain,');
//			document.documentElement.appendChild(iframe);
//			window.frames[0].window.alert(name);
//			iframe.parentNode.removeChild(iframe);
//		}
//			
//		alert('修改');
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