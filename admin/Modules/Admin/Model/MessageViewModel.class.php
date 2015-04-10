<?php
class MessageViewModel extends ViewModel {
	Public $viewFields = array(
		'Message' => array('id','send','content','time'),
		'Member' => array('m_nickname' => 'mname','_on' => 'Message.mid = Member.mid'),
		'Company' => array('c_abbr' => 'cname','_on' => 'Message.cid = Company.cid'),
		);
}