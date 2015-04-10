<?php
class ProFollowViewModel extends ViewModel {
   Public $viewFields = array(
	'Follow' => array('id','user','follow_id','group_id'),	//Group,混淆字段，下次必须注意！
	'Users' => array('user_nickname' => 'nickname','user_avatar' => 'avatar','user_following' => 'following','user_followers' => 'followers', 'user_intro' => 'intro', '_on' => 'Follow.follow_id = Users.ID'),
	);
}