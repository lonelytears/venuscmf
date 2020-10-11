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
** 文章 控制器
*/

class Article extends Adminbase {
	private static $_category = null; 	// 数据表对象
	private static $_article = null; 	// 数据表对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_category = Loader::model('Category');
		self::$_article = Loader::model('Article');
		
	}
	
	// 文章列表
	public function index() {
		
		// 当前页
		$nowPage = 1;
		if(isset($_GET['page']) && $_GET['page']){
			$nowPage = input('param.page/d');
			$nowPage = $nowPage > 0 ? $nowPage : 1;
		}
		
		// 每页显示条数，默认 10 条
		$pagesize = 10;
		
		// 处理查询条件
		$where = array();
		
		// 时间
		$whereTimeS = array();
		$whereTimeE = array();
		if(isset($_GET['startime']) && $_GET['startime'] && isset($_GET['endtime']) && $_GET['endtime']){
			// 检查时间日期的合法性
			$startime = input('param.startime');
			$endtime = input('param.endtime');
			$starTimes = date('Y-m-d', strtotime($startime));
			$endTimes = date('Y-m-d', strtotime($endtime));
			if($startime === $starTimes){ }else{
				echo '<script>alert("起始时间不合法！");</script>';
				$startime = '';
			}
			if($endtime === $endTimes){ }else{
				echo '<script>alert("结束时间不合法！");</script>';
				$endtime = '';
			}
			$FindStarTime = strtotime($startime);
			$FindendTime = strtotime($endtime) + 86400 - 1;
			
			if($FindStarTime > $FindendTime){
				echo '<script>alert("开始时间不能大于结束时间！");</script>';
				$startime = '';
				$endtime = '';
			}
			// 区间查询
			if($startime && $endtime){
				$where['time_report'] = array(
									array('egt', $FindStarTime),
									array('elt', $FindendTime),
									'and'
								);
			}
		}else{
			$startime = '';
			$endtime = '';
		}
		
		// 标题
		$title = '';
		if(isset($_GET['title']) && $_GET['title']){
			$title = input('param.title');
			$where['title'] = array('like', '%' . $title . '%');
		}
		
		// 获取分页后的文章列表
		$pageData = self::$_article -> articleLists($where, $nowPage, $pagesize);
		
		// 替换
		foreach($pageData['lists'] as $key => &$val){
			// 处理缩略图显示
			$val['faceimg'] = show_image($val['faceimg']);
			// 获取分类名称
			$val['cname'] = self::$_category -> getName($val['cid']);
		}
		
		$showDatas = array(
				'lists' 		=> $pageData['lists'],
				'allpages' 		=> $pageData['allpages'], 	// 总页数
				'nowpage' 		=> $pageData['nowpage'], 	// 当前页
				'startime' 		=> $startime,
				'endtime' 		=> $endtime,
				'title' 		=> $title
			);
		
		$this -> assign($showDatas);
		return view('index');
	}
	
	// 新增文章页面
	public function add() {
		
		// 文章分类
		$this -> assign('categorylists', $this -> categoryLists());
		return view('add');
	}
	
	// 保存新增文章
	public function addsave() {
		
		$inputs = input('post.');
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Article.add');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		// 处理时间
		$inputs['time_report'] = strtotime($inputs['time_report']);
		$inputs['time_create'] = time();
		$inputs['time_update'] = time();
		
		// 保存数据
		if(self::$_article -> data($inputs) -> save()){
			// 执行拉取远程图片
			self::$_article -> getRemoteImage(self::$_article -> id);
			
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败');
	}
	
	// 编辑文章页面
	public function edit() {
		
		$id = input('param.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$lists = self::$_article -> getDetail($id);
		if(!$lists){
			$this -> error('参数错误');
		}
		
		// 处理缩略图显示
		$lists['faceimg'] = show_image($lists['faceimg']);
		
		// 文章分类
		$this -> assign('categorylists', $this -> categoryLists($lists['cid']));
		$this -> assign('lists', $lists);
		return view('edit');
	}
	
	// 保存编辑文章
	public function editsave() {
		
		$inputs = input('post.');
		
		$id = (int) $inputs['id'];
		if(!$id){
			$this -> error('参数错误');
		}
		
		// 使用模型验证器进行验证
		$result = $this -> validate($inputs, 'Article.edit');
		if(true !== $result){
			// 验证失败 输出错误信息
			$this -> error($result);
		}
		
		$find = self::$_article -> where(array('id' => $id)) -> value('id');
		if(!$find){
			$this -> error('参数错误');
		}
		
		// 处理时间
		$inputs['time_report'] = strtotime($inputs['time_report']);
		$inputs['time_update'] = time();
		
		// 保存数据
		unset($inputs['id']);
		if(self::$_article -> save($inputs, array('id' => $id))){
			// 执行拉取远程图片
			self::$_article -> getRemoteImage($id);
			
			$this -> success('操作成功');
		}
		
		$this -> error('操作失败或没有数据可更新');
	}
	
	// 编辑文章状态
	public function editstatus() {
		
		$id = input('post.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_article -> editStatus($id);
		if($result){
			$this -> success('操作成功');
		}
		$this -> error('操作失败');
	}
	
	// 删除文章
	public function deletes() {
		
		$id = input('post.id/d');
		if(!$id){
			$this -> error('参数错误');
		}
		
		$result = self::$_article -> deletes($id);
		if($result['status'] == 1){
			$this -> success('删除成功');
		}
		$this -> error($result['msg']);
	}
	
	// 文章分类
	private function categoryLists($pickId = 0) {
		$lists = self::$_category -> categoryLists();
		
		$html = '<select name="cid" class="form-control width300">';
		$html .= '<option value="0">--选择分类--</option>';
		foreach($lists as $key => $val){
			// 被选中
			$selected = '';
			if($pickId == $val['id']){
				$selected = 'selected';
			}
			
			// 如果有子分类
			if(self::$_category -> hasChild($val['id'])){
				$html .= '<option value="' . $val['id'] . '" disabled ' . $selected . '>' . get_tree_icons($val['path']) . $val['name'] . '</option>';
			}else{
				$html .= '<option value="' . $val['id'] . '" ' . $selected . '>' . get_tree_icons($val['path']) . $val['name'] . '</option>';
			}
		}
		$html .= '</select>';
		
		return $html;
	}
	
	// 带参数的上传图片，并保存到数据库中
	public function upimage() {
		
		$id = input('post.aid', 0, 'intval');
		$lists = self::$_article -> where(array('id' => $id)) -> column('id, faceimg');
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
			$saveRes = self::$_article -> save($saveData, array('id' => $id));
			
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
