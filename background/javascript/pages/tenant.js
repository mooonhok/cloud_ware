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
        url: p_url+"adminall.php/tenants?admin_id="+adminid+"&page="+page+"&per_page=10",
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
                                    arr.push( '<tr><td onclick="change_tenant('+item.tenant_id + ')" style="cursor:pointer;">'+item.company+'</td><td>'+item.from_city+'</td><td>'+item.tenant_num+'</td><td>'+item.customer.customer_name+'</td><td>'+item.sales_name+'</td><td>'+item.begin_time+'</td><td>'+item.note_remain+'</td><td onclick="tenant_xq('+item.tenant_id + ')"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
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
    // $(".tenant_tk").css("display","block");
    // $(".tenant_tk div input").val("");
    // $.ajax({
    //     url: p_url+"tenant_nedb.php/getTenant1?tenant_id="+id,
    //     dataType: 'json',
    //     type: 'get',
    //     ContentType: "application/json;charset=utf-8",
    //     data: JSON.stringify({}),
    //     success: function(msg) {
    //         console.log(msg);
    //         $("#tenant_id").val(msg.tenant.tenant_id);
    //         $("#tenant_num").val(msg.tenant.tenant_num);
    //         $("#app_id").val(msg.tenant.appid);
    //         $("#secret").val(msg.tenant.secret);
    //         $("#customer_name").val(msg.tenant.customer_name);
    //         $("#customer_phone").val(msg.tenant.customer_phone);
    //         $("#note_remain").val(msg.tenant.note_remain);
    //         $("#address").val(msg.tenant.address);
    //         $("#qq").val(msg.tenant.qq);
    //         $("#email").val(msg.tenant.email);
    //     },
    //     error: function(xhr) {
    //         alert("获取后台失败！");
    //     }
    // });
    var adminid=$.session.get('adminid');
    var index=layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['800px', '550px'], //宽高
        content: '<div class="tenant_tk">' +
        '<h1 style="text-align:center;">修改租户公司信息</h1>' +
        '<div>' +
        '<div>租户编号</div>' +
        '<div>运单号开头</div>' +
        '<div>微信appid</div>' +
        '<div>微信secret</div>' +
        '<div>租户负责人</div>' +
        '<div>租户电话</div>' +
        '<div>剩余短信条数</div>' +
        '<div>租户地址</div>' +
        '</div>' +
        '<div>' +
        '<input type="text" id="tenant_id" disabled="disabled"/>' +
        '<input type="text" id="tenant_num" disabled="disabled"/>' +
        '<input type="text" id="app_id"/>' +
        '<input type="text" id="secret"/>' +
        '<input type="text" id="customer_name"/>' +
        '<input type="text" id="customer_phone"/>' +
        '<input type="text" id="note_remain"/>' +
        '<input type="text" id="address"/>' +
        '<input type="text" id="qq" style="display: none;"/>' +
        '<input type="text" id="email" style="display: none;"/>' +
        '</div>' +
        '<button id="tenant_sure">确定</button><button id="tenant_cancle">取消</button>' +
        '</div>'
    });

    $.ajax({
        url: p_url+"tenant_nedb.php/getTenant1?tenant_id="+id,
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

    $("#tenant_cancle").on("click",function(){
        layer.close(index);
    });

    $("#tenant_sure").on("click",function(){
        $.ajax({
            url: p_url+"adminall.php/uptenant",
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
                layer.close(index);
            },
            error: function(xhr) {
                alert("获取后台失败！");
            }
        });
    });
}

// function tenant_ensure(adminid){
//     $.ajax({
//         url: p_url+"adminall.php/uptenant",
//         dataType: 'json',
//         type: 'put',
//         ContentType: "application/json;charset=utf-8",
//         data: JSON.stringify({
//             tenant_id:$("#tenant_id").val(),
//             admin_id:adminid,
//             appid:$("#app_id").val(),
//             secret:$("#secret").val(),
//             customer_name:$("#customer_name").val(),
//             customer_phone:$("#customer_phone").val(),
//             address:$("#address").val(),
//             note_remain:$("#note_remain").val(),
//             qq:$("#qq").val(),
//             email:$("#email").val()
//         }),
//         success: function(msg) {
//             console.log(msg);
//            layer.msg(msg.desc);
//             $(".tenant_tk").css("display","none");
//         },
//         error: function(xhr) {
//             alert("获取后台失败！");
//         }
//     });
// }

function change_tenant(tenant_id){
    $.ajax({
        url: p_url+"tenant.php/company_name?company="+tenant_id,
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            if(msg.result==0){
                $.session.remove('company');
                $.session.remove('company_name');
                $.session.set('company',tenant_id);
                $.session.set('company_name',msg.company_name);
                window.location.reload();
            }
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });

}