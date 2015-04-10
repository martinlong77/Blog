<?php
class CompanyTeacherViewModel extends ViewModel {
	Public $viewFields = array(
		'CompanyTeacher' => array('tid','name','experience','subject'),
		'Company' => array('c_name' => 'cname','_on' => 'CompanyTeacher.cid = company.cid'),
		);
}