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
** 管理员用户 控制器
*/

class Users extends Adminbase {
	private static $_users = null; // 数据表对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_users = Loader::model('Users');
		
	}
	
	// 管理员列表
	public function index() {
		
		$lists = self::$_users -> usersLists();
		
		$this -> assign('userslists', $lists);
		return view('index');
	}
	
	// 新增管理员页面
	public function add() {
		
		$this -> assign('rolelists', $this -> getRoleList());
		return view('add');
	}
	
	// 保存新增管理员
	public function addsave() {
		
		$inputs = input('post.');
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Users.add');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 验证 name 是否重复添加
		$this -> checkNameAdd($inputs['name']);
		
		// 产生加密密钥
		$encrypt_salt = md5(random_string(20));
		
		// 处理生成密码、加密密钥
		$inputs['passwd'] = manager_password($inputs['passwd'], $encrypt_salt);
		$inputs['encrypt_salt'] = $encrypt_salt;
		
		// 处理时间
		$inputs['time_create'] = time();
		$inputs['time_update'] = time();
		
		$role_id = $inputs['role_id'];
		// 验证角色 ID
		$this -> checkRoleId($role_id);
		unset($inputs['role_id']);
		
		// 保存数据
		self::$_users -> data($inputs);
		if(self::$_users -> save()){
			// 处理角色
			$datas = array(
					'role_id' 	=> $role_id,
					'uid' 		=> self::$_users -> id
				);
			Loader::model('Roleuser') -> writeRoleUser($datas);
			
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败');
	}
	
	// 编辑管理员页面
	public function edit() {
		
		$id = input('param.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 判断，不能修改 超级管理员
		if($id == 1){
			$this -> error('角色信息错误');
		}
		
		$lists = self::$_users -> getUsersDetail($id);
		if(!$lists){
			$this -> error('参数错误');
		}
		
		$this -> assign('rolelists', $this -> getRoleList($id));
		$this -> assign('lists', $lists);
		return view('edit');
	}
	
	// 保存编辑管理员
	public function editsave() {
		
		$inputs = input('post.');
		
		$id = (int) $inputs['id'];
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 判断，不能修改 超级管理员
		if($id == 1){
			$this -> error('角色信息错误');
		}
		
		$find = self::$_users -> where(array('id' => $id)) -> value('id');
		if(!$find){
			$this -> error('参数错误');
		}
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Users.edit');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 验证 name 是否重复添加
		$this -> checkNameUpdate($inputs);
		
		// 处理密码
		if(!empty($inputs['passwd'])){
			// 产生加密密钥
			$encrypt_salt = md5(random_string(20));
			
			// 处理生成密码、加密密钥
			$inputs['passwd'] = manager_password($inputs['passwd'], $encrypt_salt);
			$inputs['encrypt_salt'] = $encrypt_salt;
		}
		
		// 处理时间
		$inputs['time_update'] = time();
		
		$role_id = $inputs['role_id'];
		// 验证角色 ID
		$this -> checkRoleId($role_id);
		unset($inputs['role_id']);
		unset($inputs['id']);
		
		// 保存数据
		if(self::$_users -> save($inputs, array('id' => $id))){
			// 处理角色
			$datas = array(
					'role_id' 	=> $role_id,
					'uid' 		=> $id
				);
			Loader::model('Roleuser') -> writeRoleUser($datas);
			
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败或没有数据可更新');
	}
	
	// 删除管理员
	public function deletes() {
		
		$this -> error('删除功能暂不开放');
	}
	
	// 角色选择
	private function getRoleList($uid = 0) {
		// 输出 有效 角色，不输出超级管理员
		$rolelists = Loader::model('Role') -> where(array('id' => array('gt', 1)))
										-> column('id, name, status');
		
		$role_id = '';
		if($uid){
			// 输出所在角色组
			$roleInfo = Loader::model('Roleuser') -> where(array('uid' => $uid)) -> value('role_id');
			if($roleInfo){
				$role_id = $roleInfo;
			}
		}
		
		$roleBox = '';
		foreach($rolelists as $key => $val){
			// 处理权限状态，启用 / 禁用
			$status = '';
			$color = '#333';
			if($val['status'] == 0){
				$status = 'disabled';
				$color = '#CCC';
			}
			
			$roleBox .= '<label class="radio-inline" style="color: ' . $color . '">';
			if($role_id && ($role_id == $val['id'])){
				$roleBox .= '<input type="radio" name="role_id" value="' . $val['id'] . '" checked ' . $status . ' />' .  $val['name'];
			}else{
				$roleBox .= '<input type="radio" name="role_id" value="' . $val['id'] . '" ' . $status . ' />' .  $val['name'];
			}
			$roleBox .= '</label>';
		}
		return $roleBox;
	}
	
	// 验证 是否重复添加
	private function checkNameAdd($name) {
		// 传入为单个元素，则为字符串
		$find = self::$_users -> where(array('name' => $name)) -> find();
		if($find){
			$this -> error('同样的记录已经存在');
		}
		return true;
	}
	
	// 验证是否重复添加
	private function checkNameUpdate($data) {
		
		$find = self::$_users -> where(array('name' => $data['name'])) -> value('id');
		
		if($find && $find != $data['id']){
			$this -> error('同样的记录已经存在');
		}
		return true;
	}
	
	/*
	** 验证角色 ID
	*/
	private function checkRoleId($role_id) {
		if($role_id == 1){
			// 不能使用超级管理员
			$this -> error('请选择正确的角色');
		}
		
		// 大于 1，验证是否存在、是否启用
		$where = array('id' => $role_id, 'status' => 1);
		$find = Loader::model('Role') -> where($where) -> find();
		if($find){
			return true;
		}
		$this -> error('请选择正确的角色');
	}
	
}
