$(function(){
  var adminid=$.session.get('adminid');
//  var adminid=3;
    if(adminid==null||adminid==""){
    	window.location.href=p_url+"tenantsback/login.html";
    }
    var page = $.getUrlParam('page');
    var tenant_id=null;
    tenant_id=$.getUrlParam("tenant_id");
    $.ajax({
	 url: p_url+"tenantsback.php/gettenants?adminid="+adminid,
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
        	if(msg.result==0){
        		$("#companytitle").html(msg.tenants[0].name);
        		if(tenant_id==null||tenant_id==""){
        		tenant_id=msg.tenants[0].tenant_id;
        		}
        		$(".order_id").val("");
        		for(var i=0;i<msg.tenants.length;i++){
        		$(".order_id").append('<option value="' + msg.tenants[i].tenant_id + '">' + msg.tenants[i].name + '</option>');
        		}
        	}
        	loadorders(tenant_id,page);
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
    
    $(".sousuo_z").on("click",function(){
         tenant_id=$(".order_id").val();
        loadorders(tenant_id,page);
    });
});





(function($) {
    $.getUrlParam = function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r != null) return decodeURI(r[2]);
        return null;
    }
})(jQuery);

function loadorders(tenant_id,page) {
    if(tenant_id==null){
       tenant_id="";
    }
    if(page==null){
        page=1;
    }
    $.ajax({
        url: p_url+"tenantsback.php/lorrys?tenant-id="+tenant_id+"&page="+page+"&perpage=10",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
           
            $("#tb1").html("");
          if(msg.lorrys!=null){
            //调用分页
            layui.use(['laypage', 'layer'], function(){
                var laypage = layui.laypage;
                laypage.render({
                    elem: 'demo20'
                    ,count:msg.count
                    ,curr:page
                    ,limit: 10
                    ,jump: function(obj,first){
                        if(!first){
                            loadorders(tenant_id,obj.curr);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData=msg.argees;
                            layui.each(thisData, function(index, item){
                                arr.push('<tr><td style="display:none">'+item.applorryid+'</td><td>'+item.plate_number+'</td><td>'
                                +item.driver_name+'</td><td>'
                                +item.driver_phone+'</td><td>'
                                +item.typename+'</td><td>'
                                +item.length+'</td><td>'
                                +item.deadweight+'</td><td class="look"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
                            });
                            return arr.join('');
                        }();
                        $(".look").on("click",function(){
                            var app_lorry_id=$(this).parent().children().eq(0).text();
                            lorry_xq(app_lorry_id);
                        })
                    }
                });
            });
            }else{
             $("#tb1").html("尚未有数据");	
            }
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}


function lorry_xq(id){
    // $(".tenant_tk").css("display","block");
    // $(".tenant_tk div input").val("");

    var index=layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['800px', '600px'], //宽高
        content: '<div class="tenant_tk">' +
        '<h1 style="text-align:center;">修改车辆信息</h1>' +
        '<div>' +
        '<div>手机号</div>' +
        '<div>司机名字</div>' +
        '<div>身份证号码</div>' +
        '<div>车牌号</div>' +
        '<div>车长</div>' +
        '<div>车的类型</div>' +
        '<div>载重</div>' +
        '</div>' +
        '<div>' +
        '<input type="text" id="lorry_id" style="display:none;"/>' +
        '<input type="text" id="phone"/>' +
        '<input type="text" id="name"/>' +
        '<input type="text" id="id_card"/>' +
        '<input type="text" id="plate_number"/>' +
        '<select id="lorry_length"></select>' +
        '<select id="lorry_type"></select>' +
        '<input type="text" id="lorry_weight"/>' +
        '</div>' +
        '<h3>身份证正面</h3>' +
        '<label><img src="" id="id_z" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="id_z"/></label>'+
        '<h3>身份证反面</h3>' +
        '<label><img src="" id="id_f" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="id_f"/></label>'+
        '<h3>驾驶证正面</h3>' +
        '<label><img src="" id="j_z" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="j_z"/></label>'+
        '<h3>驾驶证反面</h3>' +
        '<label><img src="" id="j_f" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="j_f"/></label>'+
        '<h3>行驶证正面</h3>' +
        '<label><img src="" id="x_z" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="x_z"</label>'+
        '<h3>行驶证反面</h3>' +
        '<label><img src="" id="x_f" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="x_f"/></label>'+
        '<button id="order_cancle">关闭</button>' +
        '</div>'
    });



  $("#order_cancle").on("click",function(){
        layer.close(index);
  });
  
    $.ajax({
        url: p_url+"lorry.php/getAppLorry?app_lorry_id="+id+"",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            // console.log(msg);
            $("#lorry_id").val(msg.lorrys.app_lorry_id);
            $("#phone").val(msg.lorrys.phone);
            $("#name").val(msg.lorrys.name);
            $("#id_card").val(msg.lorrys.id_number);
            $("#plate_number").val(msg.lorrys.plate_number);
            // $("#lorry_length").val(msg.lorrys.lorry_length_name);
            // $("#lorry_type").val(msg.lorrys.lorry_type_name);
            $("#lorry_weight").val(msg.lorrys.lorry_load_name);
            $("#id_z").attr("src",msg.lorrys.identity_card_z);
            $("#id_f").attr("src",msg.lorrys.identity_card_f);
            $("#j_z").attr("src",msg.lorrys.driver_license_fp);
            $("#j_f").attr("src",msg.lorrys.driver_license_tp);
            $("#x_z").attr("src",msg.lorrys.driving_license_fp);
            $("#x_f").attr("src",msg.lorrys.driving_license_tp);
            $.ajax({
                url: p_url+"app.php/lorry_type",
                dataType: 'json',
                type: 'get',
                ContentType: "application/json;charset=utf-8",
                data: JSON.stringify({}),
                success: function(ret) {
                    // console.log(msg);
                    if(ret.result==0){
                        for(var i=0;i<ret.lorry_type.length;i++){
                            $('#lorry_type').append('<option value="'+ret.lorry_type[i].lorry_type_id+'" id="ve_size'+ret.lorry_type[i].lorry_type_id+'">'+ret.lorry_type[i].lorry_type_name+'</option>');
                            $('#ve_size'+msg.lorrys.type).attr('selected','selected');
                        }
                    }
                },
                error: function(xhr) {
                    alert("获取后台失败！");
                }
            });

            $.ajax({
                url: p_url+"app.php/lorry_long",
                dataType: 'json',
                type: 'get',
                ContentType: "application/json;charset=utf-8",
                data: JSON.stringify({}),
                success: function(ret) {
                    if(ret.result==0){
                        for(var i=0;i<ret.vehiche_long.length;i++){
                            $('#lorry_length').append('<option value="'+ret.vehiche_long[i].lorry_length_id+'" id="ve_long'+ret.vehiche_long[i].lorry_length_id+'">'+ret.vehiche_long[i].lorry_length+'</option>');
                            $('#ve_long'+msg.lorrys.length).attr('selected','selected');
                        }
                    }
                },
                error: function(xhr) {
                    alert("获取后台失败！");
                }
            });
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
    
}