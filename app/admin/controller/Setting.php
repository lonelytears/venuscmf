<?php
namespace app\admin\controller;
use app\common\controller\Adminbase;
use \think\Request;
use \think\Db;

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
** 网站设置 控制器
*/

class Setting extends Adminbase {
	private static $_options 	= null; // 数据表对象
	private static $_source 	= null; // 目录资源对象
	
	// 优先加载
	public function _initialize() {
		parent::_initialize();
		
		// 实例化数据表模型
		self::$_options = Db::name('Options');
		
	}
	
	// 网站信息
	public function siteinfo() {
		$content = self::$_options -> where(array('title' => 'siteinfo')) -> value('content');
		$content = json_decode($content, true);
		
		$this -> assign('lists', $content);
		return view('siteinfo');
	}
	
	// 保存网站信息
	public function siteinfosave() {
		$inputs = input('post.');
		
		if(!$inputs['sitename']){
			$this -> error('请输入网站名称');
		}
		
		$sitestatus = (int) $inputs['sitestatus'];
		$sitestatus = $sitestatus > 0 ? 1 : 0;
		unset($inputs['sitestatus']);
		
		$datas = array();
		foreach($inputs as $key => $val){
			$key = trim($key);
			$datas[$key] = $val;
		}
		$datas['sitestatus'] = $sitestatus;
		
		// json 格式化
		$content = json_encode($datas);
		
		// 保存
		$result = self::$_options -> where(array('title' => 'siteinfo'))
									-> update(array('content'=>$content));
		
		if($result){
			$this -> success('操作成功');
		}
		$this -> error('操作失败或没有数据更新');
	}
	
	// 图片设置
	public function imgset() {
		$content = self::$_options -> where(array('title' => 'imgset')) -> value('content');
		$content = json_decode($content, true);
		
		$this -> assign('lists', $content);
		return view('imgset');
	}
	
	// 保存图片设置
	public function imgsetsave() {
		$inputs = input('post.');
		
		if(!$inputs['sitename']){
			$this -> error('请输入网站名称');
		}
		
		$sitestatus = (int) $inputs['sitestatus'];
		$sitestatus = $sitestatus > 0 ? 1 : 0;
		unset($inputs['sitestatus']);
		
		$datas = array();
		foreach($inputs as $key => $val){
			$key = trim($key);
			$datas[$key] = $val;
		}
		$datas['sitestatus'] = $sitestatus;
		
		// json 格式化
		$content = json_encode($datas);
		
		// 保存
		$result = self::$_options -> where(array('title' => 'imgset'))
									-> update(array('content'=>$content));
		
		if($result){
			$this -> success('操作成功');
		}
		$this -> error('操作失败或没有数据更新');
	}
	
	// 更新缓存
	public function recache() {
		// 指定缓存目录
		$cacheDir 	= CACHE_PATH;
		$tempDir 	= TEMP_PATH;
		
		$dirArray = array($cacheDir, $tempDir);
		
		// 调用 函数删除缓存文件
		$this -> rmDirFile($dirArray);
		
		// 释放资源
		if(self::$_source){
			self::$_source -> close();
		}
		
		$this -> success('清除缓存成功');
	}
	
	// 删除缓存文件及文件夹
	private function rmDirFile($dirArray) {
		
		// 清文件缓存
		foreach($dirArray as $dirValue){
			// 目录资源
			self::$_source = dir($dirValue);
			if(self::$_source){
				while (false !== $entry = self::$_source -> read()){
					// 过滤目录
					if($entry == '.' || $entry == '..'){
						continue;
					}else{
						// 清除缓存文件
						if(is_file($dirValue . $entry)){
							unlink($dirValue . $entry);
						}else{
							// 递归
							$this -> rmDirFile(array($dirValue . $entry . '/'));
							// 删除文件夹
							rmdir($dirValue . $entry  . '/');
						}
					}
				}
			}
		}
	}
	
}
