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
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
		<link rel="stylesheet" href="css/woyaojijian.css">
		<title>我要寄件</title>
	</head>
	<body>
		<div class="box">
			<!-- top -->
			<div class="top">
				<div class="top1">
					<div class="wenzi"><span class="span1">发</span>货人</div>
					<div class="shuru">
					</div>
					<div class="fuhao"><img src="images/left_arrow.png" alt=""></div>
				</div>

				<div class="top2">
					<div class="wenzi"><span class="span2">收</span>货人</div>
					<div class="shuru1">
					</div>
					<div class="fuhao"><img src="images/left_arrow.png" alt=""></div>
				</div>
			</div>
			<!-- center -->
			<div class="center">
				<div class="center_1">
					<div class="wenzi">货物品名</div>
					<div class="text">
						<input type="text" placeholder="必填项" class="huowu">
					</div>
				</div>

				<div class="center_3">
					<div class="wenzi">重量(吨)</div>
					<div class="text3">
						<input type="number" placeholder="必填项" class="zhong">
					</div>
				</div>

				<div class="center_3">
					<div class="wenzi">体积(m³)</div>
					<div class="text4">
						<input type="number" placeholder="必填项" class="tiji">
					</div>
				</div>
				<div class="center_4">
					<div class="wenzi">包装</div>
					<div class="text10">
						<select name="package" id="package">
								<option value="">请选择</option>
						</select>
					</div>
				</div>
				<div class="center_5">
					<div class="wenzi">件数</div>
					<div class="text1">
						<input type="tel" placeholder="必填项" class="jianshu">
					</div>
				</div>
				<div class="center_6">
					<div class="wenzi">备注</div>
					<div class="text1">
						<input type="text" placeholder="必填项" class="needs" disabled="disabled" style="color: black;">
					</div>
				</div>
			</div>
			<!-- foot -->
			<div class="foot">
				<div class="foot1">
					<div class="wenzi">货物价值(万元)</div>
					<div class="text">
						<input type="number" placeholder="必填项" class="jiazhi">
					</div>
				</div>
				<div class="foot2">
					<div class="wenzi">付款方式</div>
					<div class="text2">
						<input type="text" placeholder="必填项" class="money" disabled="disabled" style="color: black;">
					</div>
				</div>
			</div>
			<!-- tijiao -->
			<div class="tijiao">
				提交
			</div>
			<div class="kbox"></div>
			<div style="clear: both;"></div>
		</div>
		<!-- box2 -->
		<div class="box2">
			<div class="box2-1">
				<div class="jj">
					<div class="ls">历史地址</div>
					<div class="jj1">
						<h3>新增发货人</h3>
					</div>
					<div class="qx">取消</div>
				</div>
				<form>
					<div class="name1">
						<div class="lab">
							<label for="">姓名:</label>
						</div>
						
					</div>
					<div class="bor"></div>
					<div class="name1">
						<div class="name2">
							<input type="text" class="name_1" placeholder="最多15个字">
						</div>
					</div>
					<div class="bor"></div>
					<div class="phone1">
						<div class="lab">
							<label for="">联系方式:</label>
						</div>
						
					</div>
					<div class="bor"></div>
					<div class="phone1">
						<div class="phone2">
							<input type="tel" class="phone_1">
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi1">
						<div class="lab">
							<label for="">地址:</label>
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi1">
						<div class="dizhi2">
							<select name="province" id="province1" onchange="getPro1()" class="pro">
								<option value="">请选择</option>
							</select>
							<select name="city" id="city1" class="cit">
								<option value="">请选择</option>
							</select>
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi3">
						<div class="lab">
							<label for="">详细地址:</label>
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi3">
						<div class="dizhi3-1">
							<textarea id="dizhi_1" rows="2"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="tijiao2" id="subaddress3">保存地址</div>
			<div class="kbox"></div>
		</div>
		<!--  -->
		<div class="box1" id="box10">
			<div class="box10_1">
				<div class="box10_1_1">历史地址</div>
				<div class="qx5">取消</div>
			</div>
			<div id="box101">
				<div style="clear:both;"></div>
			</div>
<!--			<div class="box10_2" id="subaddress2">确认</div>-->
			<div style="clear:both;"></div>
		</div>
		<!-- box3 -->
		<div class="box3">
			<div class="box2-1">
				<div class="jj">
					<div class="ls1">历史地址</div>
					<div class="jj1">
						<h3>新增收货人</h3>
					</div>
					<div class="qx1">取消</div>
				</div>
				<form>
					<div class="name1">
						<div class="lab">
							<label for="">姓名</label>
						</div>
					</div>
					<div class="bor"></div>
					<div class="name1">
						<div class="name2">
							<input type="text" class="name_2" placeholder="最多15个字">
						</div>
					</div>
					<div class="bor"></div>
					<div class="phone1">
						<div class="lab">
							<label for="">联系方式</label>
						</div>
						
					</div>
					<div class="bor"></div>
					<div class="phone1">
						<div class="phone2">
							<input type="tel" class="phone_2">
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi1">
						<div class="lab">
							<label for="">地址</label>
						</div>	
					</div>
					<div class="bor"></div>
					<div class="dizhi1">
						<div class="dizhi2">
							<select name="province" id="province2" onchange="getPro2()" class="pro1">
								<option value="">请选择</option>
							</select>
							<select name="city" id="city2" class="cit1">
								<option value="">请选择</option>
							</select>
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi3">
						<div class="lab">
							<label for="">详细地址:</label>
						</div>	
					</div>
					<div class="bor"></div>
					<div class="dizhi3">
						<div class="dizhi3-1">
							<textarea id="dizhi_2" rows="2"></textarea>
						</div>
					</div>
				
				</form>
			</div>

			<div class="tijiao2" id="subaddress5">保存地址</div>
			<div class="kbox"></div>
		</div>
		<!--  -->
		<div class="box1" id="box11">
			<div class="box11_1">
				<div class="box11_1_1">历史地址</div>
				<div class="qx6">取消</div>
			</div>
			<div id="box111">
				<div style="clear:both;"></div>
			</div>
