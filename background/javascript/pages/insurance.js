$(function(){
var adminid=$.session.get('adminid');
    var page = $.getUrlParam('page');
    var tenant_id=$.getUrlParam("tenant_id");
    loadinsurances(tenant_id,page);
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


function loadinsurances(tenant_id,page) {
    if(page==null){
        page=1;
    }
    $.ajax({
        url: p_url+"adminall.php/lastinsurance?tenant_id="+tenant_id+"&page="+page+"&per_page=10",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            $("#tb4").html("");
            //调用分页
            layui.use(['laypage', 'layer'], function(){
                var laypage = layui.laypage;
                laypage.render({
                    elem: 'demo5'
                    ,count:msg.count
                    ,curr:page
                    ,limit: 10
                    ,jump: function(obj,first){
                        if(!first){
                            loadtenants(adminid,obj.curr);
                        }
                        //模拟渲染
                        document.getElementById('tb4').innerHTML = function(){
                            var arr = []
                                ,thisData = msg.rechanges;
                            layui.each(thisData, function(index, item){
                            		 arr.push( '<tr><td>'+item.tenant_id+'</td><td>'
                                    +item.company+'</td><td>'+item.insurance_start_time+'</td><td>'+item.duration+'</td><td>'
                                    +item.insurance_amount+'</td><td>'+item.insurance_price+'</td><td>'+
                                    item.insurance_id+'</td><td>'+item.from_city+'</td><td>'+
                                    item.receive_city+'</td><td>'+item.plate_number+'</td><td>'+
                                    item.driver_name+'</td><td>'+item.customer_phone+'</td><td>'+item.goods_name+'</td></tr>');
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

