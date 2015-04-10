<?php
class OfficialModel extends Model{
	protected $_map = array(
        's_t' => 'begin',
        'e_t' => 'end',
	 );
	
	protected $_validate = array(
		//0存在字段就验证 1必须验证 2值不为空的时候验证	  
        array('title','water','请输入标题',0,'callback'),
        array('begin','require','请输入开始时间！'),
        array('end','require','请输入结束时间！'),  
        array('content','water','请输入内容',0,'callback'),    
	);

    protected $_auto = array(
        //1新增数据的时候处理 2更新数据的时候处理 3所有情况都进行处理
        array('begin','strtotime',3,'function'),
        array('end','strtotime',3,'function'),
        array('time','time',1,'function'),
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