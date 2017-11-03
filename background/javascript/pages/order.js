$(function(){
    var adminid=$.session.get('adminid');
    var page = $.getUrlParam('page');
    loadorders(adminid,page);
});

(function($) {
    $.getUrlParam = function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r != null) return decodeURI(r[2]);
        return null;
    }
})(jQuery);

function loadorders(order_id,page) {
    if(page==null){
        page=1;
    }
    $.ajax({
        url: "http://api.uminfo.cn/order.php/orders?order_id="+order_id+"&page="+page+"&per_page=10",
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
                            loadorders(order_id,obj.curr);
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
                                arr.push( '<tr><td>'+item.order_id+'</td><td>'+item.tenant.company+'</td><td>'+item.from_city.name+'</td><td>'+item.receiver.receiver_city+'</td><td>'+item.sender.customer_name+'</td><td>'+item.receiver.customer_name+'</td><td>'+item.order_datetime1+'</td><td>'+info+'</td><td onclick="tenant_xq('+item.order_id + ')"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
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


function tenant_xq(id){
    $(".tenant_tk").css("display","block");
    $(".tenant_tk div input").val("");
    $.ajax({
        url: "http://api.uminfo.cn/tenant_nedb.php/getTenant1?tenant_id="+id,
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            console.log(msg);
            $("#tenant_id").val(msg.tenant.tenant_id);
            $("#tenant_num").val(msg.tenant.tenant_num);
            $("#app_id").val(msg.tenant.appid);
            $("#secret").val(msg.tenant.secret);
            $("#customer_name").val(msg.tenant.customer_name);
            $("#customer_phone").val(msg.tenant.customer_phone);
            $("#end_time").val(msg.tenant.end_time);
            $("#address").val(msg.tenant.address);
            $("#qq").val(msg.tenant.qq);
            $("#email").val(msg.tenant.email);
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}

function tenant_ensure(adminid){
    $.ajax({
        url: "http://api.uminfo.cn/adminall.php/uptenant",
        dataType: 'json',
        type: 'put',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({
            tenant_id:$("#tenant_id").val(),
            admin_id:adminid,
            appid:$("#app_id").val(),
            secret:$("#secret").val(),
            customer_name:$("#customer_name").val(),
            customer_phone:$("#customer_phone").val(),
            address:$("#address").val(),
            end_time:$("#end_time").val(),
            qq:$("#qq").val(),
            email:$("#email").val()
        }),
        success: function(msg) {
            console.log(msg);
            layer.msg(msg.desc);
            $(".tenant_tk").css("display","none");
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}