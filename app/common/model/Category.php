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
** 文章分类表 模型
*/

class Category extends Common {
	
	// 注册模型事件
	protected static function init() {
		// 新增后事件
		Category::afterInsert(function($_category) {
			// 获取传入的数据
			$data = $_category -> data;
			
			// 处理 path、topid
			$id = $data['id'];
			$pid = $data['pid'];
			if($pid > 0){
				// 获取上级菜单的 path
				$prentPath = $_category -> where(array('id' => $pid)) -> value('path');
				
				$newData['path'] = $prentPath . '-' . $id;
			}else{
				$newData['path']  = '0-' . $id;
			}
			
			// 保存
			$_category -> save($newData, array('id' => $id));
		});
	}
	
	// 获取分类列表
	public function categoryLists() {
		// 按 path 升序
		$lists = $this -> order('path', 'asc') -> select();
		
		return $lists;
	}
	
	// 获取单个分类详情
	public function getCategoryDetail($id) {
		$lists = $this -> find($id);
		
		if($lists){
			// 获取上级分类名称
			$lists['pname'] = $this -> getName($lists['pid']);
			return $lists;
		}
		
		return false;
	}
	
	// 获取分类名称
	public function getName($id) {
		if($id == 0){
			return '作为顶级分类';
		}
		
		$pName = $this -> where(array('id' => $id)) -> value('name');
		if($pName){
			return $pName;
		}
		
		return 'unKnown';
	}
	
	// 检测是否有子分类
	public function hasChild($id) {
		
		$has = $this -> where(array('pid' => $id)) -> value('id');
		if($has){
			return true;
		}
		return false;
	}
	
	// 删除分类
	public function deletes($id) {
		// 验证 id
		$find = $this -> where(array('id' => $id)) -> value('id');
		if(!$find){
			return array('status' => 0, 'msg' => '参数错误');
		}
		
		// 检测是否有子分类
		if($this -> hasChild($id)){
			return array('status' => 0, 'msg' => '有子分类，不能删除');
		}
		
		// 检测是否有文章
		if(Loader::model('Article') -> where(array('cid' => $id)) -> find()){
			return array('status' => 0, 'msg' => '本分类下已有文章，不能删除');
		}
		
		// 执行删除
		if($this -> where(array('id' => $id)) -> delete()){
			return array('status' => 1, 'msg' => '删除成功');
		}
		return array('status' => 0, 'msg' => '删除失败');
	}
	
}
