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
** 幻灯片分类表 模型
*/

class Slideseat extends Common {
	
	// 获取分类列表
	public function slideseatLists() {
		// 按 id 升序
		$lists = $this -> order('id asc') -> select();
		
		return $lists;
	}
	
	// 获取单个分类详情
	public function getSlideseatDetail($id) {
		$lists = $this -> find($id);
		
		if($lists){
			return $lists;
		}
		
		return false;
	}
	
	// 获取分类名称
	public function getTitle($id) {
		if($id == 0){
			return 'unKnown';
		}
		
		$title = $this -> where(array('id' => $id)) -> value('title');
		if($title){
			return $title;
		}
		
		return 'unKnown';
	}
	
	// 删除幻灯片分类
	public function deletes($id) {
		
		// 验证 id
		$find = $this -> where(array('id' => $id)) -> value('id');
		if(!$find){
			return array('status' => 0, 'msg' => '参数错误');
		}
		
		// 检测是否有幻灯片
		if(Loader::model('Slide') -> where(array('cid' => $id)) -> find()){
			return array('status' => 0, 'msg' => '本分类下已有幻灯片，不能删除');
		}
		
		// 执行删除
		if($this -> where(array('id' => $id)) -> delete()){
			return array('status' => 1, 'msg' => '删除成功');
		}
		return array('status' => 0, 'msg' => '删除失败');
	}
	
}