<!--			<div class="box11_2" id="subaddress4">确认</div>-->
			<div style="clear:both;"></div>
		</div>
		<!-- box4 -->
		<!--<div class="box4">
			<div class="box4-1">
				<div class="qx2">取消</div>
				<div class="h5"><h5>包装类型</h5></div>

				<div class="box4-2">
					<div class="box4-2-1" value="1">纸箱</div>
					<div class="box4-2-2" value="2">木箱</div>
					<div class="box4-2-3" value="3">油布</div>
				</div>
				<div class="box4-3">
					<div class="box4-3-1" value="4">编织袋</div>
					<div class="box4-3-2" value="5">集装箱</div>
					<div class="box4-3-3" value="6">无包装</div>
				</div>
			</div>
		</div>-->
		<div class="box6">
			<div class="box6-1">
				<div class="qx3">取消</div>
				<div class="h5"><h5>付款方式</h5></div>
				<div class="box6-2">
					<div class="box6-2-1" value="到付">到付</div>
					<div class="box6-2-2" value="现付">现付</div>
					<div class="box6-2-3" value="月付">月付</div>
					<div class="box6-2-4" value="回单">回单</div>
				</div>
			</div>
		</div>

	<div class="box7">
			<div class="box2-1">
				<div class="jj">
					<div class="jj2">
						<h5>修改发货人</h5>
					</div>
					<div class="qx10">取消</div>
				</div>
				<form>
					<div class="name1">
						<div class="lab">
							<label for="">姓名:</label>
						</div>
					</div>
					<div class="bor"></div>
					<div class="name1">
						<div class="name2">
							<input type="text" class="name_1" id="name_1" placeholder="最多15个字">
						</div>
					</div>
					<div class="bor"></div>
					<div class="phone1">
						<div class="lab">
							<label for="">联系方式:</label>
						</div>		
					</div>
					<div class="bor"></div>
					<div class="phone1">
						<div class="phone2">
							<input type="tel" class="phone_1" id="phone_1">
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi1">
						<div class="lab">
							<label for="">地址:</label>
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi1">
						<div class="dizhi2">
							<select name="province" id="province3" onchange="getPro3()" class="pro">
								<option value="">请选择</option>
							</select>
							<select name="city" id="city3" class="cit">
								<option value="">请选择</option>
							</select>
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi3">
						<div class="lab">
							<label for="">详细地址:</label>
						</div>	
					</div>
					<div class="bor"></div>
					<div class="dizhi3">
						<div class="dizhi3-1">
							<textarea id="dizhi_3" rows="2"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="tijiao2" id="subaddress10">保存地址</div>
			<div class="kbox"></div>
		</div>

		<div class="box8">
			<div class="box2-1">
				<div class="jj">
					<div class="jj2">
						<h5>修改收货人</h5>
					</div>
					<div class="qx11">取消</div>
				</div>
				<form>
					<div class="name1">
						<div class="lab">
							<label for="">姓名</label>
						</div>	
					</div>
					<div class="bor"></div>
					<div class="name1">
						<div class="name2">
							<input type="text" class="name_2" id="name_2" placeholder="最多15个字">
						</div>
					</div>
					<div class="bor"></div>
					<div class="phone1">
						<div class="lab">
							<label for="">联系方式</label>
						</div>
					</div>
					<div class="bor"></div>
					<div class="phone1">
						<div class="phone2">
							<input type="tel" class="phone_2" id="phone_2">
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi1">
						<div class="lab">
							<label for="">地址</label>
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi1">
						<div class="dizhi2">
							<select name="province" id="province4" onchange="getPro4()" class="pro1">
								<option value="">请选择</option>
							</select>
							<select name="city" id="city4" class="cit1">
								<option value="">请选择</option>
							</select>
						</div>
					</div>
					<div class="bor"></div>
					<div class="dizhi3">
						<div class="lab">
							<label for="">详细地址:</label>
						</div>	
					</div>
					<div class="bor"></div>
					<div class="dizhi3">
						<div class="dizhi3-1">
							<textarea id="dizhi_4" rows="2"></textarea>
						</div>
					</div>
					<!-- <div class="tijiao2" id="subaddress4">
						添加新地址
					</div> -->
				</form>
			</div>

			<div class="tijiao2" id="subaddress11">保存地址</div>
			<div class="kbox"></div>
		</div>
          <div class="box12">
			<div class="box12_1">
				<div class="qx5">取消</div>
				<div class="h5">
					<h5>备注</h5></div>
				<div class="box12_a1"  id="j1"><div class="t1">寄</div>门店自寄</div>
				<div class="box12_a2" id="j2"><div class="t1">收</div>门店自提</div>
				<div class="box12_a1" id="r1"><div class="t1">寄</div>上门提货</div>
				<div class="box12_a2" id="r2" ><div class="t1">收</div>送货上门</div>
				<div class="box12_c3">确定</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="layer/layer.js"></script>
	<script type="text/javascript">
		(function($) {
			$.getUrlParam = function(name) {
				var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
				var r = window.location.search.substr(1).match(reg);
				if(r != null) return decodeURI(r[2]);
				return null;
			}
		})(jQuery);
		var tenant_name=$.getUrlParam('tenantname');
		var tenant_id=$.getUrlParam('tenant_id');
		 $(document).attr("title",tenant_name);
		//判断openid是否已经被注册
		var openid = $.cookie('openid'+tenant_id);
		var customer_send_id = "";
		var customer_accept_id = "";
