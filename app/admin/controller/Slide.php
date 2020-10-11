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
** 幻灯片 控制器
*/

class Slide extends Adminbase {
	private static $_slideseat 	= null; 	// 数据表对象
	private static $_slide 		= null; 	// 数据表对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_slideseat 	= Loader::model('Slideseat');
		self::$_slide 		= Loader::model('Slide');
		
	}
	
	// 幻灯片 列表
	public function index() {
		
		// 获取分页后的幻灯片列表
		$lists = self::$_slide -> order('id desc') -> select();
		
		// 替换
		foreach($lists as $key => &$val){
			// 处理缩略图显示
			$val['faceimg'] = show_image($val['faceimg']);
			// 获取分类名称
			$val['cname'] = self::$_slideseat -> getTitle($val['cid']);
		}
		
		$this -> assign('lists', $lists);
		return view('index');
	}
	
	// 新增幻灯片页面
	public function add() {
		
		// 幻灯片分类
		$this -> assign('categorylists', $this -> categoryLists());
		return view('add');
	}
	
	// 保存新增幻灯片
	public function addsave() {
		
		$inputs = input('post.');
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Slide.add');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 处理时间
		$inputs['time_create'] = time();
		$inputs['time_update'] = time();
		
		// 保存数据
		if(self::$_slide -> data($inputs) -> save()){
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败');
	}
	
	// 编辑幻灯片页面
	public function edit() {
		
		$id = input('param.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$lists = self::$_slide -> getDetail($id);
		if(!$lists){
			$this -> error('参数错误2');
		}
		
		// 处理缩略图显示
		$lists['faceimg'] = show_image($lists['faceimg']);
		
		// 幻灯片分类
		$this -> assign('categorylists', $this -> categoryLists($lists['cid']));
		$this -> assign('lists', $lists);
		return view('edit');
	}
	
	// 保存编辑幻灯片
	public function editsave() {
		
		$inputs = input('post.');
		
		$id = (int) $inputs['id'];
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Slide.edit');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		$find = self::$_slide -> where(array('id' => $id)) -> value('id');
		if(!$find){
			$this -> error('参数错误');
		}
		
		// 处理时间
		$inputs['time_update'] = time();
		
		// 保存数据
		unset($inputs['id']);
		if(self::$_slide -> save($inputs, array('id' => $id))){
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败或没有数据可更新');
	}
	
	// 编辑幻灯片状态
	public function editstatus() {
		
		$id = input('post.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_slide -> editStatus($id);
		if($result){
			$this -> success('操作成功');
		}
		$this -> error('操作失败');
	}
	
	// 删除幻灯片
	public function deletes() {
		
		$id = input('post.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_slide -> deletes($id);
		if($result['status'] == 1){
			$this -> success('删除成功');
		}
		$this -> error($result['msg']);
	}
	
	// 幻灯片分类
	private function categoryLists($pickId = 0) {
		$lists = self::$_slideseat -> slideseatLists();
		
		$html = '<select name="cid" class="form-control width300">';
		$html .= '<option value="0">--选择分类--</option>';
		foreach($lists as $key => $val){
			// 被选中
			$selected = '';
			if($pickId == $val['id']){
				$selected = 'selected';
			}
			
			$html .= '<option value="' . $val['id'] . '" ' . $selected . '>' . $val['title'] . '</option>';
		}
		$html .= '</select>';
		
		return $html;
	}
	
	// 带参数的上传图片，并保存到数据库中
	public function upimage() {
		
		$id = input('post.aid/d');
		$lists = self::$_slide -> where(array('id' => $id)) -> column('id, faceimg');
		if(!$lists){
			$this -> error('参数错误');
		}
		
		// 执行上传
		$result = upload_img();
		
		// 上传成功
		if($result['status'] == 1){
			$result['imgurl'] = $result['url'];
			
			// 保存数据
			$saveData = array('faceimg' => $result['url'], 'time_update' => time());
			$saveRes = self::$_slide -> save($saveData, array('id' => $id));
			
			// 保存成功
			if($saveRes){
				// 判断旧图片是否存在，存在则删除
				if(file_exists('.' . $lists['faceimg'])){
					@unlink('.' . $lists['faceimg']);
				}
			}
		}
		
		// 返回JSON数据格式
		header('Content-Type:application/json; charset=utf-8');
		exit(json_encode($result, 0));
	}
	
}
