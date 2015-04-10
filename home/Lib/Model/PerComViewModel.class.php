<?php
class PerComViewModel extends ViewModel {
	Public $viewFields = array(
		'PerComments' => array('uid','host_id' => 'hid','comment_id','comment_time','comment_content'),	
		'Users' => array('user_nickname','user_avatar','_on' => 'PerComments.uid = Users.ID'),
		);
}