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
				url: p_url+"tenantsback.php/sign",
				dataType: 'json',
				type: 'post',
				ContentType: "application/json;charset=utf-8",
				data: JSON.stringify({
					name:name,
					password:password,
					type:1
				}),
				success: function(msg) {
				   if(msg.result == 0) {
                       $('.mask,.dialog').hide();
					   window.location.href = p_url+"tenantsback/index.html";
                       $.session.set('adminid',msg.admin);
				  }else{
				  		alert(msg.desc);
				  }
				},
				error: function(xhr) {
					layer.msg("获取后台失败");
				}
			});
		}
	});
});
