$(function(){
	$('#entry').click(function(){
		if($('#adminName').val()==''){
			$('.mask,.dialog').show();
			$('.dialog .dialog-bd p').html('请输入管理员账号');
		}else if($('#adminPwd').val()==''){
			$('.mask,.dialog').show();
			$('.dialog .dialog-bd p').html('请输入管理员密码');
		}else if($(".yanzhengma").val().length<=0){
			$('.mask,.dialog').show();
			$('.dialog .dialog-bd p').html('请输入验证码');
		}else if($(".yanzhengma").val().toUpperCase()!=code.toUpperCase()){
			$('.mask,.dialog').show();
			$('.dialog .dialog-bd p').html('验证码输入有误');
			createCode();
		}else{
			var name=$('#adminName').val();
			var password=$('#adminPwd').val();
			$.ajax({
				url: "http://api.uminfo.cn/salesback.php/sadmin",
				dataType: 'json',
				type: 'post',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({
					username:name,
					password:password,
					type:1
				}),
				success: function(msg) {
				   if(msg.result == 0) {
                       $('.mask,.dialog').hide();
					   window.location.href = "http://api.uminfo.cn/backsales/index.html";
                       $.session.set('adminid',msg.admin);
				  }
				},
				error: function(xhr) {
					layer.msg("获取后台失败");
				}
			});
		}
	});
});
