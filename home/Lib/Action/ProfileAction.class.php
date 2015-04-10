<?php
class ProfileAction extends Action {
  //查看个人信息
  Public function index(){    
        $User = M("Users");
        
        $id = I('get.id');
        $uid = $_SESSION['id'];
        //统计访问量
        if (session('visited') != $id) {
            $User->where(array('user' => $id))->setInc('user_visits'); //没有点击过则统计+1
            session('visited', $id);  //并记录访问过的人           
        }
            
        $friendList = getRelaList($id,6,1);
        $followList = getRelaList($id,4,2);
        $fansList = getRelaList($id,4,3);
        $comment = D("PerComView");    //创建评论模型         
        $commentList = $comment->where(array('host_id' => $id))->order('comment_id ASC')->select();  //获取评论内容，时间，评论人ID

        $param = array(
          'result' => $commentList,      //分页用的数组或sql
          'listvar' => 'commentList',      //分页循环变量
          'listRows' => 10,     //每页记录数
          'target' => 'comment_list',  //ajax更新内容的容器id
          'pagesId' => 'fpage',    //分页后页的容器id不带
          'template' => 'Pub:comment_content',//ajax更新模板
        );
            
        R('Pub/AjaxPage',array($param));        
                      
        if ($id != $uid) {
          $info = getInfo($id,'all');

          if ($info['secret'] == 1) {
            $info['sex'] = '<font color="#999999">保密</font>';
          }

          if ($_SESSION['isLoginIn'] == 1) {
           $rela = getRela($id);
           $info['isFriend'] = $rela['isFriend'];
           $info['isFollow'] = $rela['isFollow'];  
          }
        } else {
          $info = $_SESSION;
        }                    
        
        $navi = getNaviInfo($id,1); //导航条
        $navi['comment_url'] = U('Pub/comment?do=profile'); //回复控制器
        $navi['del_com_url'] = U('Pub/deleteCom?del=profile');  //删除评论控制器
        $navi['index'] = 1; //1显示登录后或特定页面的导航条

        $this->assign('navi',$navi); //用于导航条ID
        $this->assign('info', $info);
        $this->assign('friendList', $friendList);
        $this->assign('followList', $followList);
        $this->assign('fansList', $fansList);
        
        $this->display();
  }

