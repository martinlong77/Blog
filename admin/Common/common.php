<?php
/*
 * $str，要进行判断的内容
 * $arr2，二维数组
 */
function in_2array($str,$arr2){
   $exist = false;
   foreach($arr2 as $value){
     if(in_array($arr,$value)){
        $exist = true;
        break;    //循环判断字符串是否存在于一位数组，存在则跳出  返回结果
     }
   }
   return $exist;
}

//返回图片所需求的路径,$type何种类型的路径,$web网站显示还是本地显示
function path_change($path,$type = 'del',$web = 1){	
	if ($type == 'display') {	//显示路径
		//网站
		$web == 1 ? $re_path = '/'.substr($path, strpos($path, 'U')) : $re_path = '/aijiajiao/'.substr($path, strpos($path, 'U'));	//本地 
			
	} else {	//相对路径，用于删除
		strpos($path, 'ueditor') ? $re_path = './'.substr($path, strpos($path, 'P')) : $re_path = './'.substr($path, strpos($path, 'U'));
	}
		
	return $re_path;
}	


/*
 * 将文章中的图片匹配出来
 * $str，要进行处理的内容
 * $ext，要匹配的扩展名
 * $num，取出的下标数
 */
 function img_match($str,$ext='jpg|jpeg|gif|bmp|png',$num){ 
    $list = array(); //这里存放结果map
    $c1 = preg_match_all('/<img\s.*?>/', $str, $m1); //先取出所有img标签文本
    if ($num) {
    	$c1 = $num;
    }
    for($i=0; $i<$c1; $i++) {     //对所有的img标签进行取属性
      $c2 = preg_match_all('/(\w+)\s*=\s*(?:(?:(["\'])(.*?)(?=\2))|([^\/\s]*))/', $m1[0][$i], $m2); //匹配出所有的属性
      for($j=0; $j<$c2; $j++) { //将匹配完的结果进行结构重组
        $list[$i][$m2[1][$j]] = !empty($m2[4][$j]) ? $m2[4][$j] : $m2[3][$j];
      }
    } 
    return $list;
 }
 
/**
 * 对用户的密码进行加密
 * @param $password
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */
function password($password, $encrypt='') {
	import('ORG.Util.String');
	//$pwd = array();
	//$pwd['encrypt'] =  $encrypt ? $encrypt : String::randString(6);
	//$pwd['password'] = md5(trim($password)).$pwd['encrypt'];
	//return $encrypt ? $pwd['password'] : $pwd;
	$pwd = md5(trim($password));
	return $pwd;
}
/**
 * 取得文件扩展
 * @param $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

//解析多行sql语句转换成数组
function sql_split($sql) {
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query) {
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		$queries = array_filter($queries);
		foreach($queries as $query) {
			$str1 = substr($query, 0, 1);
			if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
		}
		$num++;
	}
	return($ret);
}

/**
 * 文件扫描
 * @param $filepath     目录
 * @param $subdir       是否搜索子目录
 * @param $ex           搜索扩展
 * @param $isdir        是否只搜索目录
 * @param $md5			是否生成MD5验证码
 * @param $enforcement  强制更新缓存
 */
function scan_file_lists($filepath, $subdir = 1, $ex = '', $isdir = 0, $md5 = 0, $enforcement = 0) {
	static $file_list = array();
	if ($enforcement) $file_list = array();
	$flags = $isdir ? GLOB_ONLYDIR : 0;
	$list = glob($filepath.'*'.(!empty($ex) && empty($subdir) ? '.'.$ex : ''), $flags);
	if (!empty($ex)) $ex_num = strlen($ex);
	foreach ($list as $k=>$v) {
		$v1 = str_replace(SITE_DIR, '', $v);
		if ($subdir && is_dir($v)) {
			scan_file_lists($v.DIRECTORY_SEPARATOR, $subdir, $ex, $isdir, $md5);
			continue;
		} 
		if (!empty($ex) && strtolower(substr($v, -$ex_num, $ex_num)) == $ex) {
			if ($md5) {
				$file_list[$v1] = md5_file($v);
			} else {
				$file_list[] = $v1;
			}
			continue;
		} elseif (!empty($ex) && strtolower(substr($v, -$ex_num, $ex_num)) != $ex) {
			unset($list[$k]);
			continue;
		}
	}
	return $file_list;
}
/**
 * 生成CNZZ统计代码
 */
function tjcode($type='1') {
	if(!S('cnzz')) return false;
	$config = S('cnzz');
	if (empty($config)) {
		return false;
	} else {
		if(!$type) $type=1;
		return '<script src=\'http://pw.cnzz.com/c.php?id='.$config['siteid'].'&l='.$type.'\' language=\'JavaScript\' charset=\'gb2312\'></script>';
	}
}