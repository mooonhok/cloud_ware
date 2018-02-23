(function($) {
	$.getUrlParam = function(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if(r != null) return decodeURI(r[2]);
		return null;
	}
})(jQuery);

$(function() {
var adminid=$.session.get('adminid');
    
    if(adminid==null||adminid==""){
    	window.location.href=p_url+"tenantsback/login.html";
    }
	var agr_id = $.getUrlParam("agreement_id");
	var tenant_id=$.getUrlParam("tenant_id");
	loader(agr_id);
	$(".sousuo_z").on("click",function(){
	window.location.href=p_url+"tenantsback/lagreement.html?tenant_id="+tenant_id;
    })
	$(".print").on("click",function(){
		$("div .printa").printArea();  
	});
});

function loader(agr_id) {
	$.ajax({
		url: p_url+"tenantsback.php/agredet?agreementid="+agr_id,
		dataType: 'json',
		type: 'get',
		ContentType: "application/json;charset=utf-8",
		data: JSON.stringify({}),
		success: function(msg) {
			if(msg.result == 0) {
              $(".do_fpcompany").html(msg.agree.company);
              $(".do_spcompany").html(msg.agree.driver_name);
              $(".do_fpname").html(msg.agree.sname);
              $(".do_fpphone").html(msg.agree.stelephone);
              $(".do_fpaddress").html(msg.agree.saddress);
              $(".do_spname").html(msg.agree.driver_name);
              $(".do_spphone").html(msg.agree.driver_phone);
              $(".do_spnumber").html(msg.agree.plate_number);
              $(".do_send_city").html(msg.agree.scity);
              $(".do_receive_city").html(msg.agree.cityname);
              $(".do_corder").html(msg.agree.ordercount);
              $(".do_ccount").html(msg.agree.ordercountgood);
              $(".do_ccapacity").html(msg.agree.ordersize);
              $(".do_cweight").html(msg.agree.orderweight);
              $(".do_cvalue").html(msg.agree.ordervalue);
              $("#do_freight").html(msg.agree.freight);
              $("#do_endtime").html(msg.agree.deadline);
              $("#do_method").html(msg.agree.pay_method);
              $("#do_comment").html(msg.agree.agreement_require);
              $(".do_schedules").append(msg.agree.schedules)
              $(".contract_time").html(msg.agree.agreement_time);
            
              $("#surepic").attr("src",msg.agree.sign_img);
			} else {
				alert(msg.desc);
			}
		},
		error: function(xhr) {
			alert("获取后台失败！");
		}
	});
}


	

