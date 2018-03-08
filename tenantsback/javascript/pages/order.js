$(function(){
	var adminid=$.session.get('adminid');
     if(adminid==null||adminid==""){
    	window.location.href=p_url+"tenantsback/login.html";
    }else{
    var page = $.getUrlParam('page');
    var tenant_id=null;
    $(".payway").append('<option value="' +0+ '">现付</option>');
      $(".payway").append('<option value="' +1+ '">到付</option>');
      $(".payway").append('<option value="' +2+ '">月结</option>');
      $(".payway").append('<option value="' +3+ '">回单</option>');
       var payway=$(".payway").val();
    $.ajax({
	 url: p_url+"tenantsback.php/gettenants?adminid="+adminid,
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
        	if(msg.result==0){
        		$(".order_id").val("");
        		for(var i=0;i<msg.tenants.length;i++){
        		$(".order_id").append('<option value="' + msg.tenants[i].tenant_id + '">' + msg.tenants[i].name + '</option>');
        		}
        		tenant_id=msg.tenants[0].tenant_id;
        		$.ajax({
		            url: p_url+"tenantsback.php/ordertongji?tenant-id="+tenant_id,
	               dataType: 'json',
	                type: 'get',
	               ContentType: "application/json;charset=utf-8",
	               data: JSON.stringify({}),
	              success: function(msg) {
	            	if(msg.result==0){
	         		$("#count1").html(msg.countorder);
	        		$("#count2").html(msg.countorder1);
	        		$("#count3").html(msg.countorder2);
	        	   }
	              },
	            error: function(xhr) {
	              alert("获取后台失败！");
	          }
	          });
        	}
        	loadorders(tenant_id,page);
        },
        error: function(xhr) {
            alert("获取后台失败！");
        }
    });
    
    $(".sousuo_z").on("click",function(){
        tenant_id=$(".order_id").val();
        var payway=$(".payway").val();
        loadorders(tenant_id,page,payway);
        $("#count1").html("");
         $("#count2").html("");
         $("#count3").html("");
	   $.ajax({
		 url: p_url+"tenantsback.php/ordertongji?tenant-id="+tenant_id,
	        dataType: 'json',
	        type: 'get',
	        ContentType: "application/json;charset=utf-8",
	        data: JSON.stringify({}),
	        success: function(msg) {
	        	if(msg.result==0){
	        		$("#count1").html(msg.countorder);
	        		$("#count2").html(msg.countorder1);
	        		$("#count3").html(msg.countorder2);
	        	}
	        },
	        error: function(xhr) {
	            alert("获取后台失败！");
	        }
	    });
    })
    }
});


(function($) {
    $.getUrlParam = function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r != null) return decodeURI(r[2]);
        return null;
    }
})(jQuery);

function loadorders(tenant_id,page,payway) {
    if(tenant_id==null){
       tenant_id="";
    }
    if(page==null){
        page=1;
    }
    if(payway==null){
    	payway="";
    }
    $.ajax({
        url: p_url+"tenantsback.php/getGoodsOrders?tenant-id="+tenant_id+"&page="+page+"&perpage=10&payway="+payway,
        dataType: 'json',
        type: 'get',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({}),
        success: function(msg) {
            console.log(msg)
            $("#tb1").html("");
            if(msg.goods_orders.length!=0){
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
                            loadorders(tenant_id,obj.curr,payway);
                        }
                        //模拟渲染
                        document.getElementById('tb1').innerHTML = function(){
                            var arr = []
                                ,thisData=msg.goods_orders;
                            layui.each(thisData, function(index, item){
                            	var a="";
                            	var b="";
                            	var c="";
                            	var d="";
                            	var e="";
                            	if(item.pay_method==0){
                            		a="现付";
                            	}else if(item.pay_method==1){
                            		a="到付";
                            		b=item.order_cost;
                            	}else if(item.pay_method==2){
                            		a="月结";
                            	}else{
                            		a="回单";
                            	}
                            	if(item.pay_status==1){
                            		c="未付";
                            	}else{
                            		c="已付";
                            	}
                            	if(item.transfer_cost==null){
                            		e=0;
                            	}else{
                            		e=item.transfer_cost;
                            	}
                            	if(item.order_status==-1){
                            		d="拒绝受理";
                            	}else if(item.order_status==0){
                            		d="下单";
                            	}else if(item.order_status==1){
                            		d="入库";
                            	}else if(item.order_status==2){
                            		d="出库";
                            	}else if(item.order_status==3){
                            		d="出发";
                            	}else if(item.order_status==4){
                            		d="到达";
                            	}else if(item.order_status==5){
                            		d="异常";
                            	}else if(item.order_status==6){
                            		d="作废";
                            	}else if(item.order_status==7){
                            		d="签收";
                            	}
                                arr.push('<tr><td>'+item.order_id+'</td><td>'
                                +item.sender.customer_name+'</td><td>'
                                +item.sender.customer_phone+'</td><td>'
                                +a+'</td><td>'
                                +item.order_cost+'</td><td>'
                                +e+'</td><td>'
                                +b+'</td><td>'
                                +d+'</td><td>'
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



