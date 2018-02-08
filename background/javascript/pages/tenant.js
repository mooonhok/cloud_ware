$(function(){
var adminid=$.session.get('adminid');
    var page = $.getUrlParam('page');
    loadtenants(adminid,page);
    $("#tenant_sure").on("click",function(){
        tenant_ensure(adminid);
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

function loadtenants(adminid,page) {
    if(page==null){
        page=1;
    }
    $.ajax({
        url: "http://api.uminfo.cn/adminall.php/tenants?admin_id="+adminid+"&page="+page+"&per_page=10",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
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
                            loadtenants(adminid,obj.curr);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData = msg.tenants;
                            layui.each(thisData, function(index, item){
                                    arr.push( '<tr><td onclick="change_tenant('+item.tenant_id + ','+item.company+')" style="cursor:pointer;">'+item.company+'</td><td>'+item.from_city+'</td><td>'+item.receive_city+'</td><td>'+item.tenant_num+'</td><td>'+item.customer.customer_name+'</td><td>'+item.sales_name+'</td><td>'+item.begin_time+'</td><td>'+item.note_remain+'</td><td onclick="tenant_xq('+item.tenant_id + ')"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
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
            $("#note_remain").val(msg.tenant.note_remain);
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
            note_remain:$("#note_remain").val(),
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

function change_tenant(tenant_id,tenant_company){
    $.session.remove('company');
    $.session.remove('company_name');
    $.session.set('company',tenant_id);
    $.session.set('company_name',tenant_company);
    window.reload();
}