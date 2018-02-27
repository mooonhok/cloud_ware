$(function(){
    var adminid=$.session.get('adminid');
    var page = $.getUrlParam('page');
    var plate_number='';
    loadlorrys(plate_number,page) ;
    $('#order_close').on("click",function () {
        $(".tenant_tk").css("display","none");
    })

    $(".sousuo_z").on("click",function(){

        var plate_number=$(".plate_number").val();
        alert(plate_number)
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
        url: p_url+"lorry.php/lorrys_plate_number?plate_number="+plate_number+"&page="+page+"&per_page=10",
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
                                arr.push( '<tr><td style="display:none">'+item.app_lorry_id+'</td><td>'+item.phone+'</td><td>'+item.name+'</td><td>'+item.id_number+'</td><td>'+item.plate_number+'</td><td>'+info+'</td><td>'+item.lorry_length_name+'</td><td>'+item.deadweight+'</td><td>'+item.lorry_type_name+'</td><td class="look"><span style="color:blue; cursor:pointer;">查看</span></td></tr>');
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

    var index=layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['800px', '600px'], //宽高
        content: '<div class="tenant_tk">' +
        '<h1 style="text-align:center;">修改车辆信息</h1>' +
        '<div>' +
        '<div>手机号</div>' +
        '<div>司机名字</div>' +
        '<div>身份证号码</div>' +
        '<div>车牌号</div>' +
        '<div>车长</div>' +
        '<div>车的类型</div>' +
        '<div>载重</div>' +
        '</div>' +
        '<div>' +
        '<input type="text" id="lorry_id" style="display:none;"/>' +
        '<input type="text" id="phone"/>' +
        '<input type="text" id="name"/>' +
        '<input type="text" id="id_card"/>' +
        '<input type="text" id="plate_number"/>' +
        '<select id="lorry_length"></select>' +
        '<select id="lorry_type"></select>' +
        '<input type="text" id="lorry_weight"/>' +
        '</div>' +
        '<h3>身份证正面</h3>' +
        '<label><img src="" id="id_z" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="id_z"/></label>'+
        '<h3>身份证反面</h3>' +
        '<label><img src="" id="id_f" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="id_f"/></label>'+
        '<h3>驾驶证正面</h3>' +
        '<label><img src="" id="j_z" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="j_z"/></label>'+
        '<h3>驾驶证反面</h3>' +
        '<label><img src="" id="j_f" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="j_f"/></label>'+
        '<h3>行驶证正面</h3>' +
        '<label><img src="" id="x_z" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="x_z"</label>'+
        '<h3>行驶证反面</h3>' +
        '<label><img src="" id="x_f" class="image1" style="width:80%;margin-left:10%;"/><input type="file" style="display:none;" class="x_f"/></label>'+
        '<button id="order_sure">确定</button><button id="order_cancle">取消</button>' +
        '</div>'
    });



    $("#order_cancle").on("click",function(){
        layer.close(index);
    });
    $.ajax({
        url: p_url+"lorry.php/getAppLorry?app_lorry_id="+id+"",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            // console.log(msg);
            $("#lorry_id").val(msg.lorrys.app_lorry_id);
            $("#phone").val(msg.lorrys.phone);
            $("#name").val(msg.lorrys.name);
            $("#id_card").val(msg.lorrys.id_number);
            $("#plate_number").val(msg.lorrys.plate_number);
            // $("#lorry_length").val(msg.lorrys.lorry_length_name);
            // $("#lorry_type").val(msg.lorrys.lorry_type_name);
            $("#lorry_weight").val(msg.lorrys.lorry_load_name);
            $("#id_z").attr("src",msg.lorrys.identity_card_z);
            $("#id_f").attr("src",msg.lorrys.identity_card_f);
            $("#j_z").attr("src",msg.lorrys.driver_license_fp);
            $("#j_f").attr("src",msg.lorrys.driver_license_tp);
            $("#x_z").attr("src",msg.lorrys.driving_license_fp);
            $("#x_f").attr("src",msg.lorrys.driving_license_tp);
            $.ajax({
                url: p_url+"app.php/lorry_type",
                dataType: 'json',
                type: 'get',
                ContentType: "application/json;charset=utf-8",
                data: JSON.stringify({}),
                success: function(ret) {
                    // console.log(msg);
                    if(ret.result==0){
                        for(var i=0;i<ret.lorry_type.length;i++){
                            $('#lorry_type').append('<option value="'+ret.lorry_type[i].lorry_type_id+'" id="ve_size'+ret.lorry_type[i].lorry_type_id+'">'+ret.lorry_type[i].lorry_type_name+'</option>');
                            $('#ve_size'+msg.lorrys.type).attr('selected','selected');
                        }
                    }
                },
                error: function(xhr) {
                    alert("获取后台失败！");
                }
            });

            $.ajax({
                url: p_url+"app.php/lorry_long",
                dataType: 'json',
                type: 'get',
                ContentType: "application/json;charset=utf-8",
                data: JSON.stringify({}),
                success: function(ret) {
                    if(ret.result==0){
                        for(var i=0;i<ret.vehiche_long.length;i++){
                            $('#lorry_length').append('<option value="'+ret.vehiche_long[i].lorry_length_id+'" id="ve_long'+ret.vehiche_long[i].lorry_length_id+'">'+ret.vehiche_long[i].lorry_length+'</option>');
                            $('#ve_long'+msg.lorrys.length).attr('selected','selected');
                        }
                    }
                },
                error: function(xhr) {
                    alert("获取后台失败！");
                }
            });
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });

    $(".id_z").on("change",function(){
        var file1=document.getElementsByClassName('id_z')[0].files[0];
        url=window.URL.createObjectURL(file1);
            document.getElementById('id_z').setAttribute("src",url);
    });

    $(".id_f").on("change",function(){
        var file1=document.getElementsByClassName('id_f')[0].files[0];
        url=window.URL.createObjectURL(file1);
        document.getElementById('id_f').setAttribute("src",url);
    });

    $(".j_z").on("change",function(){
        var file1=document.getElementsByClassName('j_z')[0].files[0];
        url=window.URL.createObjectURL(file1);
        document.getElementById('j_z').setAttribute("src",url);
    });

    $(".j_f").on("change",function(){
        var file1=document.getElementsByClassName('j_f')[0].files[0];
        url=window.URL.createObjectURL(file1);
        document.getElementById('j_f').setAttribute("src",url);
    });

    $(".x_z").on("change",function(){
        var file1=document.getElementsByClassName('x_z')[0].files[0];
        url=window.URL.createObjectURL(file1);
        document.getElementById('x_z').setAttribute("src",url);
    });

    $(".x_f").on("change",function(){
        var file1=document.getElementsByClassName('x_f')[0].files[0];
        url=window.URL.createObjectURL(file1);
        document.getElementById('x_f').setAttribute("src",url);
    });

    $("#order_sure").on("click",function(){
        var adminid=$.session.get('adminid');
        var lorry_id=$("#lorry_id").val();
        var phone=$("#phone").val();
        var name=$("#name").val();
        var id_number=$("#id_card").val();
        var plate_number=$("#plate_number").val();
        var lorry_length_name=$("#lorry_length").val();
        var lorry_type_name=$("#lorry_type").val();
        var lorry_load_name=$("#lorry_weight").val();
        var id_z=document.getElementsByClassName('id_z')[0].files[0];
        var id_f=document.getElementsByClassName('id_f')[0].files[0];
        var j_z=document.getElementsByClassName('j_z')[0].files[0];
        var j_x=document.getElementsByClassName('j_f')[0].files[0];
        var x_z=document.getElementsByClassName('x_z')[0].files[0];
        var x_f=document.getElementsByClassName('x_f')[0].files[0];
            var formdata = new FormData();
           formdata.append('admin_id', adminid);
           formdata.append('app_lorry_id', lorry_id);
           formdata.append('phone', phone);
           formdata.append('name', name);
           formdata.append('id_number', id_number);
           formdata.append('plate_number', plate_number);
           formdata.append('lorry_length_name', lorry_length_name);
           formdata.append('lorry_type_name', lorry_type_name);
           formdata.append('lorry_load_name', lorry_load_name);
           formdata.append('identity_card_z', id_z);
           formdata.append('identity_card_f', id_f);
           formdata.append('driver_license_fp', j_z);
           formdata.append('driver_license_tp', j_x);
           formdata.append('driving_license_fp', x_z);
           formdata.append('driving_license_tp', x_f);
            var xhr = new XMLHttpRequest();
            xhr.open("POST" ,p_url+"lorry.php/app_lorry", true);
            xhr.send(formdata);
            xhr.onload = function(e) {
                if (this.status == 200) {
                    layer.msg('上传成功');
                };
            };
    });
}
