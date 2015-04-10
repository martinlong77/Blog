<?php
class KeywordModel extends Model{
	protected $_validate = array(
		//0存在字段就验证 1必须验证 2值不为空的时候验证	  
        array('name','require','请输入名称',1), 
        array('pinyin','require','请输入拼音缩写',1),    
	);
}