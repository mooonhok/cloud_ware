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
	<meta http-equiv="Access-Control-Allow-Origin" content="*">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<link rel="stylesheet" href="css/wangshangxunjia.css">
	<title>网上询价</title>
</head>
<body>
<div class="box">
	<div class="top">
		<div class="wenzi">货物类型</div> 
		<div class="text">
			<input type="text" id="goods_name" placeholder="必填项">
		</div>			
	</div>
	<div class="center">
	<div class="center_1">
		<div class="wenzi">体积(m³)</div>
		
		<div class="text">
		<input type="number" id="vol" placeholder="必填项">
		</div>		
	</div>
	<div class="center_2">
		<div class="wenzi">重量(吨)</div>
		<div class="text">
		<input type="number" id="weight" placeholder="必填项">
		</div>	
	</div>		
	</div>
	<div class="foot">
		<div class="foot_1">
			<div class="wenzi4">发站</div>
		<div class="foot_1_1">
				<select  id="province1" onchange="getPro1()">
					<option value="0">请选择</option>
				</select>
				
				<select id="city1">
					<option value="0">请选择</option>
				</select>
			</div>
		</div>	
		<div class="foot_2">
			<div class="wenzi4">到站</div>
		<div class="foot_2_1">
				<select  id="province2" onchange="getPro2()">
					<option value="0">请选择</option>
				</select>				
				<select id="city2">
					<option value="0">请选择</option>
				</select>
			</div>
		</div>
	</div>
	<div class="top">
		<div class="wenzi">联系电话</div> 
		<div class="text">
			<input type="text" id="tel" placeholder="必填项">
		</div>			
	</div>
	<div class="center">
	<div class="xunjia">
		询价
	</div>
</div>	
</body>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
	 $(".box2").css("display","none");
	 $(".box3").css("display","none");
	 $(".foot_1").on("click",function(){
	 	$(".box2").css("display","block");
	 	$(".box2").css("z-index","100");
	 })
	 $(".foot_2").on("click",function(){
	 	$(".box3").css("display","block");
	 	$(".box3").css("z-index","100");
	 })
	 $(".ok1").on("click",function(){
	 	$(".box2").css("display","none");
	 })
	 $(".ok2").on("click",function(){
	 	$(".box3").css("display","none");
	 })		
	</script>		
	<script type="text/javascript">		
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
				url: "http://api.uminfo.cn/customer.php/wx_openid?wx_openid="+openid,
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id", tenant_id);
				},
				dataType: 'json',
				type: 'get',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({
					
				}),
				success: function(msg) {
					if(msg.result == 0) {
						window.location.href = "http://api.uminfo.cn/weixin/test.html?tenant_id="+tenant_id+"&page=5";
					} 
				},
				error: function(e) {
					alert("获取后台失败！")					
				}
			});
		}
</script>
	<script>
   	//获取省份和城市列表1
   	$.ajax({
		            url :"http://api.uminfo.cn/city.php/province",
		             beforeSend: function(request) {
                        request.setRequestHeader("tenant-id", tenant_id);  
                    },
			        dataType:'json',
			        type:'get',
			        ContentType:"application/json;charset=utf-8",
			        data:JSON.stringify({
			        }),
			        success:function(msg){
                        for(var i=0;i<msg.province.length;i++){
                         	$("#province1").append('<option value="' + msg.province[i].id + '">' + msg.province[i].name + '</option>');
                        }
				    },
			        error:function(e){
			            alert("省份列表1信息出错！");
			        }
	          });            
			function getPro1() {	
			     $("#city1").empty();
				var pid=$("#province1 option:selected").val();
				$.ajax({
		            url :"http://api.uminfo.cn/city.php/city?pid="+pid,
		             beforeSend: function(request) {
                        request.setRequestHeader("tenant-id", tenant_id);  
                    },
			        dataType:'json',
			        type:'get',
			        ContentType:"application/json;charset=utf-8",
			        data:JSON.stringify({
			        
			        }),
			        success:function(msg){
                        for(var i=0;i<msg.city.length;i++){
                         	$("#city1").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
                        }
				    },
			        error:function(e){
		            alert("城市1列表信息出错！");
			        }
	          });
           } 	
   </script>
<script>
	//获取城市列表2
		$.ajax({
		            url :"http://api.uminfo.cn/city.php/province",
		             beforeSend: function(request) {
                        request.setRequestHeader("tenant-id", tenant_id);  
                    },
			        dataType:'json',
			        type:'get',
			        ContentType:"application/json;charset=utf-8",
			        data:JSON.stringify({
			        }),
			        success:function(msg){
                        for(var i=0;i<msg.province.length;i++){
                         	$("#province2").append('<option value="' + msg.province[i].id + '">' + msg.province[i].name + '</option>');
                        }
				    },
			        error:function(e){
			            alert("省份列表2信息出错！");
			        }
	          });            
			function getPro2() {	
			     $("#city2").empty();
				var pid=$("#province2 option:selected").val();
				$.ajax({
		            url :"http://api.uminfo.cn/city.php/city?pid="+pid,
		             beforeSend: function(request) {
                        request.setRequestHeader("tenant-id", tenant_id);  
                    },
			        dataType:'json',
			        type:'get',
			        ContentType:"application/json;charset=utf-8",
			        data:JSON.stringify({
			        }),
			        success:function(msg){
                        for(var i=0;i<msg.city.length;i++){
                         	$("#city2").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
                        }
				    },
			        error:function(e){
		            alert("城市1列表信息出错！");
			        }
	          });
          } 	
</script>
<script type="text/javascript">
		$(".xunjia").on("click",function(){
//			alert('ok');
            var type=$("#goods_name").val();
            var big=$("#vol").val();
            var weight=$("#weight").val();
            var sendcity=$("#city1 option:selected").val();
            var receive=$("#city2 option:selected").val();
            var tel=$("#tel").val();
            alert(type+"///"+big+"$$$"+weight+"****"+sendcity+"^^^^"+receive+"!!!!"+tenant_id+"%%%%"+openid);
            $.ajax({
            	  url :"http://api.uminfo.cn/secectpay.php/pay",
		           beforeSend: function(request) {
                       request.setRequestHeader("tenant-id", tenant_id);  
                    },
			        dataType:'json',
			        type:'post',
			        ContentType:"application/json;charset=utf-8",
			        data:JSON.stringify({
			        	type:type,
			        	big:big,
			        	weight:weight,
			        	sendcity:sendcity,
			        	receive:receive,
			        	openid:openid,
			        	tel:tel
			        }),
			        success:function(msg){
                     alert(msg.desc);
				    },
			        error:function(e){
		            alert("提交失败！");
			        }
            });
		})
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