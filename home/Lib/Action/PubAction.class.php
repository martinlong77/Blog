<?php
class PubAction extends Action {  
  //验证码
  Public function verify() {
    import('ORG.Util.Image');
    Image::buildImageVerify(4, 5, 'jpeg', 60, 32);
  }

  //留言操作
  Public function comment() {
    $reply_to = I('post.reply_to'); //回复id,用于发送提醒
    if (!$reply_to) {
      $verify = I('post.verify');
      if ($_SESSION['verify'] != md5($verify)) {
        $this->ajaxReturn(0,'验证码错误!',0);
      }
    }
    
    $do = I('get.do');
    $id = I('post.id'); //回复所在的博客、图片、个人主页id
    $Remind = M('Remind');

    //判断是执行那种操作
    if ($do == 'pic') {
      //图片评论模型
      $Comment = D("PicComments");
    } elseif ($do == 'article') {
      //文章评论模型
      $Comment = D("Comments"); 
    } elseif ($do == 'profile') {
      //个人信息页评论模型
      $Comment = D("PerComments");
    } else {
      $this->error('参数错误');
    }          
             
    $comDis = $Comment->create();
    
    if (!$comDis) {
     //检查是否灌水
     $this->ajaxReturn(0,$Comment->getError(),0);
    }

    $Comment->comment_ip = get_client_ip(); //获取评论ip

    if ($reply_to) {     
      $url = I('post.url');
      $name = I('post.name');
      $Comment->comment_content = '回复<a href="'.$url.'" target="_blank">'.$name.'</a>: '.$comDis['comment_content'];
    }
    
    $comDis['comment_time'] = date('Y-m-d H:i',$comDis['comment_time']);  //AJAX返回用于显示的时间
    $comDis['new_id'] = $Comment->add();  //新增评论              
    $uid = $comDis['uid'];
    $commentId = $comDis['new_id'];

    if($commentId){
      switch ($do) {
        case 'pic':
          $Images = M("Images");
          $imgMap = array('image_id' => $id);
          $Images->where($imgMap)->setInc('comcount');   //添加评论数
          $user = $Images->where($imgMap)->getField('user');

          if ($user != $uid || $reply_to) {
            $message['receiver'] = $user;
            if ($reply_to) {
              $message['receiver'] = $reply_to;
            }
            
            $message['sender'] = $uid;
            $message['remind_type'] = 3;  //3表示照片评论
            $message['from_id'] = $commentId;
            $message['pid'] = $comDis['post_id']; //评论所在文章id
            $message['remind_status'] = 1; //1表示未读
            $Remind->add($message); 
          }

          if ($user != $uid && $user != $reply_to) { //若用户A和B在用户C的个人页面下评论,给用户C也发送一条提醒
           $message['receiver'] = $user;
           $message['sender'] = $uid;
           $message['remind_type'] = 3;  
           $message['from_id'] = $commentId;  //用于提醒数据表连接查询
           $message['remind_status'] = 1;  
           $Remind->add($message);
          }
          break;
        
        case 'article':
          $Posts = M("Posts");
          $postMap = array('post_id' => $id);
          $Posts->where($postMap)->setInc('comcount');   //添加评论数
          $user = $Posts->where($postMap)->getField('user');
          
          if ($user != $uid || $reply_to) {
            $message['receiver'] = $user;
            if ($reply_to) {
              $message['receiver'] = $reply_to;
            }
            
            $message['sender'] = $uid;
            $message['remind_type'] = 2;  //2表示博客评论
            $message['from_id'] = $commentId;
            $message['pid'] = $comDis['post_id']; //评论所在文章id
            $message['remind_status'] = 1; //1表示未读
            $Remind->add($message); 
          }

          if ($user != $uid && $user != $reply_to) { //若用户A和B在用户C的个人页面下评论,给用户C也发送一条提醒
           $message['receiver'] = $user;
           $message['sender'] = $uid;
           $message['remind_type'] = 2;  
           $message['from_id'] = $commentId;  //用于提醒数据表连接查询
           $message['remind_status'] = 1;  
           $Remind->add($message);
          }                                       
          break;

        case 'profile':
         $hid = $comDis['host_id'];   //个人页面主人ID
         if ($hid != $uid || $reply_to) { //不是回复自己个人页面或回复的不是自己的评论则发送提醒
          $message['receiver'] = $hid;
          if ($reply_to) {
            $message['receiver'] = $reply_to;
          }
          
          $message['sender'] = $uid;
          $message['remind_type'] = 1;  //1表示留言
          $message['from_id'] = $commentId;  //用于提醒数据表连接查询
          $message['remind_status'] = 1;  //1为未读
          $Remind->add($message);

          if ($hid != $reply_to && $hid != $uid) { //若用户A和B在用户C的个人页面下评论,给用户C也发送一条提醒
           $message['receiver'] = $hid;
           $message['sender'] = $uid;
           $message['remind_type'] = 1;  //1表示留言
           $message['from_id'] = $commentId;  //用于提醒数据表连接查询
           $message['remind_status'] = 1;  //1为未读
           $Remind->add($message);
          }
         }         
            break;  
      }
      
      $this->ajaxReturn($comDis,'回复成功~',1);      //成功返回成功模板 
     
    }else{
      $this->ajaxReturn(0,'留言失败~',0);     //失败返回失败模板
    }
  }

