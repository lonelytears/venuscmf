<?php
namespace app\common\controller;
use app\common\controller\Appbase;

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
** 后台基类控制器
*/

class Adminbase extends Appbase {
	private static $_uid 		= 0; 	// 当前登陆的管理员 id
	private static $_role_id 	= 0; 	// 当前登陆的管理员角色组 id
	
	// 优先加载
	public function  _initialize() {
		parent::_initialize();
		
		// 验证是否登陆登陆
		self::$_uid 	= session('uid');
		self::$_role_id = session('role_id');
		if(self::$_uid && self::$_role_id){
			// 正确，验证权限组是否变更
			if(self::$_uid == 1){
				// 超级管理员 跳过
			}else{
				// 验证权限组是否变更
				$role_id = \think\Db::name('roleuser') -> where('uid', '=', self::$_uid) -> value('role_id');
				if($role_id == self::$_role_id){
					// 正确
				}else{
					// 注销，重新登陆
					session(null);
					header('Location:' . url('publics/login'));
					exit();
				}
			}
		}else{
			// 未登陆，跳转到登陆页
			session(null);
			//if(is_ajax()){
				// 超时
				//exit('relogin');
			//	$this -> error('操作超时，请重新登陆');
				//$this -> error('relogin');
			//}else{
				header('Location:' . url('publics/login'));
				exit();
			//}
		}
		
		// 检查管理员用户的合法性
		$managerLawful = check_manager_lawful(self::$_uid);
		if(!$managerLawful){
			session(null);
			//if(is_ajax()){
				// 超时
				//exit('relogin');
			//	$this -> error('操作超时，请重新登陆');
				//$this -> error('relogin');
			//}else{
				header('Location:' . url('publics/login'));
				exit();
			//}
		}
		
		// 即时检查当前管理员是否具有当前模块、控制器、方法的权限
		if($managerLawful['role_id'] == 1){
			// 如果是超级管理员或超级管理员组，直接通过
		}else{
			if(!auth_check(self::$_uid, $managerLawful['role_access'])){
				if(is_ajax() && is_post()){
					// ajax 的 post 方式
					$this -> error('您的权限不足！');
				}else{
					exit('您的权限不足！');
				}
				//exit('您的权限不足！');
			}
		}
		
		$showDatas = array(
				// 输出左侧菜单
				'sidebar' 		=> $this -> sidebar(),
				// 输出当前菜单 / 当前位置
				'nowmenuname' 	=> $this -> getNowMenuName('name'),
				// 输出用户名
				'username' 		=> session('nickname') ? session('nickname') : session('username'),
				// 输出系统时间
				'systime' 		=> time()
			);
		
		$this -> assign($showDatas);
	}
	
	// 输出左侧菜单，仅支持 3 级
	private function sidebar() {
		$lists = \think\Loader::model('Menu') -> sidebar();
		
		// 当前菜单的顶级菜单 id
		$topid = $this -> getNowMenuName('topid');
		
		// 控制 返回按钮的 显示 / 隐藏，需要 css / js 配合
		if($topid == 'index'){
			// 首页
			$active_back = '';
		}else{
			$active_back = 'active';
		}
		
		$html = '<ul>';
		$html .= '<li class="topbackup ' . $active_back . '">';
		$html .= '<a class="topmenu menu-backup">';
		$html .= '<i class="fa fa-reply"></i> 返回';
		$html .= '</a>';
		$html .= '</li>';
		foreach($lists as $keyTop => $valTop){
			// 顶级目录
			if($valTop['pid'] == 0){
				// 顶级菜单显示 / 隐藏菜单，需要 css / js 配合
				if($topid == 'index'){
					// 首页
					$class_show = 'showall';
					// 一级菜单显隐
					$active = '';
				}else{
					if($topid == $valTop['id']){
						$class_show = 'showthis';
						$active = 'active';
					}else{
						$class_show = 'showhide';
						$active = '';
					}
				}
				
				$html .= '<li class="topbox ' . $class_show . '">';
				$html .= '<a class="topmenu ' . $active . '" data-pid="' . $valTop['id'] . '">';
				$html .= '<i class="fa ' . $valTop['icon'] . '"></i> ' . $valTop['name'];
				$html .= '<b><i class="fa fa-angle-right"></i></b>';
				$html .= '</a>';
				
				// 一级目录
				if(isset($valTop['child']) && $valTop['child']){
					$html .= '<ul class="topmenu-box ' . $active . ' topmenu-box-' . $valTop['id'] . '">';
					
					// 处理一级目录
					foreach($valTop['child'] as $keyFir => $valFir){
						if(isset($valFir['child']) && $valFir['child']){
							// 有二级目录
							$html .= '<li>';
							$html .= '<a class="firmenu" data-pid="' . $valFir['id'] . '">';
							$html .= '<i class="fa fa-caret-right"></i> ' . $valFir['name'];
							$html .= '<b><i class="fa fa-angle-right"></i></b>';
							$html .= '</a>';
							$html .= '<ul class="firmenu-box firmenu-box-' . $valFir['id'] . '">';
							
							foreach($valFir['child'] as $keySec => $valSec){
								$html .= '<li>';
								$html .= '<a class="secmenu linkmenu" data-url="' . url($valSec['url']) . '" data-title="' . $valSec['name'] . '">';
								$html .= '<i class="fa fa-hand-o-right"></i> ' . $valSec['name'];
								$html .= '</a>';
								$html .= '</li>';
							}
							
							$html .= '</ul></li>';
						}else{
							// 无二级目录
							$html .= '<li>';
							$html .= '<a class="firmenu linkmenu" data-url="' . url($valFir['url']) . '" data-title="' . $valFir['name'] . '">';
							$html .= '<i class="fa fa-caret-right"></i> ' . $valFir['name'];
							$html .= '</a>';
							$html .= '</li>';
						}
					}
					$html .= '</ul>';
				}
				$html .= '</li>';
			}
		}
		$html .= '</ul>';
		
		return $html;
	}
	
	// 获取当前菜单名称 / 当前位置
	private function getNowMenuName($type = 'all') {
		$lists = cache('Menu');
		
		$Request = \think\Request::instance();
		$nowUrl = strtolower($Request->module() . '/' . $Request->controller() . '/' . $Request->action());
		
		if($nowUrl == 'admin/index/index'){
			return 'index';
		}else{
			foreach($lists as $key => $val){
				if($val['url'] == $nowUrl){
					if($type == 'all'){
						return array('topid' => $val['topid'], 'name' => $val['name']);
					}
					if($type == 'topid'){
						return $val['topid'];
					}
					if($type == 'name'){
						return $val['name'];
					}
				}
			}
		}
		return 'unknow';
	}
	
}
