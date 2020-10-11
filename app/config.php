<?php

/*
** 应用公共配置
*/

// 报除 notice 外的错误
error_reporting(E_ALL & ~E_NOTICE);

return [
	'app_debug' 				=> true, 		// 应用调试模式
	'app_trace' 				=> false, 		// 应用Trace
	'app_multi_module' 			=> true, 		// 是否支持多模块
	'default_module' 			=> 'home', 		// 默认模块名
	'deny_module_list' 			=> ['common'], 	// 禁止访问模块
	'url_html_suffix' 			=> 'html', 		// URL伪静态后缀
	'url_route_on' 				=> true, 		// 是否开启路由
	'route_complete_match' 		=> false, 		// 路由使用完整匹配
	'route_config_file' 		=> ['route'], 	// 路由配置文件（支持配置多个）
	'url_route_must' 			=> false, 		// 是否强制使用路由
	
	'template' => [
			'type' 				=> 'Think', 	// 模板引擎类型 支持 php think 支持扩展
			'view_path' 		=> '', 			// 模板路径
			'view_suffix' 		=> 'html', 		// 模板后缀
			'view_depr' 		=> DS, 			// 模板文件名分隔符
			'tpl_begin' 		=> '{{', 		// 模板引擎普通标签开始标记
			'tpl_end' 			=> '}}', 		// 模板引擎普通标签结束标记
			'taglib_begin' 		=> '<', 		// 标签库标签开始标记
			'taglib_end' 		=> '>', 		// 标签库标签结束标记
		],
	
	// 默认跳转页面对应的模板文件
	'dispatch_success_tmpl' 	=> THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
	'dispatch_error_tmpl' 		=> THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
	
	// 异常页面的模板文件
	'exception_tmpl' 			=> THINK_PATH . 'tpl' . DS . 'think_exception.tpl',
	
	// 错误显示信息,非调试模式有效
	'error_message' 			=> '页面错误！请稍后再试～',
	// 显示错误信息
	'show_error_msg' 			=> false,
	
	// 注册命名空间
	'root_namespace' => [
			// 验证码
			'captcha'  => '../app/extend/captcha/',
		],
	
];
