<?php
namespace app\admin\controller;
use app\common\controller\Appbase;
use think\Db;

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
** 登陆 控制器
*/

class Publics extends Appbase {
	
	// 优先加载
	public function  _initialize() {
		parent::_initialize();
		
	}
	
	// 登陆页面
	public function login() {
		
		$uid = session('uid');
		if($uid){
			// 已登陆，跳转到后台首页
			header('Location:' . url('index/index'));
			exit();
		}else{
			// 跳转到登陆页面
			return view('login');
		}
	}
	
	// 注销登陆
	public function logout() {
		// 清空用户菜单缓存
		$uid = session('uid');
		
		// 记录日志
		//log_write($uid, '注销', array());
		
		// 清空 session
		session(null);
		
		$this -> success('注销成功', url('publics/login'));
	}
	
	// 处理登陆事件
	public function dologin() {
		// 处理提交的数据
		$inputs = input('post.');
		
		// 检测数据
		$this -> checkDatas($inputs);
		
		$name = $inputs['username'];
		$pass = $inputs['password'];
		
		// 检测用户信息
		$result = Db::name('users') -> where('name', '=', $name) -> find();
		if(!$result){
			$this -> error('用户信息错误', '', array('refresh' => 1));
		}
		
		// 检测密码
		$passwd = manager_password($pass, $result['encrypt_salt']);
		if($passwd != $result['passwd']){
			$this -> error('用户信息错误', '', array('refresh' => 1));
		}
		
		// 检测用户状态，0 禁用 1 正常
		if($result['status'] == 0){
			$this -> error('用户信息错误', '', array('refresh' => 1));
		}
		
		// 检查用户的合法性
		$managerLawful = check_manager_lawful($result['id']);
		if(!$managerLawful){
			$this -> error('用户信息错误', '', array('refresh' => 1));
		}
		
		// 登入成功，把管理员信息放入 session里，并页面跳转
		session('uid', $result['id']);
		session('role_id', $managerLawful['role_id']);
		session('username', $result['name']);
		session('nickname', $result['nickname']);
		session('login_ip', $result['login_ip']);
		session('login_time', $result['time_login']);
		session('login_count', $result['login_count'] + 1);
		
		// 更新管理员登陆信息
		$data['login_ip'] 		= ip2long(request() -> ip());
		$data['time_login'] 	= time();
		$data['login_count'] 	= $result['login_count'] + 1;
		Db::name('users') -> where('id', '=', $result['id']) -> update($data);
		
		// 记录日志
		//log_write($result['id'], '登陆', $data);
		
		$this -> success('登陆成功', url('index/index'));
	}
	
	// 生成验证码
	public function verify() {
		$config = array(
				'fontSize' 	=> 30, 		// 验证码字体大小
				'length' 	=> 5, 		// 验证码位数
				'useNoise' 	=> false, 	// 是否启用验证码杂点
		);
		
		$Captcha = new \captcha\Captcha($config);
		return $Captcha -> entry();
	}
	
	// 检测验证码
	private function checkVerify($code) {
		$Captcha = new \captcha\Captcha();
		return $Captcha -> check($code);
	}
	
	// 检测数据
	private function checkDatas($datas) {
		$name = $datas['username'];
		$pass = $datas['password'];
		$code = $datas['verify'];
		
		if(!$name){
			$this -> error('用户名不能为空');
		}
		
		if(!$pass){
			$this -> error('密码不能为空');
		}
		
		if(!$code){
			$this -> error('验证码不能为空');
		}
		
		// 检测验证码
		if(!$this -> checkVerify($code)){
			$this -> error('验证码错误', '', array('refresh' => 1));
		}
	}
	
}
