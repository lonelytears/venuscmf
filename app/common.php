<?php

/*
** 应用公共函数
*/


// 判断访问的客户端类型
// 本函数来源于网络
function user_agent() {
	$ua = $_SERVER['HTTP_USER_AGENT'];
	
	$iphone = strstr(strtolower($ua), 'mobile');
	$android = strstr(strtolower($ua), 'android');
	$windowsPhone = strstr(strtolower($ua), 'phone');
	
	function androidTablet($ua) {
		if(strstr(strtolower($ua), 'android') ){
			if(!strstr(strtolower($ua), 'mobile')){
				return true;
			}
		}
	}
	$androidTablet = androidTablet($ua);
	$ipad = strstr(strtolower($ua), 'ipad');
	if($androidTablet || $ipad){
		return 'tablet';
	}elseif($iphone && !$ipad || $android && !$androidTablet || $windowsPhone){
		return 'mobile';
	}else{
		return 'desktop';
	}
}

/*
** 随机字符串生成
** @param int $len 生成的字符串长度
** @return string
*/
function random_string($len = 6) {
	$chars = array(
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
			'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
			'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
			'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
			'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2',
			'3', '4', '5', '6', '7', '8', '9'
		);
	
	$charsLen = count($chars) - 1;
	shuffle($chars); // 将数组打乱
	
	$output = '';
	for($i = 0; $i < $len; $i++){
		$output .= $chars[mt_rand(0, $charsLen)];
	}
	return $output;
}

// 对象转为数组
function object2array($object) {
	$result = array();
	foreach($object as $value){
		$result[] = json_decode($value, true);
	}
	return $result;
}


