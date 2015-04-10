<?php
class ApplyViewModel extends ViewModel {
	Public $viewFields = array(
		'Apply' => array('user','apply_id','apply_time','apply_message','apply_status'),	
		'Users' => array('user_avatar' => 'avatar','user_nickname' => 'nickname','_on' => 'Apply.user = Users.ID'),
		);
}