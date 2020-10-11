<?php
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
** admin 模块函数
*/


/*
** 后台管理员密码加密方法
** @param string $password 要加密的字符串
** @return string
*/
function manager_password($password, $encrypt) {
	return md5(md5($encrypt . $password));
}

// 组合输出分类树状列表图标(缩进)
function get_tree_icons($path) {
	$paths = explode('-', $path);
	$length = count($paths);
	$icons = '';
	// 顶级
	if($length == 2){
		$icons = '';
	}
	if($length > 2){
		$iconsNum = $length * ($length - 1);
		for($i = 0; $i < $iconsNum; $i++){
			$icons .= '&nbsp;';
		}
		$icons = $icons . '|---';
	}
	
	return $icons;
}

/*
** 正则匹配文本中的所有图片地址
** 可以供 编辑保存时 对比图片、删除图片(编辑本地图片时与函数 sys_diff_del_img() 共用)
** @param string $string 编辑器处理后的文本文档
** @param string $type 要取的图片类型，默认 all 所有图片，local 本地图片，remote 远程图片
*/
function preg_img($string = '', $type = 'all') {
	if($string){
		// 实体转字符
		$string = htmlspecialchars_decode($string);
		/*
		$preg = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.bmp|\.jpeg]))[\'|\"].*?[\/]?>/';
		*/
		$preg = '/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/';
		preg_match_all($preg, $string, $imgArr);
		$docImgArray = $imgArr[1];
		
		if($type == 'all'){
			// 输出所有图片
			return $docImgArray;
		}else{
			$imgArray = array(); // 重新组合数组
			foreach($docImgArray as $key => $val){
				if($type == 'remote' && strpos($val, 'http') !== false){ // 位置可以取 0 时，不为 false
					// 远程图片
					$imgArray[] = $val;
				}
				if($type == 'local' && !(strpos($val, 'http') !== false)){ // 位置可以取 0 时，不为 false
					// 本地图片
					$imgArray[] = $val;
				}
			}
			return $imgArray;
		}
	}
	return array();
}

/*
** 处理文章本地图片
** 编辑保存时对比编辑器中的新图旧图，删除不存在、不使用的图
****** 注意新旧图片数组参数顺序，不要弄错了 ******
** @param array $oldImgArr 旧图片数组
** @param array $oldImgArr 新图片数组(新编辑保存)
*/
function diff_del_img($oldImgArr = array(), $newImgArr = array()) {
	// 没有旧图片，不处理
	if(! $oldImgArr){
		return true;
	}
	
	// 有旧图片，但新编辑时无图片，删除旧数组中的所有图片
	if($oldImgArr && !$newImgArr){
		foreach($oldImgArr as $keyO => $valO){
			// 如果图片存在，删除图片
			if($valO && file_exists('.' . $valO)){
				@unlink('.' . $valO);
			}
		}
		return true;
	}
	
	// 有旧图片，新编辑时也有图片
	if($oldImgArr && $newImgArr){
		$diffOldArray = array();
		foreach($oldImgArr as $keyOO => $valOO){
			// 如果旧图片不在新图片组中，删除旧图
			if(! in_array($valOO, $newImgArr)){
				//$diffOldArray[] = $valOO;
				// 如果图片存在，删除图片
				if($valOO && file_exists('.' . $valOO)){
					@unlink('.' . $valOO);
				}
			}
		}
		//return $diffOldArray;
		return true;
	}
}

// 显示图片，如果图片不存在，显示默认图片
function show_image($url = '') {
	if(empty($url)){
		// 地址为空，显示默认图片
		return '/static/images/default.png';
	}else{
		// 地址不为空，判断是远程还是本地文件
		if(strpos($url, 'http') !== false){ // 位置可以取 0
			if(remote_file_exists($url)){
				return $url;
			}else{
				// 图片不存在，显示默认图片
				return '/static/images/default.png';
			}
		}else{
			// 本地文件
			if(file_exists('.' . $url)){
				return $url;
			}else{
				// 图片不存在，显示默认图片
				return '/static/images/default.png';
			}
		}
	}
}

// curl 判断远程文件是否存在，返回 true 或 false
function remote_file_exists($url) {
	$curl = curl_init($url);
	// 不取回数据
	curl_setopt($curl, CURLOPT_NOBODY, true);
	// 发送请求
	$result = curl_exec($curl);
	
	$found = false;
	if($result !== false) {
		// 检查http响应码是否为200
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($statusCode == 200){
			$found = true;
		}
	}
	curl_close($curl);
	return $found;
}

