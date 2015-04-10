<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class OtherAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();		
		$this->menu_db       = D('Menu');
		$this->ab_db    = M('Ab');
		$this->msg_db     = D('MessageView');	
		$this->evaluate_db	 = D('EvaluateView');
		$this->release_db	 = M('Release');		
		$this->qna_db    = M('Qna');
		$this->comment_db    = M('ArticleComment');
		$this->transaction_db    = D('TransactionView');
		$this->ct_db    = D('CompanyTeacherView');
	}

	//评论列表
	public function comment_list($page=1, $rows=10, $sort = 'id', $order = 'DESC') {
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->comment_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->comment_db->order($order)->field('aid',true)->limit($limit)->select();

		  $Member = M('Member');
		  $Company = M('Company');
		  for ($i=0; $i < count($list); $i++) {
		  	if($list[$i]['utype'] == 1){
		  		$list[$i]['name'] = $Company->where(array('cid' => $list[$i]['uid']))->getField('c_abbr');
		  	} else {		
		  		$list[$i]['name'] = $Member->where(array('mid' => $list[$i]['uid']))->getField('m_nickname');
		  	}	
		  }
		  //dump($list);
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {     	  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}
	
	//活动公告
	public function gh_list($page=1, $rows=10, $sort = 'ab_id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->ab_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->ab_db->order($order)->field('ab_id as id,ab_title as title,ab_end as end,ab_type as type,ab_akind as akind,ab_atype as atype,ab_time as time,ab_payed_count as payed_count,ab_click as click')->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) {
		  	$list[$i]['type'] == 1 ? $list[$i]['type'] = '公告' : $list[$i]['type'] = '活动';
		  	if ($list[$i]['akind']){ 
		  	  	$list[$i]['akind'] == 1 ? $list[$i]['akind'] = '资讯' : $list[$i]['akind'] = '策划';
		  	}

		  	switch ($list[$i]['atype']) {
		  		case '1':
		  			$list[$i]['atype'] = '游学活动';
		  			break;
		  		
		  		case '2':
		  			$list[$i]['atype'] = '假日档期';
		  			break;

		  		case '3':
		  			$list[$i]['atype'] = '才艺剧场';
		  			break;

		  		case '4':
		  			$list[$i]['atype'] = '亲子活动';
		  			break;	

		  		default:
		  			$list[$i]['atype'] = '公告';
		  			break;		
		  	}

		  	$list[$i]['enrolled'] = 0;
		  	$list[$i]['time'] = date('Y-m-d H:i:s',$list[$i]['time']);
		  	$list[$i]['end'] = date('Y-m-d',$list[$i]['end']);
		  }
		  //dump($list);
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {     	  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}

	public function message_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->msg_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->msg_db->order($order)->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) {
		  	if ($list[$i]['send'] == 1){
		  	  $list[$i]['sname'] = $list[$i]['mname'];
		  	  $list[$i]['rname'] = $list[$i]['cname'];	
		  	  $list[$i]['send'] = '个人';
		  	} else {
		  	  $list[$i]['sname'] = $list[$i]['cname'];
		  	  $list[$i]['rname'] = $list[$i]['mname'];
		  	  $list[$i]['send'] = '机构';
		  	} 

		  	$list[$i]['time'] = date('Y-m-d H:i:s',$list[$i]['time']);
		  }
		  //dump($list);
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {     	  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}		
	}

	public function evaluate_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->evaluate_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->evaluate_db->order($order)->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) {
		  	$list[$i]['time'] = date('Y-m-d H:i:s',$list[$i]['time']);
		  }
		  //dump($list);
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {     	  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}		
	}

	public function qna_list($page=1, $rows=10, $sort = 'q_id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->qna_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->qna_db->order($order)->field('q_id as id,q_title as title,q_time as time,q_click as click,r_id,u_type,u_id')->limit($limit)->select();

		  $Member = M('Member');
      	  $Company = M('Company');
		  for ($i=0; $i < count($list); $i++) {
		  	$list[$i]['time'] = date('Y-m-d H:i:s',$list[$i]['time']);
		  	if ($list[$i]['r_id'] != 0) {
		  		$list[$i]['type'] = '回答';
		  		$list[$i]['title'] = '回答：'.$this->qna_db->where(array('q_id' => $list[$i]['r_id']))->getField('q_title');
		  	} else {		  	
		  		$list[$i]['type'] = '问题';
		  	}

		    $list[$i]['u_type'] == 1 ? $list[$i]['utype'] = '机构' : $list[$i]['utype'] = '个人';

		  	if ($list[$i]['u_type'] == 1) {
          		$user_info = $Company->where(array('cid' => $list[$i]['u_id']))->field('c_abbr as name')->find();
          		$list[$i]['name'] = $user_info['name'];
        	}

        	if ($list[$i]['u_type'] == 2) {
          		$user_info = $Member->where(array('mid' => $list[$i]['u_id']))->field('m_nickname as name')->find();
          		$list[$i]['name'] = $user_info['name'];
        	}
		  }
		  //dump($list);
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {     	  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}	
	}

	public function transaction_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->transaction_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->transaction_db->order($order)->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) {
		  	$list[$i]['pt'] = date('Y-m-d H:i:s',$list[$i]['pt']);
		  	$list[$i]['bt'] = date('Y-m-d H:i:s',$list[$i]['bt']);
		  	switch ($list[$i]['status']) {
		  		case '0':
		  			$list[$i]['status'] = '购买未付款';
		  			break;
		  		
		  		case '1':
		  			$list[$i]['status'] = '付款未确认';
		  			break;

		  		case '2':
		  			$list[$i]['status'] = '确认未评价';
		  			break;

		  		case '3':
		  			$list[$i]['status'] = '已评价';
		  			break;		
		  	}
		  }
		  //dump($list);
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {     	  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}

	public function teacher_list($page=1, $rows=10, $sort = 'tid', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->ct_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->ct_db->order($order)->limit($limit)->select();

		  //dump($list);
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {     	  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}	
	}

	public function release_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->release_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->release_db->field('id,lname,start,end,pt,pay')->order($order)->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) { 
		  	$list[$i]['start'] = date('Y-m-d',$list[$i]['end']);
		  	$list[$i]['end'] = date('Y-m-d',$list[$i]['end']);
		  }

		  
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {      
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}	
	}	

	public function delete($type){
		$id = I('post.id');
		switch ($type) {
			case 'evaluate':
				$result = M('Evaluate')->where(array('id' => $id))->delete();
				break;
			
			case 'gh':
				$Ab = M('Ab');
				$arr_path = $Ab->where(array('ab_id' => $id))->field('ab_content as content,ab_cover as cover')->find();
				$img_list = img_match($arr_path['content']);//查找内容中的图片
				for ($i=0; $i < count($img_list); $i++) {
					$path = path_change($img_list[$i]['src']);	

					@unlink($path);	//删除图片
				}

				$cover_path = path_change($arr_path['cover']);	
				@unlink($cover_path);

				$result = $Ab->where(array('ab_id' => $id))->delete();
				break;

			case 'msg':
				$result = M('Message')->where(array('id' => $id))->delete();
				break;

			case 'comment':
				$result = $this->comment_db->where(array('id' => $id))->delete();
				break;	
				
			case 'qna':
				$result = $this->qna_db->where(array('q_id' => $id))->delete();
				break;
				
			case 'teacher':
				$CT = M('CompanyTeacher');
				$avatar_path = $CT->where(array('tid' => $id))->getField('avatar');
				$avatar_path = path_change($avatar_path);	
				unlink($avatar_path);

				$result = $CT->where(array('tid' => $id))->delete();
				break;
				
			case 'transaction':
				$result = M('Transaction')->where(array('id' => $id))->delete();
				break;	

			case 'release':
				$result = M('Release')->where(array('id' => $id))->delete();
				break;				
		}

		$result ? $this->success() : $this->error('删除失败！');
	}

	public function BanUser($type) {
		$id = I('post.id');
		if ($type == 'msg') {
			$u_info = M('Message')->where(array('id' => $id))->field('cid,mid,send')->find();
			$u_info['send'] == 1 ? M('Member')->where(array('mid' => $u_info['mid']))->setField('m_status',1) : M('Company')->where(array('cid' => $u_info['cid']))->setField('c_status',1);

		} else {
			$u_info = $this->comment_db->where(array('id' => $id))->field('uid,utype')->find();
			$u_info['utype'] == 1 ? M('Company')->where(array('cid' => $u_info['uid']))->setField('c_status',1) : M('Member')->where(array('mid' => $u_info['uid']))->setField('m_status',1);
		}
		
	}
}