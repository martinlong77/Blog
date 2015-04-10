<?php
class CommentViewModel extends ViewModel {
	Public $viewFields = array(
		'Comments' => array('uid','comment_id','comment_time','comment_content'),	//Group,混淆字段，下次必须注意！
		'Users' => array('user_nickname','user_avatar','_on' => 'Comments.uid = Users.ID'),
		'Posts' => array('user' => 'hid','_on' => 'Comments.pid = Posts.post_id'),
		);
}