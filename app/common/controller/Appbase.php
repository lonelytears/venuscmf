<?php
namespace app\common\controller;
use think\Controller;

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
** 网站基类控制器
*/

class Appbase extends Controller {
	
	// 优先加载
	public function  _initialize() {
		
		// 版本号
		$this -> assign('version', 'venuscmf_tp5_V20170926');
		// 输出系统时间
		$this -> assign('systime', time());
	}
	
}
