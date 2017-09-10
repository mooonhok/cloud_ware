(function($) {
			$.getUrlParam = function(name) {
				var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
				var r = window.location.search.substr(1).match(reg);
				if(r != null) return unescape(r[2]);
				return null;
			}
		})(jQuery);

$(document).ready(function(){
	var city_id = $.getUrlParam("city_id");
	var company = $.getUrlParam("company");
	alert(city_id);
	alert(company);
	if(city_id==null){
		city_id='';
	}
	if(company==null){
		company='';
	}
	$.ajax({
		url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurance_rechanges?city_id="+city_id+"&company="+company+"",
		dataType:"json",
		contentType:"application/json;charset=utf-8",
		data:JSON.stringify({
 		}),
 		success:function(msg){
 			console.log(msg);
 			var testdata2 =msg.insurance_rechanges;

 		  $('#testtable3').yhhDataTable({
		  'paginate':{
		  	'enabled':true,
			'changeDisplayLen':true,
			'type':'updown',
			'visibleGo': true
		},
		    'tbodyRow':{
			'zebra':true,
			'write':function(d){
			if (d.status==0) {
				return '<tr><td>'+d.company+'</td><td>'+d.pay_time+'</td><td>'+d.money+'</td><td>'+d.id+'</td><td><button type="button" value="'+d.status+'" onclick="btn(this)">确认支付</button></td></tr>';
			}else{
		     return '<tr><td>'+d.company+'</td><td>'+d.pay_time+'</td><td>'+d.money+'</td><td>'+d.id+'</td><td><button type="button" disabled="disabled" value="'+d.status+'">已支付</button></td></tr>';
			}
			}
		},
		    'tbodyData':{
			'enabled':true,  /*是否传入表格数据*/
			'source':testdata2 /*传入的表格数据*/
		}
	});
 		         
 		}, error:function(xhr) {
		  	  	alert(xhr.responseText);
 		  	  }

	})
$(".hunt").on("click",function(){
	var options=$("#city1 option:selected");
	var cit = options.val();
    console.log(options.val());
	if(cit==0){
		alert("请选择城市")
	}else{
		window.location.href="http://mooonhok-cloudware.daoapp.io/insurance/safe.html?city_id="+cit;
		
		
	}

	$(".select_2_2").on("click",function(){
	var city = $(".select_2_1 input").val();
	console.log(city);
	if(cit==0){
		alert("请选择搜索公司地区");
	}else if(cit!=0&&city==0){
		alert("请填写搜索公司名称");
	}else if(cit!=0&&city!=0){
		window.location.href="http://mooonhok-cloudware.daoapp.io/insurance/safe.html?city_id="+cit+"&company="+city;
	}
})

  })




})