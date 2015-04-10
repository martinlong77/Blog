<?php
class PostsModel extends RelationModel {
	Protected $_map = array(
		'title' => 'post_title',
		'tag' => 'post_tag',
		'cid' => 'post_category',
		'content' => 'post_content',
		'comment' => 'comment_status',
		'status' => 'post_status',
		);

	Protected $_validate = array(
		array('post_title', 'water', '请输入标题', 0,'callback'),
		array('post_content', 'water', '请输入内容', 0,'callback'),				
		);

	Protected $_auto = array(
		array('post_title','strip_tags',3,'function'),
		array('post_tag','strip_tags',3,'function'),
		);

	Protected function water($st) {
    	//验证用户是否胡乱输入空格回车灌水
    	$str = trim($st);// 首先去掉头尾空格
    	$str = preg_replace('/\s(?=\s)/', '', $str);// 接着去掉两个空格以上的
    	$str = preg_replace('/[\n\r\t]/', ' ', $str); //最后将非空格替换为一个空格

    	if ($str) {
    		return true;
    	} else {
    		return false;
    	}	
  	}

	Protected $_link = array(
		'Users' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'Users',
			'foreign_key' => 'user',
			//'mapping_fields' => 'user_avatar,user_nickname,user_following,user_followers,user_visits,user_collect',
			'as_fields' => 'user_avatar:avatar,user_nickname:nickname,user_following:following,user_followers:followers,user_visits:visits,user_collect:collect',
			),

		'nickname' => array(
			'mapping_type' => BELONGS_TO,
			'class_name' => 'Users',
			'foreign_key' => 'user',
			'mapping_fields' => 'user_nickname',
			'as_fields' => 'user_nickname:nickname',
			),
		);
}