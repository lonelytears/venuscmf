<?php
/*
** admin 模块配置
*/

return [
	'session' => [
			'prefix' 			=> 'think', 	// SESSION 前缀
			'type' 				=> '', 			// 驱动方式 支持redis memcache memcached
			'auto_start' 		=> true, 		// 是否自动开启 SESSION
		],
	
	'cookie' => [
			'prefix' 			=> '', 		// cookie 名称前缀
			'expire' 			=> 0, 		// cookie 保存时间
			'path' 				=> '/', 	// cookie 保存路径
			'domain' 			=> '', 		// cookie 有效域名
			'secure' 			=> false, 	// cookie 启用安全传输
			'httponly' 			=> '', 		// httponly设置
			'setcookie' 		=> true, 	// 是否使用 setcookie
		],
	
	// 分页配置
	'paginate' => [
			'type' 				=> 'bootstrap',
			'var_page' 			=> 'page',
			'list_rows' 		=> 15,
		],
	
];
