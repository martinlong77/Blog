<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class EnrollAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();		
		$this->menu_db       = D('Menu');
		$this->enroll_db    = M('Enroll');
	}
	
	//公告
	public function enroll_list($page=1, $rows=20, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->enroll_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->enroll_db->order($order)->field('mid',true)->limit($limit)->select();

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

	public function del(){ //删除操作
		$id = I('post.id');		

		$result = $this->enroll_db->where(array('id' => $id))->delete();
		$result ? $this->success() : $this->error('删除失败！');	
	}  			
}