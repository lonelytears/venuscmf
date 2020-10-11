$(function(){
	// 改变主体内容高度的函数
	function resizeBox() {
		// 计算主体高度
		var winHeight = $(window).height(); // 当前窗口可视区域高度
		var boxHeight = winHeight - 41;
		if(boxHeight < 400){
			boxHeight = 400;
		}
		// 菜单栏高度
		$('.main .sidebar').css('height', boxHeight);
		// 主体内容高度
		$('.main .maincont').css('height', boxHeight - 37);
	}
	resizeBox();
	// 窗口大小改变时，动态改变主体内容高度
	$(window).resize(function(){
		resizeBox();
	});
	
	// 注销
	$('.topnav').on('click', 'a.btn-logout', function() {
		var url = $(this).attr('data-url');
		layer.confirm('您要离开了吗？', function(index) {
			$.post(url, {}, function(data){
				if(data.code == 1){
					layer.msg(data.msg, {icon:1, time:1500}, function() {
						window.location.href = root_url;
					});
				}
			});
		});
	});
	
	// 更新缓存
	$('.topnav').on('click', 'a.btn-recache', function() {
		var url = $(this).attr('data-url');
		$.post(url, {}, function(data){
			if(data.code == 1){
				layer.msg(data.msg, {icon:1, time:1500}, function() {
					window.location.href = root_url;
				});
			}else{
				layer.msg(data.msg, {icon:5, time:1500});
			}
		});
	});
	
	// 刷新页面
	$('body').on('click', 'a.btn-refresh', function() {
		window.location.href = window.location.href;
	});
	
	// 左侧菜单栏 显示 / 隐藏
	var show_sidebar = 'yes'; // 默认显示菜单栏，与下面合作
	$('.topnav').on('click', 'a.btn-launch', function() {
		show_sidebar = $.cookie('show_sidebar');
		if(show_sidebar == 'not'){
			$.cookie('show_sidebar', 'yes', {path: '/'});
		}else{
			$.cookie('show_sidebar', 'not', {path: '/'});
		}
		done_sidebar();
	});
	// 显示 / 隐藏左侧菜单栏函数
	function done_sidebar() {
		show_sidebar = $.cookie('show_sidebar');
		if(show_sidebar == 'not'){
			// 隐藏菜单栏
			$('.sidebar').css('display', 'none');
			$('.main').css('padding-left', '0px');
		}else{
			// 显示菜单栏
			$('.sidebar').css('display', 'block');
			$('.main').css('padding-left', '205px');
		}
	}
	done_sidebar();
	
	// 一级菜单展开
	$('#sidebar').on('click', 'a.topmenu', function() {
		var pid = $(this).attr('data-pid');
		
		if(pid){
			if(!$(this).hasClass('active')){
				$(this).addClass('active');
			}
			
			$('#sidebar .topmenu.active').each(function(ids) {
				// 当前对象的 pid
				var activePid = $(this).attr('data-pid');
				if(pid == activePid){
					// 不处理自己
				}else{
					$(this).removeClass('active');
					$('#sidebar .topmenu-box-' + activePid).slideUp(300);
					$('#sidebar .topmenu-box-' + activePid).removeClass('active');
				}
			});
			
			// 展开一级菜单
			if(!$('#sidebar .topmenu-box-' + pid).hasClass('active')){
				$('#sidebar .topmenu-box-' + pid).addClass('active');
				$('#sidebar .topmenu-box-' + pid).slideDown(300);
			}
		}
		return false;
	});
	
	// 点击菜单加载页面
	$('#sidebar').on('click', 'a.linkmenu', function() {
		var url = $(this).attr('data-url');
		var title = $(this).attr('data-title');
		
		if(url){
			window.location.href = url;
		}
		return false;
	});
	
	// 菜单返回
	$('#sidebar').on('click', 'a.menu-backup', function() {
		$('#sidebar li.topbackup').css('display', 'none');
		$('#sidebar li.topbox').removeClass('showhide');
		$('#sidebar .topmenu-box').css('display', 'none');
		$('#sidebar .topmenu-box').removeClass('active');;
	});
	
	// 开启公历节日
	laydate.render({
		elem: '#calendar',
		calendar: true,
		format: 'yyyy 年 MM 月 dd 日',
		btns: ['now', 'confirm'],
	});
	
	// 表单 保存
	$('body').on('click', 'a.btn-save', function() {
		// 提交的地址
		var url = $('body form[name="dataForm"]').attr('action');
		// 返回列表页地址
		var return_url = $('body a.index-url').attr('href');
		// 提交的数据
		var formData = $('body form[name="dataForm"]').serialize();
		
		var load_index = layer.load(0, {shade: [0.1, '#FFF']}); // 加载层
		$.post(url, formData, function(data){
			layer.close(load_index); // 关闭加载层
			if(data.code == 1){
				// 清空表单值
				$('body form[name="dataForm"] input').val('');
				// 显示提示并跳转
				layer.msg(data.msg, {icon:1, time:1500}, function(){
					//if(data.url){
					//	window.location.href = data.url;
					//}else{
						// 调用函数 返回列表页
						back_referrer(return_url);
					//}
				});
				layer.close(pop_index); // 关闭弹窗
			}else{
				layer.msg(data.msg, {icon:5, time:1500});
			}
		});
	});
	
	// 表单 取消 / 返回
	$('body').on('click', 'a.btn-cancel', function() {
		var return_url = $('body a.index-url').attr('href');
		// 调用函数 返回列表页
		back_referrer(return_url);
	});
	
	// 返回列表页的函数
	function back_referrer(return_url) {
		var edit_back_referrer = $.cookie('edit_back_referrer');
		if(edit_back_referrer){
			// 编辑返回
			window.location.href = edit_back_referrer;
		}else{
			window.location.href = return_url;
		}
		return false;
	}
	
	// ajax 更新状态
	$('body').on('click', 'a.btn-editstatus', function() {
		var url = $(this).attr('data-url');
		var id = $(this).attr('data-id');
		layer.confirm('切换状态吗？', function(index) {
			$.post(url, {id:id}, function(data){
				if(data.code == 1){
					layer.msg(data.msg, {icon:1, time:1500}, function() {
						window.location.href = window.location.href;
					});
				}else{
					layer.msg(data.msg, {icon:5, time:1500});
				}
			});
		});
	});
	
	// ajax 删除
	$('body').on('click', 'a.btn-deletes', function() {
		var url = $(this).attr('data-url');
		var id = $(this).attr('data-id');
		layer.confirm('确认删除吗？', function(index) {
			$.post(url, {id:id}, function(data){
				if(data.code == 1){
					layer.msg(data.msg, {icon:1, time:1500}, function() {
						window.location.href = window.location.href;
					});
				}else{
					layer.msg(data.msg, {icon:5, time:1500});
				}
			});
		});
	});
	
	// 点击查看封面图 / 缩略图
	$('body').on('click', 'img.show-faceimg', function() {
		var img_src = $(this).attr('src');
		layer.open({
			type: 1,
			title: false,
			closeBtn: 0,
			area: ['500px', '312px'],
			skin: 'layui-layer-nobg', // 没有背景色
			shadeClose: true,
			shade: [0.1, '#fff'], // 0.1透明度的白色背景
			content: '<img src="' + img_src + '" style="width: 500px; height: 312px;">'
		});
	});
	
	// 点击修改密码
	var pop_index = '';
	$('body').on('click', 'a.btn-repasswd', function() {
		var url 	= $(this).attr('data-url');
		var title 	= $(this).attr('data-title');
		var width 	= $(this).attr('data-width') + 'px';
		var height 	= $(this).attr('data-height') + 'px';
		
		$('#pop_html').load(url);
		
		pop_index = layer.open({
			type: 1,
			title: title,
			closeBtn: 1,
			area: [width, height],
			shadeClose: false,
			shade: [0.1, '#fff'], // 0.1透明度的白色背景
			content: $('#pop_html')
		});
	});
	
	// 修改密码返回
	$('body').on('click', 'a.btn-psbackup', function() {
		layer.close(pop_index); // 关闭弹窗
		$('#pop_html').html('');
	});
	
});