//		if(openid != null) {
//			$.ajax({
//				url: "http://api.uminfo.cn/customer.php/wx_openid?wx_openid=" + openid,
//				beforeSend: function(request) {
//					request.setRequestHeader("tenant-id", tenant_id);
//				},
//				dataType: 'json',
//				type: 'get',
//				ContentType: "application/json;charset=utf-8",
//				data: JSON.stringify({}),
//				success: function(msg) {
//				   if(msg.result == 0) {
//					   window.location.href = "http://api.uminfo.cn/weixin/test.html?tenant_id="+tenant_id+"&page=5";
//				  }
//				},
//				error: function(xhr) {
//					layer.msg("获取后台失败");
//				}
//			});
//		}
window.alert = function(name){
			 var iframe = document.createElement("IFRAME");
			iframe.style.display="none";
			iframe.setAttribute("src", 'data:text/plain,');
			document.documentElement.appendChild(iframe);
			window.frames[0].window.alert(name);
			iframe.parentNode.removeChild(iframe);
		}
		$(document).ready(function() {
			$(".top1").on("click", function() {
				loadsend(openid,tenant_id);
				$(".box2").css("display", "block");
				$(".box2").css("z-index", "100");
				$(".box").css("display","none");
				// $(".box10_1_1").on("click", function() {
				// 	$("#box10").css("display", "none");
				// 	$(".box2").css("display", "block");
				// 	$(".box2").css("z-index", "100");
				// })
			})
		})
	</script>
	<script type="text/javascript">
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".top2").on("click", function() {
				loadaccept(openid,tenant_id);
				$(".box3").css("display", "block");
				$(".box3").css("z-index", "100");
				$(".box").css("display","none");
				// $(".box11_1_1").on("click", function() {
				// 	$(".box3").css("display", "block");
				// 	$(".box3").css("z-index", "100");
				// 	$("#box11").css("display", "none");
				// })
			})
		})
	</script>
	<script type="text/javascript">
		var a=null;
		var c=null;
		$(".center_6").on("click",function(){
			$(".box12").css("display","block");
		});
			$(".qx5").on("click",function(){
			$(".box12").css("display","none");
		});
	     $("#j1").on("click",function(){
	     	$("#j1").css("background-color","orange");
	     	$("#r1").css("background-color","#24D9CA");
	     });
	     $("#j2").on("click",function(){
	     	$("#r2").css("background-color","#24D9CA");
	     	$("#j2").css("background-color","orange");
	     });
	     $("#r1").on("click",function(){
	     	$("#r1").css("background-color","orange");
	     	$("#j1").css("background-color","#24D9CA");
	     });
	     $("#r2").on("click",function(){
	     	$("#r2").css("background-color","orange");
	     	$("#j2").css("background-color","#24D9CA");
	     });
	     $(".box12_c3").on("click",function(){
	     
              for(var b=0;b<document.getElementsByClassName("box12_a1").length;b++){
                if(document.getElementsByClassName("box12_a1")[b].style.backgroundColor=="orange"){
                a=document.getElementsByClassName("box12_a1")[b].textContent.substring(1,5);
               }
             }
               for(var d=0;d<document.getElementsByClassName("box12_a2").length;d++){
                if(document.getElementsByClassName("box12_a2")[d].style.backgroundColor=="orange"){
                c=document.getElementsByClassName("box12_a2")[d].textContent.substring(1,5);
               }
             } 
            
             if(a!=null&&c!=null){
             $(".needs").val("寄:"+a+";  收:"+c);
             $(".box12").css("display","none");
             }else{
             	layer.msg("您必须选择两个备注");
             }
	     });
