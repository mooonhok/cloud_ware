$(function(){
    var adminid=$.session.get('adminid');
    var page = $.getUrlParam('page');
    var plate_number='';
    loadlorrys(plate_number,page) ;
    $('#order_close').on("click",function () {
        $(".tenant_tk").css("display","none");
    })

    $(".sousuo_z").on("click",function(){
        alert(1)
        var plate_number=$(".plate_number").val();
        loadlorrys(plate_number,page) ;
    })
});

(function($) {
    $.getUrlParam = function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r != null) return decodeURI(r[2]);
        return null;
    }
})(jQuery);

function loadlorrys(plate_number,page) {
    if(plate_number==null){
        plate_number="";
    }
    if(page==null){
        page=1;
    }
    $.ajax({
        url: "http://api.uminfo.cn/lorry.php/lorrys_plate_number?plate_number="+plate_number+"&page="+page+"&per_page=10",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            console.log(msg)
            $("#tb1").html("");
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
                            loadlorrys(plate_number,obj.curr);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData = msg.lorrys;
                            layui.each(thisData, function(index, item){
                                var info='-';
                                if(item.flag==1){
                                    info='派送车';
                                }else if(item.flag==0){
                                    info='运输车';
                                }
                                arr.push( '<tr><td style="display:none">'+item.app_lorry_id+'</td><td>'+item.phone+'</td><td>'+item.name+'</td><td>'+item.id_number+'</td><td>'+item.plate_number+'</td><td>'+info+'</td><td>'+item.lorry_length_name+'</td><td>'+item.deadweight+'</td><td>'+item.lorry_type_name+'</td><td class="look"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
                            });
                            return arr.join('');
                        }();
                        $(".look").on("click",function(){
                            var app_lorry_id=$(this).parent().children().eq(0).text();
                            alert(app_lorry_id)
                            lorry_xq(app_lorry_id);
                        })
                    }
                });
            });
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
        area: ['800px', '450px'], //宽高
        content: '<div class="tenant_tk">' +
        '<h1 style="text-align:center;">详情</h1>' +
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
        '<input type="text" id="phone"/>' +
        '<input type="text" id="name"/>' +
        '<input type="text" id="id_card"/>' +
        '<input type="text" id="plate_number"/>' +
        '<input type="text" id="lorry_length"/>' +
        '<input type="text" id="lorry_type"/>' +
        '<input type="text" id="lorry_weight"/>' +
        '</div>' +
        '<h3>身份证正面</h3>' +
        '<img src="" id="id_z" class="image1"/>'+
        '<h3>身份证反面</h3>' +
        '<img src="" id="id_f" class="image1"/>'+
        '<h3>驾驶证正面</h3>' +
        '<img src="" id="j_z" class="image1"/>'+
        '<h3>驾驶证反面</h3>' +
        '<img src="" id="j_f" class="image1"/>'+
        '<h3>行驶证正面</h3>' +
        '<img src="" id="x_z" class="image1"/>'+
        '<h3>行驶证反面</h3>' +
        '<img src="" id="x_f" class="image1"/>'+
        '<button id="order_close">关闭</button>' +
        '</div>'
    });
    $("#order_close").on("click",function(){
        layer.close(index);
    });
    $.ajax({
        url: "http://api.uminfo.cn/lorry.php/getAppLorry?app_lorry_id="+id+"",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            console.log(msg);
            $("#phone").val(msg.lorrys.phone);
            $("#name").val(msg.lorrys.name);
            $("#id_card").val(msg.lorrys.id_number);
            $("#plate_number").val(msg.lorrys.plate_number);
            $("#lorry_length").val(msg.lorrys.lorry_length_name);
            $("#lorry_type").val(msg.lorrys.lorry_type_name);
            $("#lorry_weight").val(msg.lorrys.lorry_load_name);
            $("#id_z").attr("src",msg.lorrys.identity_card_z);
            $("#id_f").attr("src",msg.lorrys.identity_card_f);
            $("#j_z").attr("src",msg.lorrys.driver_license_fp);
            $("#j_f").attr("src",msg.lorrys.driver_license_tp);
            $("#x_z").attr("src",msg.lorrys.driving_license_fp);
            $("#x_f").attr("src",msg.lorrys.driving_license_tp);
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}
