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
		<title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<title>联系我们</title>
		<style>
			* {
				margin: 0 auto;
				padding: 0;
				box-sizing: border-box;
			}
			
			html,
			body {
				height: 100%;
				width: 100%;
				overflow: hidden;
				margin: 0;
				padding: 0;
				background-color: #EEEEEE;
				color:#333333;
			}
			.box{
				height:90%;
				width:90%;
				margin-left:5%;
				margin-top:5%;
				/*background-color:rgb(238,238,238);*/
				border-radius: 10px;
				background-color:white;
				border:1px solid grey;
			}
			.topleft{
				float:left;
				width:30%;
				height:100px;
				margin-left:2%;
				margin-top:10px;
				line-height:100px;
				text-align: center;
			
			}
			.topleft img{
				width:100px;
				height:100px;
				border-radius:50%;
			}
			.topright{
				float:left;
				width:63%;
				height:100px;
				margin-left:3%;
				margin-top:10px;
			
			}
			
			.tc{
				height:40px;
				width:100%;
				word-wrap:break-word;
				line-height:40px;
				float:left;
				font-size:19px;
				text-align: center;
			}
			.tc2{
				height:40px;
				width:100%;
				word-wrap:break-word;
				line-height:40px;
				float:left;
				font-size:17px;
				text-align: center;
			}
			.center{
				width:96%;
				margin-left:2%;
				height:140px;
				margin-top:10px;
				float:left;
			}
			.tw2{
				width:25%;
				height:35px;
				line-height:35px;
				float:left;
				/*margin-top:2px;*/
				margin-left:2%;
			 }
			 .tf1{
			 	width:80%;
			 	margin-left:3%;
			 	height:35px;
			 	/*margin-top:2px;*/
			 	line-height: 35px;
			 	float:left;
			 }
			 .tf{
			 	width:67%;
			 	margin-left:3%;
			 	height:35px;
			 	/*margin-top:2px;*/
			 	line-height: 35px;
			 	float:left;
			 }
			 .foot{
			 	height:300px;
			 	width:96%;
			 	margin-left:2%;
			 }
			  .foot1{
			 	height:35px;
			 	width:20%;
			 	margin-left:32%;
			 	line-height:35px;
			 	background-color: white;
			 	text-align: center;
			 	position: absolute;
			 	top:300px;
			 }
			 .xian{
			 	height:1px;
			 	width:98%;
			 	border:1px solid #999999;
			 	position:relative;
			    top:300px;
			 }
			 #foot2{
			 	width:96%;
			 	height:150px;
			 	margin-top:55px;
			 	background-color:white;
			 	word-wrap:break-word;
			 	border-radius: 10px;
			 	margin-left:2%;
			 	float:left;
			 	text-indent:2em;
			 }
			
		</style>
	</head>
	<body>
		<div class="box">
           <div class="topleft"><img src="" id="head"></div>
           <div class="topright">
           	<div id="cname" class="tc"></div>
           	<div id="cname2" class="tc2"></div>
           </div>
           <div class="center">
           	<div class="center1"><div class="tw2">联系人:</div><div class="tf" id="name"></div></div>
            <div class="center1"><div class="tw2">电话:</div><a class="tf" id="tel" href=""></a></div>
           	<div class="center1"><div class="tw2">地址：</div><div class="tf" id="address"></div></div>
           	<div class="tf1" id="address1"></div>
           </div>
            <div class="foot"><div class="xian"></div><div class="foot1">经营项目</div><div id="foot2"></div></div>
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
                  $("#head").attr('src', msg.tenant.tenantheadimg);
                  var d=msg.tenant.company.split("");
                  var c=null;
                  for(var f=msg.tenant.company.length;f>0;f--){
                  	if(d[f]=="股"){
                  		c=msg.tenant.company.split("股");
                  		 $("#cname2").html("股"+c[1]);
                  	}else{
                  	    c=msg.tenant.company.split("有");
                  	     $("#cname2").html("有"+c[1]);
                  	}
                  }
                   $('#cname').html(c[0]);
                    $("#name").html(msg.contact.customer_name);
                    var m=null;
                    var f=null;
                    if(msg.tenant.ad.length>=12 ){
                    	m=msg.tenant.ad.substr(12);
                    	f=msg.tenant.ad.substr(0,12);
                    }else{
                    	f=msg.tenant.ad;
                    }
                   $('#address').html(f);
                   $("#address1").html(m);
                   $('#tel').html(msg.contact.customer_phone);
                   $('#tel').attr('href',"tel:"+msg.contact.customer_phone);
                   $("#foot2").html(msg.tenant.service_items);   
                   if(msg.tenant.business_card!=null&&msg.tenant.business_card!=""){
                   	   $(".box").css("background","url("+msg.tenant.business_card+")");
                   }   
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
</html>