  //个人信息编辑页面
  Public function editProfile() {
      if (!I('get.set')) {
        $Area = M('Areas');
        //获取省级地区
        $province = $Area->where(array('parent_id' => 1))->select();
        $city = $Area->where(array('parent_id' => $_SESSION['location'][0]))->select();
        
        $this->assign('city',$city);
        $this->assign('province',$province);
        $this->assign('info', $info);
      }

      $navi = getNaviInfo($_SESSION['id'],1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条
      $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

      $this->assign('navi',$navi); //用于导航条ID
      $this->display();
  }

  //编辑信息操作
  Public function editInfo() {
      $User = D("Users");
      $id = I('get.uid');  //用户ID,因为FF和uploadify冲突导致获取不到SESSION，需要重新传值
      $userMap = array('ID' => $id);
      $edit = I('get.edit');

      switch ($edit) {
        case 'pass':
        $pass = I('post.pword');
        $check_pass = I('post.old_pword');
        $repass = I('post.repword');

        $old_pass = $User->where($userMap)->getField('user_pass');  //取得旧密码

        if ($old_pass !== md5($check_pass)) {
          $this->error('旧密码不正确');
        } elseif ($pass !== $repass) {
          $this->error('两次密码不一致');
        } else {
          //清空验证
          $validate = array(
            array('user_pass','6,20','密码长度不正确',0,'length')
          );
          $User->setProperty('_validate',$validate);
          $auto = array(); 
          $User->setProperty('_auto',$auto);
          if (!$User->create()) {
            $this->error($User->getError());
          }

          if ($User->where($userMap)->setField('user_pass', md5($pass))) {
            $this->ajaxReturn(U('profile/index?about=1&id='.$id),'密码修改成功',1);
          } else {
            $this->error('密码修改失败');
          }
        }  
          break;

        case 'avatar':
        $step = I('get.step');
          if ($step == 1) {  
            import("ORG.Net.UploadFile");
            $upload = new UploadFile();
            $upload->maxSize = 1024*1024;     //设置上传大小
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');    //设置允许的类型
            $upload->savePath = './Uploads/avatar/'.$id.'/';  //设置路径

            if (!$upload->upload()) { // 上传错误提示错误信息
              $this->ajaxReturn(0,$upload->getErrorMsg(),0);
          
            } else {
              $img = $upload->getUploadFileInfo();

              $oriPic = $img[0]['savepath'].$img[0]['savename'];
              $tempSize = getimagesize($oriPic);
              if($tempSize[0] < 180 || $tempSize[1] < 180){
               @unlink($oriPic);
               //判断宽和高是否符合头像要求
               $this->ajaxReturn(0,'图片宽或高不得小于180像素！',0);
              }               
         
              $_SESSION['preview'] = $oriPic;
              $this->ajaxReturn(substr($oriPic, 1),'传的漂亮',1);
            }

          } elseif ($step == 2) {
            
            $params = $_POST;           //裁剪参数
            if(!isset($params) && empty($params)){
              return;
            }            

            $oriSrc = '.'.$params['src']; //原图路径
            $genOriSrc = basename($oriSrc); //原图文件名
            $avSrc = str_replace($genOriSrc, 'av_'.$genOriSrc, $oriSrc); //头像路径

            import('ORG.Util.Image.ThinkImage');
            $imgResource = new ThinkImage(); 
            $imgResource->open($oriSrc)->crop($params['w'],$params['h'],$params['x'],$params['y'])->save($avSrc);
                         
            //生成缩略图
            $imgResource->open($avSrc)->thumb(180,180, 1)->save($avSrc);
              
            $avSrc = substr($avSrc, 1);  
            //将剪切后的头像数据写入数据库
            $User->where($userMap)->setField('user_avatar', $avSrc);

            $Image = M("Images");
            if ($params['keep_ori'] == 1) {
              $oriData = array('user' => $id, 'image_album' => 1, 'image_title' => basename($oriSrc), 'image_createtime' => time(), 'image_path' => substr($oriSrc, 1));
              $Image->add($oriData);
            } else {
              @unlink($oriSrc);
            }
            
            $_SESSION['avatar'] = $avSrc; //写入session
            
            $avData = array('user' => $id, 'image_album' => 2, 'image_title' => basename($avSrc), 'image_createtime' => time(), 'image_path' => $avSrc); //剪切头像写入相册
            if ($Image->add($avData)) {
              $this->ajaxReturn(1,'新头像保存成功',1);              
            } else {
              $this->error('修改失败');
            }
                                                       
        } else {
          //重新上传则删除不要的原图
          unlink($_SESSION['preview']);
        }
       
          break;
            
        case 'info':
        $disname = I('post.disname');
        //判断是否修改昵称
        if ($disname != $_SESSION['nickname']) { 
         $validate = array(          
           array('user_nickname','water','请输入昵称',0,'callback'), 
           array('user_nickname','require','昵称太热门，已经被别人抢啦',0,'unique'),          
           array('user_email', 'email', 'email格式不正确'),
         );

        } else {
         $validate = array(          
           array('user_email', 'email', 'email格式不正确'),
         );
        }

        //清空自动完成
        $auto = array();
        $User->setProperty("_auto",$auto);
        $User->setProperty("_validate",$validate);        
        $newInfo = $User->create();
        if (!$newInfo) {
          $this->ajaxReturn(0,$User->getError(),0);
        }

        if ($User->where($userMap)->save()) { 
            $_SESSION['nickname'] = $newInfo['user_nickname'];
            $_SESSION['email'] = $newInfo['user_email'];
            $_SESSION['birthday'] = $newInfo['user_birthday'];
            $_SESSION['sex'] = $newInfo['user_sex'];
            $_SESSION['secret'] = $newInfo['sex_secret'];
            $_SESSION['location'] = explode(' ', $newInfo['user_location']);
            $_SESSION['address'] = M('Areas')->field('area_name')->where(array('area_id' => array('in', array($_SESSION['location'][0],$_SESSION['location'][1]))))->select();         
            $this->success('个人资料修改成功!');            
          } else {
            $this->error('修改失败!');
          }
          break;
      }        
  }

  //关系操作
  Public function rela() {
        $User = M("Users");
        $Follow = M("Follow");
        $Friend = M("Friends");
        $Apply = D("Apply");
        $set = I('get.set');  //操作动作
        $fid = I('post.fid'); //关系操作对象ID
        $aid = I('post.aid'); //申请ID
        $uid = $_SESSION['id'];
        $applyMap = array('apply_id' => $aid);

        switch ($set) {
          //关注
          case 'follow':          
            if ($Follow->add(array('user' => $uid, 'follow_id' => $fid))) {
              $User->where(array('ID' => $uid))->setInc('user_following');
              $User->where(array('ID' => $fid))->setInc('user_followers');
              $_SESSION['following']++;
              $this->ajaxReturn(1,'关注',1);
              
            } else {
              $this->error('加关注失败。');
            }
            break;

          //取消关注
          case 'unFollow':
            if ($Follow->where(array('user' => $uid, 'follow_id' => $fid))->delete()) {
              //操作者关注数-1
              $User->where(array('ID' => $uid))->setDec('user_following');
              $_SESSION['following']--;
              //被关注者粉丝数-1
              $User->where(array('ID' => $fid))->setDec('user_followers');
              $this->ajaxReturn(1,'已取消',1);
            } else {              
              $this->error('取消失败');             
            }
            break;

          //移除粉丝
          case 'moveFans':
            if ($Follow->where(array('follow_id' => $uid, 'user' => $fid))->delete()) {  
                //粉丝的关注数-1
                $User->where(array('ID' => $fid))->setDec('user_following'); //操作者的粉丝数-1 
                $User->where(array('ID' => $uid))->setDec('user_followers');
                $_SESSION['followers']--;  
                $this->ajaxReturn(1,'移除成功。',1);

            } else {                
                $this->error('移除失败。');
            }
            break;

          //好友申请
          case 'apply':
            $Apply->create();
            $Apply->user = $uid;
        
            if ($Apply->add()) {
              $this->ajaxReturn(1,'已发出',1);
            } else {
              $this->error('发出失败');
            }
            break;

          //取消申请
          case 'unApply':
            if ($Apply->where(array('user' => $uid, 'applicant_id' => $fid))->delete()) {
                $this->ajaxReturn(1,'取消成功',1);
            } else {
                $this->error('取消失败');
            }
            break;

          //移除好友
          case 'reFriend':
            $friendMap = array('user' => $uid, 'friend_id' => $fid);
            $myMap = array('user' => $fid, 'friend_id' => $uid);
            if ($Friend->where($friendMap)->delete() && $Friend->where($myMap)->delete()) {

              $applyMap = array('user' => $uid, 'applicant_id' => $fid);
              if ($Apply->where($applyMap)->count()) {
                $Apply->where($applyMap)->delete(); //移除表中的反馈提示信息               
              } else {
                $applyMap = array('user' => $fid, 'applicant_id' => $uid);
                $Apply->where($applyMap)->delete(); //移除表中的反馈提示信息
              }                              
                
                $User->where(array('ID' => $fid))->setDec('user_friends');
                $User->where(array('ID' => $uid))->setDec('user_friends');
                $_SESSION['friends']--;
                $this->ajaxReturn(1,'已删除',1);         
                
            } else {
                $this->error('删除失败。');
            }
            break;

          case 'addFriend': //添加好友
            $myMap = array('user' => $uid, 'friend_id' => $fid);           
            $friendMap = array('user' => $fid, 'friend_id' => $uid);
            //被添加好友也添加一个申请者为好友，用于好友列表查询
            $myCount = array('ID' => $uid);
            $friendCount = array('ID' => $fid);
        
            if ($Friend->add($myMap) && $Friend->add($friendMap)) {         
                //如果执行成功执行各自好友数量+1并删除申请
                $User->where($myCount)->setInc('user_friends');
                $User->where($friendCount)->setInc('user_friends');
                $feedback['apply_message'] = '已经通过了你的申请，你们现在是好友啦！';
                $feedback['applicant_id'] = $fid;
                $feedback['user'] = $uid;
                $feedback['apply_time'] = time();
                $feedback['apply_status'] = 3;  //2表示反馈信息
                $Apply->where($applyMap)->save($feedback);
                $_SESSION['friends']++;
                $this->ajaxReturn(1,'添加成功！我们已经是好友了！',1);
            } else {          
                $this->error('添加失败');
            }
            break; 
          
          case 'refuse':
            if ($Apply->where($applyMap)->delete()) {
              $this->ajaxReturn(1,'执行成功',1);
              $_SESSION['message']--;
            } else {
              $this->error('执行失败');
            }
            break;

          case 'deleteFeedback':
            if ($Apply->where($applyMap)->delete()) {
              $this->ajaxReturn(1,'删除成功',1);
            } else {
              $this->error('删除失败');
            }
            break;  
        }
  }

  Public function reminder() {
    $list = $_GET['list'];
    $uid = $_SESSION['id'];
    $remindModel = M('Remind');

    switch ($list) {
      case 'friend':
      $Apply = D("ApplyView");
      $uid = $_SESSION['id'];
      $applyMap = array('applicant_id' => $uid);      
      $applyCount = $Apply->where($applyMap)->count();  
      if ($applyCount) {
        import("ORG.Util.Page");    
        $p = new Page($applyCount, 12);       
        $applyList = $Apply->order('apply_id DESC')->limit($p->firstRow.','.$p->listRows)->where($applyMap)->select();  
        $p->setConfig('theme','%upPage% %linkPage% %downPage%');  //设置显示分页类参数
        $fpage = $p->show();     //输出分页类
        $this->assign('fpage', $fpage);

        $applyModel = M('Apply');
        $applyMap = array('applicant_id' => $uid,'apply_status' => 1); //未读确认消息查找条件
        $applyModel->where($applyMap)->setField('apply_status',2); //将未读确认消息设置为已读
        $applyMap = array('applicant_id' => $uid,'apply_status' => 3); //未读反馈消息查找条件
        $applyModel->where($applyMap)->setField('apply_status',4); //将未读反馈消息设置为已读

        //简单提醒,要做带剩余数的提醒重开个页面来做
        if ($_SESSION['unread_apply']) {
          $_SESSION['unread_news'] -= $_SESSION['unread_apply']; //刷新信息提示数
        } 

        $_SESSION['unread_apply'] = 0;       
      }

      $navi = getNaviInfo($uid,1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条
      $navi['user'] = $uid;
      $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

      $this->assign('recommend',getRecommend());
      $this->assign('navi',$navi); //用于导航条ID
      $this->assign('applyList',$applyList);        
      $this->display();
        break;
          
      case 'message':
        $Remind = D("MessageRemindView");              
        $remindMap = array('receiver' => $uid,'remind_type' => 1);      
        $remindCount = $Remind->where($remindMap)->count();
        if ($remindCount) {
          import("ORG.Util.Page");    
          $p = new Page($remindCount, 12);       
          $remindList = $Remind->order('id DESC')->limit($p->firstRow.','.$p->listRows)->where($remindMap)->select();  
          $p->setConfig('theme','%upPage% %linkPage% %downPage%');  //设置显示分页类参数
          $fpage = $p->show();     //输出分页类
          $this->assign('fpage', $fpage);

          for ($i=0; $i < $remindCount; $i++) {   //为删除列表添加判断
            $remindList[$i]['hid'] = $remindList[$i]['orignal_id'];
          }

          $remindMap = array('receiver' => $uid,'remind_type' => 1,'remind_status' => 1); //未读留言查找条件
          $remindModel->where($remindMap)->setField('remind_status',2); //将当前未读留言设置为已读

          if ($_SESSION['unread_message']) {
            $_SESSION['unread_news'] -= $_SESSION['unread_message']; //刷新信息提示数
          } 

          $_SESSION['unread_message'] = 0;  //刷新留言提示数
        }

        $navi = getNaviInfo($uid,1); //导航条
        $navi['index'] = 1; //1显示登录后或特定页面的导航条
        $navi['comment_url'] = U('Pub/comment?do=profile'); //回复控制器
        $navi['del_com_url'] = U('Pub/deleteCom?del=profile');  //删除评论控制器
        $navi['user'] = $uid;
        $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

        $this->assign('recommend',getRecommend());
        $this->assign('navi',$navi); //用于导航条ID
        $this->assign('remindList',$remindList);        
        $this->display();
        break;

      case 'comment':
        $Remind = D("CommentRemindView");              
        $remindMap = array('receiver' => $uid,'remind_type' => 2);      
        $remindCount = $Remind->where($remindMap)->count();
        if ($remindCount) {
          import("ORG.Util.Page");    
          $p = new Page($remindCount, 12);       
          $remindList = $Remind->order('id DESC')->limit($p->firstRow.','.$p->listRows)->where($remindMap)->select();  
          $p->setConfig('theme','%upPage% %linkPage% %downPage%');  //设置显示分页类参数
          $fpage = $p->show();     //输出分页类
          $this->assign('fpage', $fpage);
          
          $remindMap = array('receiver' => $uid,'remind_type' => 2,'remind_status' => 1); //未读博客评论查找条件
          $remindModel->where($remindMap)->setField('remind_status',2); //将未读博客评论设置为已读

          if ($_SESSION['unread_post_comment']) {
            $_SESSION['unread_news'] -= $_SESSION['unread_post_comment']; //刷新信息提示数
            $_SESSION['unread_comment'] -= $_SESSION['unread_post_comment'];  //刷新评论提示数
          } 

          $_SESSION['unread_post_comment'] = 0;  //刷新文章留言提示数
        }
        
        $navi = getNaviInfo($uid,1); //导航条
        $navi['comment_url'] = U('Pub/comment?do=article'); //回复控制器
        $navi['del_com_url'] = U('Pub/deleteCom?del=article');  //删除评论控制器
        $navi['index'] = 1; //1显示登录后或特定页面的导航条
        $navi['user'] = $uid;
        $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

        $this->assign('recommend',getRecommend());
        $this->assign('navi',$navi); //用于导航条ID
        $this->assign('remindList',$remindList);        
        $this->display();
        break;

      case 'pic_comment':
        $Remind = D("PicCommentRemindView");              
        $remindMap = array('receiver' => $uid,'remind_type' => 3);      
        $remindCount = $Remind->where($remindMap)->count();
        if ($remindCount) {
          import("ORG.Util.Page");    
          $p = new Page($remindCount, 12);       
          $remindList = $Remind->order('id DESC')->limit($p->firstRow.','.$p->listRows)->where($remindMap)->select();  
          $p->setConfig('theme','%upPage% %linkPage% %downPage%');  //设置显示分页类参数
          $fpage = $p->show();     //输出分页类
          $this->assign('fpage', $fpage);

          $remindMap = array('receiver' => $uid,'remind_type' => 3,'remind_status' => 1); //未读图片评论查找条件
          $remindModel->where($remindMap)->setField('remind_status',2); //将未读图片评论设置为已读
          if ($_SESSION['unread_pic_comment']) {
            $_SESSION['unread_news'] -= $_SESSION['unread_pic_comment']; //刷新信息提示数
            $_SESSION['unread_comment'] -= $_SESSION['unread_pic_comment'];//刷新评论提示数
          } 

          $_SESSION['unread_pic_comment'] = 0; //刷新评论留言提示数         
        }

        $navi = getNaviInfo($uid,1); //导航条
        $navi['comment_url'] = U('Pub/comment?do=pic'); //回复控制器
        $navi['del_com_url'] = U('Pub/deleteCom?del=pic');  //删除评论控制器
        $navi['index'] = 1; //1显示登录后或特定页面的导航条
        $navi['user'] = $uid;
        $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

        $this->assign('recommend',getRecommend());
        $this->assign('navi',$navi); //用于导航条ID
        $this->assign('remindList',$remindList);        
        $this->display();
        break;      
    }         
  }

  //粉丝列表
  Public function fansList() {  
      $id = I('get.id');  //过滤get，获取ID  
      $map = array('follow_id' => $id);

      $Fans = D("FollowerView");        
      $fansCount = $Fans->where($map)->count();  //统计评论数
      if ($fansCount) {
        import("ORG.Util.Page");    //导入分页类库
        $p = new Page($fansCount, 12);   
        //通过条件分别查询出好友、关注、粉丝列表     
        $fansList = $Fans->where($map)->order('id DESC')->limit($p->firstRow.','.$p->listRows)->select();  
        $p->setConfig('theme','%upPage% %linkPage% %downPage%');  //设置显示分页类参数
        $fpage = $p->show();     //输出分页类 
        $this->assign('fpage', $fpage);    
      }

      //剪切用户名
      $fansList = cutInfo($fansList,$fansCount);

      if (session('id') != $id) {
        $info = getInfo($id);
        //关系判断函数
        $rela = getRela($id);
        $info['isFriend'] = $rela['isFriend'];
        $info['isFollow'] = $rela['isFollow'];
      } else {
        $info = $_SESSION;      
      } 

      $navi = getNaviInfo($id,1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条

      $this->assign('navi',$navi); //用于导航条ID       
      $this->assign('fansList', $fansList);      
      $this->assign('info', $info);
      $this->display(); 
  }

  //好友列表
  Public function friendList() {          
      $id = I('get.id');  //过滤get，获取ID
      $gid = I('get.gid');
      $myId = $_SESSION['id'];
      $groupMap = array('user' => $id, 'group_id' => $gid);
      $map = array('user' => $id);
      $Friend = D("FriendsView");
      import("ORG.Util.Page");    //导入分页类库
      //分组查看好友
      if ($gid) {
        if ($id != session('id')) {
            $this->error('参数错误');
        }
        
        $friendCount = $Friend->where($groupMap)->count(); 
        $p = new Page($friendCount, 12);
        
        //好友数不为空则查询列表
        if ($friendCount) {                      
          //通过条件分别查询出好友、关注、粉丝列表     
          $friendList = $Friend->where($groupMap)->field('nickname,avatar,followers,intro,following,friend_id,group_id')->order('id DESC')->limit($p->firstRow.','.$p->listRows)->select();  
        }
      //不分组查看
      } else {
        $friendCount = $Friend->where($map)->count(); 
        $p = new Page($friendCount, 12);           
        
        //好友数不为空则查询列表
        if ($friendCount) {                    
          //通过条件分别查询出好友、关注、粉丝列表     
          $friendList = $Friend->where($map)->field('nickname,avatar,intro,followers,following,friend_id,group_id')->order('id DESC')->limit($p->firstRow.','.$p->listRows)->select();  
        }
      }

      $p->setConfig('theme','%upPage% %linkPage% %downPage%');  //设置显示分页类参数
      $fpage = $p->show();     //输出分页类

      //关注列表
      $friendList = cutInfo($friendList,$friendCount);     
                                       
      //若是自己的关注列表则生成分组
      if ($myId != $id) {
        $info = getInfo($id);
        //关系判断函数
        $rela = getRela($id);
        $info['isFriend'] = $rela['isFriend'];
        $info['isFollow'] = $rela['isFollow'];
      } else {
        $info = $_SESSION;        
        $groupList = M("Group")->where(array('user' => $id, 'group_meta' => 2))->order('group_id ASC')->field('group_id as cg_id,group_name as name')->select(); //生成分组列表
        $operaUrl = getUrlType('friend');

        $this->assign('operaUrl', $operaUrl);
        $this->assign('cate_group', $groupList);   //输出分组列表       
      }
      
      $navi = getNaviInfo($id,1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条

      $this->assign('navi',$navi); //用于导航条ID        
      $this->assign('fpage', $fpage); 
      $this->assign('friendList', $friendList);
      $this->assign('info',$info);      
      $this->display();               
  }

  //关注列表
  Public function followList() {
      $id = I('get.id');  //过滤get，获取ID
      $myId = $_SESSION['id'];
      $gid = I('get.gid');
      $groupMap = array('user' => $id, 'group_id' => $gid);
      $map = array('user' => $id);
      import("ORG.Util.Page");    //导入分页类库
      $Follow = D("ProFollowView");
      if ($gid) {
        //分组仅限自己查看
        if ($id  != session('id')) {
            $this->error('操作失败');
        }

        $followCount = $Follow->where($groupMap)->count(); 
        $p = new Page($followCount, 12); 
        //关注数不为空则查询列表
        if ($followCount) {                                
          //通过条件分别查询出好友、关注、粉丝列表     
          $followList = $Follow->where($groupMap)->field('nickname,avatar,followers,following,intro,follow_id,group_id')->order('id DESC')->limit($p->firstRow.','.$p->listRows)->select();  
        }

      } else {
        $followCount = $Follow->where($map)->count();  
        $p = new Page($followCount, 12);
        //关注数不为空则查询列表
        if ($followCount) {                      
          //通过条件分别查询出好友、关注、粉丝列表     
          $followList = $Follow->where($map)->field('nickname,avatar,followers,intro,following,follow_id,group_id')->order('id DESC')->limit($p->firstRow.','.$p->listRows)->select();            
        }
      }
      
      $p->setConfig('theme','%upPage% %linkPage% %downPage%');  //设置显示分页类参数
      $fpage = $p->show();     //输出分页类       

      //剪切用户名
      $followList = cutInfo($followList,$followCount);
                                      
      //若是自己的关注列表则生成分组
      if ($id != $myId) {
        $info = getInfo($id);
        //关系判断函数
        $rela = getRela($id);
        $info['isFriend'] = $rela['isFriend'];
        $info['isFollow'] = $rela['isFollow'];
      } else {
        $groupList = M("Group")->where(array('user' => $id, 'group_meta' => 1))->order('group_id ASC')->field('group_id as cg_id,group_name as name')->select(); //生成分组列表
        $info = $_SESSION;
        $operaUrl = getUrlType('follow'); //分类操作url

        $this->assign('operaUrl', $operaUrl);
        $this->assign('cate_group', $groupList);   //输出分组列表 
      }
      
      $navi = getNaviInfo($id,1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条

      $this->assign('navi',$navi); //用于导航条ID
      $this->assign('fpage', $fpage); 
      $this->assign('followList', $followList);
      $this->assign('info',$info);      
      $this->display();          
  }

  //修改分组
  Public function makeGroup() {
      $Group = D("Group");

      if (!$Group->create()) {
       $this->error($Group->getError());
      }

      $Friend = M("Friends");
      $Follow = M("Follow");
      $uid = $_SESSION['id'];      
      $name = I('post.name');
      $gid = I('post.cg_id');
      $fid = I('post.pf_id');
      $action = I('get.action');
      $meta = I('get.meta');


      switch ($action) {
        //添加分组,1关注分组,2好友分组
        case 'add':
          $gid = $Group->where(array('user' => $uid, 'group_meta' => $meta))->max('group_id');
          $data = array('user' => $uid, 'group_id' => $gid+1, 'group_name' => $name, 'group_meta' => $meta);
          if ( $Group->add($data) ) {
            $ajaxBack['id'] = $data['group_id'];
            $this->ajaxReturn($ajaxBack,'添加成功',1);
          } else {
            $this->error('添加失败');
          }  

          break;

        //修改分组名
        case 'name':
          if ($meta == 1) {
             $groupMap = array('user' => $uid, 'group_id' => $gid, 'group_meta' => 1);
             if ( $Group->where($groupMap)->setField('group_name', $name) ) {
               $this->ajaxReturn($groupMap,'已修改',1);
             } else {
               $this->error('修改失败');
             }
             
          } else {
            $groupMap = array('user' => $uid, 'group_id' => $gid, 'group_meta' => 2);
            if ( $Group->where($groupMap)->setField('group_name', $name) ) {
                $this->ajaxReturn($groupMap,'已修改',1);
             } else {
               $this->error('修改失败');
             }
          }
          
          break;

        //选择分组  
        case 'move':          
          if ($meta == 1) {
            $followMap = array('user' => $uid, 'follow_id' => $fid);           
            if ( $Follow->where($followMap)->setField('group_id', $gid) ) {
               $this->ajaxReturn(1,'转移成功！',1); 
            } else {
               $this->error('转移失败');
            }
            
          } else {  
            $friendMap = array('user' => $uid, 'friend_id' => $fid);         
            if ( $Friend->where($friendMap)->setField('group_id', $gid) ) {
               $this->ajaxReturn(1,'转移成功！',1); 
            } else {
               $this->error('转移失败');
            }
          }
          
          break;
        
        //删除分组
        case 'del':
          if ($meta == 1) {
            $groupInfo = array('user' => $uid, 'group_id' => $gid, 'group_meta' => 1);
            $followInfo = array('user' => $uid, 'group_id' => $gid);

            if ( $Group->where($groupInfo)->delete() ) {
              $Follow->where($followInfo)->setField('group_id', 0);
              $this->success('已删除');
            } else {
              $this->error('删除失败');
            }

          } else {
            $groupInfo = array('user' => $uid, 'group_id' => $gid, 'group_meta' => 2);
            $friendInfo = array('user' => $uid, 'group_id' => $gid);

            if ( $Group->where($groupInfo)->delete() ) {
              $Friend->where($friendInfo)->setField('group_id', 0);
              $this->success('已删除');
            } else {
              $this->error('删除失败');
            }
          }

          break;
      }
  }

  //获取地域信息
  public function getArea(){
      $Area = M('Areas')->where(array('parent_id' => $_POST['areaId']))->select();
      $this->ajaxReturn($Area);
  } 
}