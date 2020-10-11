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
** 文章分类 控制器
*/

class Category extends Adminbase {
	private static $_category = null; // 数据表对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_category = Loader::model('Category');
		
	}
	
	// 分类列表
	public function index() {
		
		$lists = self::$_category -> categoryLists();
		
		$this -> assign('categorylists', $lists);
		return view('index');
	}
	
	// 新增分类页面
	public function add() {
		
		$lists = self::$_category -> categoryLists();
		
		$this -> assign('categorylists', $lists);
		return view('add');
	}
	
	// 保存新增分类
	public function addsave() {
		
		$inputs = input('post.');
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Category.add');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 验证新增数据 name 是否重复
		$this -> checkNameAdd($inputs);
		
		// 保存数据
		if(self::$_category -> data($inputs) -> save()){
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败');
	}
	
	// 编辑分类页面
	public function edit() {
		
		$id = input('param.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$lists = self::$_category -> getCategoryDetail($id);
		if(!$lists){
			$this -> error('参数错误');
		}
		
		$this -> assign('lists', $lists);
		return view('edit');
	}
	
	// 保存编辑分类
	public function editsave() {
		
		$inputs = input('post.');
		
		$id = (int) $inputs['id'];
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Category.edit');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		$find = self::$_category -> where(array('id' => $id)) -> value('id');
		if(!$find){
			$this -> error('参数错误');
		}
		
		// 验证更新数据 name 是否重复
		$this -> checkNameUpdate($inputs);
		unset($inputs['id']);
		
		// 保存数据
		if(self::$_category -> save($inputs, array('id' => $id))){
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败或没有数据可更新');
	}
	
	// 删除分类
	public function deletes() {
		
		$id = input('post.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_category -> deletes($id);
		if($result['status'] == 1){
			$this -> success('删除成功');
		}
		$this -> error($result['msg']);
	}
	
	// 验证新增数据 name 是否重复添加
	private function checkNameAdd($data) {
		$checkData = array('name' => $data['name']);
		$find = self::$_category -> where($checkData) -> find();
		if($find){
			$this -> error('同样的记录已经存在');
		}
		return true;
	}
	
	// 验证更新数据 name 是否重复添加
	private function checkNameUpdate($data) {
		
		$checkData = array('name' => $data['name']);
		$find = self::$_category -> where($checkData) -> value('id');
		
		if($find && $find != $data['id']){
			$this -> error('同样的记录已经存在');
		}
		return true;
	}
	
}
