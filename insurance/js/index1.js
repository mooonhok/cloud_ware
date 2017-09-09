$(document).ready(function(){
	// var a = "";
	// var b = "";
	// var c = "";
	// var d = "";
	// var e = "";
	var testdata2 = "";
	$.ajax({
		url:"http://mooonhok-cloudware.daoapp.io/rechange_insurance.php/insurance_rechanges?tenant_id=1",
		dataType:"json",
		contentType:"application/json;charset=utf-8",
		data:JSON.stringify({
 		}),
 		success:function(msg){
 			console.log(msg);
 		    for(var i=0;i<msg.insurance_rechanges.length;i++){

 		    	console.log(msg.insurance_rechanges.length);
 		    	 //    a += msg.insurance_rechanges[i].company+",";
 		    		// b += msg.insurance_rechanges[i].pay_time+",";
 		    		// c += msg.insurance_rechanges[i].money+",";
 		    		// d += '合同详情'+",";
 		    		// e += '<button type="button" value="'+msg.insurance_rechanges[i].id+'" onclick="btn(this)">确认支付</button>'+",";
 		    		// a1 = a.split(",")
 		    		// b1 = b.split(",")
 		    		// c1 = c.split(",")
 		    		// d1 = d.split(",")
 		    		// e1 = e.split(",")
 		    		// console.log(a1)
 		    		// console.log(b1)
 		    		// console.log(c1)
 		    		// console.log(d1)
 		    		// console.log(e1)
 		    		
 		    		testdata2 += {'a':msg.insurance_rechanges[i].company,'b':msg.insurance_rechanges[i].pay_time,'c':msg.insurance_rechanges[i].money,'d':'合同详情','e':'<button type="button" value="'+msg.insurance_rechanges[i].id+'" onclick="btn(this)">确认支付</button>'}; 
 		    		console.log(testdata2)
 		    	if(msg.insurance_rechanges[i].status!=0){	    		
                    testdata2 += {'a':msg.insurance_rechanges[i].company,'b':msg.insurance_rechanges[i].pay_time,'c':msg.insurance_rechanges[i].money,'d':'合同详情','e':'<button type="button" value="'+msg.insurance_rechanges[i].id+'" onclick="btn(this)">已支付</button>'}; 
 		    		console.log(testdata2)
 		    	}
 		    	// console.log(a);
 		    	// console.log(b);
 		    	// console.log(c);
 		    	// console.log(d);
 		    	// console.log(e);
 		    	 // var testdata2=[{'a':a,'b':b,'c':c,'d':d,'e':e},]; 
 		    	 console.log(testdata2);
 		    }
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
 		         
 		}, error:function(xhr) {
		  	  	alert(xhr.responseText);
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