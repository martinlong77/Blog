<?php
class FollowViewModel extends ViewModel {
	Public $viewFields = array(
		'Follow' => array('user','follow_id','group_id'),	//Group,混淆字段，下次必须注意！
		'Users' => array('user_nickname' => 'nickname','user_avatar' => 'avatar','_on' => 'Follow.follow_id = Users.ID'),
		'Posts' => array('post_id','post_title','post_createtime','readcount','comcount', '_on' => 'Posts.user = Follow.follow_id'),
		);
}