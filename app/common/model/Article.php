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
** 文章表 模型
*/

class Article extends Common {
	
	// 插入或更新数据 后 拉取远程图片，不用编辑器自带的
	public function getRemoteImage($id) {
		$content = $this -> where(array('id' => $id)) -> value('content');
		
		// 反转义
		$content = htmlspecialchars_decode($content);
		
		// 要替换的数据，数组键值一一顺序对应
		// 函数获取 远程图片链接，remote 指远程图片
		$imgArray = preg_img($content, 'remote');
		$replaceArray = get_remote_image($imgArray);
		// 执行替换
		$finalContent = str_replace($replaceArray['oldfile'], $replaceArray['newfile'], $content);
		// 过滤并转义
		$finalContent = htmlspecialchars($finalContent);
		$finalContent = stripslashes($finalContent);
		$finalContent = trim($finalContent);
		
		// 保存
		$this -> save(array('content' => $finalContent), array('id' => $id));
	}
	
	// 获取文章分页列表
	public function articleLists(
		$where 		= array(), 		// 查询条件
		$nowPage 	= 1, 			// 当前页
		$pagesize 	= 20, 			// 每页条数
		$order 		= 'id desc' 	// 排序方式
	) {
		// 计算页数
		$total = $this -> where($where) -> count();
		$allPages = (int) ceil($total / $pagesize);
		// 验证输入的页码
		if($nowPage > $allPages){
			$nowPage = 1;
		}
		
		// 分页
		$pageStart = ($nowPage - 1) * $pagesize;
		$pageLimit = $pagesize;
		
		$lists = $this -> where($where)
						-> limit($pageStart, $pageLimit)
						-> order($order)
						-> select();
		
		return array(
				'lists' 	=> $lists,
				'total' 	=> $total,
				'allpages' 	=> $allPages,
				'nowpage' 	=> $nowPage
			);
	}
	
	// 获取单个文章详情
	public function getDetail($id) {
		$lists = $this -> find($id);
		
		if($lists){
			return $lists;
		}
		
		return false;
	}
	
	// 编辑状态
	public function editStatus($id) {
		$lists = $this -> where('id', '=', $id) -> find();
		
		if($lists){
			$status = $lists['status'] ? 0 : 1;
			
			$result = $this -> save(array('status' => $status, 'time_update' => time()), array('id' => $id));
			if($result){
				return true;
			}
		}
		return false;
	}
	
	// 删除文章
	public function deletes($id) {
		
		// 验证 id
		$lists = $this -> where(array('id' => $id)) -> find();
		if(!$lists){
			return array('status' => 0, 'msg' => '参数错误');
		}
		
		// 删除缩略图
		@unlink('.' . $lists['faceimg']);
		// 删除文章内容图片
		$content = htmlspecialchars_decode($lists['content']);
		$imgArray = preg_img($content, 'local');
		foreach($imgArray as $key => $val){
			@unlink('.' . $val);
		}
		
		// 执行删除
		if($this -> where(array('id' => $id)) -> delete()){
			return array('status' => 1, 'msg' => '删除成功');
		}
		return array('status' => 0, 'msg' => '删除失败');
	}
	
}
