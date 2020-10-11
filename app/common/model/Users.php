<?php
namespace app\common\model;
use app\common\model\Common;
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
** 后台管理员用户表 模型
*/

class Users extends Common {
	
	// 获取用户列表
	public function usersLists() {
		// 按 id 升序
		$lists = $this -> order('id', 'asc') -> select();
		
		// 获取所属角色名称
		foreach($lists as $key => &$val){
			$val['rolename'] = '未分配';
			
			if($val['id'] == 1){
				$val['rolename'] = '超级管理员';
			}else{
				// 获取用户对应的角色id
				$role_id = Loader::model('Roleuser') -> getUserRoleId($val['id']);
				if($role_id){
					// 获取角色名称
					$role_name = Loader::model('Role') -> getRoleName($role_id);
					$val['rolename'] = $role_name;
				}
			}
			$val['time_login'] = $val['time_login'] ? date('Y-m-d H:i:s', $val['time_login']) : '尚未登陆过';
		}
		
		return $lists;
	}
	
	// 获取单个用户详情
	public function getUsersDetail($id) {
		$lists = $this -> find($id);
		
		if($lists){
			return $lists;
		}
		
		return false;
	}
	
	// 编辑状态
	public function editStatus($id) {
		
		// 不能修改 超级管理员
		if($id == 1){
			return false;
		}
		
		$lists = $this -> find($id);
		
		if($lists){
			$status = $lists['status'] ? 0 : 1;
			
			$result = $this -> save(array('status' => $status), array('id' => $id));
			if($result){
				return true;
			}
		}
		return false;
	}
	
}
