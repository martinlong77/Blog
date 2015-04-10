<?php
class FollowerViewModel extends ViewModel {
   Public $viewFields = array(
	'Follow' => array('id','user','follow_id'),	
	'Users' => array('user_nickname' => 'nickname','user_intro' => 'intro','user_avatar' => 'avatar','user_following' => 'following','user_followers' => 'followers','_on' => 'Follow.user = Users.ID'),
	);
}