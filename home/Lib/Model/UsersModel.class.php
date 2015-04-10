<?php
class UsersModel extends Model{
	protected $_map = array(
		'uid' => 'ID',
		'uname' => 'user_login',
		'pword' => 'user_pass',
		'disname' => 'user_nickname',
		'email' => 'user_email',
		'birth' => 'user_birthday',
		'location' =>'user_location',
		'intro' => 'user_intro',
		'avatar' => 'user_avatar',
		'sex' => 'user_sex',
		'secret' => 'sex_secret',
	 );
	
	protected $_validate = array(
		  //0存在字段就验证 1必须验证 2值不为空的时候验证
		  array('user_login', 'require', '账号已经被注册了！', 0,'unique'),				
          array('user_login','6,20','账号长度不正确！',0,'length'),		  
          array('user_nickname','water','请输入昵称',0,'callback'), 
          array('user_nickname','require','昵称太热门，已经被抢咯',0,'unique'),
          array('user_pass','6,20','密码长度不正确',0,'length'),
          array('user_email', 'email', 'email格式不正确'),          
		);

	protected $_auto = array(
		//1新增数据的时候处理 2更新数据的时候处理 3所有情况都进行处理
		array('user_registered','time',1,'function'),
		array('user_pass','md5',1,'function'),
		array('user_avatar','/Public/images/common/main/default.gif',1),	
        array('user_intro','这家伙很懒,什么也没留下',1),
        array('user_login','strtolower',1,'function'),
		);

	//防灌水
  	Protected function water($st) {
    	//验证用户是否胡乱输入空格回车灌水
    	$str = trim($st);// 首先去掉头尾空格
    	$str = preg_replace('/\s(?=\s)/', '', $str);// 接着去掉两个空格以上的
    	$str = preg_replace('/[\n\r\t]/', ' ', $str); //最后将非空格替换为一个空格

    	if (!empty($str)) {
    		return true;
    	} else {
    		return false;
    	}	
  	}
}