//		$(".center_4").on("click", function() {
//			$(".box4").css("display", "block");
//		})
//		$(".box4-2-1").on("click", function() {
//			var box4 = $(this).html();
//			$(".baozhuang").val(box4);
//			$(".box4").css("display", "none");
//		})
//		$(".box4-2-2").on("click", function() {
//			var box4 = $(this).html();
//			$(".baozhuang").val(box4);
//			$(".box4").css("display", "none");
//		})
//		$(".box4-2-3").on("click", function() {
//			var box4 = $(this).html();
//			$(".baozhuang").val(box4);
//			$(".box4").css("display", "none");
//		})
//		$(".box4-3-1").on("click", function() {
//			var box4 = $(this).html();
//			$(".baozhuang").val(box4);
//			$(".box4").css("display", "none");
//		})
//		$(".box4-3-2").on("click", function() {
//			var box4 = $(this).html();
//			$(".baozhuang").val(box4);
//			$(".box4").css("display", "none");
//		})
//		$(".box4-3-3").on("click", function() {
//			var box4 = $(this).html();
//			$(".baozhuang").val(box4);
//			$(".box4").css("display", "none");
//		})
	</script>
	<script type="text/javascript">
		$(".foot2").on("click", function() {
			$(".box6").css("display", "block");
		})
		$(".box6-2-1").on("click", function() {
			var box6 = $(this).html();
			$(".money").val(box6);
			$(".box6").css("display", "none");
		})
		$(".box6-2-2").on("click", function() {
			var box4 = $(this).html();
			$(".money").val(box4);
			$(".box6").css("display", "none");
		})
		$(".box6-2-3").on("click", function() {
			var box4 = $(this).html();
			$(".money").val(box4);
			$(".box6").css("display", "none");
		})
			$(".box6-2-4").on("click", function() {
			var box4 = $(this).html();
			$(".money").val(box4);
			$(".box6").css("display", "none");
		})
	</script>
	<script>
		$.ajax({
			url: "http://api.uminfo.cn/goods_package_nedb.php/getGoodsPackages",
			dataType: 'json',
			type: 'get',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({}),
			success: function(msg) {
				for(var i = 0; i < msg.goods_package.length; i++) {
					$("#package").append('<option value="' + msg.goods_package[i].goods_package_id + '">' + msg.goods_package[i].goods_package + '</option>');
				}
			},
			error: function(e) {
				layer.msg("包装列表出错!");
			}
		});
		//获取省份和城市列表1
		$.ajax({
			url: "http://api.uminfo.cn/city.php/province",
			dataType: 'json',
			type: 'get',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({}),
			success: function(msg) {
				for(var i = 0; i < msg.province.length; i++) {
					$("#province1").append('<option value="' + msg.province[i].id + '">' + msg.province[i].name + '</option>');
				}
			},
			error: function(e) {
				layer.msg("省份列表1信息出错!");
			}
		});
		function getPro1() {
			$("#city1").empty();
			var pid = $("#province1 option:selected").val();
			$.ajax({
				url: "http://api.uminfo.cn/city.php/city?pid=" + pid,
				dataType: 'json',
				type: 'get',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({}),
				success: function(msg) {
					for(var i = 0; i < msg.city.length; i++) {
						$("#city1").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
					}
				},
				error: function(e) {
					layer.msg("城市1列表信息出错!");
				}
			});
		}
	</script>
	<script>
		//获取城市列表2
		$.ajax({
			url: "http://api.uminfo.cn/city.php/province",
			dataType: 'json',
			type: 'get',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({}),
			success: function(msg) {
				for(var i = 0; i < msg.province.length; i++) {
					$("#province2").append('<option value="' + msg.province[i].id + '">' + msg.province[i].name + '</option>');
				}
			},
			error: function(e) {
				layer.msg("省份列表2信息出错!");
			}
		});
		function getPro2() {
			$("#city2").empty();
			var pid = $("#province2 option:selected").val();
			$.ajax({
				url: "http://api.uminfo.cn/city.php/city?pid=" + pid,
				dataType: 'json',
				type: 'get',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({}),
				success: function(msg) {
					for(var i = 0; i < msg.city.length; i++) {
						$("#city2").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
					}
				},
				error: function(e) {
					layer.msg("城市2列表信息出错!");
				}
			});
		}
	</script>
	<script>
		//获取城市列表3
		$.ajax({
			url: "http://api.uminfo.cn/city.php/province",
			dataType: 'json',
			type: 'get',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({}),
			success: function(msg) {
				for(var i = 0; i < msg.province.length; i++) {
					$("#province3").append('<option value="' + msg.province[i].id + '">' + msg.province[i].name + '</option>');
				}
			},
			error: function(e) {
				layer.msg("省份列表3信息出错!");
			}
		});
		$.ajax({
			url: "http://api.uminfo.cn/city.php/citys",
			dataType: 'json',
			type: 'get',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({}),
			success: function(msg) {
				for(var i = 0; i < msg.city.length; i++) {
					$("#city3").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
				}
			},
			error: function(e) {
				layer.msg("省份列表3信息出错!");
			}
		});
		function getPro3() {
			$("#city3").empty();
			var pid = $("#province3 option:selected").val();
			$.ajax({
				url: "http://api.uminfo.cn/city.php/city?pid=" + pid,
				dataType: 'json',
				type: 'get',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({}),
				success: function(msg) {
					for(var i = 0; i < msg.city.length; i++) {
						$("#city3").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
					}
				},
				error: function(e) {
					layer.msg("城市3列表信息出错!");
				}
			});
		}
	</script>
	<script>
		//获取城市列表4
		$.ajax({
			url: "http://api.uminfo.cn/city.php/province",
			dataType: 'json',
			type: 'get',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({}),
			success: function(msg) {
				for(var i = 0; i < msg.province.length; i++) {
					$("#province4").append('<option value="' + msg.province[i].id + '">' + msg.province[i].name + '</option>');
				}
			},
			error: function(e) {
				layer.msg("省份列表4信息出错!");
			}
		});

        $.ajax({
			url: "http://api.uminfo.cn/city.php/citys",
			dataType: 'json',
			type: 'get',
			ContentType: "application/json;charset=utf-8",
			data: JSON.stringify({}),
			success: function(msg) {
				for(var i = 0; i < msg.city.length; i++) {
					$("#city4").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
				}
			},
			error: function(e) {
				layer.msg("省份列表4信息出错!");
			}
		});

		function getPro4() {
			$("#city4").empty();
			var pid = $("#province4 option:selected").val();
			$.ajax({
				url: "http://api.uminfo.cn/city.php/city?pid=" + pid,
				dataType: 'json',
				type: 'get',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({}),
				success: function(msg) {
					for(var i = 0; i < msg.city.length; i++) {
						$("#city4").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
					}
				},
				error: function(e) {
					layer.msg("城市4列表信息出错!");
				}
			});
		}
	</script>
    <script>
        function sure2(id){
            tenant_id=$.getUrlParam('tenant_id');
            	customer_send_id = id;
            	var name_a=$("#s"+id).parent().prev().prev().prev().prev().text();
            	if(name_a.length>=8){
            	    name_a=name_a.substr(0,1)+"***"+name_a.substr(name_a.length-6,5);
               }
            	$(".shuru").html(name_a+"&nbsp;&nbsp;"+$("#s"+id).parent().prev().prev().text());
            	$(".box2").css("display","none");
            	$("#box10").css("display", "none");
            	$(".box").css("display","block");
            	if(customer_send_id==customer_accept_id){
            		layer.msg("选择收货人有误，请重新选择");
            	}
        }
    </script>
	<script type="text/javascript">
		//加载寄件人信息
		function loadsend(openid,tenant_id) {
			$.ajax({
				url: "http://api.uminfo.cn/customer.php/wxaddress?wx_openid=" + openid,
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id", tenant_id);
				},
				dataType: 'json',
				type: 'post',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({}),
				success: function(msg) {
					$("#box101").html("");
					if(msg.wxmessage==null||msg.wxmessage==""){
						layer.msg("地址记录为空，请添加地址");
					}else{
					for(var i = 0; i < msg.wxmessage.length; i++) {
						var a = '<div class="name"><p class="j_name">' + msg.wxmessage[i].customer_name + '</p></div><div class="phone"><p>' +
							msg.wxmessage[i].customer_phone + '</p></div><div class="dizhi"><p><span class="shen">'+msg.wxmessage[i].customer_province+'</span><span class="shi">' +
							msg.wxmessage[i].customer_city + '</span><span class=xx>' +
							msg.wxmessage[i].customer_address + '</span></p></div><div class="xian"></div><div class="tu"><div class="bj1_a" id="s'+msg.wxmessage[i].customer_id+'">确认</div>' +
							'</div><div class="tu2"><div class="bj"><img src="images/bj.png" alt=""></div>' +
							'<div class="bj1" id="c'+msg.wxmessage[i].customer_id+'">编辑</div><div class="sc"><img src="images/sc.png" alt=""></div><div class="sc1" id="d'+msg.wxmessage[i].customer_id+'">删除</div></div><div class="xian1"><div class="kbai"></div></div>';
						$("#box101").append(a);
                        $("#s"+msg.wxmessage[i].customer_id+"").attr('onclick',"sure2('"+msg.wxmessage[i].customer_id+"')");
						$("#c"+msg.wxmessage[i].customer_id+"").attr('onclick',"edit('"+msg.wxmessage[i].customer_id+"')");
                        $("#d"+msg.wxmessage[i].customer_id+"").attr('onclick',"delet('"+msg.wxmessage[i].customer_id+"')");
					}
				}
				},
				error: function(xhr) {
				layer.msg("获取后台失败！");
				}
			});
		}
		
	</script>
	<script type="text/javascript">
		function edit(id){
					$.ajax({
					url: "http://api.uminfo.cn/customer.php/onewxaddress",
					beforeSend: function(request) {
						request.setRequestHeader("tenant-id", tenant_id);
					},
					dataType: 'json',
					type: 'post',
					ContentType: "application/json;charset=utf-8",
					data: JSON.stringify({
						wx_openid:openid,
						customer_id:id,
					}),
					success: function(msg) {
			    
			    $("#name_1").val(msg.wxmessage.customer_name);
			    $("#phone_1").val(msg.wxmessage.customer_phone);
			    $("#province3").val(msg.wxmessage.customer_province);
			    $("#city3").val(msg.wxmessage.customer_city);
			    $("#dizhi_3").text(msg.wxmessage.customer_address);
					},
					error: function(xhr) {
						alert("获取数据失败");
					}
				});
				$(".box2").css("display","none");
				$(".box7").css("display","block");
				$("#box10").css("display", "none");
			$("#subaddress10").on("click",function(){
			var name_3 = $("#name_1").val();
			var phone_3 = $("#phone_1").val();
			var cit3 = $("#city3").val();
			var dizhi_3 = $("#dizhi_3").val();
			var province_3 =$("#province3").val();
			if(!/^1[34578]\d{9}$/.test(phone_3)){
				layer.msg("手机号码格式不对");
				return false;
				}else if(name_3==null||name_3==""){
					layer.msg("姓名为空");
				return false;
				}else if(cit3==null||cit3==""){
					layer.msg("未选择城市");
				return false;
				}else if(dizhi_3==null||dizhi_3==""){
					layer.msg("地址为空");
				return false;
			}else{
				$.ajax({
					url: "http://api.uminfo.cn/customer.php/customer_address",
					beforeSend: function(request) {
						request.setRequestHeader("tenant-id", tenant_id);
					},
					dataType: 'json',
					type: 'put',
					ContentType: "application/json;charset=utf-8",
					data: JSON.stringify({
						customer_name: name_3,
						customer_phone: phone_3,
						city_id: cit3,
						address: dizhi_3,
						type: 1,
						wx_openid: openid,
						customer_id:id,
					}),
					success: function(msg) {
					  layer.msg("修改地址成功");
						loadsend(openid,tenant_id);
						$(".box7").css("display", "none");
						$("#box10").css("display", "block");
						$("#box10").css("z-index", "100");
					},
					error: function(xhr) {
						alert("获取数据失败");
					}
				});
			}
		})
		}
		

	</script>
	<script type="text/javascript">
		//删除方法
		function delet(id,tenant_id) {
			if(tenant_id==null){
				 tenant_id=$.getUrlParam('tenant_id');
			}
			$.ajax({
				url: "http://api.uminfo.cn/customer.php/customer?customerid=" + id+"",
				beforeSend: function(request) {
					request.setRequestHeader("tenant-id", tenant_id);
				},
				dataType: 'json',
				type: 'delete',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({}),
				success: function(msg) {
				//	alert(1)
				//	layer.msg("删除成功");
					loadsend(openid,tenant_id);
					loadaccept(openid,tenant_id);
				},
				error: function(xhr) {
			//		alert(2)
					layer.msg("获取后台失败！");
				}
			});
		}
	</script>
    <script>
        function sure1(id){
                tenant_id=$.getUrlParam('tenant_id');
            	customer_accept_id =id;
            	if(customer_accept_id != customer_send_id) {
            	    var name_a=$("#r"+id).parent().prev().prev().prev().prev().text();
            	    if(name_a.length>=8){
                           name_a=name_a.substr(0,1)+"***"+name_a.substr(name_a.length-6,5);
                   }
            		$(".shuru1").html($("#r"+id).parent().prev().prev().prev().prev().text()+"&nbsp;&nbsp;"+$("#r"+id).parent().prev().prev().text());
            		$(".box3").css("display","none");
            		$("#box11").css("display", "none");
            		$(".box").css("display","block");
            	} else {
            		layer.msg("寄件人不能和收件人相同");
            	}
        }
    </script>
	
	<script type="text/javascript">
		//加载收货地址
		function loadaccept(openid,tenant_id) {
			$.ajax({
				url: "http://api.uminfo.cn/customer.php/wxaddress?wx_openid=" + openid,
				beforeSend: function(request) {
				request.setRequestHeader("tenant-id", tenant_id);
			},
				dataType: 'json',
				type: 'post',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({}),
				success: function(msg) {
					$("#box111").html("");
					if(msg.wxmessage==null||msg.wxmessage==""){
						layer.msg("地址记录为空，请添加地址");
					}else{
					for(var i = 0; i < msg.wxmessage.length; i++) {
                        var a = '<div class="name"><p class="s_name">' + msg.wxmessage[i].customer_name + '</p></div><div class="phone"><p>' +
                            msg.wxmessage[i].customer_phone + '</p></div><div class="dizhi"><p><span class=shen1>'+msg.wxmessage[i].customer_province+'</span><span class="shi1">' +
                            msg.wxmessage[i].customer_city + '</span><span class="xx1">' +
                            msg.wxmessage[i].customer_address + '</span></p></div><div class="xian"></div><div class="tu"><div class="bj1_a" id="r'+msg.wxmessage[i].customer_id+'">确认</div></div><div class="tu2"><div class="bj"><img src="images/bj.png" alt=""></div>' +
                            '<div class="bj1" id="c'+msg.wxmessage[i].customer_id+'">编辑</div><div class="sc"><img src="images/sc.png" alt=""></div><div class="sc1" id="d'+msg.wxmessage[i].customer_id+'">删除</div></div><div class="xian1"><div class="kbai"></div></div>';
                        $("#box111").append(a);
						// $("#subaddress4").on('click', function() {
						// 	customer_accept_id = $("input[class='rad2']:checked").val();
						// 	if(customer_accept_id != customer_send_id) {
						// 	    var name_a=$("input[class='rad2']:checked").parent().prev().prev().prev().prev().text();
						// 	    if(name_a.length>=8){
                         //                name_a=name_a.substr(0,1)+"***"+name_a.substr(name_a.length-6,5);
                         //        }
						// 		$(".shuru1").html($("input[class='rad2']:checked").parent().prev().prev().prev().prev().text()+"&nbsp;&nbsp;"+$("input[class='rad2']:checked").parent().prev().prev().text());
						// 		$(".box3").css("display","none");
						// 		$("#box11").css("display", "none");
						// 		$(".box").css("display","block");
						// 	} else {
						// 		layer.msg("寄件人不能和收件人相同");
						// 	}
						// });
                        $("#r"+msg.wxmessage[i].customer_id+"").attr('onclick',"sure1('"+msg.wxmessage[i].customer_id+"')");
                        $("#c"+msg.wxmessage[i].customer_id+"").attr('onclick',"edit1('"+msg.wxmessage[i].customer_id+"')");
                        $("#d"+msg.wxmessage[i].customer_id+"").attr('onclick',"delet('"+msg.wxmessage[i].customer_id+"')");
					}
					}
				},
				error: function(xhr) {
					layer.msg("获取后台失败！");
				}
			});
		}
	</script>
	<script type="text/javascript">
		function edit1(id){
					$.ajax({
					url: "http://api.uminfo.cn/customer.php/onewxaddress",
					beforeSend: function(request) {
						request.setRequestHeader("tenant-id", tenant_id);
					},
					dataType: 'json',
					type: 'post',
					ContentType: "application/json;charset=utf-8",
					data: JSON.stringify({
						wx_openid:openid,
						customer_id:id,
					}),
					success: function(msg) {
			    
			    $("#name_2").val(msg.wxmessage.customer_name);
			    $("#phone_2").val(msg.wxmessage.customer_phone);
			    $("#province4").val(msg.wxmessage.customer_province);
			    $("#city4").val(msg.wxmessage.customer_city);
			    $("#dizhi_4").text(msg.wxmessage.customer_address);
					},
					error: function(xhr) {
						alert("获取数据失败");
					}
				});
				$(".box3").css("display","none");
				$(".box8").css("display","block");
				$("#box11").css("display", "none");
			$("#subaddress11").on("click", function() {
			var name_4 = $("#name_2").val();
			var phone_4 = $("#phone_2").val();
			var cit4 = $("#city4").val();
			var dizhi_4 = $("#dizhi_4").val();
			var province_4 =$("#province4").val();
			if(!/^1[34578]\d{9}$/.test(phone_4)) {
				layer.msg("手机号码格式不对");
				return false;
			}else if(name_4==null||name_4==""){
				layer.msg("姓名不能为空");
				return false;
			}else if(cit4==null||cit4==""){
				layer.msg("未选择城市");
				return false;
			}else if(dizhi_4==null||dizhi_4==""){
				layer.msg("地址为空");
				return false;
			} else {
				$.ajax({
					url: "http://api.uminfo.cn/customer.php/customer_address",
					beforeSend: function(request) {
						request.setRequestHeader("tenant-id", tenant_id);
					},
					dataType: 'json',
					type: 'put',
					ContentType: "application/json;charset=utf-8",
					data: JSON.stringify({
						customer_name: name_4,
						customer_phone: phone_4,
						city_id: cit4,
						address: dizhi_4,
						type: 0,
						wx_openid: openid,
						customer_id:id,
					}),
					success: function(msg) {
					  layer.msg("修改地址成功");
						loadaccept(openid,tenant_id);
						$(".box8").css("display", "none");
						$("#box11").css("display", "block");
						$("#box11").css("z-index", "100");
					},
					error: function(xhr) {
						alert("获取数据失败");
					}
				});
			}
		});
		}
		
	</script>
	<script type="text/javascript">
		//添加寄件地址s
		$("#subaddress3").on("click", function() {
			var name_1 = $(".name_1").val();
			var phone_1 = $(".phone_1").val();
			var cit =$('#city1 option:selected').val();
			var cit_v=$('#city1 option:selected').text();
			var dizhi_1 = $("#dizhi_1").val();
			var province_1 =$("#province1").val();

			if(!/^1[34578]\d{9}$/.test(phone_1)) {
				layer.msg("手机号码格式不对");
				return false;
				}else if(name_1==null||name_1==""){
					layer.msg("寄件人姓名为空");
				return false;
				}else if(cit==null||cit==""){
					layer.msg("未选择城市");
				return false;
				}else if(dizhi_1==null||dizhi_1==""){
					layer.msg("地址不能为空");
				return false;
			} else {
				$.ajax({
					url: "http://api.uminfo.cn/customer.php/plus_customer",
					beforeSend: function(request) {
						request.setRequestHeader("tenant-id", tenant_id);
					},
					dataType: 'json',
					type: 'post',
					ContentType: "application/json;charset=utf-8",
					data: JSON.stringify({
						customer_name: name_1,
						customer_phone: phone_1,
						city_id: cit,
						address: dizhi_1,
						type: 1,
						wx_openid: openid,
					}),
					success: function(msg) {
				  layer.msg("添加地址成功");
                        if(name_1.length>=10){
                            name_1=name_1.substr(0,1)+"***"+name_1.substr(name_1.length-6,5);
                        }
				  		$(".shuru").html(name_1+"&nbsp;&nbsp;"+cit_v+ dizhi_1);
				  		customer_send_id=msg.customer_id;
						loadsend(openid,tenant_id);
						$(".box2").css("display", "none");
						$(".box").css("display","block");
					},
					error: function(xhr) {
						alert("获取数据失败");
					}
				});
			}
		});
	</script>
	<script type="text/javascript">
		//添加收货地址
		$("#subaddress5").on("click", function() {
			var name_2 = $(".name_2").val();
			var phone_2 = $(".phone_2").val();
			var cit1 = $('#city2 option:selected').val();
			var cit1_v=$('#city2 option:selected').text();
			var dizhi_2 = $("#dizhi_2").val();
			var province_2 =$("#province2").val();
			if(!/^1[34578]\d{9}$/.test(phone_2)) {
				layer.msg("手机号码格式不对");
				return false;
			}else if(name_2==null||name_2==""){
				layer.msg("收货人姓名为空");
				return false;
			}else if(cit1==null||cit1==""){
				layer.msg("未选择城市");
				return false;
			}else if(dizhi_2==null||dizhi_2==""){
				layer.msg("地址不能为空");
				return false;
			} else {
				$.ajax({
					url: "http://api.uminfo.cn/customer.php/plus_customer",
					beforeSend: function(request) {
						request.setRequestHeader("tenant-id", tenant_id);
					},
					dataType: 'json',
					type: 'post',
					ContentType: "application/json;charset=utf-8",
					data: JSON.stringify({
						customer_name: name_2,
						customer_phone: phone_2,
						city_id: cit1,
						address: dizhi_2,
						type: 0,
						wx_openid: openid,
					}),
					success: function(msg) {
					  layer.msg("添加地址成功");
					  customer_accept_id=msg.customer_id;
					  if(name_2.length>=10){
                          name_2=name_2.substr(0,1)+"***"+name_2.substr(name_2.length-6,5);
                      }
					    $(".shuru1").html(name_2+"&nbsp;&nbsp;"+cit1_v+ dizhi_2);
						loadaccept(openid,tenant_id);
						$(".box3").css("display", "none");
						$(".box").css("display","block");
					},
					error: function(xhr) {
						alert("获取数据失败");
					}
				});
			}
		});
	</script>
	<script type="text/javascript">
		$(".tijiao").on('click', function() {
			var huowu = $(".huowu").val();
			var str1 = $(".zhong").val();
			var zhong = str1 * 1;
			var str2 = $(".tiji").val();
			var tiji = str2 * 1;
			
			var jianshu = parseInt($(".jianshu").val());
			var needs = $(".needs").val();
			var jiazhi = parseInt($(".jiazhi").val());
			var money = $(".money").val();
			var baozhuang=$("#package").val();
			var str = 4;
			if(money == "到付") {
				str = 1;
			} else if(money == "现付") {
				str = 0;
			} else if(money == "月付") {
				str = 2;
			}else if(money=="回单"){
				str=3;
			}
			if(customer_send_id == "" || customer_send_id == null) {
				layer.msg("发货人信息不全");
			} else if(customer_accept_id == "" || customer_accept_id == null) {
				layer.msg("收件人信息不全");
			} else if(huowu == "" || huowu == null) {
				layer.msg("缺少货物名");
			} else if(zhong == "" || zhong == 0) {
				layer.msg("缺少货物重量或货物重量不能为0");
			} else if(tiji == "" || tiji == 0) {
				layer.msg("缺少货物体积或体积不能为0");
			} else if(baozhuang == "") {
				layer.msg("未选择货物包装");
			} else if(jianshu == "" || jianshu == 0) {
				layer.msg("缺少货物件数或货物件数不能为0");
			} else if(jiazhi == "" || jiazhi == 0) {
				layer.msg("缺少货物价值或货物价值不能为0");
			} else if(str == 4) {
				layer.msg("未选择付款方式");
			} else if(customer_accept_id==customer_send_id){
				layer.msg("收货人与寄件人相同");
			}else if(needs==""||needs==null){
				layer.msg("您未选择备注");
			}else{
			    // alert(customer_send_id+"////"+customer_accept_id+"////"+huowu+"////"+zhong+"////"+tiji+"////"+baozhuang+"////"+jianshu+"////"+needs+"////"+jiazhi+"////"+str+"////"+openid);
				$.ajax({
					url: "http://api.uminfo.cn/wxmessage.php/wxmessage_insert",
					beforeSend: function(request) {
						request.setRequestHeader("tenant-id", tenant_id);
					},
					dataType: 'json',
					type: 'post',
					ContentType: "application/json;charset=utf-8",
					data: JSON.stringify({
						customer_send_id: customer_send_id,
						customer_accept_id: customer_accept_id,
						goods_name: huowu,
						goods_weight: zhong,
						goods_capacity: tiji,
						goods_package: baozhuang,
						goods_count: jianshu,
						special_need: needs,
						good_worth: jiazhi,
						pay_method: str,
						openid: openid
					}),
					success: function(msg) {
                        if(msg.result==1){
                        	 alert("订单提交成功");
                        	wx.closeWindow();
                        };
					},
					error: function(xhr) {
						layer.msg("提交订单失败");
					},
				})
			}
		})
	</script>
	<script type="text/javascript">
		$(".qx").on("click", function() {
			$(".box2").css("display", "none");
			$(".box").css("display", "block");
		})
		$(".qx10").on("click", function() {
			$(".box7").css("display", "none");
			$("#box10").css("display", "block");
			$("#box10").css("z-index", "100");
		})
		$(".qx1").on("click", function() {
			$(".box3").css("display", "none");
			$(".box").css("display", "block");
		})
		$(".qx11").on("click", function() {
			$(".box8").css("display", "none");
			$("#box11").css("display", "block");
			$("#box11").css("z-index", "100");
		})
		$(".qx2").on("click", function() {
			$(".box4").css("display", "none");
		})
		$(".qx3").on("click", function() {
			$(".box6").css("display", "none");
		})
		$(".qx6").on("click", function() {
			$("#box11").css("display", "none");
//			$(".box").css("display","block");
		})
		$(".qx5").on("click", function() {
			$("#box10").css("display", "none");
//			$(".box").css("display","block");
		})
		$(".ls").on("click",function(){
			$("#box10").css("display","block");
			$("#box10").css("z-index", "100");
		})
		$(".ls1").on("click",function(){
			$("#box11").css("display","block");
			$("#box11").css("z-index", "100");
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
<script type="text/javascript">
 $(".name_1").on("keyup",function () {
     var leng=$(this).val().length;
     if(leng>15){
         $(this).val($(this).val().substr(0,15));
     }
 })
 $(".name_2").on("keyup",function () {
     var leng=$(this).val().length;
     if(leng>15){
         $(this).val($(this).val().substr(0,15));
     }
 })

</script>
</html>