var tab;

layui.config({
    base: 'js/',
    version: new Date().getTime()
}).use(['element', 'layer', 'navbar', 'tab'], function () {
    var element = layui.element(),
        $ = layui.jquery,
        layer = layui.layer,
        navbar = layui.navbar();
    tab = layui.tab({
        elem: '.admin-nav-card', //设置选项卡容器
        //maxSetting: {
        //max: 5,
        //tipMsg: '只能开5个哇，不能再开了。真的。'
        //},
        contextMenu: true,
        onSwitch: function (data) {
            // console.log(data.id); //当前Tab的Id
            // console.log(data.index); //得到当前Tab的所在下标
            // console.log(data.elem); //得到当前的Tab大容器
            // console.log(tab.getCurrentTabId())
        },
        closeBefore: function (obj) { //tab 关闭之前触发的事件
            // console.log(obj);
            //obj.title  -- 标题
            //obj.url    -- 链接地址
            //obj.id     -- id
            //obj.tabId  -- lay-id
            if (obj.title === 'BTable') {
                layer.confirm('确定要关闭' + obj.title + '吗?', { icon: 3, title: '系统提示' }, function (index) {
                    //因为confirm是非阻塞的，所以这里关闭当前tab需要调用一下deleteTab方法
                    tab.deleteTab(obj.tabId);
                    layer.close(index);
                });
                //返回true会直接关闭当前tab
                return false;
            }else if(obj.title==='表单'){
                layer.confirm('未保存的数据可能会丢失哦，确定要关闭吗?', { icon: 3, title: '系统提示' }, function (index) {
                    tab.deleteTab(obj.tabId);
                    layer.close(index);
                });
                return false;
            }
            return true;
        }
    });
    //iframe自适应
    $(window).on('resize', function () {
        var $content = $('.admin-nav-card .layui-tab-content');
        $content.height($(this).height() - 147);
        $content.find('iframe').each(function () {
            $(this).height($content.height());
        });
    }).resize();

    //设置navbar
    navbar.set({
        spreadOne: true,
        elem: '#admin-navbar-side',
        cached: true,
        data: navs
		/*cached:true,
		url: 'datas/nav.json'*/
    });
    //渲染navbar
    navbar.render();
    //监听点击事件
    navbar.on('click(side)', function (data) {
        tab.tabAdd(data.field);
    });
	
    //清除缓存
    $('#cleanCached').on('click', function () {
        navbar.cleanCached();
        layer.alert('清除完成!', { icon: 1, title: '系统提示', skin: 'layui-layer-molv' }, function () {
            location.reload();//刷新
        });
    });

    $('.admin-side-toggle').on('click', function () {
        var sideWidth = $('#admin-side').width();
        if (sideWidth === 200) {
            $('#admin-body').animate({
                left: '0'
            }); //admin-footer
            $('#admin-footer').animate({
                left: '0'
            });
            $('#admin-side').animate({
                width: '0'
            });
        } else {
            $('#admin-body').animate({
                left: '200px'
            });
            $('#admin-footer').animate({
                left: '200px'
            });
            $('#admin-side').animate({
                width: '200px'
            });
        }
    });
    $('.admin-side-full').on('click', function () {
        var docElm = document.documentElement;
        //W3C  
        if (docElm.requestFullscreen) {
            docElm.requestFullscreen();
        }
        //FireFox  
        else if (docElm.mozRequestFullScreen) {
            docElm.mozRequestFullScreen();
        }
        //Chrome等  
        else if (docElm.webkitRequestFullScreen) {
            docElm.webkitRequestFullScreen();
        }
        //IE11
        else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
        layer.msg('按Esc即可退出全屏');
    });
	
	//修改密码
    $('#alterPwd').on('click', function () {
        // tab.tabAdd({
            // href: 'main.html',
            // icon: 'fa-gear',
            // title: '修改密码'
        // });
		var content='';
		content+='<div>';
		content+='<div class="layui-form-item">';
		content+='<label class="layui-form-label">原密码</label>';
		content+='<div class="layui-input-inline">';
		content+='<input type="password" class="layui-input" id="pwd0" autocomplete="off">';
		content+='</div>';
		content+='</div>';
		content+='<div class="layui-form-item">';
		content+='<label class="layui-form-label">新密码</label>';
		content+='<div class="layui-input-inline">';
		content+='<input type="password" class="layui-input" id="pwd1" autocomplete="off">';
		content+='</div>';
		content+='</div>';
		content+='<div class="layui-form-item">';
		content+='<label class="layui-form-label">确认密码</label>';
		content+='<div class="layui-input-inline">';
		content+='<input type="password" class="layui-input" id="pwd2" autocomplete="off">';
		content+='</div>';
		content+='</div>';
		content+='</div>';
		layer.open({
			title:'修改密码',
			content:content,
			skin:'layui-layer-molv',
			btn:['确定修改','取消'],
			closeBtn:2,
			yes:function(index,layero){
				var pwd0=$("#pwd0").val();
				var pwd1=$("#pwd1").val();
				var pwd2=$("#pwd2").val();
				if(pwd0==""){
					layer.tips('请填写原密码','#pwd0');
				}else if(pwd1==""){
					layer.tips('请填写新密码','#pwd1');
				}else if(pwd2==""){
					layer.tips('请填写确认密码','#pwd2');
				}else if(pwd1!=pwd2){
					layer.tips('新密码与确认密码不一致','#pwd1');
				}else{
					$.ajax({
						url:"http://api.uminfo.cn/tenantsback.php/alterpw",
						dataType:'json',
						type:'put',
						ContentType:"application/json;charset=utf-8",
						data:JSON.stringify({
							id:sessionStorage.getItem('admin_id'),
							password1:pwd0,
							password2:pwd1
						}),
						success:function(msg){
							console.log(msg);
							if(msg.result==0){
								layer.close(index);
							}else{
								layer.tips('原密码不正确','#pwd0');
							}
						},
						error:function(xhr) {
							layer.msg("获取后台失败！");
						}
					});
				}
			},
			btn2:function(index,layero){
				layer.close(index);
			},
			cancel:function(){
			}
		});
    });

    //锁屏
    $(document).on('keydown', function () {
        var e = window.event;
        if (e.keyCode === 76 && e.altKey) {
            lock($, layer);
        }
    });
    $('#lock').on('click', function () {
        lock($, layer);
    });
	if(sessionStorage.getItem('lock_flag')==1){
		lock($, layer);
	}
	
	//注销
	$('#exit1').on('click', function () {
		sessionStorage.removeItem('admin_id');
		sessionStorage.removeItem('admin_name');
		window.location.href="login.html";
    });
	$('#exit2').on('click', function () {
		sessionStorage.removeItem('admin_id');
		sessionStorage.removeItem('admin_name');
		window.location.href="login.html";
    });
	

    //手机设备的简单适配
    var treeMobile = $('.site-tree-mobile'),
        shadeMobile = $('.site-mobile-shade');
    treeMobile.on('click', function () {
        $('body').addClass('site-mobile');
    });
    shadeMobile.on('click', function () {
        $('body').removeClass('site-mobile');
    });
});

