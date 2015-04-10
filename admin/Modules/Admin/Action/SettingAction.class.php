<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class SettingAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();
		$this->setting_db    = M('Setting');
		$this->keyword_db    = D('Keyword');
		$this->menu_db       = D('Menu');		
		$this->admin_db      = D('Admin');
	}
	
	public function site(){
		$userid = session('userid');
		if ($this->isPost()){
			$data = $_POST;
			if($this->setting_db->where(array('id' => 1))->save($data)) {
				$this->success('网站信息修改成功');
			} else {
				$this->error('网站信息修改失败');
			}
		}else {
			$currentpos = $this->menu_db->currentPos($this->_get('menuid'));  //栏目位置
			$info = $this->setting_db->find();
			if($info['img1']) $info['relative_path1'] = path_change($info['img1']);
			if($info['img2']) $info['relative_path2'] = path_change($info['img2']);
			//dump($info);
			$this->assign('info',$info);
	    	$this->assign(currentpos, $currentpos);
			$this->display();
		}
	}

	public function delete_img(){	//删除封面		
		$path = I('post.path');
		unlink($path);
		$this->setting_db->where('id = 1')->setField(I('post.img'),'') !== false ? $this->ajaxReturn(1,'删除成功！',1) : $this->ajaxReturn(0,'删除失败！找不到原图不存在或您已经删除过了！',0);			
	}

	//关键词列表
	public function keyword_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->keyword_db->where('parent_id > 0')->count();
		  $order = $sort.' '.$order;
		  $list = $this->keyword_db->order($order)->where('parent_id > 0')->limit($limit)->select();
		  for ($i=0; $i < count($list); $i++) { 
		  	$list[$i]['parent'] = $this->keyword_db->where(array('id' => $list[$i]['parent_id']))->getField('name');
		  	$list[$i]['status'] == 1 ? $list[$i]['status'] = '是' : $list[$i]['status'] = '否';
		  }
		  
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}

	public function keyword_operate(){
		$userid = session('userid');
		$data = $_POST;
		if ($this->isPost()){
			if (!$this->keyword_db->create()) $this->error($this->keyword_db->getError()); 

			$id = $data['id'];
			if (!$id) {
				$this->keyword_db->add() ? $this->ajaxReturn(U('Setting/keyword_list'),'添加成功！', 1) : $this->error('添加失败');
			} else {
				$this->keyword_db->save() !== false ? $this->ajaxReturn(U('Setting/keyword_list'),'修改成功！', 1) : $this->error('修改失败');
			}
			
		}else {
			$id = I('get.id');			
			$currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
			$keyword = $this->keyword_db->where(array('id'=>$id))->find();
			$parent_list = $this->keyword_db->limit(4)->field('id,name')->select();		

			$this->assign('parent_list',$parent_list);
			$this->assign('keyword',$keyword);
	    	$this->assign(currentpos, $currentpos);
			$this->display('keyword_operate');
		}
	}

	public function keyword_show(){
		$id = I('post.id');
		$map = array('id' => $id);
		$kw = $this->keyword_db->where($map)->field('status,parent_id')->find();
		$now = $this->keyword_db->where(array('parent_id' => $kw['parent_id'],'status' => 1))->count();
		if ($kw['status'] == 0 && $now >= 6) $this->error('首页展示已超过最大数量限制!');

		$kw['status'] == 1 ? $result = $this->keyword_db->where($map)->setField('status',0) : $result = $this->keyword_db->where($map)->setField('status',1);
		$result !== false ? $this->success('成功') : $this->error('设置失败，请联系管理员。');
	}

	public function core_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->keyword_db->where('parent_id < 0')->count();
		  $order = $sort.' '.$order;
		  $list = $this->keyword_db->order($order)->where('parent_id < 0')->limit($limit)->select();
		  
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {  
		  $this->display();	  
		}
	}

	public function core_operate(){
		$userid = session('userid');
		$data = $_POST;
		if ($this->isPost()){
			if (!$this->keyword_db->create()) $this->error($this->keyword_db->getError()); 

			$id = $data['id'];
			if (!$id) {
				$this->keyword_db->add() ? $this->ajaxReturn(U('Setting/core_list'),'添加成功！', 1) : $this->error('添加失败');
			} else {
				$this->keyword_db->save() !== false ? $this->ajaxReturn(U('Setting/core_list'),'修改成功！', 1) : $this->error('修改失败');
			}
			
		}else {
			$id = I('get.id');			
			$currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
			$keyword = $this->keyword_db->where(array('id'=>$id))->find();
			$parent_list = array(array('id' => -1,'name' => '分类'), array('id' => -2,'name' => '等级'),array('id' => -3,'name' => '区域'));		

			$this->assign('parent_list',$parent_list);
			$this->assign('keyword',$keyword);
	    	$this->assign(currentpos, $currentpos);
			$this->display('core_operate');
		}
	}

	public function delete_kw() {
		$result = $this->keyword_db->where(array('id' => I('post.id')))->delete();
		$result ? $this->success('删除成功') : $this->error('没有数据或已删除过了，请稍后再试');
	}
}
