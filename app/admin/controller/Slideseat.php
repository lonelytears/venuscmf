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
** 幻灯片分类 控制器
*/

class Slideseat extends Adminbase {
	private static $_slideseat = null; // 数据表对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_slideseat = Loader::model('Slideseat');
		
	}
	
	// 幻灯片分类列表
	public function index() {
		
		$lists = self::$_slideseat -> slideseatLists();
		
		$this -> assign('slideseatlists', $lists);
		return view('index');
	}
	
	// 新增幻灯片分类页面
	public function add() {
		
		return view('add');
	}
	
	// 保存新增幻灯片分类
	public function addsave() {
		
		$inputs = input('post.');
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Slideseat.add');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 验证新增 title 是否重复
		$this -> checkTitleAdd($inputs);
		
		// 保存数据
		if(self::$_slideseat -> data($inputs) -> save()){
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败');
	}
	
	// 编辑幻灯片分类页面
	public function edit() {
		
		$id = input('param.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$lists = self::$_slideseat -> getSlideseatDetail($id);
		if(!$lists){
			$this -> error('参数错误');
		}
		
		$this -> assign('lists', $lists);
		return view('edit');
	}
	
	// 保存编辑幻灯片分类
	public function editsave() {
		
		$inputs = input('post.');
		
		$id = (int) $inputs['id'];
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Slideseat.edit');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		$find = self::$_slideseat -> where(array('id' => $id)) -> value('id');
		if(!$find){
			$this -> error('参数错误');
		}
		
		// 验证新增 title 是否重复
		$this -> checkTitleUpdate($inputs);
		
		// 保存数据
		unset($inputs['id']);
		if(self::$_slideseat -> save($inputs, array('id' => $id))){
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败或没有数据可更新');
	}
	
	// 删除幻灯片分类
	public function deletes() {
		
		$id = input('post.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_slideseat -> deletes($id);
		if($result['status'] == 1){
			$this -> success('删除成功');
		}
		$this -> error($result['msg']);
	}
	
	// 验证新增数据 title 是否重复添加
	private function checkTitleAdd($data) {
		$checkData = array('title' => $data['title']);
		$find = self::$_slideseat -> where($checkData) -> find();
		if($find){
			$this -> error('同样的记录已经存在');
		}
		return true;
	}
	
	// 验证更新数据 title 是否重复添加
	private function checkTitleUpdate($data) {
		
		$checkData = array('title' => $data['title']);
		$find = self::$_slideseat -> where($checkData) -> value('id');
		
		if($find && $find != $data['id']){
			$this -> error('同样的记录已经存在');
		}
		return true;
	}
	
}
