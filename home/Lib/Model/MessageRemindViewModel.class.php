<?php
class MessageRemindViewModel extends ViewModel {
	Public $viewFields = array(
		'Remind' => array('id','sender','remind_status','from_id'),	//不知为什么必须要带主键
		'PerComments' => array('comment_content','comment_time','host_id' => 'orignal_id','_on' => 'Remind.from_id = PerComments.comment_id'),			
		'Users' => array('user_avatar' => 'avatar','user_nickname' => 'nickname','_on' => 'Remind.sender = Users.ID'),
		);
}