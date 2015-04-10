<?php
class PicComViewModel extends ViewModel {
	Public $viewFields = array(
		'PicComments' => array('uid','comment_id','comment_time','comment_content'),
		'Users' => array('user_nickname','user_avatar','_on' => 'PicComments.uid = Users.ID'),
		'Images' => array('user' => 'hid','_on' => 'PicComments.img_id = Images.image_id'),
		);
}