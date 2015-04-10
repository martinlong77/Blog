<?php
class ApplyModel extends Model {
	Protected $_map = array(
	 'friend' => 'applicant_id',
	 'message' => 'apply_message',
	);

	Protected $_auto = array(
		array('apply_time', 'time', 1, 'function'),
		array('apply_message', 'strip_tags', 1, 'function'),
		array('apply_message', 'trim', 1, 'function'),
		array('apply_status', '1'),				
	);	
}