// 拉取远程图片
function get_remote_image($imgArray = array(), $mySavePath = 'article') {
	$date = date('Ym');
	//远程抓取图片配置
	$config = array(
			// 保存路径，保存文件用
			'trueSavePath' => ROOT_PATH . 'public/uploads/' . $mySavePath . '/' . $date . '/',
			// 保存到数据表用
			'savePath' => '/uploads/' . $mySavePath . '/' . $date . '/',
			// 文件允许格式
			'allowFiles' => array('gif' , 'png' , 'jpg' , 'jpeg' , 'bmp'),
			// 文件大小限制，单位 KB
			'maxSize' => 3000,
		);
	
	$oldFile = array();
	$newFile = array();
	foreach($imgArray as $key => $imgUrl){
		
		// 判断处理 文件类型、后缀名
		$imgInfo 	= getimagesize($imgUrl);
		$imgInfo 	= explode('/', $imgInfo['mime']);
		$mimeType 	= strtolower($imgInfo[0]);
		$extName 	= strtolower($imgInfo[1]);
		
		//$extName 	= strtolower(pathinfo($imgUrl, PATHINFO_EXTENSION));
		
		// 处理图片
		if($mimeType == 'image' && in_array($extName, $config['allowFiles'])){
		//if(in_array($extName, $config['allowFiles'])){
			// 打开输出缓冲区并获取远程图片
			ob_start();
			$context = stream_context_create(
				array(
					// 不跟随重定向
					'http' => array('follow_location' => false)
				)
			);
			
			// 请确保 php.ini 中的 fopen wrappers 已经激活
			readfile($imgUrl, false, $context);
			$ob_img = ob_get_contents();
			ob_end_clean();
			
			/*
			$ch=curl_init();
			$timeout=5;
			curl_setopt($ch,CURLOPT_URL, $imgUrl);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, $timeout);
			$ob_img=curl_exec($ch);
			curl_close($ch);
			*/
			
			// 大小验证
			$imgSize = strlen($ob_img); // 得到图片大小
			$allowSize = 1024 * $config['maxSize'];
			if($imgSize > 0 && $allowSize > $imgSize){
				// 创建保存位置
				if(!file_exists($config['trueSavePath'])){
					// true 表示能创建多级目录
					mkdir($config['trueSavePath'], 0777, true);
				}
				
				// 重命名图片
				$newImgName = md5(round(microtime(true) * 1000)) . '.' . $extName;
				// 写入文件
				$saveFile = $config['trueSavePath'] . $newImgName;
				file_write($saveFile, $ob_img);
				
				// 输出要保存到数据表的图片路径名，不要有 '.'
				$showFile = $config['savePath'] . $newImgName;
				
				// 把原图路径与保存的本地新图路径对应，以便替换文章内容图片路径
				// 要保证键值对一一对应
				$oldFile[$key] = $imgUrl;
				$newFile[$key] = $showFile;
			}
		}
		// 暂停 1秒
		sleep(1);
	}
	return array('oldfile' => $oldFile, 'newfile' => $newFile);
}

// 写入文件，抓取远程图片时调用
function file_write($file, $content) {
	try{
		$fp2 = @fopen($file, 'w');
		fwrite($fp2, $content);
		fclose($fp2);
		return true;
	}catch(Exception $e){
		return false;
	}
}

/*
** 上传图片
** @param $saveArrRoot string 保存成功返回数据的数组根名称，默认 file，应配合上传插件调试后确认
** @param $savepath string 保存的目录，默认 article
*/
function upload_img($saveArrRoot = 'file', $savepath = 'article') {
	
	$file = request() -> file('file');
	
	$config = array(
				'size' 	=> 3145728,
				'ext' 	=> 'jpg,gif,png,jpeg,bmp'
			);
	
	$savePath = ROOT_PATH . 'public' . DS . 'uploads/' . $savepath;
	
	if($file){
		$info = $file -> validate($config) -> move($savePath);
		if($info){
			// 获取图片路径
			$data['url'] = '/uploads/' . $savepath . '/' . $info -> getSaveName();
			
			// 上传成功
			$data['msg'] 	= '上传成功';
			$data['status'] = 1;
		}else{
			// 上传失败
			$data['msg'] 	= $info -> getError();
			$data['status'] = 0;
		}
		
		return $data;
	}
	
}

// 检查管理员用户的合法性，注意顺序
function check_manager_lawful($uid = 0) {
	$uid = (int) $uid;
	
	if(!$uid){
		return false;
	}
	
	// 检查用户是否被禁用
	$userStatus = \think\Db::name('users') -> where(array('id' => $uid)) -> value('status');
	if(!$userStatus){
		return false;
	}
	
	if($uid == 1){
		// 如果是超级管理员
		$role_id = 1;
		return array('role_id' => $role_id, 'role_access' => array());
	}else{
		// 检查用户是否在对应的角色组里
		$role_id = \think\Db::name('roleuser') -> where(array('uid' => $uid)) -> value('role_id');
	}
	
	if($role_id){
		// 检查是否已启用角色
		$roleStatus = \think\Db::name('role') -> where(array('id' => $role_id)) -> value('status');
		if(!$roleStatus || $roleStatus == 0){
			return false;
		}
		
		// 必须要检查角色是否启用才能通过
		// 如果是超级管理员角色组，直接通过
		// 此处应该注释掉
		if($role_id == 1){
			return array('role_id' => $role_id, 'role_access' => array());
		}
		
		// 检查是否已给角色分配权限
		$roleAccess = \think\Db::name('authaccess') -> where(array('role_id' => $role_id)) -> select();
		if(!$roleAccess){
			return false;
		}
		return array('role_id' => $role_id, 'role_access' => $roleAccess);
	}
	return false;
}

/*
** 检查权限，配合检查管理员用户的合法性使用
** @param name string 			需要验证的规则列表，支持逗号分隔的权限规则或索引数组
** @param uid  int 				认证用户的id
** @param roleAccess array 		检查管理员用户的合法性返回的权限规则
** @return boolean           通过验证返回 true；失败返回 false
*/
function auth_check($uid = 0, $roleAccess = array(), $name = '') {
	$uid = (int) $uid;
	
	if(!$uid){
		return false;
	}
	
	if($uid == 1){
		// 如果是超级管理员，直接通过
		return true;
	}
	
	if(!$roleAccess){
		return false;
	}
	
	// 检查模块、控制器和方法
	if(empty($name)){
		$request = \think\Request::instance();
		$name = strtolower($request->module() . '/' . $request->controller() . '/' . $request->action());
	}else{
		$name = strtolower($name);
	}
	
	// 后台首页，直接通过
	if($name == 'admin/index/index'){
		return true;
	}
	
	foreach($roleAccess as $accRule){
		$rule_name = strtolower($accRule['rule_name']);
		if($rule_name == $name){
			return true;
		}
	}
	
	return false;
}




