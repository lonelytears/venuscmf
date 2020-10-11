<?php
namespace app\home\controller;
use app\common\controller\Homebase;

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
** 前台首页
*/

class Index extends Homebase {
	
	// 优先加载
	public function  _initialize() {
		parent::_initialize();
		
	}
	
	public function index() {
		
		
		return 'home';
		//return view('index');
	}
	
}
