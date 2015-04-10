<?php
class FriendsViewModel extends ViewModel {
   Public $viewFields = array(
	'Friends' => array('id','user','friend_id','group_id'),	//Group,混淆字段，下次必须注意！
	'Users' => array('user_nickname' => 'nickname','user_intro' => 'intro','user_avatar' => 'avatar','user_following' => 'following','user_followers' => 'followers' ,'_on' => 'Friends.friend_id = Users.ID'),
	);
}