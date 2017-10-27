$(function(){
var adminid=$.session.get('adminid');
    var page = $.getUrlParam('page');
    loadtenants(adminid,page);
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
                            loadtenants(adminid,obj.curr);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData = msg.insurance_rechanges;
                            layui.each(thisData, function(index, item){
                                    arr.push( '<tr><td>'+item.company+'</td><td>'+item.from_city+'</td><td>'+item.receive_city+'</td><td>'+item.tenant_num+'</td><td>'+item.customer.customer_name+'</td><td>'+item.sales_name+'</td><td>'+item.begin_time+'</td><td>'+item.end_date+'</td><td onclick="ins('+item.tenant_id + ')"><span style="color:blue; cursor:pointer;">扣费详情</span></td></tr>');
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