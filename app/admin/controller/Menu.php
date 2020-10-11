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
** 菜单 控制器
*/

class Menu extends Adminbase {
	private static $_menu = null; // 数据表对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_menu = Loader::model('Menu');
		
	}
	
	// 菜单列表
	public function index() {
		
		$lists = self::$_menu -> menuLists();
		
		$this -> assign('menulists', $lists);
		return view('index');
	}
	
	// 新增菜单页面
	public function add() {
		
		$lists = self::$_menu -> menuLists();
		
		$this -> assign('menulists', $lists);
		return view('add');
	}
	
	// 保存新增菜单
	public function addsave() {
		
		$inputs = input('post.');
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Menu.add');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 验证新增数据是否重复
		$this -> checkActionAdd($inputs);
		
		// 保存数据
		if(self::$_menu -> data($inputs) -> save()){
			$this -> success('操作成功');
		}
		$this -> error('操作失败');
	}
	
	// 编辑菜单页面
	public function edit() {
		
		$id = input('param.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$lists = self::$_menu -> getMenuDetail($id);
		if(!$lists){
			$this -> error('参数错误');
		}
		
		$this -> assign('lists', $lists);
		return view('edit');
	}
	
	// 保存编辑菜单
	public function editsave() {
		
		$inputs = input('post.');
		
		$id = (int) $inputs['id'];
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Menu.edit');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		$find = self::$_menu -> where(array('id' => $id)) -> value('id');
		if(!$find){
			$this -> error('参数错误');
		}
		
		// 验证更新数据是否重复
		$this -> checkActionUpdate($inputs);
		unset($inputs['id']);
		
		// 使用模型功能保存数据，方便调用模型事件
		if(self::$_menu -> save($inputs, array('id' => $id))){
			$this -> success('操作成功');
		}
		$this -> error('操作失败或没有数据可更新');
	}
	
	// 删除菜单
	public function deletes() {
		
		$id = input('post.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_menu -> deletes($id);
		if($result['status'] == 1){
			$this -> success('删除成功');
		}
		$this -> error($result['msg']);
	}
	
	// 验证新增数据 验证 module,control,actions 是否重复添加
	private function checkActionAdd($data) {
		$checkData = array(
				'module' 	=> $data['module'],
				'control' 	=> $data['control'],
				'actions' 	=> $data['actions']
			);
		$find = self::$_menu -> where($checkData) -> find();
		if($find){
			$this -> error('同样的记录已经存在');
		}
		return true;
	}
	
	// 验证更新数据 验证 module,control,actions 是否重复添加
	private function checkActionUpdate($data) {
		
		$checkData = array(
				'module' 	=> $data['module'],
				'control' 	=> $data['control'],
				'actions' 	=> $data['actions']
			);
		$find = self::$_menu -> where($checkData) -> value('id');
		
		if($find && $find != $data['id']){
			$this -> error('同样的记录已经存在');
		}
		return true;
	}
	
}
