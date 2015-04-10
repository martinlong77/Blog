<?php
class PicAction extends Action {
  Public function proPic() {
    $id = I('get.id');
    $aid = I('get.aid');
    $uid = $_SESSION['id'];
    $Album = M("Album");
    $Image = M("Images");
    $userMap = array('user' => $id);     
    //如果没有传人ID返回 
    if (!$id) {
      $this->error('页面不存在');
    }        
      
    //若是否是查看专辑操作则显示专辑的图片
    if ($aid) {
          $imageMap = array('user' => $id,'image_album' => $aid);
          $albumMap = array('user' => $id,'album_id' => $aid);
          $albumInfo = $Album->where($albumMap)->field('album_name as name,album_create_time as time')->find();
          
          $imageCount = $Image->where($imageMap)->count();                         
          $albumInfo['count'] = $imageCount;  //将图片总数写入专辑信息数组
          $this->assign('albumInfo', $albumInfo); 

          if ($imageCount) { 
            $imageList = $Image->where($imageMap)->field('image_id,image_album,image_title,image_path')->limit(6)->order('image_id DESC')->select();

            $this->assign('imageList',$imageList);                        
          }                         
          
          $sideAlbumMap = array('user' => $id, 'album_id' => array('EGT', $aid));   //大于等于当前专辑的查询条件         
          $album = $Album->where($sideAlbumMap)->field('id,user,album_create_time', true)->order('album_id ASC')->limit(2)->select(); //专辑备选列表

          //备选专辑2为空则查询小于列表
          if (!$album[1]) {
            $sideAlbumMap = array('user' => $id, 'album_id' => array('ELT', $aid));
            $album = $Album->where($sideAlbumMap)->field('id,user,album_create_time', true)->order('album_id DESC')->limit(2)->select();
          }

          $this->assign('album', $album);

          //如果是自己的专辑则取出所有专辑列表提供转移专辑使用
          if ($id == $uid) {
            $selectAlbum = $Album->where($userMap)->field('album_id as aid,album_name as name')->order('album_id ASC')->select(); 
            $this->assign('selectAlbum',$selectAlbum);
          }   

        //不是查看专辑操作则显示专辑列表    
        } else {
            import("ORG.Util.Page");            
            $albumCount = $Album->where($userMap)->count(); 
            $p = new Page($albumCount, 6);       
            //取出分类
            $albumList = $Album->where($userMap)->field('id,album_create_time,album_key', true)->limit($p->firstRow.','.$p->listRows)->order('album_id DESC')->select();
            $p->setConfig('theme','%upPage% %linkPage% %downPage%');      //设置分页显示方式
            $page = $p->show();     //输出分页
            $this->assign('page', $page); 
            $this->assign('albumList', $albumList);                 
        }

        if ($id != $uid) {
          $info = getInfo($id);
        } else {
          $info = $_SESSION;         
          $albumMap = array('user' => $uid,'album_id' => array('GT',2));
          $albumSideList = $Album->where($albumMap)->field('album_id as cg_id,album_name as name')->order('album_id DESC')->select();

          $operaUrl = getUrlType('pic'); //分类操作url

          $this->assign('operaUrl', $operaUrl);
          $this->assign('cate_group',$albumSideList);
        }
            
        $navi = getNaviInfo($id,2); //导航条
        $navi['index'] = 1; //1显示登录后或特定页面的导航条
        $navi['jump'] = 1;  //用于JS判断删除文章是否跳转

        $this->assign('navi',$navi);     
        $this->assign('info', $info); 
        $this->display();
  }

