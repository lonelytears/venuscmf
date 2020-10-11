$(function(){
	// 刷新验证码
	function refreshVerify() {
		var url = $('#logverify').attr('data-src');
		var ranTime = Math.random();
		var src = url.replace('rantime', ranTime);
		$('#logverify').attr('src', src);
		$('input[name="verify"]').val('');  // 清空验证码输入框
	}

	// 单击刷新验证码
	$('body').on('click', '#logverify', function() {
		refreshVerify();
	});
	
	// 处理登陆的函数
	function doLogin(){
		var urls = $('form[name="loginForm"]').attr('action');
		var formData = $('form[name="loginForm"]').serialize();
		// 定义 loading 层
		var loading_index = layer.load(0, {shade: [0.1,'#FFF']});
		$.post(urls, formData, function(data) {
			layer.close(loading_index); // 关闭 loading 层
			if(data.code == 1){
				layer.msg(data.msg, {icon:1,time:1500}, function() {
					window.location.href = data.url;
				});
			}else{
				layer.msg(data.msg, {icon:5, time:1500}, function() {
					if(data.data.refresh == 1){
						// 刷新验证码
						refreshVerify();
					}
				});
			}
		});
		return false;
	}

	// 点击登陆
	$('body').on('click', 'a.btn-dologin', function() {
		doLogin();
	});

	// 监听输入框的回车事件
	$('input').keydown(function(event) {
		if(event.keyCode == 13){
			doLogin();
		}
	});
	
	// 更换背景图像
	change_bg();
	function change_bg() {
		var bg = Math.floor(Math.random() * 9 + 1);
		$('body').css('background-image', 'url(/static/admin/images/loginbg_0'+ bg +'.jpg)');
	}
	
	// 用户名获取焦点
	$('input[name="username"]').focus();
	
});
