<?php
namespace app\common\model;
use app\common\model\Common;

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
** 幻灯片表 模型
*/

class Slide extends Common {
	
	// 获取单个幻灯片详情
	public function getDetail($id) {
		$lists = $this -> find($id);
		
		if($lists){
			return $lists;
		}
		
		return false;
	}
	
	// 编辑状态
	public function editStatus($id) {
		$lists = $this -> find($id);
		
		if($lists){
			$status = $lists['status'] ? 0 : 1;
			
			$result = $this -> save(array('status' => $status, 'time_update' => time()), array('id' => $id));
			if($result){
				return true;
			}
		}
		return false;
	}
	
	// 删除幻灯片
	public function deletes($id) {
		
		// 验证 id
		$lists = $this -> where(array('id' => $id)) -> find();
		if(!$lists){
			return array('status' => 0, 'msg' => '参数错误');
		}
		
		// 删除缩略图
		@unlink('.' . $lists['faceimg']);
		
		// 执行删除
		if($this -> where(array('id' => $id)) -> delete()){
			return array('status' => 1, 'msg' => '删除成功');
		}
		return array('status' => 0, 'msg' => '删除失败');
	}
	
}