var isShowLock = false;
function lock($, layer) {
    if (isShowLock)
        return;
    //自定页
    layer.open({
        title: false,
        type: 1,
        closeBtn: 0,
        anim: 6,
        content: $('#lock-temp').html(),
        shade: [0.9, '#393D49'],
        success: function (layero, lockIndex) {
			sessionStorage.setItem('lock_flag',1);
            isShowLock = true;
            //给显示用户名赋值
            layero.find('div#lockName').text(sessionStorage.getItem('admin_name'));
			
            layero.find('input[name=lockPwd]').on('focus', function () {
                var $this = $(this);
                if ($this.val() === '输入密码解锁..') {
                    $this.val('').attr('type', 'password');
                }
            })
			.on('blur', function () {
				var $this = $(this);
				if ($this.val() === '' || $this.length === 0) {
					$this.attr('type', 'text').val('输入密码解锁..');
				}
			});
			
            //绑定解锁按钮的点击事件
            layero.find('button#unlock').on('click', function () {
                var $lockBox = $('div#lock-box');
                var name = $lockBox.find('div#lockName').text();
                var pwd = $lockBox.find('input[name=lockPwd]').val();
                if (pwd === '输入密码解锁..' || pwd.length === 0) {
                    layer.msg('请输入密码..', {
                        icon: 2,
                        time: 1000
                    });
                    return;
                }
                unlock(name, pwd);
            });
			/**
			 * 解锁操作方法
			 * @param {String} 用户名
			 * @param {String} 密码
			 */
            var unlock = function (name, pwd) {
				$.ajax({
					url: "http://api.uminfo.cn/tenantsback.php/sign",
					dataType: 'json',
					type: 'post',
					ContentType: "application/json;charset=utf-8",
					data: JSON.stringify({
						name:name,
						password:pwd,
						type:1
					}),
					success: function(msg) {
						if(msg.result == 0) {
							//关闭锁屏层
							sessionStorage.setItem('lock_flag',0);
							isShowLock = false;
							layer.close(lockIndex);
						}else{
							layer.msg("密码不正确！", {
								icon: 2,
								time: 1000
							});
						}
					},
					error: function(xhr) {
						layer.msg("获取后台失败！");
					}
				});
            };
        }
    });
};