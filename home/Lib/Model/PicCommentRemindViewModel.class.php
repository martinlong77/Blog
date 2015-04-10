<?php
class PicCommentRemindViewModel extends ViewModel {
	Public $viewFields = array(
		'Remind' => array('id','sender','remind_status','from_id'),
		'PicComments' => array('comment_content','img_id' => 'orignal_id','comment_time','_on' => 'Remind.from_id = PicComments.comment_id'),
		'Images' => array('image_title' => 'title','user' => 'hid','_on' => 'PicComments.img_id = Images.image_id'),
		'Users' => array('user_avatar' => 'avatar','user_nickname' => 'nickname','_on' => 'Remind.sender = Users.ID'),
		);
}