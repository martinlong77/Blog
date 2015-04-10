<?php
class CollectViewModel extends ViewModel {
	Public $viewFields = array(
		'Collect' => array('user','post_author'),
		'Posts' => array('post_id','post_title','post_createtime','readcount','comcount', '_on' => 'Collect.collect_id=Posts.post_id'),
		'Users' => array('user_nickname' => 'nickname', '_on' => 'Collect.post_author=Users.ID'),
		);		
}