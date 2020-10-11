<?php
namespace app\admin\controller;
use app\common\controller\Adminbase;
use \think\Loader;

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
** 管理员独立控制器
*/

class Myinfo extends Adminbase {
	private static $_users = null; // 数据表对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_users = Loader::model('users');
		
	}
	
	// 修改密码页面
	public function repasswd() {
		
		if(request()->isPost() && isset($_POST['oldpasswd']) && isset($_POST['newpasswd']) && isset($_POST['renewpasswd'])){
			$inputs = input('post.');
			$this -> repasswdsave($inputs);
		}else{
			// 修改密码页面
			return view('repasswd');
		}
	}
	
	// 保存修改密码
	private function repasswdsave($inputs) {
		
		$oldPasswd 		= $inputs['oldpasswd'];
		$newPasswd 		= $inputs['newpasswd'];
		$reNewPasswd 	= $inputs['renewpasswd'];
		
		// 判断
		if(!$oldPasswd){
			$this -> error('请输入原密码');
		}
		if(!$newPasswd){
			$this -> error('请输入新密码');
		}
		if(!$reNewPasswd){
			$this -> error('请再次输入新密码');
		}
		if(strlen($newPasswd) < 6){
			$this -> error('新密码长度不能小于 6 位');
		}
		if($newPasswd != $reNewPasswd){
			$this -> error('两次输入的新密码不一致');
		}
		
		$uid = session('uid');
		
		// 获取用户信息
		$myinfo = self::$_users -> where(array('id' => $uid))
								-> field(array('id', 'passwd', 'encrypt_salt'))
								-> find();
		
		// 用户信息不存在
		if(!$myinfo){
			session(null); // 清空 session
			$this -> error('用户信息不存在', url('publics/login'));
		}
		
		// 加密原密码
		$passwdOldEnc = manager_password($oldPasswd, $myinfo['encrypt_salt']);
		// 加密新密码
		$passwdNewEnc = manager_password($reNewPasswd, $myinfo['encrypt_salt']);
		
		// 判断原密码
		if($myinfo['passwd'] != $passwdOldEnc){
			$this -> error('原密码错误');
		}
		
		// 判断原密码和新密码
		if($myinfo['passwd'] == $passwdNewEnc){
			$this -> error('新密码和原密码不能相同');
		}
		
		// 真实处理新密码
		// 产生新的加密密钥
		$encrypt_salt = md5(random_string(20));
		
		// 处理生成密码、加密密钥
		$trueNewEncPasswd = manager_password($reNewPasswd, $encrypt_salt);
		
		// 保存修改
		$saveData = array('passwd' => $trueNewEncPasswd, 'encrypt_salt' => $encrypt_salt);
		$result = self::$_users -> where(array('id' => $uid))
								-> data($saveData)
								-> save();
		
		if($result){
			// 记录日志
			//log_record(session('uid'), '修改密码', array());
			
			session(null); // 清空 session
			$this -> success('修改密码成功，请重新登陆', url('publics/login'));
		}
		$this -> error('修改密码失败');
	}
	
}
