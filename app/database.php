<?php

/*
** 数据库配置
*/

return [
	'type' 			=> 'mysql', 		// 数据库类型
	'hostname' 		=> '127.0.0.1', 	// 服务器地址
	'hostport' 		=> '3306', 			// 端口
	'charset' 		=> 'utf8', 			// 数据库编码默认采用utf8
	'database' 		=> 'venuscmf', 		// 数据库名
	'prefix' 		=> 'ven_', 			// 数据库表前缀
	'username' 		=> 'root', 			// 用户名
	'password' 		=> '', 			// 密码
	
	'debug' 		=> true, 			// 数据库调试模式
	
	// 数据库部署方式：0 单一 1 分布或主从
	'deploy' 		=> 0,
	// 数据库读写是否分离，主从式有效
	'rw_separate' 	=> false,
	// 读写分离后 主服务器数量
	'master_num' 	=> 1,
	// 指定从服务器序号
	'slave_no' 		=> '',
];