  Public function image(){
        $id = I('get.id');
        $imageMap = array('image_id' => $id);        
        $Image = D("Images");
        $image = $Image->where($imageMap)->find();
        $uid = $image['user'];

        if (empty($image)) {
            $this->error('您要查看的页面不存在');
        }
        
        if (session('clickPic') != $id) {
            $Image->where($imageMap)->setInc('readcount');   //否则则增加1
            session('clickPic', $id);  //同时赋上ID到click 
        }

        //获取上下张图片信息
        $image['p'] = $Image->field('image_id,image_path')->where(array('user' => $uid,'image_id' => array('LT', $id),'image_album' => $image['image_album']))->order('image_id DESC')->find(); 
        $image['n'] = $Image->field('image_id,image_path')->where(array('user' => $uid,'image_id' => array('GT', $id),'image_album' => $image['image_album']))->order('image_id ASC')->find();

        $picCom = D("PicComView");
        $commentList = $picCom->where($imageMap)->select();

        $param = array(
          'result' => $commentList,      //分页用的数组或sql
          'listvar' => 'commentList',      //分页循环变量
          'listRows' => 10,     //每页记录数
          'target' => 'comment_list',  //ajax更新内容的容器id
          'pagesId' => 'fpage',    //分页后页的容器id不带
          'template' => 'Pub:comment_content',//ajax更新模板
        );
            
        R('Pub/AjaxPage',array($param));  

        //获取包括本专辑在内的两个专辑
        $Album = M("Album");
        $album = $Album->field('album_id,album_name,album_cover')->where(array('user' => $uid, 'album_id' => array('EGT',$image['image_album'])))->limit(2)->select();

        //如果第二个专辑是空的话，获取小于这个专辑的两个专辑
        if (!$album[1]) {
          $album = $Album->field('album_id,album_name,album_cover')->where(array('user' => $uid, 'album_id' => array('ELT',$image['image_album'])))->limit(2)->select();
        }

        $navi = getNaviInfo($uid,2); //导航条 
        $navi['comment_url'] = U('Pub/comment?do=pic');
        $navi['del_com_url'] = U('Pub/deleteCom?del=pic');
        $navi['index'] = 1; //1显示登录后或特定页面的导航条

        $this->assign('navi',$navi); //用于导航条ID       
        $this->assign('recommend', getRecommend()); //获取推荐博客
        $this->assign('album', $album);
        $this->assign('info', $image);
        $this->assign('fpage', $fpage);
        $this->display();
  }

  //上传新图片
  Public function poNew() {
      $do = I('get.do');
      $uid = $_SESSION['id'];
      $aid = I('post.cid');

      if ($do == 'po') {
        $uid = I('get.uid');  //FF下和uploadify冲突导致获取不到SESSION，需要从GET赋值
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize = 2097152;     //设置上传大小
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg', 'bmp');    //设置允许的类型
        $upload->saveRule = 'uniqid';
        $upload->autoSub = true;
        $upload->subType = 'date';
        $upload->savePath = './Uploads/pic/'.$uid.'/';  //设置路径

        if ($upload->upload()) {
          $img = $upload->getUploadFileInfo();
        } else {
          $this->error($upload->getErrorMsg());
        }           

        $_SESSION['uploadimages'][] = '/Uploads/pic/'.$uid.'/'.$img[0]['savename'];//将图片放进session，并以数组形式保存
        $data[] = '/Uploads/pic/'.$uid.'/'.$img[0]['savename'];

        $Image = M("Images");
        foreach($data as $key => $value){
          $info = pathinfo($value);
          $name = basename($value,'.'.$info['extension']);
          $imgData[$key] = array('user' => $uid, 'image_album' => $aid, 'image_title' => $name, 'image_description' => '暂无描述', 'image_createtime' => time(), 'image_path' => $value);
          $Image->add($imgData[$key]); 
        }  

        //如果专辑没有封面自动设置第一张上传图片为封面        
        $Album = M("Album");
        $albumMap = array('user' => $uid, 'album_id' => $aid);
        $cover = $Album->where($albumMap)->getField('album_cover');
        if (!$cover) {
          $Album->where($albumMap)->setField('album_cover', $data[0]);
        }
        unset($data);   

        //遍历显示图片
        foreach($_SESSION['uploadimages'] as $key => $value){
          echo '<div style="float:left;margin-left:30px;"><img src="'.$value.'" width="150px"  /><br><span class="rela_info">'.$name.'</span></div>';             
        }        
          
      } else {
        unset($_SESSION['uploadimages']);
        $Album = M("Album");
        $albumList = $Album->field('user,album_id,album_name')->where(array('user' => $uid))->order('album_id ASC')->select();

        $navi = getNaviInfo($uid,2); //导航条
        $navi['index'] = 1; //1显示登录后或特定页面的导航条

        $map = array('user' => $uid,'album_id' => array('GT',2));
        $editList = $Album->where($map)->field('album_id as cg_id,album_name as name')->order('album_id DESC')->select();

        $operaUrl = getUrlType('pic'); //分类操作url

        $this->assign('operaUrl', $operaUrl);
        $this->assign('cate_group',$editList);
        $this->assign('navi',$navi); //用于导航条ID  
        $this->assign('albumList', $albumList);
        $this->display();
      }         
  }

