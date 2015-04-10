<?php
class TeacherViewModel extends ViewModel {
	Public $viewFields = array(
		'Teacher' => array('t_graduate' => 'graduate','t_type' => 'type','t_area' => 'area','t_warea' => 'warea','t_experience' => 'exp','t_subject' => 'subject','t_msubject' => 'msubject'),
		'Member' => array('mid' => 'id','m_realname' => 'name','m_sex' => 'sex','m_un' => 'un','m_reg_time' => 'reg_time','m_log_time' => 'log_time','m_qq' => 'qq', 'm_tel' => 'tel','m_birth' => 'birth', 'm_status' => 'status','m_email' => 'email' ,'_on' => 'Teacher.mid = Member.mid'),
		);
}