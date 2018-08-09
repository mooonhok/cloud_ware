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
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
		<script type="text/javascript" src="layer/layer.js"></script>
		  <script type="text/javascript" src="js/config.js"></script>
		<title>公司介绍</title>
		<title></title>
		<style>
			*{
	        margin: 0;
	       padding: 0;
         	font-family: "楷体";
         }
         .top{
         	height:50px;
         	width:98%;
         	margin-left:2%;
         	line-height:50px;
         	font-size:20px;
         	color:rgb(92,163,185);
         	text-align:center;
         	word-wrap:break-word;
         	float:left;
         }
         .center{
         	width:90%;
         	margin-left:5%;
         	height:150px;
         	float:left;
         }
         .center img{
         	width:100%;
         	/*margin-left:2%;*/
         	height:150px;
         	border-radius: 3px;
         		position: relative;
         		
         }
         #c_text{
         	width:100%;
         	height:30px;
         	line-height:30px;
            position: absolute;
            top:168px;
            left:5%;
            color:white;
         }
         .foot{
         	width:90%;
         	margin-left:5%;
         	color:#333333;
         	font-size:16px;
         	word-wrap:break-word;
         	float:left;
         	margin-top:10px;
         	border:1px solid #eeeeee;
         	border-radius: 4px;
         	text-indent:2em;
         }
		 .wangzhi{
			width:90%;
         	margin-left:5%;
			text-align:center;
         	font-size:20px;
         	float:left;
         	margin-top:10px;
		 }
		</style>
	</head>
	<body>
		<div class="top" id="company"></div>
		<div class="center"><img id="cimg" src=""><div id="c_text"></div></div>
		<div class="foot"></div>
		<div class="wangzhi"><span>官方网址:</span><span style="color:rgb(92,163,185);">www.jswsx56.cn</span></div>
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
    
       //判断openid是否已经被注册
       var openid = $.cookie('openid'+tenant_id);
		 $(document).ready(function(){
           $.ajax({
               url: p_url+"tenant.php/tenant_introduction?tenant_id="+tenant_id,
               beforeSend: function(request) {
               },
               dataType: 'json',
               type: 'get',
               ContentType: "application/json;charset=utf-8",
               data: JSON.stringify({
               }),
               success: function(msg) {
               	if(msg.result==0){
               		 $("#company").html(msg.tenant.company);
               		 $("#cimg").attr('src', msg.tenant.tenantimg);
               		 $("#c_text").html(msg.tenant.tenanttext);
               		 $(".foot").html(msg.tenant.c_introduction);
               	}else{
               		 layer.msg(msg.desc);
               	}
               },
               error: function(e) {
                   layer.msg("获取后台失败！");
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
