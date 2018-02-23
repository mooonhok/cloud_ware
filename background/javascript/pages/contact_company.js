$(function(){
    var adminid=$.session.get('adminid');
    var page = $.getUrlParam('page');
    var telephone='';
    load_contact_companys(telephone,page) ;
    $('#order_close').on("click",function () {
        $(".tenant_tk").css("display","none");
    })

    $(".sousuo_z").on("click",function(){
        alert(1)
        var telephone=$(".telephone").val();
        load_contact_companys(telephone,page) ;
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

function load_contact_companys(telephone,page) {
    if(telephone==null){
        telephone="";
    }
    if(page==null){
        page=1;
    }
    $.ajax({
        url: p_url+"adminall.php/contact_company?telephone="+telephone+"&page="+page+"&per_page=10",
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
                            load_contact_companys(telephone,obj.curr);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData = msg.contact_companys;
                            layui.each(thisData, function(index, item){
                                arr.push( '<tr><td style="display:none">+item.id+</td><td>'+item.company+'</td><td>'+item.telephone+'</td><td>'+item.contact_name+'</td><td>'+item.address+'</td></tr>');
                            });
                            return arr.join('');
                        }();
                        // $(".look").on("click",function(){
                        //     var app_lorry_id=$(this).parent().children().eq(0).text();
                        //     lorry_xq(app_lorry_id);
                        // })
                    }
                });
            });
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}


