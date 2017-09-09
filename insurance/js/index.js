$(document).ready(function(){
	var date2=null;
	var date3=null;
	//充值记录
 	$.ajax({
		  	  url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurance_rechanges?tenant_id=1",
 		  	  dataType:'json',
		  	  type:'get',
		  	  contentType:"application/json;charset=utf-8",
 		  	  data:JSON.stringify({
 		  	  }),
 		  	  success:function(msg){
 		  	  	console.log(msg);
 		         for(var i=0;i<msg.insurance_rechanges.length;i++){
 		         	if(msg.insurance_rechanges[i].status==0){
 		         		var testdata2=[{'a':'msg.insurance_rechanges[i].company','b':'msg.insurance_rechanges[i].pay_time',
	             'c':'msg.insurance_rechanges[i].money','d':'合同详情','e':'<button type="button" value="'+msg.insurance_rechanges[i].id+'" onclick="btn(this)">确认支付</button>'}];
	                console.log(testdata2);
	                $('#testtable3').yhhDataTable({
		              'paginate':{
			          'changeDisplayLen':true,
			          'type':'updown',
			          'visibleGo': true
		 },
		           'tbodyRow':{
			       'zebra':true,
			       'write':function(d){
				       return '<tr><td>'+d.a+'</td><td>'+d.b+'</td><td>'+d.c+'</td><td>'+d.d+'</td><td>'+d.e+'</td></tr>';
			}
		},
		           'tbodyData':{
			       'enabled':true,  /*是否传入表格数据*/
			       'source':testdata2 /*传入的表格数据*/
		}
	});
 		         	}else{
 		         var testdata2=[{'a':'msg.insurance_rechanges[i].company','b':'msg.insurance_rechanges[i].pay_time',
	             'c':'msg.insurance_rechanges[i].money','d':'','e':'已经支付'}];
	              console.log(testdata2);
	              $('#testtable3').yhhDataTable({
		              'paginate':{
			          'changeDisplayLen':true,
			          'type':'updown',
			          'visibleGo': true
		 },
		           'tbodyRow':{
			       'zebra':true,
			       'write':function(d){
				       return '<tr><td>'+d.a+'</td><td>'+d.b+'</td><td>'+d.c+'</td><td>'+d.d+'</td><td>'+d.e+'</td></tr>';
			}
		},
		           'tbodyData':{
			       'enabled':true,  /*是否传入表格数据*/
			       'source':testdata2 /*传入的表格数据*/
		}
	});
 		         	}   
	             // date2+=i;
               }
		  	  },
 		  	  error:function(xhr) {
		  	  	alert(xhr.responseText);
 		  	  }
		});

	
	// $('#testtable3').yhhDataTable({
	// 	'paginate':{
	// 		'changeDisplayLen':true,
	// 		'type':'updown',
	// 		'visibleGo': true
	// 	},
	// 	'tbodyRow':{
	// 		'zebra':true,
	// 		'write':function(d){
	// 			 return '<tr><td>'+d.a+'</td><td>'+d.b+'</td><td>'+d.c+'</td><td>'+d.d+'</td><td>'+d.e+'</td></tr>';
	// 		}
	// 	},
	// 	'tbodyData':{
	// 		'enabled':true,  /*是否传入表格数据*/
	// 		'source':testdata2 /*传入的表格数据*/
	// 	}
	// });
	//历史保险记录
	// $.ajax({
	// 	  	  url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurances?tenant_id=1",
 // 		  	  dataType:'json',
	// 	  	  type:'get',
	// 	  	  contentType:"application/json;charset=utf-8",
 // 		  	  data:JSON.stringify({
 // 		  	  }),
 // 		  	  success:function(msg){
 // 		         for(var a=0;a<msg.insurances.length;a++){
	//              var a={'a':msg.insurances[i].plate_number,'b':msg.insurance[i].driver_name,'c':msg.insurances[i].driver_phone,
	//              'd':'<button type="button" value="'+msg.insurances[i].id+'" onclick="but(this)">详情</button>','e':msg.insurances[i].insurance_price,'f':msg.insurance[i].insurance_money,'g':msg.insurance[i].time};
	//              date3+=a;
 //               }
	// 	  	  },
 // 		  	  error:function(xhr) {
	// 	  	  	alert(xhr.responseText);
 // 		  	  }
	// 	});
	// var testdata3 = {'code':'000','data':[date3]};
	// $('#testtable4').yhhDataTable({
	// 	'paginate':{
	// 		'changeDisplayLen':true,
	// 		'type':'updown',
	// 		'visibleGo': true
	// 	},
	// 	'tbodyRow':{
	// 		'zebra':true,
	// 		'write':function(d){
	// 			return "<tr><td>'+d.a+'</td><td>'+d.b+'</td><td>'+d.c+'</td><td>'+d.d+'</td><td>'+d.e+'</td><td>'+d.f+'</td><td>'+d.g+'</td></tr>";
				
	// 		}
	// 	},
	// 	'tbodyData':{
	// 		'enabled':true,  /*是否传入表格数据*/
	// 		'source':testdata3 /*传入的表格数据*/
	// 	},
	// 	'backDataHandle':function(d){
	// 		if (d.code == '000'){
	// 			return d.data;
	// 		} else {
	// 			alert('出错信息');
	// 			return [];
	// 		}
	// 	}
	// });
	
	/*更新表格*/ 
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
}); 


	
