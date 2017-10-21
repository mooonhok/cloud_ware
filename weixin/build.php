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