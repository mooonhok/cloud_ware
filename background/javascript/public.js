
$(function(){
	'use strict';
	//左侧导航菜单效果
	$('.side-menu li dt').click(function(){
		$(this).parents('li').addClass('open');
		$(this).parents('.side-menu').find('.InitialPage').removeClass('active');
		$(this).parents('li').siblings().removeClass('open');
		
	});
	$('.side-menu dd a').click(function(){
		$(this).parents('li').addClass('open');
		$(this).parents('li').siblings().removeClass('open')
	});
	$('.side-menu li').each(function(){
		//判断链接相当（当前页面导航样式）
		if($(this).find('a').attr('href') == window.location.pathname){
			$(this).addClass('open');
			$(this).siblings().find('.InitialPage').removeClass('active');
			$('.InitialPage').removeClass('active');
		}else if($('.side-menu h2 a').attr('href') == window.location.pathname){
			$('.InitialPage').addClass('active');
		}   
	});
    //Tab选项卡.
    $('.tab-nav li').click(function(){
    	var liIndex = $('.tab-nav li').index(this);
    	$(this).addClass('active').siblings().removeClass('active');
    	$('.tab-cont').eq(liIndex).fadeIn().siblings('.tab-cont').hide();
    });
    //Button下拉菜单
    $('.btn-drop-group .btn').click(function(e){
    	$(this).siblings('.drop-list').show();
    	$(this).parent().siblings().find('.drop-list').hide();
    	$(document).one('click', function(){
	        $('.btn-drop-group .drop-list').hide();
	    });
	    e.stopPropagation();
    });
    	//点击list将当前值复制于button
	    $('.btn-drop-group .drop-list li').click(function(){
	    	$(this).parent().parent().hide();
	    });
	//左侧菜单收缩
	$('.top-hd .hd-lt').click(function(){
		$('.side-nav').toggleClass('hide');
		$('.top-hd,.main-cont,.btm-ft').toggleClass('switchMenu');
		$('.top-hd .hd-lt a').toggleClass('icon-exchange');
		localStorage.setItem('setLayOut1','hide');
		localStorage.setItem('setLayOut2','switchMenu');
		localStorage.setItem('setLayOut3','icon-exchange');
		if(!$('.side-nav').hasClass('hide')){
			localStorage.removeItem('setLayOut1');
			localStorage.removeItem('setLayOut2');
			localStorage.removeItem('setLayOut3');
		}
	});
	$('.side-nav').addClass(localStorage.getItem('setLayOut1'));
	$('.top-hd,.main-cont,.btm-ft').addClass(localStorage.getItem('setLayOut2'));
	$('.top-hd .hd-lt a').addClass(localStorage.getItem('setLayOut3'));
	

	//弹出层基础效果（确认/取消/关闭）
	$('.JyesBtn').click(function(){
		$(this).parents('.dialog').hide();
		if($('.mask').attr('display','block')){
			$('.mask').hide();
		}
	});
	$('.JnoBtn,.JclosePanel').click(function(){
		$(this).parents('.dialog').hide();
		if($('.mask').attr('display','block')){
			$('.mask').hide();
		}
	});
	//基础弹出窗
	$('.JopenPanel').click(function(){
		$('.dialog').show();
		$('.dialog').css('box-shadow','0 0 30px #d2d2d2');
	});
	//带遮罩
	$('.JopenMaskPanel').click(function(){
		$('.dialog,.mask').show();
		$('.dialog').css('box-shadow','none');
	});
	$('.mask').click(function(){
		$(this).hide();
		$('.dialog').hide();
	});
	//自动关闭消息窗口
	$('.Jmsg').click(function(){
		$('dialog').show().delay(5000).hide(0);
	});	
	//安全退出
	$('#JsSignOut').click(function(){
		layer.confirm('确定登出管理中心？', {
		  title:'系统提示',
		  btn: ['确定','取消']
		}, function(){
		  location.href = 'index.html';
		  $.session.remove('adminid');
          $.session.remove('admintype');
          $.session.remove('adminusername');
          $.session.remove('company');
          $.session.remove('company_name');
		});
	});

    var company_name=$.session.get('company_name');
    $('#shmz_name').html(company_name);
    var adminusername=$.session.get('adminusername');
    $("#gly_username").text(adminusername);
	$("#shxx_message").on("click",function(){
        layer.prompt({title: '请输入公司商务编号或公司简称或公司所在城市'}, function(pass, index){
            layer.close(index);
            if(pass){
                $.ajax({
                    url: p_url+"adminall.php/get_tenant?name="+pass+"",
                    dataType: 'json',
                    type: 'get',
                    ContentType: "application/json;charset=utf-8",
                    data: JSON.stringify({}),
                    success: function(msg) {
                    	if(msg.result==0){
                    		var tenant='';
                    		for(var i=0;i<msg.tenants.length;i++){
                                tenant+='<tr><td id="'+msg.tenants[i].tenant_id+'" style="cursor:pointer;">'+msg.tenants[i].company+'</td></tr>';
							}
							var company='<table style="width:100%;" id="gssh_biao">'+tenant+'</table>';
                            var index1=layer.open({
                                type: 1,
                                skin: 'layui-layer-rim', //加上边框
                                area: ['420px', '240px'], //宽高
                                content: company
                            });
                            $("#gssh_biao tr td").on("click",function(){
                                // alert($(this).attr('id'))
								$.session.remove('company');
                                $.session.remove('company_name');
                                $.session.set('company',$(this).attr('id'));
                                $.session.set('company_name',$(this).text());
                                $('#shmz_name').html($(this).text());
                                window.location.reload();
                                layer.close(index1);

							})
						}else{
                            layer.open({
                                type: 1,
                                skin: 'layui-layer-rim', //加上边框
                                area: ['420px', '240px'], //宽高
                                content: '没有与之匹配的物流公司'
                            });
						}

					}
                });
			}


        });
	})

});

//捐赠
// function reciprocate(){
// 	layer.open({
// 	  type: 1,
// 	  skin: 'layui-layer-demo',
// 	  closeBtn:1,
// 	  anim: 2,
// 	  shadeClose: false,
// 	  title:'喝杯咖啡O(∩_∩)O',
// 	  content: '<div class="pl-20 pr-20">'
// 		  +'<table class="table table-bordered table-striped mt-10">'
// 		  	+'<tr>'
// 		  		+'<td><img src="images/wechat_qrcode.jpg" style="width:auto;max-width:100%;height:120px;"/></td>'
// 		  		+'<td><img src="images/alipay_qrcode.jpg" style="width:auto;max-width:100%;height:120px;"/></td>'
// 		  	+'</tr>'
// 		  	+'<tr class="cen">'
// 		  		+'<td class="text-primary">微信打赏</td>'
// 		  		+'<td class="text-primary">支付宝打赏</td>'
// 		  	+'</tr>'
// 		  +'</table>'
// 	  +'</div>'
// 	});
// }
