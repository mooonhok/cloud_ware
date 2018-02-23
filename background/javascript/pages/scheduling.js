$(function(){
    var adminid=$.session.get('adminid');
    var company=$.session.get('company');
    var page = $.getUrlParam('page');
    var scheduling_id='';
    loadschedulings(scheduling_id,page,company) ;
    $('#order_close').on("click",function () {
        $(".tenant_tk").css("display","none");
    })

    $(".sousuo_z").on("click",function(){
        alert(1)
        var scheduling_id=$(".scheduling_id").val();
        loadschedulings(scheduling_id,page,company) ;
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

function loadschedulings(scheduling_id,page,company) {
    if(scheduling_id==null){
        scheduling_id="";
    }
    if(page==null){
        page=1;
    }
    if(company==null){
        company='';
    }
    $.ajax({
        url: p_url+"scheduling.php/schedulings_scheduling_id?scheduling_id="+scheduling_id+"&page="+page+"&per_page=10&company="+company,
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
                            loadschedulings(scheduling_id,obj.curr,company);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData = msg.schedulings;
                            layui.each(thisData, function(index, item){
                                var info='-';
                                if(item.scheduling_status==1){
                                    info='等待装车';
                                }else if(item.scheduling_status==2){
                                    info='装车完成';
                                }else if(item.scheduling_status==3){
                                    info='司机确认';
                                }else if(item.scheduling_status==4){
                                    info='在途';
                                }else if(item.scheduling_status==5){
                                    info='到达';
                                }else if(item.scheduling_status==6){
                                    info='取消';
                                }
                                arr.push( '<tr><td>'+item.scheduling_id+'</td><td>'+item.tenant.company+'</td><td>'+item.tenant_suoshu.name+'</td><td>'+item.send_city.name+'</td><td>'+item.receive_city.name+'</td><td>'+item.receiver.customer_name+'</td><td>'+item.scheduling_datetime+'</td><td>'+info+'</td><td class="look"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
                            });
                            return arr.join('');
                        }();
                        $(".look").on("click",function(){
                            var sche_id=$(this).parent().children().eq(0).text();
                            scheduling_xq(sche_id);
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


function scheduling_xq(id){
    $(".tenant_tk").css("display","block");
    $(".tenant_tk div input").val("");
    $.ajax({
        url: p_url+"scheduling.php/scheduling_orders_scheduling_id?scheduling_id="+id+"",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            console.log(msg);
            $("#tenant_id").val(msg.lorry.lorry_id);
            $("#tenant_num").val(msg.lorry.driver_name);
            $("#app_id").val(msg.lorry.plate_number);
            for(var i=0;i<msg.order_goods.length;i++){
                var a='<div></div>'
            }
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}
