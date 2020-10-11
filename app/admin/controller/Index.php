<?php
namespace app\admin\controller;
use app\common\controller\Adminbase;

// +----------------------------------------------------------------------
// | VenusCMF
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2099
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 水蕃茄 <lzhf237@126.com>
// +----------------------------------------------------------------------

/*
** 后台首页
*/

class Index extends Adminbase {
	
	// 优先加载
	public function  _initialize() {
		parent::_initialize();
		
	}
	
	public function index() {
		
		$this -> assign('sysinfo', $this -> sysinfo());
		return view('index');
	}
	
	// 系统信息
	private function sysinfo() {
		//$mysql = M() -> query('select VERSION() as version');
		//$mysql = $mysql[0]['version'];
		$mysql = empty($mysql) ? '未知' : $mysql;
		
		return array(
			'操作系统' 		=> PHP_OS,
			'运行环境' 		=> $_SERVER["SERVER_SOFTWARE"],
			'PHP 运行方式' 	=> php_sapi_name(),
			'PHP 版本' 		=> PHP_VERSION,
			'MYSQL 版本' 	=> $mysql,
			'上传附件限制' 	=> ini_get('upload_max_filesize'),
			'执行时间限制' 	=> ini_get('max_execution_time') . 's',
			'剩余空间' 		=> round((@disk_free_space('.') / (1024 * 1024)), 2) . 'M',
		);
	}
	
}
