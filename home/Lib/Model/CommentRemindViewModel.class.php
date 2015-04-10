<?php
class CommentRemindViewModel extends ViewModel {
	Public $viewFields = array(
		'Remind' => array('id','sender','remind_status','from_id'),
		'Comments' => array('comment_content','comment_time','pid' => 'orignal_id','_on' => 'Remind.from_id = Comments.comment_id'),
		'Posts' => array('post_title' => 'title','user' => 'hid','_on' => 'Comments.pid = Posts.post_id'),	//comment.pid作为查询条件,要摆在comment查询后	
		'Users' => array('user_avatar' => 'avatar','user_nickname' => 'nickname','_on' => 'Remind.sender = Users.ID'),
		);
}