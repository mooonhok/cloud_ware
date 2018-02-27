$(function() {
	// var adminid = $.session.get('adminid');
  $("#getlocation").on("click", function() {
	window.open("http://lbs.qq.com/tool/getpoint/");
   });
   $(".center6").on("click",function(){
   	addmini();
   });
   $("#pic1").change(function() {  
        var file = this.files[0];  
        var reader = new FileReader();  
        reader.readAsDataURL(file);//调用自带方法进行转换  
        reader.onload = function(e) {  
            $("#pic1s").val(this.result);//将转换后的编码保存到input供后台使用  
        };   
    });  
    $("#pic2").change(function() {  
        var file = this.files[0];  
        var reader = new FileReader();  
        reader.readAsDataURL(file);//调用自带方法进行转换  
        reader.onload = function(e) {  
            $("#pic2s").val(this.result);//将转换后的编码保存到input供后台使用  
        };   
    });
     $("#pic3").change(function() {  
        var file = this.files[0];  
        var reader = new FileReader();  
        reader.readAsDataURL(file);//调用自带方法进行转换  
        reader.onload = function(e) {  
            $("#pic3s").val(this.result);//将转换后的编码保存到input供后台使用  
        };   
    });
     $("#pic4").change(function() {  
        var file = this.files[0];  
        var reader = new FileReader();  
        reader.readAsDataURL(file);//调用自带方法进行转换  
        reader.onload = function(e) {  
            $("#pic4s").val(this.result);//将转换后的编码保存到input供后台使用  
        };   
    });
     $("#pic5").change(function() {  
        var file = this.files[0];  
        var reader = new FileReader();  
        reader.readAsDataURL(file);//调用自带方法进行转换  
        reader.onload = function(e) {  
            $("#pic5s").val(this.result);//将转换后的编码保存到input供后台使用  
        };   
    });
     $("#pic6").change(function() {  
        var file = this.files[0];  
        var reader = new FileReader();  
        reader.readAsDataURL(file);//调用自带方法进行转换  
        reader.onload = function(e) {  
            $("#pic16").val(this.result);//将转换后的编码保存到input供后台使用  
        };   
    });
   
});
//获取省份和城市列表1
$.ajax({
	url: p_url+"city.php/province",
	dataType: 'json',
	type: 'get',
	ContentType: "application/json;charset=utf-8",
	data: JSON.stringify({}),
	success: function(msg) {
		for(var i = 0; i < msg.province.length; i++) {
			$("#province1").append('<option value="' + msg.province[i].id + '">' + msg.province[i].name + '</option>');
		}
	},
	error: function(e) {
		alert("省份列表1信息出错!");
	}
});

function getPro1() {
	$("#city1").empty();
	var pid = $("#province1 option:selected").val();
	$.ajax({
		url: p_url+"city.php/city?pid=" + pid,
		dataType: 'json',
		type: 'get',
		ContentType: "application/json;charset=utf-8",
		data: JSON.stringify({}),
		success: function(msg) {
			for(var i = 0; i < msg.city.length; i++) {
				$("#city1").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
			}
		},
		error: function(e) {
			$("#city1").append('<option value="">请选择市</option>');
		}
	});
}
//获取城市列表2
$.ajax({
	url: p_url+"city.php/province",
	dataType: 'json',
	type: 'get',
	ContentType: "application/json;charset=utf-8",
	data: JSON.stringify({}),
	success: function(msg) {
		for(var i = 0; i < msg.province.length; i++) {
			$("#province2").append('<option value="' + msg.province[i].id + '">' + msg.province[i].name + '</option>');
		}
	},
	error: function(e) {
		alert("省份列表2信息出错!");
	}
});

function getPro2() {
	$("#city2").empty();
	var pid = $("#province2 option:selected").val();
	$.ajax({
		url: p_url+"city.php/city?pid=" + pid,
		dataType: 'json',
		type: 'get',
		ContentType: "application/json;charset=utf-8",
		data: JSON.stringify({}),
		success: function(msg) {
			for(var i = 0; i < msg.city.length; i++) {
				$("#city2").append('<option value="' + msg.city[i].id + '">' + msg.city[i].name + '</option>');
			}
		},
		error: function(e) {
			$("#city2").append('<option value="">请选择市</option>');
		}
	});
}
var adminid=$.session.get('adminid');
function addmini(){
    var index = layer.load(1, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
    });
	var company=$("#companyname").val();
	var line=$("#line").val();
	var name=$("#name").val();
	var telephone=$("#telephone").val();
	var loaction=$("#location").val();
	var address=$("#address").val();
	var intro=$("#intro").val();
    var phone=$("#phone").val();
    var publicname=$("#publicname").val();
	var type=$("#type").val();
	var fcity=$("#city1").val();
	var tcity=$("#city2").val();
    var pic1=$("#pic1s").val();
    var pic2=$("#pic2s").val();
    var pic3=$("#pic3s").val();
    var pic4=$("#pic4s").val();
    var pic5=$("#pic5s").val();
    var pic6=$("#pic6s").val();
    arr=loaction.split(",");
    if(company==null||company==""){
    	alert("公司名称不能为空");
    }else if(line==null||line==""){
    	alert("路线不能为空");
    }else if(telephone==null||telephone==""){
    	alert("手机号码不能为空");
    }else if(loaction==null||location==""){
    	alert("地理坐标不能为空");
    }else if(address==null||address==""){
    	alert("地址不能为空");
    }else if(fcity==null||fcity==""||tcity==null||tcity==""){
    	alert("出发城市和到达城市不能为空");
    }else{
    	$.ajax({
        url: p_url+"mini.php/addmini",
        dataType: 'json',
        type: 'post',
        ContentType: "application/json;charset=utf-8",
        data: JSON.stringify({
        	name:company,
        	line:line,
        	intro:intro,
        	person:name,
        	telephone:telephone,
        	phone:phone,
        	address:address,
        	lat:arr[0],
        	lng:arr[1],
        	pubname:publicname,
        	pubimg:pic6,
        	flag:type,
        	fcity:fcity,
        	tcity:tcity,
        	pic1:pic1,
        	pic2:pic2,
        	pic3:pic3,
        	pic4:pic4,
        	pic5:pic5,
			adminid:adminid
        }),
        success: function(msg) {
        	if(msg.result==0){
        		layer.close(index);
        		alert("添加成功");
        		window.location.reload();
        	}else{
        		alert(msg.desc);
        	}
        },
        error: function(xhr) {
            alert("获取后台失败！"+xhr.responseText);
           
        }
    });
   }
}


