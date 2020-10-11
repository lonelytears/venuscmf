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
** 角色管理 控制器
*/

class Rbac extends Adminbase {
	private static $_role = null; // 数据表对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_role = Loader::model('Role');
	}
	
	// 角色列表
	public function index() {
		
		$lists = self::$_role -> roleLists();
		
		$this -> assign('rolelists', $lists);
		return view('index');
	}
	
	// 新增角色页面
	public function add() {
		
		return view('add');
	}
	
	// 保存新增角色
	public function addsave() {
		$inputs = input('post.');
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Role.add');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 验证新增角色名称是否重复
		$this -> checkNameAdd($inputs['name']);
		
		// 保存数据
		if(self::$_role -> data($inputs) -> save()){
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败');
	}
	
	// 编辑角色页面
	public function edit() {
		
		$id = input('param.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 获取本条角色数据
		$lists = self::$_role -> getRoleDetail($id);
		if(!$lists){
			$this -> error('参数错误');
		}
		
		$this -> assign('lists', $lists);
		return view('edit');
	}
	
	// 保存编辑角色
	public function editsave() {
		$inputs = input('post.');
		
		$id = (int) $inputs['id'];
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Role.edit');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 检测本条角色数据是否存在
		$find = self::$_role -> where(array('id' => $id)) -> value('id');
		if(!$find){
			$this -> error('参数错误');
		}
		
		// 验证编辑角色名称是否重复
		$this -> checkNameUpdate($inputs);
		unset($inputs['id']);
		
		// 保存数据
		if(self::$_role -> save($inputs, array('id' => $id))){
			$this -> success('操作成功');
		}
		$this -> error('操作失败或没有数据可更新');
	}
	
	// 删除角色
	public function deletes() {
		
		$id = input('post.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_role -> deletes($id);
		if($result['status'] == 1){
			$this -> success('删除成功');
		}
		$this -> error($result['msg']);
	}
	
	// 编辑角色状态
	public function editstatus() {
		
		$id = input('param.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_role -> editStatus($id);
		if($result){
			$this -> success('操作成功');
		}
		$this -> error('操作失败');
	}
	
	// 角色权限设置页面
	public function authorize() {
		$role_id = input('param.id/d');
		$role_id = $role_id > 0 ? $role_id : 0;
		if(!$role_id){
			$this -> error('角色信息错误');
		}
		// 判断，不能修改 超级管理员
		if($role_id == 1){
			$this -> error('角色信息错误');
		}
		
		// 获取已经分配的权限
		$roleHas = Loader::model('Authaccess') -> getHavingAuth($role_id);
		
		// 获取后台顶级菜单
		$topMenu = Loader::model('Menu') -> getTopMenu();
		
		$rbacMenu = '';
		// 获取子菜单
		foreach($topMenu as $key => $val){
			$childMenu = Loader::model('Menu') -> getAllChild($val['id']);
			
			// 顶级菜单
			$rbacMenu .= '<tr><td>';
			$roleTop = strtolower($val['module'] . '/' . $val['control'] . '/' . $val['actions']);
			if($roleHas && in_array($roleTop, $roleHas)){
				// 标示已获取的顶级权限，即已选中
				$rbacMenu .= '<label class="checkbox-inline"><input type="checkbox" class="rolecheck rbac-top" data-pid="' . $val['id'] . '" name="ids[]" value="' . $val['id'] . '" checked> ' . $val['name'] . '</label>';
			}else{
				$rbacMenu .= '<label class="checkbox-inline"><input type="checkbox" class="rolecheck rbac-top" data-pid="' . $val['id'] . '" name="ids[]" value="' . $val['id'] . '"> ' . $val['name'] . '</label>';
			}
			$rbacMenu .= '</td></tr>';
			
			// 子菜单
			if($childMenu){
				$rbacMenu .= '<tr><td style="padding: 0px 50px;">';
				foreach($childMenu as $ckey => $cval){
					$roleChild = strtolower($cval['module'] . '/' . $cval['control'] . '/' . $cval['actions']);
					if($cval['pid'] == $val['id']){
						// 子菜单中的顶级菜单
						$rbacMenu .= '<div style="margin-left: -25px; color: red;">';
						if($roleHas && in_array($roleChild, $roleHas)){
							// 标示已获取的权限，即已选中
							$rbacMenu .= '<label class="checkbox-inline"><input type="checkbox" class="rolecheck rbac-top-' . $val['id'] . '" data-myid="' . $cval['id'] . '" name="ids[]" value="' . $cval['id'] . '" checked> ' . $cval['name'] . '</label>';
						}else{
							$rbacMenu .= '<label class="checkbox-inline"><input type="checkbox" class="rolecheck rbac-top-' . $val['id'] . '" data-myid="' . $cval['id'] . '" name="ids[]" value="' . $cval['id'] . '"> ' . $cval['name'] . '</label>';
						}
						$rbacMenu .= '</div>';
					}else{
						if($roleHas && in_array($roleChild, $roleHas)){
							// 标示已获取的权限，即已选中
							$rbacMenu .= '<label class="checkbox-inline"><input type="checkbox" class="rolecheck rbac-top-' . $val['id'] . ' firtop-' . $cval['mytopid'] . '" name="ids[]" value="' . $cval['id'] . '" checked> ' . $cval['name'] . '</label>';
						}else{
							$rbacMenu .= '<label class="checkbox-inline"><input type="checkbox" class="rolecheck rbac-top-' . $val['id'] . ' firtop-' . $cval['mytopid'] . '" name="ids[]" value="' . $cval['id'] . '"> ' . $cval['name'] . '</label>';
						}
					}
				}
				$rbacMenu .= '</td></tr>';
			}
		}
		
		$this -> assign('rbacmenu', $rbacMenu);
		$this -> assign('role_id', $role_id);
		return view('authorize');
	}
	
	// 保存角色权限设置
	public function authorizesave() {
		
		$inputs = input('post.');
		
		$role_id = (int) $inputs['id'];
		$role_id = $role_id > 0 ? $role_id : 0;
		
		$ids = $inputs['ids']; // 数组
		
		if(!$role_id){
			$this -> error('角色信息错误');
		}
		
		// 判断，不能修改超级管理员的
		if($role_id == 1){
			$this -> error('角色信息错误');
		}
		
		// 处理 权限信息 id
		$newIds = array();
		if($ids){
			$newIds = $ids;
			
			if($newIds && is_array($newIds)){
				// in 方法查出 对应的菜单信息
				$menuLists = Loader::model('Menu') -> where(array('id' => array('in', $newIds)))
											-> column('id,module,control,actions');
				
				if($menuLists){
					// 批量删除旧的权限信息
					Loader::model('Authaccess') -> where(array('role_id' => $role_id)) -> delete();
					
					// 组合权限信息
					$authArray = array();
					foreach($menuLists as $key => $val){
						$val['role_id'] = $role_id;
						$val['rule_name'] = strtolower($val['module'] . '/' . $val['control'] . '/' . $val['actions']);
						unset($val['module']);
						unset($val['control']);
						unset($val['actions']);
						unset($val['id']);
						
						$authArray[] = $val;
					}
					
					// 批量插入权限信息
					Loader::model('Authaccess') -> saveAll($authArray);
					
					$this -> success('操作成功');
				}
			}
			$this -> error('权限信息错误');
		}else{
			// 批量删除旧的权限信息
			Loader::model('Authaccess') -> where(array('role_id' => $role_id)) -> delete();
			
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败');
	}
	
	// 验证新增 name 是否重复
	private function checkNameAdd($name) {
		
		$find = self::$_role -> where(array('name' => $name)) -> find();
		if($find){
			$this -> error('同样的数据已经存在');
		}
		return true;
	}
	
	// 验证编辑 name 是否重复
	private function checkNameUpdate($data) {
		// 检查是否重复添加
		$id = $data['id'];
		
		$find = self::$_role -> where(array('name' => $data['name'])) -> value('id');
		if($find && $find != $id){
			$this -> error('同样的数据已经存在');
		}
		return true;
	}
	
}