  //删除留言
  Public function deleteCom() {
    //留言ID
    $id = I('post.id');
    $cid = I('post.cid');
    $commentMap = array('comment_id' => $cid);
    $del = I('get.del');
    $Remind = M('Remind');

    switch ($del) {
      //删除图片留言
      case 'pic':
        $PicComment = M("PicComments");
        $Images = M("Images");
        $imgMap = array('image_id' => $id);

        if ($PicComment->where($commentMap)->delete()) {
            $remindMap = array('from_id' => $cid,'remind_type' => 3);
            $Remind->where($remindMap)->delete();
            $Images->where($imgMap)->setDec('comcount');
            $this->success('已删除');
        } else {
            $this->error('删除失败');
        }
        break;
      //删除个人留言
      case 'profile':
          $PerComment = M("PerComments");

          if ($PerComment->where($commentMap)->delete()) {
            $remindMap = array('from_id' => $cid,'remind_type' => 1);
            $Remind->where($remindMap)->delete();
            $this->success('已删除');
          } else {
            $this->error('删除失败');
          }
        break;
      //删除文章留言
      case 'article':
          $Comment = M("Comments");
          $Posts = M("Posts");
          $postMap = array('post_id' => $id);

          if ($Comment->where($commentMap)->delete()) {
            $remindMap = array('from_id' => $cid,'remind_type' => 2);
            $Remind->where($remindMap)->delete(); //删除提醒
            $Posts->where($postMap)->setDec('comcount');  //评论-1
            $this->success('已删除');
          } else {
            $this->ajaxReturn(0,'删除失败',0);
          } 
        break;
    }     
  }

  //举报留言
  Public function ComplaintsCom() {

  }

  /**
    +----------------------------------------------------------
   * 分页函数 支持sql和数据集分页 sql请用 buildSelectSql()函数生成
    +----------------------------------------------------------
   * @access public
    +----------------------------------------------------------
   * @param array   $result 排好序的数据集或者查询的sql语句
   * @param int       $totalRows  每页显示记录数 默认21
   * @param string $listvar    赋给模板遍历的变量名 默认list
   * @param string $parameter  分页跳转的参数
   * @param string $target  分页后点链接显示的内容id名
   * @param string $pagesId  分页后点链接元素外层id名
   * @param string $template ajaxlist的模板名
   * @param string $url ajax分页自定义的url
    +----------------------------------------------------------
   */
  public function AjaxPage($param) {
    //从数组中将变量导入到当前的符号表,以$key = $value;的形式
    extract($param);
    import("ORG.Util.AjaxPage");
    //总记录数
    $flag = is_string($result); //判断是结果集还是SQL语句

    //结果集则直接统计结果，SQL语句则执行查询
    if ($flag) {
      $totalRows = M()->table($result . ' a')->count();
    } else {      
      $totalRows = ($result) ? count($result) : 1;
    }

    //创建分页对象
    if ($target && $pagesId) {
      $p = new Page($totalRows, $listRows, $target, $pagesId);
    } else {
      $p = new Page($totalRows, $listRows, $parameter,$url); 
    }

    //取出数据
    if ($flag) {
      $result .= " LIMIT {$p->firstRow},{$p->listRows}";
      $volist = M()->query($result);
    } else {
      $volist = array_slice($result, $p->firstRow, $p->listRows);
    }

    if ($unAjax) {
      $p->setConfig('theme','%upPage% %linkPage% %downPage%');
    } else {
      $p->setConfig('theme','%upPage% %linkPage% %downPage% %ajax%');
    }
    //分页显示
    $page = $p->show();
    //模板赋值
    $this->assign($listvar, $volist);
    $this->assign("page", $page);

    //判断ajax请求
    if ($this->isAjax()) {
      layout(false);
      $template = (!$template) ? 'ajaxlist' : $template;
      exit($this->fetch($template));
    }
    return $volist;
  }

