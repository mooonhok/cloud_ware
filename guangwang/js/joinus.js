		$(function() {
			$(".sure_a").on("click", function() {
				var company = $(".joinus_body_i").val();
				var name = $(".joinus_body_j").val();
				var telephone = $(".joinus_body_k").val();
				var address = $(".joinus_body_l").val();
				if(company.length <= 0) {
					layer.msg('公司名字不能为空');
				} else if(name.length <= 0) {
					layer.msg('负责人名字不能为空');
				} else if(!/^1[34578]\d{9}$/.test(telephone)) {
					layer.msg('手机号有误');
				} else if(address.length <= 0) {
					layer.msg('公司地址不能为空');
				} else {
					$.ajax({
						url: "http://api.uminfo.cn/contactCompany.php/addCompany",
						dataType: 'json',
						type: 'post',
						contentType: "application/json;charset=utf-8",
						data: JSON.stringify({
							company: company,
							contact_name: name,
							telephone: telephone,
							address: address
						}),
						success: function(msg) {
							//	alert(msg.result);
							if(msg.result == 0) {
								layer.msg('感谢您的信任，我们会尽快与您取得联系', {
									time: 2000
								});
								window.location.reload();
							} else {
								layer.msg(msg.desc);
							}
						},
						error: function(xhr) {
							alert(xhr.responseText);
						}
					});
				}
			});
			
	$(".salesman_t").on("click",function(){
        window.location.href="yindex.html";
            });
     $(".first_page").on("click",function(){
     	window.location.href="index.html";
     });
     $(".down_load").on("click",function(){
     	window.location.href="index.html";
     });
     $(".assist_t").on("click",function(){
     	window.location.href="assist.html";
     });
    $(".recruit_t").on("click",function(){
     	window.location.href="recruit.html";
    });
		});