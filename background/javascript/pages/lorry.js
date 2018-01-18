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
                                arr.push( '<tr><td style="display:none">+item.app_lorry_id+</td><td>'+item.phone+'</td><td>'+item.name+'</td><td>'+item.id_number+'</td><td>'+item.plate_number+'</td><td>'+info+'</td><td>'+item.lorry_length_name+'</td><td>'+item.deadweight+'</td><td>'+item.lorry_type_name+'</td><td class="look"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
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
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}


function lorry_xq(id){
    // $(".tenant_tk").css("display","block");
    // $(".tenant_tk div input").val("");
    // $.ajax({
    //     url: "http://api.uminfo.cn/lorry.php/lorrys_lorry_id?app_lorry_id="+id+"",
    //     dataType: 'json',
    //     type: 'get',
    //     ContentType: "application/json;charset=utf-8",
    //     data: JSON.stringify({}),
    //     success: function(msg) {
    //         console.log(msg);
    //         $("#tenant_id").val(msg.lorry.lorry_id);
    //         $("#tenant_num").val(msg.lorry.driver_name);
    //         $("#app_id").val(msg.lorry.plate_number);
    //         for(var i=0;i<msg.order_goods.length;i++){
    //             var a='<div></div>'
    //         }
    //     },
    //     error: function(xhr) {
    //         alert("获取后台失败！");
    //     }
    // });
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['420px', '400px'], //宽高
        content: '            <div class="tenant_tk">' +
        '                <h1 style="text-align:center;">详情</h1>' +
        '                <div>' +
        '                    <div>货物名称</div>' +
        '                    <div>货物重量(吨)</div>' +
        '                    <div>货物体积(立方)</div>' +
        '                    <div>货物数量</div>' +
        '                    <div>货物价值(万元)</div>' +
        '                    <div>货物包装</div>' +
        '                </div>\n' +
        '                <div>\n' +
        '                    <input type="text" id="tenant_id" disabled="disabled"/>' +
        '                    <input type="text" id="tenant_num" disabled="disabled"/>' +
        '                    <input type="text" id="app_id" disabled="disabled"/>' +
        '                    <input type="text" id="secret" disabled="disabled"/>' +
        '                    <input type="text" id="customer_name" disabled="disabled"/>' +
        '                    <input type="text" id="customer_phone" disabled="disabled"/>' +
        '                </div>' +
        '                <button id="order_close">关闭</button>' +
        '            </div>'
    });
}
