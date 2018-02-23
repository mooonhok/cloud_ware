$(function(){
    var adminid=$.session.get('adminid');
    var company=$.session.get('company');
    var company_name=$.session.get('company_name');
    $('#shmz_name').html(company_name);
    var page = $.getUrlParam('page');
    var agreement_id='';
    loadagreements(agreement_id,page,company) ;
    $('#order_close').on("click",function () {
        $(".tenant_tk").css("display","none");
    })

    $(".sousuo_z").on("click",function(){
        alert(1)
        var agreement_id=$(".agreement_id").val();
        loadagreements(agreement_id,page,company) ;
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

function loadagreements(agreement_id,page,company) {
    if(agreement_id==null){
        agreement_id="";
    }
    if(page==null){
        page=1;
    }
    if(company==null){
        company='';
    }
    $.ajax({
        url: p_url+"agreement.php/agreements_suoyou?agreement_id="+agreement_id+"&page="+page+"&per_page=10&company="+company,
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
                            loadagreements(agreement_id,obj.curr,company);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData = msg.agreements;
                            layui.each(thisData, function(index, item){
                                // var info='-';
                                // if(item.scheduling_status==1){
                                //     info='等待装车';
                                // }else if(item.scheduling_status==2){
                                //     info='装车完成';
                                // }else if(item.scheduling_status==3){
                                //     info='司机确认';
                                // }else if(item.scheduling_status==4){
                                //     info='在途';
                                // }else if(item.scheduling_status==5){
                                //     info='到达';
                                // }else if(item.scheduling_status==6){
                                //     info='取消';
                                // }
                                arr.push( '<tr><td>'+item.agreement_id+'</td><td>'+item.tenant.company+'</td><td>'+item.lorry.plate_number+'</td><td>'+item.lorry.driver_name+'</td><td>'+item.lorry.driver_phone+'</td><td>'+item.rcity+'</td><td>'+item.agreement_time+'</td><td>'+item.agreement_require+'</td></tr>');
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