$(function() {
	var adminid = $.session.get('adminid');
	var page = null;

	loadminis(page);
});

function loadminis(page) {
	if(page == null) {
		page = 1;
	}
	$.ajax({
		url: "http://api.uminfo.cn/mini.php/allmini?page=" + page + "&per_page=10",
		dataType: 'json',
		type: 'get',
		ContentType: "application/json;charset=utf-8",
		data: JSON.stringify({}),
		success: function(msg) {
			console.log(msg)
			$("#tb1").html("");
			//调用分页
			layui.use(['laypage', 'layer'], function() {
				var laypage = layui.laypage;
				laypage.render({
					elem: 'demo20',
					count: msg.count,
					curr: page,
					limit: 10,
					jump: function(obj, first) {
						if(!first) {
							loadminis(obj.curr);
						}
						//模拟渲染
						document.getElementById('tb1').innerHTML = function() {
							var arr = [],
								thisData = msg.mini_tenant;
							layui.each(thisData, function(index, item) {
								var a = "";
								if(item.flag == 1) {
									a = "物流专线";
								} else {
									a = "精品专线";
								}
								arr.push('<tr><td>' + item.id +
									'</td><td>' + item.name +
									'</td><td>' + item.address +
									'</td><td>' + item.person +
									'</td><td>' + item.phone +
									'</td><td>' + a +
									'</td><td>' + item.public_name +
									'</td><td>' + item.telephone +
									'</td><td onclick="minishow(' +
									item.id + ')"><span style="color:blue; cursor:pointer;">查看</span>' +
									'</td><td onclick="routeshow(' +
									item.id + ')"><span style="color:blue; cursor:pointer;">查看</span>' + '</tr>');
							});
							return arr.join('');
						}();
					}
				});
			});
		},
		error: function(xhr) {
			alert("获取后台失败！");
		}
	});

}

function minishow(id) {

	var index = layer.open({
		type: 1,
		skin: 'layui-layer-rim', //加上边框
		area: ['800px', '700px'], //宽高
		content: '<div class="tenant_tk">' +
			'<h1 style="text-align:center;">修改小程序租户信息</h1>' +
			'<div>' +
			'<div>公司名</div>' +
			'<div>公司地址</div>' +
			'<div>联系人</div>' +
			'<div>电话</div>' +
			'<div>类型</div>' +
			'<div>公众号名称</div>' +
			'<div>坐标</div>' +
			'<div>座机号</div>' +
			'<div>路线介绍</div>' +
			'<div>公司介绍</div>' +
			'</div>' +
			'<div>' +
			'<input type="text" id="id" style="display:none;"/>' +
			'<input type="text" id="company" disabled="disabled"/>' +
			'<input type="text" id="address" disabled="disabled"/>' +
			'<input type="text" id="person" disabled="disabled"/>' +
			'<input type="text" id="phone" disabled="disabled"/>' +
			'<select id="flag" disabled="disabled"></select>' +
			'<input type="text" id="publicname" disabled="disabled"/>' +
			'<input type="text" id="location" disabled="disabled"/>' +
			'<input type="text" id="telephone" disabled="disabled"/>' +
			'<input type="text" id="line" disabled="disabled"/>' +
			'<input type="text" id="intro" disabled="disabled"/>' +
			'</div>' +

			'<h3>列表图片</h3>' +
			'<label><img src="" id="img1" class="image1"/><input type="file" style="display:none;" class="id_f"/></label>' +
			'<h3>滚动广告1</h3>' +
			'<label><img src="" id="swiper_img1" class="image1"/><input type="file" style="display:none;" class="j_z"/></label>' +
			'<h3>微信公众号图片</h3>' +
			'<label><img src="" id="publicimg" class="image1"/><input type="file" style="display:none;" class="id_z"/></label>' +
			'<h3>滚动广告2</h3>' +
			'<label><img src="" id="swiper_img2" class="image1"/><input type="file" style="display:none;" class="j_f"/></label>' +
			'<h3>滚动广告3</h3>' +
			'<label><img src="" id="swiper_img3" class="image1"/><input type="file" style="display:none;" class="x_z"</label>' +
			'<h3>滚动广告4</h3>' +
			'<label><img src="" id="swiper_img4" class="image1"/><input type="file" style="display:none;" class="x_f"/></label>' +
			//      '<button id="order_sure">确定</button><button id="order_cancle">取消</button>' +
			'<button id="order_cancle">关闭</button>' +
			'</div>'
	});

	$("#order_cancle").on("click", function() {
		layer.close(index);
	});
	$.ajax({
		url: "http://api.uminfo.cn/mini.php/minibyid?tid=" + id,
		dataType: 'json',
		type: 'get',
		ContentType: "application/json;charset=utf-8",
		data: JSON.stringify({}),
		success: function(msg) {
			if(msg.result == 0) {
				$("#id").val(msg.routes.id);
				$("#company").val(msg.routes.name);
				$("#address").val(msg.routes.address);
				$("#person").val(msg.routes.person);
				$("#telephone").val(msg.routes.telephone);
				$("#phone").val(msg.routes.phone);
				$("#publicname").val(msg.routes.public_name);
				$("#location").val(msg.routes.latitude + "," + msg.routes.longitude);
				$("#line").val(msg.routes.line);
				$("#intro").val(msg.routes.intro);
				$('#flag').append('<option value="0" id="select1">精品物流</option>');
				$('#flag').append('<option value="1" id="select2">物流专线</option>');
				$('#select' + msg.routes.flag).attr('selected', 'selected');
				$("#publicimg").attr("src", msg.routes.public_img);
				$("#img1").attr("src", msg.routes.img);
				$("#swiper_img1").attr("src", msg.routes.swiper_img1);
				$("#swiper_img2").attr("src", msg.routes.swiper_img2);
				$("#swiper_img3").attr("src", msg.routes.swiper_img3);
				$("#swiper_img4").attr("src", msg.routes.swiper_img4);
			} else {
				alert(msg.desc);
			}
		},
		error: function(xhr) {
			alert("获取后台失败！");
		}
	});

}

function routeshow(id) {
	var index = layer.open({
		type: 1,
		skin: 'layui-layer-rim', //加上边框
		area: ['800px', '700px'], //宽高
		content: '<div class="tenant_tk">' +
			'<h1 style="text-align:center;">小程序租户线路</h1>' +
			'<div id="textint">' +
			'<div>公司名</div>' +
			'</div>' +
			'<div id="routes">' +
			'<input type="text" id="id" style="display:none;"/>' +
			'<input type="text" id="company" disabled="disabled"/>' +
			'</div>' +
			'<button id="route_sure">确定</button><button id="route_cancle">取消</button>' +
			'</div>'
	});
	$("#route_cancle").on("click", function() {
		layer.close(index);
	});
	$.ajax({
		url: "http://api.uminfo.cn/mini.php/minibyid?tid=" + id,
		dataType: 'json',
		type: 'get',
		ContentType: "application/json;charset=utf-8",
		data: JSON.stringify({}),
		success: function(msg) {
			if(msg.result == 0) {
				$("#id").val(msg.routes.id);
				$("#company").val(msg.routes.name);
				$("#routes").append("<span>");
				for(var h = 0; h < msg.routes.routecount; h++) {
				    $("#routes").append(msg.routes.route[h].line+',');
				}
				$("#routes").append("</span>");
			} else {
				alert(msg.desc);
			}
		},
		error: function(xhr) {
			alert("获取后台失败！");
		}
	});

}