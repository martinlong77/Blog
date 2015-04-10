<?php
class LessonViewModel extends ViewModel {
	Public $viewFields = array(
		//'Lesson' => array('id','name','p_lesson','status','createtime'),
		'Lesson' => array('id','name','l_type','click','status','createtime'),
		'Company' => array('c_abbr','_on' => 'lesson.cid = company.cid'),
		'Keyword' => array('name' => 't_name','_on' => 'lesson.type = keyword.id'),
		);
}