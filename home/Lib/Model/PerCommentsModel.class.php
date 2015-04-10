<?php
class PerCommentsModel extends Model {
	Protected $_map = array(
		'id' => 'host_id',
		'message' => 'comment_content',
	);

	protected $_validate=array(
        array('comment_content', '0,240', '字数超过了限制', 1, 'length', 1),
        array('comment_content', 'water', '请不要灌水', 1, 'callback', 1),                       
    );

    protected $_auto = array(
        array('comment_time','time',1,'function'),
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