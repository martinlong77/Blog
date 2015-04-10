<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class ContentAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();		
		$this->menu_db        = D('Menu');
		$this->article_db     = M('Article');
		$this->article_img_db = M('ArticleImg');		
		$this->type_db        = M('Type');
		$this->lesson_view_db = D('LessonView');
		$this->lesson_db      = M('Lesson');
		$this->banner_db      = M('Banner');
		$this->official_db    = M('Official');
	}
	
	//文章列表
	public function article_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->article_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->article_db->field('id,title,createtime,type,keywords,click')->order($order)->limit($limit)->select();
		  
		  for ($i=0; $i < count($list); $i++) { 
		  	$list[$i]['createtime'] = date('Y-m-d H:i',$list[$i]['createtime']);
		  	$list[$i]['type'] = $this->type_db->where(array('number' => $list[$i]['type']))->getField('name');
		  }
    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}

	//增改文章
	public function article_operate(){
		$userid = session('userid');
		$data = $_POST;
		$data['content'] = stripcslashes($data['content']);
		if ($this->isPost()){
			$id = $data['id'];
			$img_list = img_match($data['content']);
			//若没有上传封面且文中有图片则自动用第一张图片作为封面
			if(empty($data['cover']) && $img_list[0]['src']) $data['cover'] = $img_list[0]['src'];	//空间不够大,直接用url来发表

			//if(empty($data['cover']) && $img_list[0]['src']) $data['cover'] = path_change($img_list[0]['src'], 'display', 0);	//上传保存到空间,前提是空间够大 

			if (!$id) {		//发表新文章	
				if ($data['activity']) {
					$activity['title'] = $data['title'];
					$activity['content'] = $data['content'];
					$activity['cover'] = $data['cover'];
					$activity['type'] = 2;
					$activity['atype'] = 2;
					$activity['akind'] = 1;
					$activity['time'] = time();
					$this->official_db->add($activity);
				}

				unset($data['activity']);
				unset($data['id']);
				$data['createtime'] = time();
															
				if ($id = $this->article_db->add($data)) {										
					for ($i=0; $i < count($img_list); $i++) {
						$path = path_change($img_list[$i]['src']);	

						$article_img_data = array('aid' => $id,'path' => $path); 
						$this->article_img_db->add($article_img_data);
					}


					$this->ajaxReturn(U('Content/article_list'),'恭喜您！新文章发表成功！', 1);
				} else {
					$this->ajaxReturn(0,'很遗憾！发表失败！',0);
				}
			    
			} else {	//修改文章				
				unset($data['activity']);
				if ($this->article_db->save($data) !== false) {	//全等判断不会才不会返回假，用于前台判断			
					$ext_list = $this->article_img_db->where(array('aid' => $id))->field('aid',true)->select();//查找出当前文章包含的所有图片
					for ($i=0; $i < count($ext_list); $i++) { 
						$ext_id_path[$ext_list[$i]['id']] = $ext_list[$i]['path']; //将图片以文章ID=>路径path的形式保存到数组ext_list
					}

					//此时会产生两种情况，第一种是匹配到的ID，即本次操作没有改变的图片ID。第二种是没有匹配到的ID，即本次改变的ID，目的是把那些匹配到的ID所在元素删掉，然后删除剩下没有匹配到ID的图片和数据。将没有匹配到的路径组成一个数组，添加到数据表中。										
					for ($i=0; $i < count($img_list); $i++) {
						$path[$i] = path_change($img_list[$i]['src']);

						$ext_id = array_search($path[$i], $ext_id_path);	//匹配到的ID
						if ($ext_id) {	//若存在则执行删除操作
							unset($ext_id_path[$ext_id]);	//将匹配到的元素删掉，剩下要删除的ID	
							unset($path[$i]);	//删除匹配到的路径
						}
						$ext_id = '';	//清空以继续操作																
					}

					if ($ext_id_path) {	//删除不存在的图片
						$map['id'] = $id;
						$current_cover = $this->article_db->where($map)->getField('cover');	//查找当前封面

						$current_relative_cover = path_change($data['cover']);	
						foreach ($ext_id_path as $key => $value) {
						  $del_id .= $key.',';	//生成要删除的ID
						  if ($current_relative_cover == $value) $this->article_db->where($map)->setField('cover',''); 
						  @unlink($value);	//删除图片
						}

						//若文章中还有图片则补上空白封面
				 		//if($img_list[0]['src']) $this->article_db->where($map)->setField('cover',path_change($img_list[0]['src'], 'display', 0)); //空间够大可用

				 		if($img_list[0]['src']) $this->article_db->where($map)->setField('cover',$img_list[0]['src']); //空间不够大						

						$del_id = substr($del_id, 0,-1);
						$this->article_img_db->where(array('id' => array('IN', $del_id)))->delete();	//删除图片数据
					}

					if ($path) {	//添加新图片						
						foreach ($path as $key => $value) {
						  $article_img_data = array('aid' => $id,'path' => $value);		
						  $this->article_img_db->add($article_img_data);
						}	
					}
					

					$this->ajaxReturn(U('Content/article_list'),'文章修改成功', 1);
				} else {
					$this->ajaxReturn(0,'修改失败！',0);
				}
			}			
			
		}else {
			$id = I('get.id');			
			$currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
			$article = $this->article_db->where(array('id'=>$id))->find();
			if ($article['relative_path']) $article['relative_path'] = path_change($article['cover']);	
			$type = $this->type_db->order('number DESC')->select();			

			$this->assign('type',$type);
			$this->assign('article',$article);
	    	$this->assign(currentpos, $currentpos);
			$this->display('article_operate');
		}
	}
	
	//删除文章
	public function article_delete() {
		$aid = I('post.id');		
		$path_list = $this->article_img_db->where(array('aid' => $aid))->field('path')->select();	//查找文章相关图片路径

		if ($path_list) {	//若存在则执行删除图片操作
			for ($i=0; $i < count($path_list); $i++) {	//删除图片路径 
				@unlink($path_list[$i]['path']);
			}

			$img_del_result = $this->article_img_db->where(array('aid' => $aid))->delete();	//删除数据库中图片信息
		}
		//删除封面文件
		$cover = $this->article_db->where(array('id' => $aid))->getField('cover');	

		$cover = path_change($cover);
		@unlink($cover);	
		$result = $this->article_db->where(array('id' => $aid))->delete();
		$result ? $this->success('删除成功') : $this->error('没有数据或已删除过了，请稍后再试');
	}


	//优惠课列表
	public function preferential_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->lesson_view_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->lesson_view_db->order($order)->limit($limit)->select();
		  
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

		  	 	case '3' :
		  	 		$list[$i]['status'] = '右侧推荐';
		  	 		break;

		  	 	case '4' :
		  	 		$list[$i]['status'] = '右侧推荐+首页展示';
		  	 		break;		
		  	 }

		  	 switch ($list[$i]['l_type']) {
		  	 	case '1':
		  	 		$list[$i]['l_type'] = '普通课程';
		  	 		break;
		  	 	
		  	 	case '2':
		  	 		$list[$i]['l_type'] = '优惠课程';
		  	 		break;

		  	 	case '3':
		  	 		$list[$i]['l_type'] = '特色课程';
		  	 		break;	
		  	 }

		  	 $list[$i]['createtime'] = date('Y-m-d H:i:s',$list[$i]['createtime']);
		  	 //$list[$i]['p_lesson'] == 1 ? $list[$i]['p_lesson'] = '是' : $list[$i]['p_lesson'] = '否';  	 
		  }

    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}

	public function banner_list($page=1, $rows=10, $sort = 'id', $order = 'DESC'){
		if($this->isPost()){
		  $limit = ($page - 1) * $rows . "," . $rows;
		  $total = $this->banner_db->count();
		  $order = $sort.' '.$order;
		  $list = $this->banner_db->order($order)->limit($limit)->select();
		  
		  for ($i=0; $i < count($list); $i++) {
		  	switch ($list[$i]['type']) {
		  		case '0':
		  			$list[$i]['type'] = '机构'; 
		  			break;
		  		
		  		case '1':
		  			$list[$i]['type'] = '优惠课'; 
		  			break;

		  		case '2':
		  			$list[$i]['type'] = '活动';
		  			break;	
		  	}		  	
		  }

    	  $data = array('total'=>$total, 'rows'=>$list);    	  
    	  $this->ajaxReturn($data);
    	} else {  
    	  $currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
		  $this->assign(currentpos, $currentpos);	
		  $this->display();	  
		}
	}	

	public function banner_operate(){
		$userid = session('userid');
		$data = $_POST;
		if ($this->isPost()){
			$id = $data['id'];
			unset($data['id']);
			//$exist_level = $this->banner_db->where(array('level'))
			if ($id) {
				$result = $this->banner_db->where(array('id' => $id))->save($data);
				$result !== false ? $this->success('修改成功') : $this->error('修改失败');
			} else {
				$result = $this->banner_db->add($data);
				$result !== fales ? $this->success('添加成功') : $this->error('添加失败');
			}
			
		}else {
			$id = I('get.id');			
			$currentpos = $this->menu_db->currentPos(I('get.menuid'));  //栏目位置
			$banner = $this->banner_db->where(array('id'=>$id))->find();
			$banner['relative_path'] = path_change($banner['img']);	

			$level = $this->banner_db->where(array('type' => $banner['type']))->count();
			if(!$level) $level = $this->banner_db->where(array('type' => 0))->count();
			if($level <= 1) $level = $level+1;	

			$this->assign('level',$level);
			$this->assign('banner',$banner);
	    	$this->assign(currentpos, $currentpos);
			$this->display('banner_operate');
		}	
	}

	public function get_level(){
		$count = $this->banner_db->where(array('type' => I('post.type')))->count();
		if($count <= 1) $count = $count+1;
		for ($i=0; $i < $count; $i++) { 
			$dis = $i+1;
			echo '<option value="'.$dis.'">'.$dis.'</option>';
		}
	}

	public function delete_bn(){
		$id = I('post.id');		
		//删除封面文件
		$img = $this->banner_db->where(array('id' => $id))->getField('img');	

		$img = path_change($img);
		@unlink($img);	
		$result = $this->banner_db->where(array('id' => $id))->delete();
		$result ? $this->success('删除成功') : $this->error('没有数据或已删除过了，请稍后再试');		
	}

	//优惠课操作
	public function lesson_operate($type){
		$map = array('id' => I('post.id'));
		switch ($type) {
			case 'status':
				$status = $this->lesson_db->where($map)->getField('status');
				$status == 1 ? $status = 0 : $status = 1;
				$r = $this->lesson_db->where($map)->setField('status',$status);
				break;
			
			case 'show':
				$show = $this->lesson_db->where($map)->field('type,status')->find();
				$show_limit = 10;
				$bmap['_string'] = '(status = 2 OR status = 4) AND type = '.$show['type'].'';
				$show_count = $this->lesson_db->where($bmap)->count();
				if (($show['status'] == 0 || $show['status'] == 1 || $show['status'] == 3) && $show_count >= $show_limit) $this->error('首页展示数已达上限，请取消其他展示课程再设置。');

				switch ($show['status']) {
					//同时展示status==4
					case '3':	
						$status = 4;
						break;

					//取消首页展示保留recommend展示status==3	
					case '4':
						$status = 3;
						break;	
											
					default:
						$show['status'] == 2 ? $status = 0 : $status = 2;
						break;
				}
					
				$r = $this->lesson_db->where($map)->setField('status',$status);	
				break;

			case 'recommend':
				$recommend = $this->lesson_db->where($map)->getField('status');
				$show_limit = 5;
				$bmap['_string'] = 'status = 3 OR status = 4';
				$show_count = $this->lesson_db->where($bmap)->count();
				if (($recommend == 0 || $recommend == 1 || $recommend == 2) && $show_count >= $show_limit) $this->error('侧边栏展示数已达上限，请取消其他展示课程再设置。');

				switch ($recommend) {
					case '2':
						$r = $this->lesson_db->where($map)->setField('status', 4);
						break;

					case '3':
						$r = $this->lesson_db->where($map)->setField('status', 0);
						break;
					
					case '4':
						$r = $this->lesson_db->where($map)->setField('status', 2);
						break;

					default:
						$r = $this->lesson_db->where($map)->setField('status', 3);
						break;	

				}				

				break;	

			case 'setp':
					$lesson = $this->lesson_db->field('status,p_lesson')->where($map)->find();
					if ($lesson['status'] == 1) $this->lesson_db->where($map)->setField('status', 0);
					$lesson['p_lesson'] == 1 ? $p_lesson = 0 : $p_lesson = 1;
					$r = $this->lesson_db->where($map)->setField('p_lesson', $p_lesson);
					break;	

			case 'delete':
				$arr_path = $this->lesson_db->where($map)->field('cover,content')->find();
				$img_list = img_match($arr_path['content']);
				for ($i=0; $i < count($img_list); $i++) {
					$path = path_change($img_list[$i]['src']);	

					@unlink($path);	//删除宣传中的每一张图
				}

				$cover_path = path_change($arr_path['cover']);
				@unlink($cover_path);	//删除封面

				$r = $this->lesson_db->where($map)->delete();				
				break;	
		}

		$r ? $this->success('操作成功') : $this->error('操作失败');
	}
	
	public function upload_cover(){		//上传封面
		import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize = 1024*1024;     //设置上传大小
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg', 'bmp');    //设置允许的类型
        $upload->saveRule = 'uniqid';
        $upload->autoSub = true;
        $upload->subType = 'date';
        $upload->savePath = './Uploads/';  //设置路径

        if ($upload->upload()) {
          $img = $upload->getUploadFileInfo();
          //$data['dis_path'] = '/aijiajiao/Uploads/'.$img[0]['savename'];	//本地
          $data['dis_path'] = '/Uploads/'.$img[0]['savename'];	//网站
          $data['save_path'] = './Uploads/'.$img[0]['savename'];
          $this->ajaxReturn($data);         
          //echo '<img id="a_cover" src="'.$dis_path.'" width="150px"  /><input type="hidden" id="save_path" value="'.$save_path.'" />';
        } else {
          $this->error($upload->getErrorMsg());
        }           
	}

	public function delete_cover($type){	//删除封面		
		$path = I('post.path');		
		$map['id'] = I('post.id'); 
		@unlink($path);

		switch ($type) {
			case 'article':
				$this->article_db->where($map)->setField('cover','') !== false ? $this->ajaxReturn(1,'删除成功！',1) : $this->ajaxReturn(0,'删除失败！原图不存在或您已经删除过了！',0);
				break;
			
			case 'banner':
				$this->banner_db->where($map)->setField('img','') !== false ? $this->ajaxReturn(1,'删除成功！',1) : $this->ajaxReturn(0,'删除失败！原图不存在或您已经删除过了！',0);
				break;

			case 'gh':
				$this->official_db->where($map)->setField('cover','') !== false ? $this->ajaxReturn(1,'删除成功！',1) : $this->ajaxReturn(0,'删除失败！原图不存在或您已经删除过了！',0);
				break;	
		}			
	}
}
