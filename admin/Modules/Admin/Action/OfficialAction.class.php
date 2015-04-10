<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class OfficialAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();		
		$this->menu_db       = D('Menu');
		$this->official_db    = M('Official');
	}
	
	//公告
	public function gg_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->official_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->official_db->order($order)->where(array('type = 1'))->field('id,title,time,click')->limit($limit)->select();

		  for ($i=0; $i < count($list); $i++) { 
		  	$list[$i]['time'] = date('Y-m-d',$list[$i]['time']);
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

	public function gg_operate(){
		$userid = session('userid');		
		if ($this->isPost()){
		  	$Official = D('Official');
      		if (!$Official->create()) $this->error($Official->getError());
		
			$id = I('post.id');
			if ($id) {
				$r = $Official->save();
        		$r !== falae ? $this->success('保存成功！') : $this->error('保存失败');
			} else {
				$Official->type = 1;
        		$id = $Official->add();
        		$id ? $this->ajaxReturn($id,'发表成功！',1) : $this->error('发表失败');
			}
			
		} else {		
			$currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
			$official = $this->official_db->where(array('id'=>I('get.id')))->find();	

			$this->assign('official',$official);
	    	$this->assign(currentpos, $currentpos);
			$this->display();
		}	
	}

	//活动
	public function hd_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->official_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->official_db->order($order)->where(array('type = 2'))->field('id,title,time,atype,akind,begin,end,payed_count,click')->limit($limit)->select();

		  $Enroll = M('Enroll');
		  for ($i=0; $i < count($list); $i++) {
		  	switch ($list[$i]['atype']) {
		  		case '1':
		  			$list[$i]['atype'] = '美食汇';
		  			break;
		  		
		  		case '2':
		  			$list[$i]['atype'] = '读书社';
		  			break;

		  		case '3':
		  			$list[$i]['atype'] = '旅游';
		  			break;

		  		case '4':
		  			$list[$i]['atype'] = '电影剧场';
		  			break;	
		  			
		  		case '5':
		  			$list[$i]['atype'] = '二手置换';
		  			break;				
		  	}

		  	$list[$i]['akind'] == 1 ? $list[$i]['akind'] = '资讯' : $list[$i]['akind'] = '策划';

		  	$list[$i]['enrolled'] = $Enroll->where(array('aid' => $list[$i]['id']))->count();
		  	$list[$i]['time'] = date('Y-m-d H:i:s',$list[$i]['time']);
		  	$list[$i]['begin'] == 0 ? $list[$i]['begin'] = '活动资讯' : $list[$i]['begin'] = date('Y-m-d',$list[$i]['begin']);
		  	$list[$i]['end'] == 0 ? $list[$i]['end'] = '活动资讯' : $list[$i]['end'] = date('Y-m-d',$list[$i]['end']);
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

	public function hd_operate(){
		$userid = session('userid');		
		if ($this->isPost()){
		  	$Official = D('Official');
		  	if (I('post.akind') == 1) {	//若添加资讯则不必输入开始结束时间
		  		$validate = array(
        			array('title','water','请输入标题',0,'callback'), 
        			array('content','water','请输入内容',0,'callback'),    
				);

				$Official->setProperty("_validate",$validate);
		  	}

      		if (!$Official->create()) $this->error($Official->getError());
      		$img_list = img_match($Official->content);
			//若没有上传封面且文中有图片则自动用第一张图片作为封面
			if(!$Official->cover && $img_list[0]['src']) $Official->cover = $img_list[0]['src'];	//空间不够大

			if ($Official->begin - $Official->end > 0 && I('post.akind') == 2) $this->error('未填写时间或开始时间大于结束时间！');	
			$id = I('post.id');		
			if ($id) {
				$r = $Official->save();
        		$r !== falae ? $this->success('保存成功！') : $this->error('保存失败');
			} else {
				$Official->type = 2;
        		$id = $Official->add();
        		$id ? $this->ajaxReturn($id,'发表成功！',1) : $this->error('发表失败');
			}
			
		} else {		
			$currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
			$official = $this->official_db->where(array('id'=> I('get.id')))->find();
			if ($official){
				$official['relative_path'] = path_change($official['cover']);
				$official['begin'] = date('Y-m-d',$official['begin']);	
				$official['end'] = date('Y-m-d', $official['end']);
			}	

			$this->assign('official',$official);
	    	$this->assign(currentpos, $currentpos);
			$this->display();
		}
	}

	public function del(){ //删除操作
		$id = I('post.id');
		$arr_path = $this->official_db->where(array('id' => $id))->field('content,cover')->find();
		$img_list = img_match($arr_path['content']);//查找内容中的图片
		for ($i=0; $i < count($img_list); $i++) {
			$path = path_change($img_list[$i]['src']);	

			@unlink($path);	//删除图片
		}

		$cover_path = path_change($arr_path['cover']);	
		@unlink($cover_path);

		$result = $this->official_db->where(array('id' => $id))->delete();
		$result ? $this->success() : $this->error('删除失败！');	
	}  			
}