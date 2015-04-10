<?php
class TransactionViewModel extends ViewModel {
	Public $viewFields = array(
		'Transaction' => array('id','buy_time' => 'bt','pay_time' => 'pt','deal_status' => 'status','num'),
		'Member' => array('m_nickname' => 'mname','_on' => 'Transaction.mid = Member.mid'),
		'Company' => array('c_abbr' => 'cname','_on' => 'Transaction.cid = Company.cid'),
		'Lesson' => array('name' => 'lname','_on' => 'Transaction.lid = Lesson.id'),
		);
}