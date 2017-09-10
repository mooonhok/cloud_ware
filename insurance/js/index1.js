$(document).ready(function(){
	$.ajax({
		url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurance_rechanges?tenant_id=1",
		dataType:"json",
		contentType:"application/json;charset=utf-8",
		data:JSON.stringify({
 		}),
 		success:function(msg){
 			console.log(msg);
 			var testdata2 =msg.insurance_rechanges;

 		  $('#testtable3').yhhDataTable({
		  'paginate':{
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
	var cit = $(".cit option").value
	if(cit=""){
		alert("请选择城市")
	}else{
		$.ajax({
		    url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurance_rechanges?tenant_id=1",
		    dataType:"json",
		    contentType:"application/json;charset=utf-8",
		    data:JSON.stringify({
 		}),
		   success:function(msg){
		   var fcity = msg.insurance_rechanges.from_city_id;
		   var rcity = msg.insurance_rechanges.receive_city_id;
		   var testdata4 =msg.insurance_rechanges;
		   $('#testtable3').yhhDataTable({
		  'paginate':{
			'changeDisplayLen':true,
			'type':'updown',
			'visibleGo': true
		},
		    'tbodyRow':{
			'zebra':true,
			'write':function(d){
			if (cit==frcity&&d.status==0) {
				return '<tr><td>'+d.company+'</td><td>'+d.pay_time+'</td><td>'+d.money+'</td><td>'+d.id+'</td><td><button type="button" value="'+d.status+'" onclick="btn(this)">确认支付</button></td></tr>';
			}else if(cit==frcity&&d.status!=0){
		     return '<tr><td>'+d.company+'</td><td>'+d.pay_time+'</td><td>'+d.money+'</td><td>'+d.id+'</td><td><button type="button" disabled="disabled" value="'+d.status+'">已支付</button></td></tr>';
			}else{
				alert("没有该城市的订单")
			}
			}
		},
		    'tbodyData':{
			'enabled':true,  /*是否传入表格数据*/
			'source':testdata4 /*传入的表格数据*/
		}
	});
 		         
 		}, error:function(xhr) {
		  	  	alert(xhr.responseText);
 		  	  }
		   
		})
	}
})




	var refreshTable = function(data,page){
			if ($.isEmptyObject(data)) data = {};
			var toData = {
				'ajaxParam':{'data':data}
			}
			if (!$.isEmptyObject(page)){
				toData.paginate = {};
				toData.paginate.currentPage = page;
			}
			var $table = $page.find('.result-list');
			$table.yhhDataTable('refresh',toData);
		}
})