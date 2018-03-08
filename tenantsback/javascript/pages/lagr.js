$(function(){
  var adminid=$.session.get('adminid');
//  var adminid=3;
    if(adminid==null||adminid==""){
    	window.location.href=p_url+"tenantsback/login.html";
    }
    var page = $.getUrlParam('page');
    var tenant_id=null;
    tenant_id=$.getUrlParam("tenant_id");
    $.ajax({
	 url: p_url+"tenantsback.php/gettenants?adminid="+adminid,
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
        	if(msg.result==0){
        		if(tenant_id==null||tenant_id==""){
        		tenant_id=msg.tenants[0].tenant_id;
        		}
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
        url: p_url+"tenantsback.php/lagrs?tenant-id="+tenant_id+"&page="+page+"&perpage=10",
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
           
            $("#tb1").html("");
          if(msg.argees!=null){
          	$("#count1").html(msg.count1);
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
                                ,thisData=msg.argees;
                            layui.each(thisData, function(index, item){
                            	var a="";
                            	  if(item.agreement_status==0){
                            	  	a="待签字";
                            	  }else if(item.agreement_status==1){
                            	  	a="已达成";
                            	  }else if(item.agreement_status==2){
                            	  	a="作废";
                            	  }else if(item.agreement_status==3){
                            	  	a="出险";
                            	  }
                                arr.push('<tr><td>'+item.agreement_id+'</td><td>'
                                +item.platenumber+'</td><td>'
                                +item.driver_name+'</td><td>'
                                +item.driver_phone+'</td><td>'
                                +a+'</td><td>'
                                +item.freight+'</td><td>'
                                +item.agreement_time+'</td><td><a href="'+p_url+'tenantsback/agreement.html?agreement_id='
                                +item.agreement_id+'&tenant_id='+tenant_id+'" style="color:blue;">查看</a></td></tr>');
                            });
                            return arr.join('');
                        }();
                    }
                });
            });
            }else{
             $("#tb1").html("租户下没有合同");	
            }
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
}




