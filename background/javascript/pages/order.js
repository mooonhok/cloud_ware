$(function(){
    var adminid=$.session.get('adminid');
    var company=$.session.get('company');
    var company_name=$.session.get('company_name');
    $('#shmz_name').html(company_name);
    var page = $.getUrlParam('page');
    var order_id='';
    loadorders(order_id,page,company);
    $('#order_close').on("click",function () {
        $(".tenant_tk").css("display","none");
    })

    $(".sousuo_z").on("click",function(){
        alert(1)
        var order_id=$(".order_id").val();
        loadorders(order_id,page,company);
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

function loadorders(order_id,page,company) {
    if(order_id==null){
       order_id="";
    }
    if(page==null){
        page=1;
    }
    if(company==null){
        company='';
    }
    $.ajax({
        url: p_url+"order.php/orders?order_id="+order_id+"&page="+page+"&per_page=10&company="+company,
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
                            loadorders(order_id,obj.curr,company);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData = msg.orders;
                            layui.each(thisData, function(index, item){
                                var info='-';
                                if(item.order_status==0){
                                      info='下单';
                                }else if(item.order_status==1){
                                     info='入库';
                                }else if(item.order_status==2){
                                      info='出库';
                                }else if(item.order_status==3){
                                      info='在途';
                                }else if(item.order_status==4){
                                      info='到达';
                                }else if(item.order_status==5){
                                      info='收货';
                                }
                                arr.push( '<tr><td>'+item.order_id+'</td><td>'+item.tenant.company+'</td><td>'+item.from_city.name+'</td><td>'+item.receiver.receiver_city+'</td><td>'+item.sender.customer_name+'</td><td>'+item.receiver.customer_name+'</td><td>'+item.order_datetime1+'</td><td>'+info+'</td><td onclick="order_xq('+item.order_id + ')"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
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


function order_xq(id){
    $(".tenant_tk").css("display","block");
    $(".tenant_tk div input").val("");
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['420px', '450px'], //宽高
        content: '<div class="tenant_tk">' +
        '<h1 style="text-align:center;">详情</h1>' +
        '<div>' +
        '<div>货物名称</div>' +
        '<div>货物重量(吨)</div>' +
        '<div>货物体积(立方)</div>' +
        '<div>货物数量</div>' +
        '<div>货物价值(万元)</div>' +
        '<div>货物包装</div>' +
        '</div>' +
        '<div>' +
        '<input type="text" id="tenant_id" disabled="disabled"/>' +
        '<input type="text" id="tenant_num" disabled="disabled"/>' +
        '<input type="text" id="app_id" disabled="disabled"/>' +
        '<input type="text" id="secret" disabled="disabled"/>' +
        '<input type="text" id="customer_name" disabled="disabled"/>' +
        '<input type="text" id="customer_phone" disabled="disabled"/>' +
        '</div>' +
        '<button id="order_close">关闭</button>' +
        '</div>'
    });
    $.ajax({
        url: p_url+"goods.php/goods_order_id?order_id="+id,
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            console.log(msg);
            $("#tenant_id").val(msg.goods.goods_name);
            $("#tenant_num").val(msg.goods.goods_weight);
            $("#app_id").val(msg.goods.goods_capacity);
            $("#secret").val(msg.goods.goods_count);
            $("#customer_name").val(msg.goods.goods_value);
            $("#customer_phone").val(msg.goods.goods_package);
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}
