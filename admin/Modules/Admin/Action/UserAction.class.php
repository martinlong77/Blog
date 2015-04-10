<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class UserAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();		
		$this->menu_db        = D('Menu');
		$this->company_db     = M('Company');
		$this->member_db      = M('Member');	
		$this->lesson_db	  = M('Lesson');	
		$this->teacher_db     = D('TeacherView');	
		$this->recruit_db 	  = M('Recruit');
	}
	
	//机构列表
	public function company_list($page=1, $rows=10, $sort = 'cid', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->company_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->company_db->order($order)->field('cid as id,c_name as name,c_abbr as abbr,c_tel as tel,c_status as status,c_show as ushow,c_type as type,c_reg_time as reg_time,c_log_time as log_time,c_qq as qq,c_qq_group as qq_group,c_email as email,c_un as un,c_district as district,c_web_link as web_link')->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) {
		  	switch ($list[$i]['status']) {
		  		case '0':
		  			$list[$i]['status'] = '正常';
		  			break;
		  		
		  		case '1':
		  			$list[$i]['status'] = '停用';
		  			break;

		  		case '2':
		  			$list[$i]['status'] = '首页展示';
		  			break;		
		  	}

		  	switch ($list[$i]['type']) {
		  		case '1':
		  			$list[$i]['type'] = '才艺类';
		  			break;
		  		
		  		case '2':
		  			$list[$i]['type'] = '学科类';
		  			break;

		  		case '3':
		  			$list[$i]['type'] = '才&学';
		  			break;	
		  	}

		  	$list[$i]['ushow'] == 1 ? $list[$i]['ushow'] = '否' : $list[$i]['ushow'] = '是';

		  	$list[$i]['reg_time'] = date('Y-m-d',$list[$i]['reg_time']);
		  	$list[$i]['log_time'] = date('Y-m-d',$list[$i]['log_time']);
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

	//用户列表
	public function member_list($page=1, $rows=10, $sort = 'mid', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->member_db->count();
		  $order = $sort.' '.$order;
		  $tid_list = M('Teacher')->field('mid')->select();
		  for ($i=0; $i < count($tid_list); $i++) { 
		  	$tid .= $tid_list[$i]['mid'].','; 
		  }
		  $tid = substr($tid, 0, -1);

		  $list = $this->member_db->order($order)->where(array('mid' => array('not in', $tid)))->field('mid as id, m_un as un,m_qq as qq,m_email as email,m_birth as birth, m_nickname as name,m_realname as realname,m_tel as tel, m_status as status, m_reg_time as reg_time,m_log_time as log_time,m_sex as sex')->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) { 
		  	$list[$i]['status'] == 1 ? $list[$i]['status'] = '封禁' : $list[$i]['status'] = '正常';
		  	$list[$i]['reg_time'] = date('Y-m-d H:i:s',$list[$i]['reg_time']);
		  	$list[$i]['log_time'] = date('Y-m-d H:i:s',$list[$i]['log_time']);
		  }

    	  $data = array('total'=>$total, 'rows'=>$list); 
    	  $this->ajaxReturn($data);

    	} else {  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}

	//老师列表
	public function teacher_list($page=1, $rows=10, $sort = 't_id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->teacher_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->teacher_db->order($order)->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) {
		  	$list[$i]['detail'] = '<p>所属单位：'.$list[$i]['graduate'].'</p><p>教师类型：'.$list[$i]['type'].'</p><p>所在区域：'.$list[$i]['area'].'</p><p>授课区域：'.$list[$i]['warea'].'</p><p>教学经验：'.$list[$i]['exp'].'年</p><p>主要辅导：'.$list[$i]['msubject'].'</p><p>辅导科目：'.$list[$i]['subject'].'</p>';
		  	$list[$i]['status'] == 1 ? $list[$i]['status'] = '封禁' : $list[$i]['status'] = '正常';
		  	$list[$i]['reg_time'] = date('Y-m-d',$list[$i]['reg_time']);
		  	$list[$i]['log_time'] = date('Y-m-d',$list[$i]['log_time']);
		  }

    	  $data = array('total'=>$total, 'rows'=>$list); 
    	  $this->ajaxReturn($data);

    	} else {  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}

	//招聘信息
	public function recruit_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->recruit_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->recruit_db->order($order)->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) { 
		  	$list[$i]['status'] == 1 ? $list[$i]['status'] = '显示中' : $list[$i]['status'] = '未显示';
		  	$list[$i]['cname'] = $this->company_db->where(array('cid' => $list[$i]['cid']))->getField('c_abbr');
		  	if (!$list[$i]['cname']) $list[$i]['cname'] = '【官方】爱家教';
		  }

    	  $data = array('total'=>$total, 'rows'=>$list); 
    	  $this->ajaxReturn($data);

    	} else {  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}

	public function recruit_operate() {
	  if ($this->isPost()){	
	  	$action = I('get.action');
	  	$data = $_POST;
		$id = $data['id'];
		$map = array('id' => $id);
		if ($action == 'del') {
			$result = $this->recruit_db->where($map)->delete();
			$result !== false ? $this->success('删除成功') : $this->error('删除失败');

		} elseif ($action == 'status') {
			$status = $this->recruit_db->where($map)->getField('status');
			$limit = 8;
			$count = $this->recruit_db->where(array('status' => 1))->count();
			if ($status == 0 && $count >= $limit) $this->error('首页展示数已达上限，请取消其他展示机构再设置。');		
			$status == 1 ? $r = $this->recruit_db->where($map)->setField('status', 0) : $r = $this->recruit_db->where($map)->setField('status', 1);

			$r ? $this->success('操作成功') : $this->error('操作失败');

		} else {								
			$data['cid'] = 0;
			$data['push_time'] = date('Y-m-d');
			unset($data['id']);
			if ($id) {
				$result = $this->recruit_db->where($map)->save($data);
				$result !== false ? $this->success('修改成功') : $this->error('修改失败');
			} else {
				$result = $this->recruit_db->add($data);
				$result !== fales ? $this->success('添加成功') : $this->error('添加失败');
			}
		}
	  } else {
	  	$id = I('get.id');			
		$currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		$recruit = $this->recruit_db->where(array('id'=>$id))->find();

		$this->assign('recruit', $recruit);
		$this->assign(currentpos, $currentpos);
		$this->display('recruit_operate');
	  }		
	}

	public function user_operate($type){		//用户操作
		$id = I('post.id');
		$a = I('get.action');
		if ($type == 'c') {
			$map = array('cid' => $id);			
			switch ($a) {
			  case 'show'://首页展示	
				$show = $this->company_db->field('c_type,c_status')->where($map)->find();
				$show_limit = 20;
				$smap['_string'] = 'c_status = 2 AND (c_type = '.$show['c_type'].' OR c_type = 3)';
				$show_count = $this->company_db->where($smap)->count();
				if (($show['c_status'] == 0 || $show['c_status'] == 1) && $show_count >= $show_limit) $this->error('首页展示数已达上限，请取消其他展示机构再设置。');

				if ($show['c_status'] == 1) {	//将所有该机构停用的课程重新启用
					$map['status'] = 1;	
					$this->lesson_db->where($map)->setField('status', 0);
				}

				$show['c_status'] == 2 ? $r = $this->company_db->where($map)->setField('c_status', 0) : $r = $this->company_db->where($map)->setField('c_status', 2);

				break;							
 		
			  case 'status'://停用账户
				$status = $this->company_db->where($map)->getField('c_status');
				if($status == 1){
				 	$r = $this->company_db->where($map)->setField('c_status', 0);
				 	$smap['status'] = 1;//将所有该机构停用的课程重新启用
					$this->lesson_db->where($smap)->setField('status', 0);

				} else {
				 	$r = $this->company_db->where($map)->setField('c_status', 1);
				 	$this->lesson_db->where($map)->setField('status', 1);	//停用该机构的所有课程			
				}
				break;

			  case 'ushow':
			  	$ushow = $this->company_db->where($map)->getField('c_show');
				$ushow == 1 ? $r = $this->company_db->where($map)->setField('c_show', 0) : $r = $this->company_db->where($map)->setField('c_show', 1);		
			  	break;	

			  case 'delete':	
				$arr_path = $this->company_db->where($map)->field('c_logo,c_banner,c_intro')->find();	//查找相关LOGO BANNER图片路径

				$img_list = img_match($arr_path['c_intro']);
				for ($i=0; $i < count($img_list); $i++) {
					$path = path_change($img_list[$i]['src']);	

					@unlink($path);
				}

				$logo_path = path_change($arr_path['c_logo']);
				$banner_path = path_change($arr_path['c_banner']);	
				@unlink($logo_path);
				@unlink($banner_path);

				$r = $this->company_db->where($map)->delete();
			  	break;		
	
			}		

		} else {
			$mmap = array('mid' => $id);
			if ($a == 'status') {				
				$status = $this->member_db->where($mmap)->getField('m_status');
				$status == 1 ? $r = $this->member_db->where($mmap)->setField('m_status', 0) : $r = $this->member_db->where($mmap)->setField('m_status', 1);				
			} else {

				$ava_path = $this->member_db->where($mmap)->getField('m_avatar');	//查找图片路径
				$ava_path = path_change($ava_path);
				unlink($ava_path);	//删除头像
				M('Teacher')->where($mmap)->delete();

				$r = $this->member_db->where($mmap)->delete();
			}				
		}

		$r ? $this->success('操作成功') : $this->error('操作失败');
	}
}
