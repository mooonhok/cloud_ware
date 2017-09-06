$(document).ready(function(){
	var data2=null;
	var data3=null;
	//充值记录
 	$.ajax({
		  	  url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurance_rechanges?tenant_id=1",
 		  	  dataType:'json',
		  	  type:'get',
		  	  contentType:"application/json;charset=utf-8",
 		  	  data:JSON.stringify({
 		  	  
 		  	  }),
 		  	  success:function(msg){
 		         for(var a=0;a<msg.insurance_rechanges.length;a++){
 		         	if(msg.insurance_rechanges[i].status==0){
 		         		var a={'a':msg.insurance_rechanges[i].company,'b':msg.insurance_rechanges[i].pay_time,
	             'c':msg.insurance_rechanges[i].money,'d':'合同详情','e':'<div class="affirm" id="su1" onclick="poppage1()" value="'+msg.insurance_rechanges[i].id+'">确认支付</div>'};
 		         	}else{
 		         		var a={'a':msg.insurance_rechanges[i].company,'b':msg.insurance_rechanges[i].pay_time,
	             'c':msg.insurance_rechanges[i].money,'d':'','e':'已经支付'};
 		         	}   
	             date2+=a;
               }
		  	  },
 		  	  error:function(xhr) {
		  	  	alert(xhr.responseText);
 		  	  }
		});

	var testdata2 = [date2];
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
	//历史保险记录
	$.ajax({
		  	  url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurances?tenant_id=1",
 		  	  dataType:'json',
		  	  type:'get',
		  	  contentType:"application/json;charset=utf-8",
 		  	  data:JSON.stringify({
 		  	  
 		  	  }),
 		  	  success:function(msg){
 		         for(var a=0;a<msg.insurances.length;a++){
	             var a={'a':msg.insurances[i].plate_number,'b':msg.insurance[i].driver_name,'c':msg.insurances[i].driver_phone,
	             'd':'<div class="details">详情</div>','e':msg.insurances[i].insurance_price,'f':msg.insurance[i].insurance_money,'g':msg.insurance[i].time};
	             date3+=a;
               }
		  	  },
 		  	  error:function(xhr) {
		  	  	alert(xhr.responseText);
 		  	  }
		});
	var testdata3 = {'code':'000','data':[data3]};
	$('#testtable4').yhhDataTable({
		'paginate':{
			'changeDisplayLen':true,
			'type':'updown',
			'visibleGo': true
		},
		'tbodyRow':{
			'zebra':true,
			'write':function(d){
				return '<tr><td>'+d.a+'</td><td>'+d.b+'</td><td>'+d.c+'</td><td onclick="poppage()">'+d.d+'</td><td>'+d.e+'</td><td>'+d.f+'</td><td>'+d.g+'</td></tr>';
				
			}
		},
		'tbodyData':{
			'enabled':true,  /*是否传入表格数据*/
			'source':testdata3 /*传入的表格数据*/
		},
		'backDataHandle':function(d){
			if (d.code == '000'){
				return d.data;
			} else {
				alert('出错信息');
				return [];
			}
		}
	});
	
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


	
