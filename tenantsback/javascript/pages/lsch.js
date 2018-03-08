$(function(){
 var adminid=$.session.get('adminid');
    if(adminid==null||adminid==""){
    	window.location.href=p_url+"tenantsback/login.html";
    }
    var page = $.getUrlParam('page');
    var tenant_id=null;
    $.ajax({
	 url: p_url+"tenantsback.php/gettenants?adminid="+adminid,
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
        	if(msg.result==0){
        		tenant_id=msg.tenants[0].tenant_id;
        		$(".order_id").val("");
        		for(var i=0;i<msg.tenants.length;i++){
        		$(".order_id").append('<option value="' + msg.tenants[i].tenant_id + '">' + msg.tenants[i].name + '</option>');
        		}
        	
        	}
        	 loadorders(tenant_id,page);
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
    
    $(".sousuo_z").on("click",function(){
       tenant_id=$(".order_id").val();
       
//      alert(tenant_id);
        loadorders(tenant_id,page);
    
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

function loadorders(tenant_id,page) {
    if(tenant_id==null){
       tenant_id="";
    }
    if(page==null){
        page=1;
    }
    $.ajax({
        url: p_url+"tenantsback.php/lsch?tenant-id="+tenant_id+"&page="+page+"&perpage=10",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            console.log(msg)
            $("#tb1").html("");
          if(msg.lschs!=null){
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
                            loadorders(tenant_id,obj.curr);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData=msg.lschs;
                            layui.each(thisData, function(index, item){
                            	var c="";
                            	if(item.scheduling_status==1){
                            		c="清单已制成";
                            	}else if(item.scheduling_status==2){
                            		c="司机已确";
                            	}else if(item.scheduling_status==3){
                            		c="装车完成";
                            	}else if(item.scheduling_status==4){
                            		c="清单在途";
                            	}else if(item.scheduling_status==5){
                            		c="到达";
                            	}else if(item.scheduling_status==6){
                            		c="退单";
                            	}else if(item.scheduling_status==7){
                            		c="已退单";
                            	}else if(item.scheduling_status==8){
                            		c="出险";
                            	}else if(item.scheduling_status==9){
                            		c="已处理";
                            	}
                                arr.push('<tr><td>'+item.scheduling_id+'</td><td>'
                                +item.receivername+'</td><td>'
                                +item.receivercity+'</td><td>'
                                +item.platenumber+'</td><td>'
                                +item.driver_name+'</td><td>'
                                +item.driver_phone+'</td><td>'
                                +c+'</td></tr>');
                            });
                            return arr.join('');
                        }();
                    }
                });
            });
            }else{
             $("#tb1").html("租户下没有订单");	
            }
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}



