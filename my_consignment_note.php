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
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
		<link rel="stylesheet" href="css/wodeyundan.css">
		<script type="text/javascript" src="layer/layer.js"></script>
		<title>我的运单</title>
	</head>
	<body>
		<div class="box">
			<div class="top2">
				<div class="yundanghao">
					<div class="rongqi">
						<div class="kuang">
							<input id="yundanhao" type="number" placeholder="请输入要查询的运单号" pattern="[0-9]*">
						</div>
						<div class="tu" id="saoman">
							<img src="images/saoma.png" alt="">
						</div>
					</div>
				</div>
			</div>
			<!-- center -->
			<div class="center">
				<div class="BT">
					<div class="center1">
						<h3>我寄的</h3></div>
					<div class="center2">
						<h3>我收的</h3></div>
				</div>
				<div class="BT2">
					<div class="center1_1"></div>
					<div class="center2_1"></div>
				</div>
				<div class="box1" id="bo1">
				</div>
				<div class="box2" id="bo2">
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript">
		$(".box2").hide();
		$(".center1_1").css("border", "1px solid #AAF0EB");
		$(".center2").on("click", function() {
			$(".box1").hide();
			$(".box2").show();
			$(".center1_1").css("border", "1px solid white");
			$(".center2_1").css("border", "1px solid #AAF0EB");
		})
		$(".center1").on("click", function() {
			$(".box2").hide();
			$(".box1").show();
			$(".center2_1").css("border", "1px solid white");
			$(".center1_1").css("border", "1px solid #AAF0EB");
		})

        function piaoyi(id){
            var startx;
            document.getElementById(id+'').addEventListener("touchstart", function(e) {
                startx = e.touches[0].pageX;
            }, false);
            document.getElementById(id+'').addEventListener("touchend", function(e) {
                endx = e.changedTouches[0].pageX;

                if(((endx-startx)<=$(".yundan_4").outerWidth()/4&&(startx-endx)<0)){
                    $("#"+id).animate({
                        scrollLeft:'0px',
                    },100);}else if(((startx-endx)>$(".yundan_4").outerWidth()/4)){
                    $("#"+id).animate({
                        scrollLeft:$(".yundan_4").outerWidth()+'px',
                    },100);
                }

            })
        }

        function delet(id){
		    console.log(id);
             alert(id);
             var aa=id;
            layer.confirm('你确定删除该运单？', {
                btn: ['确定','关闭'] //按钮
            }, function(){
                console.log(aa);
                alert(aa);
                $.ajax({
                    url: "http://api.uminfo.cn/wxmessage.php/wxmessage?messageid="+aa,
                    beforeSend: function(request) {
                        request.setRequestHeader("tenant-id",tenant_id);
                    },
                    dataType: 'json',
                    type: 'delete',
                    ContentType: "application/json;charset=utf-8",
                    data: JSON.stringify({
                    }),
                    success: function(msg) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert("获取后台失败！");
                    }
                });
            }, function(){
                layer.msg('关闭', {icon: 1});
            });

        }
	</script>
	<script type="text/javascript">
		$(".tu").on("click", function() {
		})
	</script>
	<script type="text/javascript">
		$(".yundan_3_1").on("click", function() {
		})
	</script>
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
					request.setRequestHeader("tenant-id",tenant_id);
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
				error: function(xhr) {
					alert("获取后台失败！");
				}
			});
		}
	</script>
	<script>
		//查询运单
		var tu=null;
		$("#yundanhao").on('keyup', function() {
			$("#bo2").html("");
			$("#bo1").html("");
			var order_id = $("#yundanhao").val();
			$.ajax({
				url: "http://api.uminfo.cn/order.php/wx_order",
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id", tenant_id);
				},
				dataType: 'json',
				type: 'post',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({
					wx_openid: openid,
					order_id: order_id
				}),
				success: function(msg) {
					if(msg.result == 2) {
						a == null;
						layer.msg("没有订单");
					} else {						
						for(var i = 0; i < msg.orders.fa.length; i++) {
					var a="<div class='piaoyi_a' id='"+msg.orders.fa[i].order_id+"'>" +
                        "<div class='piaoyi_b'><div class='xian'></div><div class='yundan'><div class='yundan_1'><p>运单号:<span>"
					+msg.orders.fa[i].order_id+"</span></p><p>订单价格:<span>"
					+msg.orders.fa[i].order_cost+"</span></p></div><div class='yundan_2'><div class='yundan_2_1'><div class='city_1'>"
					+msg.orders.fa[i].sendcity+"</div><div class='name_1'>"
					+msg.orders.fa[i].sendname+"</div></div><div class='yundan_2_2'><p class='sta'><img src='images/"
					+"to.png'></p></div><div class='yundan_2_1'><div class='city_2'>"
					+msg.orders.fa[i].acceptcity+"</div><div class='name_2'>"
					+msg.orders.fa[i].acceptname+"</div></div></div></div>" +
                        "<div class='yundan_4'><div class='yundan_4_1' onclick='delet("+msg.orders.fa[i].order_id+")'>删除</div></div><div class='yundan_3'><div class='yundan_3_1'>"
					+msg.orders.fa[i].status+"</div></div><div class='xian'></div><div class='kongbai'></div></div></div>";
								$("#bo1").append(a);
                            piaoyi(msg.orders.fa[i].order_id);
						};
							for(var i = 0; i < msg.orders.shou.length; i++) {
					var b="<div class='piaoyi_a' id='"+msg.orders.shou[i].order_id+"'>" +
                        "<div class='piaoyi_b'><div class='xian'></div><div class='yundan'><div class='yundan_1'><p>运单号:<span>"
					+msg.orders.shou[i].order_id+"</span></p><p>订单价格:<span>"
					+msg.orders.shou[i].order_cost+"</span></p></div><div class='yundan_2'><div class='yundan_2_1'><div class='city_1'>"
					+msg.orders.shou[i].sendcity+"</div><div class='name_1'>"
					+msg.orders.shou[i].sendname+"</div></div><div class='yundan_2_2'><p class='sta'><img src='images/"
					+"accept.png'></p></div><div class='yundan_2_1'><div class='city_2'>"
					+msg.orders.shou[i].acceptcity+"</div><div class='name_2'>"
					+msg.orders.shou[i].acceptname+"</div></div></div></div>" +
                        "<div class='yundan_4'><div class='yundan_4_1' onclick='delet("+msg.orders.shou[i].order_id+")'>删除</div></div><div class='yundan_3'><div class='yundan_3_1'>"
					+msg.orders.shou[i].status+"</div></div><div class='xian'></div><div class='kongbai'></div>";
								$("#bo2").append(b);
                                piaoyi(msg.orders.fa[i].order_id);
						};
						if($(".yundan_3_1").text() == "已签收") {
						$(".sta").css("color", "#000000");
					} else {
						$(".sta").css("color", "#F75000");
					}
					//点击事件
					$(".yundan").click(function() {
						var sendid = $(this).children().eq(0).children().eq(0).children().eq(0).text();
//					     window.location.href = "http://api.uminfo.cn/weixin/waybill_details.html?order_id="+sendid+"&tenant_id="+tenant_id;
                        if(sendid!='暂无'){
                            window.location.href = "http://api.uminfo.cn/weixin/waybill_details.html?order_id="+sendid+"&tenant_id="+tenant_id;
                        }
					});
					}
				},
				error: function(xhr) {
					alert("获取数据失败");
				}
			});
		});
	</script>
	<script>
		//扫一扫
	</script>
	<script>
		//我寄的
		$.ajax({
			url: "http://api.uminfo.cn/order.php/wx_orders_s",
			beforeSend: function(request) {
				request.setRequestHeader("tenant-id", tenant_id);
			},
			dataType: 'json',
			type: 'post',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({
				wx_openid:openid,
				order_id:''
			}),
			success: function(msg) {
				for(var i = 0; i < msg.orders.length; i++) {
					var a="<div class='piaoyi_a' id='"+msg.orders[i].order_id+"'>" +
                        "<div class='piaoyi_b'><div class='xian'></div><div class='yundan'><div class='yundan_1'><p>运单号:<span>"
					+msg.orders[i].order_id+"</span></p><p>订单价格:<span>"
					+msg.orders[i].order_cost+"</span></p></div><div class='yundan_2'><div class='yundan_2_1'><div class='city_1'>"
					+msg.orders[i].sendcity+"</div><div class='name_1'>"
					+msg.orders[i].sendname+"</div></div><div class='yundan_2_2'><p class='sta'><img src='images/to.png'></p></div><div class='yundan_2_1'><div class='city_2'>"
					+msg.orders[i].acceptcity+"</div><div class='name_2'>"
					+msg.orders[i].acceptname+"</div></div></div></div>" +
                        "<div class='yundan_4'><div class='yundan_4_1' onclick='delet("+msg.orders[i].order_id+")'>删除</div></div><div class='yundan_3'><div class='yundan_3_1'>"
					+msg.orders[i].status+"</div></div><div class='xian'></div><div class='kongbai'></div>";
					$("#bo1").append(a);
                    piaoyi(msg.orders[i].order_id);
				};
					if($(".yundan_3_1").text() == "已签收") {
						$(".sta").css("color", "#000000");
					} else {
						$(".sta").css("color", "#F75000");
					}
					//点击事件
					$(".yundan").click(function() {
						var sendid = $(this).children().eq(0).children().eq(0).children().eq(0).text();
                        if(sendid!='暂无'){
                            window.location.href = "http://api.uminfo.cn/weixin/waybill_details.html?order_id="+sendid+"&tenant_id="+tenant_id;
                        }
//					   window.location.href = "http://api.uminfo.cn/weixin/waybill_details.html?order_id="+sendid+"&tenant_id="+tenant_id;
					});
			},
			error: function(xhr) {
				alert("获取数据失败");
			}
		});
	</script>
	<script>
		//我收的
		$.ajax({
			url: "http://api.uminfo.cn/order.php/wx_orders_r",
			beforeSend: function(request) {
				request.setRequestHeader("tenant-id", tenant_id);
			},
			dataType: 'json',
			type: 'post',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({
				wx_openid: openid,
				order_id:''
			}),
			success: function(msg) {
				for(var i = 0; i < msg.orders.length; i++) {
					var a="<div class='piaoyi_a' id='"+msg.orders[i].order_id+"'>" +
                        "<div class='piaoyi_b'><div class='xian'></div><div class='yundan'><div class='yundan_1'><p>运单号:<span>"
					+msg.orders[i].order_id+"</span></p><p>订单价格:<span>"
					+msg.orders[i].order_cost+"</span></p></div><div class='yundan_2'><div class='yundan_2_1'><div class='city_1'>"
					+msg.orders[i].sendcity+"</div><div class='name_1'>"
					+msg.orders[i].sendname+"</div></div><div class='yundan_2_2'><p class='sta'><img src='images/accept.png'></p></div><div class='yundan_2_1'><div class='city_2'>"
					+msg.orders[i].acceptcity+"</div><div class='name_2'>"
					+msg.orders[i].acceptname+"</div></div></div></div>" +
                        "<div class='yundan_4'><div class='yundan_4_1' onclick='delet("+msg.orders[i].order_id+")'>删除</div></div><div class='yundan_3'><div class='yundan_3_1'>"
					+msg.orders[i].status+"</div></div><div class='xian'></div><div class='kongbai'></div>";
					$("#bo2").append(a);
                    piaoyi(msg.orders[i].order_id);
				};
					if($(".yundan_3_1").text() == "已签收") {
						$(".sta").css("color", "#000000");
					} else {
						$(".sta").css("color", "#F75000");
					}
					//点击事件
					$(".yundan").click(function() {
						var sendid = $(this).children().eq(0).children().eq(0).children().eq(0).text();
						if(sendid!='暂无'){
                            window.location.href = "http://api.uminfo.cn/weixin/waybill_details.html?order_id="+sendid+"&tenant_id="+tenant_id;
                        }
					});
			},
			error: function(xhr) {
				alert("获取数据失败");
			}
		});
	</script>
	<script type="text/javascript">
		 wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'checkJsApi', 'scanQRCode'
        ]
    });
    wx.ready(function () { 
  document.querySelector('#saoman').onclick = function () {  
    wx.scanQRCode({  
      needResult: 1,  
      desc: 'scanQRCode desc',  
      success: function (res) {    
      	 var a=new Array();
          a=res.resultStr.split(",");
          alert(a[1]);
          if(a[1]!=null){
              window.location.href="http://api.uminfo.cn/weixin/waybill_details.html?order_id="+a[1]+"&tenant_id="+tenant_id;
          }else{
              layer.msg("没有扫描到条形码");
          }

      }  
    });  
  };  
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