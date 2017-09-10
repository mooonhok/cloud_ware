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
		
		$.ajax({
		    url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurance_rechanges?city_id="+cit,
		    dataType:"json",
		    contentType:"application/json;charset=utf-8",
		    data:JSON.stringify({
 		}),
		   success:function(msg){
		   
		   var testdata4 =msg.insurance_rechanges;
		   console.log(testdata4);
		   $('#testtable3').yhhDataTable({
		//   'paginate':{
		// 	'changeDisplayLen':true,
		// 	'type':'updown',
		// 	'visibleGo': true
		// },
		    'tbodyRow':{
			'zebra':true,
			'write':function(d){
			if (d.status==0) {
				return '<tr><td>'+d.company+'</td><td>'+d.pay_time+'</td><td>'+d.money+'</td><td>'+d.id+'</td><td><button type="button" value="'+d.status+'" onclick="btn(this)">确认支付</button></td></tr>';
			}else if(d.status!=0){
		     return '<tr><td>'+d.company+'</td><td>'+d.pay_time+'</td><td>'+d.money+'</td><td>'+d.id+'</td><td><button type="button" disabled="disabled" value="'+d.status+'">已支付</button></td></tr>';
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




	
})