  //搜索
  Public function search() { 
    G('run');   //测试查询时间 
    $keyword = trim($this->_get(search));
    if ($keyword != '') {
      if (strpos($keyword, '+')) {
        $keyword = explode('+', $keyword);
      } else {
        $keyword = explode(' ', $keyword);
      }      
      $wordCount = count($keyword);
    }     

    //若存在关键字则添加模糊查询符号
    if ($wordCount) {
      for ($i=0; $i < $wordCount; $i++) { 
       $keyword[$i] = '%'.$keyword[$i].'%'; 
      }
 
      //查询条件,暂设3个
      if (!empty($keyword[2])) {
        $map['post_title|post_content|post_tag'] = array('like', array($keyword[0],$keyword[1],$keyword[2]),'AND');
      } elseif (!empty($keyword[1])) {
        $map['post_title|post_content|post_tag'] = array('like', array($keyword[0],$keyword[1]),'AND');
      } else {
        $map['post_title|post_content|post_tag'] = array('like', $keyword[0]);
      }
      $map['post_category'] = array('neq',2);
      $result = M('Posts')->where($map)->field('post_id,post_content,post_title,post_createtime')->order('post_id DESC')->select();      

      //将查询条件去掉
      for ($i=0; $i < $wordCount; $i++) { 
       $keyword[$i] = str_replace('%', '', $keyword[$i]); 
      }
      
      //结果总数
      $total['result'] = count($result);

      for ($i=0; $i < $total['result']; $i++) {
        //若标题存在关键字则替换标题关键字显示
        for ($j=0; $j < $wordCount; $j++) { 
         $result[$i]['post_title'] = preg_replace("/($keyword[$j])/i","<font color=\"red\">\\1</font>",$result[$i]['post_title']); 
        }
        
        $content = strip_tags($result[$i]['post_content']);   //去掉标签
        $firstFound[0] = stripos($content,$keyword[0]);  //获取关键字在字符串中第一次出现的位置

        //存在第二个关键字则查找下一个
        if ($keyword[1]) {
         $firstFound[1] = stripos($content,$keyword[1]);
         if ($keyword[2]) {
          $firstFound[2] = stripos($content,$keyword[2]);
         }
        }

        //若长度大于100字则截取,中文截取100个字
        if (strlen($content) > 50) {
         //先查找第一个获取关键字       
         $content0 = cut_str($content, $firstFound[0], 50);
         //若存在第二个关键字则继续查找
         if ($firstFound[1]) {
          //截取第二个关键字
           $content1 = $content0.cut_str($content, $firstFound[1], 35);

           if ($firstFound[2]) {
            //截取第三个关键字
             $content = $content1.cut_str($content, $firstFound[2], 35);
           //若没有第三个关键字直接显示截取过关键字0,1的内容
           } else {
             $content = $content1;             
           }
         //没有第二个关键字直接显示截取过关键字0的内容
         } else {
           $content = $content0;
         }

        }
 
        //若剪切部分没有关键字则直接显示不替换
        if (!strpos($content, $keyword)) {
          for ($j=0; $j < $wordCount; $j++) {  
           $content = preg_replace("/($keyword[$j])/i","<font color=\"red\">\\1</font>",$content);
          }

          $result[$i]['post_content'] = $content;
        } else {
          $result[$i]['post_content'] = $content;
        } 
      }
    }

    $total['time'] = G('run','end');

    $param = array(
        'result' => $result,      //分页用的数组或sql
        'listvar' => 'result',      //分页循环变量
        'listRows' => 20,     //每页记录数
        'target' => 'ajax_content',  //ajax更新内容的容器id
        'pagesId' => 'page',    //分页后页的容器id不带#
        'template' => 'Pub:content',//ajax更新模板
    );

    $navi['index'] = 2; //2显示登录后的导航条
    if ($_SESSION['isLoginIn'] == 1) {
      $navi = getNaviInfo($_SESSION['id'],1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条
    }
    
    $this->assign('navi',$navi); //用于导航条ID        
    $this->AjaxPage($param);
    $this->assign('recommend', getRecommend());
    $this->assign('total',$total);
    $this->display();
  } 
}