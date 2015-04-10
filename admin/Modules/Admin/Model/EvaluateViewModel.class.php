<?php
class EvaluateViewModel extends ViewModel {
	Public $viewFields = array(
		'Evaluate' => array('id','content','time','score'),
		'Member' => array('m_nickname' => 'mname','_on' => 'Evaluate.mid = Member.mid'),
		'Company' => array('c_abbr' => 'cname','_on' => 'Evaluate.cid = Company.cid'),
		'Lesson' => array('name' => 'lname','_on' => 'Evaluate.lid = Lesson.id'),
		);
}