  //图片操作
  Public function edit() {
    $Album = D("Album");
    $Image = D("Images");
    $set = I('get.set');  //操作指令
    $id = I('post.id'); //图片ID
    $aid = I('post.aid'); //专辑ID
    $path = I('post.path'); //图片地址
    $uid = $_SESSION['id']; //用户ID
    $imageMap = array('image_id' => $id); //图片查询条件
    $albumMap = array('user' => $uid, 'album_id' => $aid);  //专辑查询条件                        

    switch ($set) {
      case 'cover': 
        if ($Album->where($albumMap)->setField('album_cover', $path)) {                    
          $this->success('封面设置成功');
        } else {
          $this->error('封面设置失败');
        }
          break;

      case 'del':
        $cover = $Album->where($albumMap)->getField('album_cover');                            
          if ($cover == $path) {  //判断是否封面图片
           $Album->where($albumMap)->setField('album_cover',' ');
          }

          //给路径加上相对路径
          $path = '.'.$path;
          @unlink($path);
              
          if ($Image->where($imageMap)->delete()) {               
            $this->ajaxReturn(U('pic/propic?pic=1&id='.$uid.'&aid='.$aid),'删除图片成功',1);
              
          } else {
            $this->error('图片删除失败');
          } 

            break;

      case 'desc':
        $desc = I('post.desc');
        if ($Image->where($imageMap)->setField('image_description', $desc) ) {
          $this->success('修改成功');
        } else {
          $this->error('修改失败');
        } 
          break;
            
      case 'name':
        $name = I('post.name');
        if (!$Image->create()) {
          $this->error($Image->getError());  
        }

        if ($Image->where($imageMap)->setField('image_title', $name)) {
          $this->ajaxReturn(1,'修改成功', 1);
        } else {
          $this->error('修改失败');
        } 
          break;

      case 'avatar':
        if (M('Users')->where(array('ID' => $uid))->setField('user_avatar',$path)) {
          session('avatar',$path);
        $this->success('设置成功');                
        } else {
        $this->error('设置失败');
        }
      break;
      }       
  }

  //专辑新增\修改\移动\删除\显示
  Public function editAlbum() {
      $Image = M("Images");
      $Album = D("Album");
      $action = I('get.action');
      $aid = I('post.cg_id');
      $id = I('post.pf_id');     
      $name = I('post.name');
      $uid = $_SESSION['id'];
      $albumMap = array('user' => $uid, 'album_id' => $aid);
      $imageMap = array('user' => $uid, 'image_album' => $aid);
      $moveMap = array('image_id' => $id);
      $infoMap = array('user' => $uid);
      $album_info = $Album->create();

      if (!$album_info) {
        $this->error($Album->getError());
      }

        switch ($action) {
          //新增专辑
          case 'add':
           
           $maxId = $Album->where($infoMap)->max('album_id');   
           $album_info['id'] = $Album->album_id = ++$maxId;
           $Album->user = $uid;
           if ($Album->add()) {
            $this->ajaxReturn($album_info,'增加专辑成功',1);
           } else {
            $this->error('增加专辑失败');
           }
              break;

          //转移专辑
          case 'move':          
          if ($Image->where($moveMap)->setField('image_album', $aid)) {
            $this->ajaxReturn(1,'转移成功',1);
          } else {
            $this->error('转移失败');
          }
              break;
          
          //专辑名称重命名  
          case 'name':          
                if ($Album->where($albumMap)->setField('album_name', $name)) {
                    $this->ajaxReturn(1,'修改专辑名称成功！',1);
                } else {
                    $this->error('修改专辑名失败。');
                }
              break;

          case 'del':
              if ($Album->where($albumMap)->delete()) {
                  //清空专辑下所有图片
                $Image->where($imageMap)->setField('image_album', 1);

                $this->ajaxReturn(1,'删除专辑成功',1);
                    
              } else {
                  $this->error('删除专辑失败');
              }
              break;           
    }
  }

  //瀑布流获取更多图片
  Public function moreImg() {
    $id = I('get.id');
    $aid = I('get.aid');
    $uid = I('get.uid');
    $imageMap = array('user' => $uid,'image_id' => array('LT',$id), 'image_album' => $aid);
    $imgList = M('Images')->where($imageMap)->field('image_id as id,image_title as title,image_path as path')->limit(8)->order('image_id DESC')->select();
    $total = count($imgList);
    for ($i=0; $i < $total; $i++) { 
      $imgList[$i]['url'] = U('pic/image?pic=1&id='.$imgList[$i]['id']);
    }

    if ($total) {
      $this->ajaxReturn($imgList,$total,1);
    } else {
      $this->error('获取失败');
    }  
  }
}