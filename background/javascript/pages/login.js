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
			$('#entry').css("display",'none');
			$.ajax({
				url: p_url+"adminall.php/sign",
				dataType: 'json',
				type: 'post',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({
					name:name,
					password:password
				}),
				success: function(msg) {
				   if(msg.result == 0) {
                       $('.mask,.dialog').hide();
					   window.location.href = p_url+"background/tenant.html";
                       $.session.set('adminid',msg.admin.id);
                       $.session.set('admintype',msg.admin.type);
                       $.session.set('adminusername',msg.admin.username);
				  }
				},
				error: function(xhr) {
					layer.msg("获取后台失败");
				}
			});
		}
	});
});
