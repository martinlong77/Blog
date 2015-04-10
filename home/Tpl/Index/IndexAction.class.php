<?php
class IndexAction extends Action {
    Public function index(){
        $Post = D("Posts");
        import("ORG.Util.Page");
        $postCount = $Post->where(array('readcount' => array('gt', 3), 'post_category' => array('NEQ', 2), 'reproduced' => 0))->count();
        $p = new Page($postCount, 6);
        $postList = $Post->relation('nickname')->field('post_category,post_synopsis', true)->where(array('readcount' => array('gt', 3), 'post_category' => array('NEQ', 2), 'reproduced' => 0))->limit($p->firstRow.','.$p->listRows)->order('readcount DESC')->select();
        $p->setConfig('theme','%upPage% %linkPage% %downPage%');      //设置分页显示方式
        $page = $p->show();     //输出分页

        if (!empty($postList)) {
          //切割标签
          for ($i=0; $i<$postCount; $i++) {
            if (!empty($postList[$i]['post_tag'])) {
              $postList[$i]['post_tag'] = explode(' ', $postList[$i]['post_tag']);
            }

            if (!empty($postList[$i]['post_content'])) {
              $postList[$i]['post_content'] = cut_str(strip_tags($postList[$i]['post_content'], '<br><p><b><img>'),0, 300);
            }  
          }
        } 

        $navi['index'] = 2; //2显示登陆前导航条控制
        if (session('isLoginIn') == 1) {
          $uid = $_SESSION['id']; //如果已登陆获取信息
          $navi = getNaviInfo($uid,1); //导航条
          $navi['index'] = 1; //1显示登录后或特定页面的导航条           
          $info = $_SESSION;      
          $this->assign('collectId', checkCollect($uid));          
          $this->assign('info',$info); 
        }
        
        $this->assign('navi',$navi);               
        $this->assign('recommend', getRecommend());       
        $this->assign('postList', $postList);
        $this->assign('fpage', $page);
        $this->display();
    }      

    Public function register() {
        if (session('isLoginIn') == 1) {
          $this->error('执行错误',U('Index/index'));
        }
        
        $this->display();
    }

    Public function login() {
        if (session('isLoginIn') === 1) {
          $this->error('执行错误',U('Index/index?hot=1'));
        }

        $this->display();
    }

    Public function loginIn() {
        if (IS_POST) {
           $User = D("Users");
           //验证密码加MD5            
           $auto = array(
            array('user_login','strtolower',1,'function'),
            array('user_pass','md5',1,'function'),
           ); 

           $User->setProperty('_auto',$auto);

           //清空验证
           $validate = array();
           $User->setProperty('_validate',$validate);
           //创建数据对象
           $ucd = $User->create();
           if (!$ucd) {
           //失败输出错误信息
               $this->ajaxReturn(0,$User->getError(),0);
           } else {
              //确认用户名密码是否正确
               $info = $User->field('id,user_avatar as avatar,user_nickname as nickname,user_following as following,user_followers as followers,user_collect as collect,user_email as email,user_birthday as birthday,user_location as location,user_intro as intro,user_visits as visits,user_friends as friends,user_sex as sex,sex_secret as secret')->where($ucd)->find();
               if ($info) {                  
                  session(array('name'=>'session_id','expire' => 3600));
                  
                  //取出地址数据查询名称
                  $info['location'] = explode(' ', $info['location']);
                  $info['address'] = M('Areas')->field('area_name')->where(array('area_id' => array('in', array($info['location'][0],$info['location'][1]))))->select();        
                  $info['unread_apply'] = M("Apply")->where(array('applicant_id' => $info['id'],'apply_status' => array('in','1,3')))->count();  //获取用户未读申请数,1表示申请,3表示申请反馈
                  $Remind = M("Remind");
                  $info['unread_message'] = $Remind->where(array('receiver' => $info['id'],'remind_status' => 1, 'remind_type' => 1))->count();  //获取用户未读留言数,status=1表示未读
                  $info['unread_post_comment'] = $Remind->where(array('receiver' => $info['id'],'remind_status' => 1, 'remind_type' => 2))->count();  //获取用户未读留言数,status=1表示未读
                  $info['unread_pic_comment'] = $Remind->where(array('receiver' => $info['id'],'remind_status' => 1, 'remind_type' => 3))->count();  //获取用户未读留言数,status=1表示未读
                  $info['unread_comment'] = $info['unread_post_comment']+$info['unread_pic_comment']; //评论总数
                  $info['unread_news'] = $info['unread_comment']+$info['unread_message']+$info['unread_apply'];
                  $info['isLoginIn'] = 1;   //设置登陆状态
                  $info['collectId'] = checkCollect($info['id']); //获取收藏文章ID
                  $info['loginTime'] = time();  //获取登陆时间
                  $User->where(array('ID' => $info['id']))->setField('last_login',$info['loginTime']);
                  $_SESSION = $info;

                  $this->ajaxReturn(U('Blog/proBlog?blog=1&id='.$info['id']),1, 1);

               } else {
                  $this->ajaxReturn(0,'用户名或密码错误。',0);
               }
           }
           
        }
    }

    //登出
    Public function loginOut() {
      session(null); //清空SESSION

      if (isset($_COOKIE[session_name()])) {
        setCookie(session_name(),'',time()-42000,'/');
      }

      if ($_SESSION == null) {
        $this->ajaxReturn(U('index/index?hot=1'),'已登出',1);        
      } else {
        $this->error('登出错误');
      }      
    }

    Public function registered() {
        $User = D("Users");

        if ($_POST['pword'] != $_POST['npword']) {
          $this->ajaxReturn('','两次密码不一致',0);
        }        

        if (!$User->create()) {
          $this->ajaxReturn(0,$User->getError(),0);
        }              

        if (session('verify') != md5($_POST['verify'])) {
          $this->ajaxReturn(0,'验证码错误',0);
        }

        $User->user_ip = get_client_ip();
        $info['birthday'] = $User->user_birthday = date('Y-m-d');
        $info['nickname'] = $User->user_nickname;
        $info['avatar'] = $User->user_avatar;
        $info['email'] = $User->user_email;
        $info['intro'] = $User->user_intro;
          
        $id = $User->add();    

        if (!$id) {
          $this->ajaxReturn(0,'注册失败，请联系网站管理员！',0);
        } else {
          //创建一个头像相册,一个默认相册
          $Album = M("Album");
          $defaultAlbum = array('user' => $id, 'album_id' => 1, 'album_name' => '默认相册','album_create_time' => time()); 
          $avatarAlbum = array('user' => $id, 'album_id' => 2, 'album_name' => '头像相册','album_create_time' => time());            
          $Album->add($avatarAlbum); 
          $Album->add($defaultAlbum);

          $Category = M("Category");
          $defaultCate = array('user' => $id, 'category_id' => 1, 'category_name' => '默认文章');
          $secretCate = array('user' => $id, 'category_id' => 2, 'category_name' => '私密博文');          
          $Category->add($defaultCate);
          $Category->add($secretCate);

          $info['id'] = $id;          
          $info['following'] = $info['followers'] = $info['collect'] = $info['visits'] = $info['friends'] =  0;
          $info['location'][0] = $info['location'][1] = $info['isLoginIn'] = 1;
          $info['address'] = M('Areas')->field('area_name')->where(array('area_id' => array('in', array($info['location'][0],$info['location'][1]))))->select();        
          $info['unread_apply'] = $info['unread_post_comment'] = $info['unread_message'] = $info['unread_pic_comment'] = $info['unread_comment'] = $info['unread_news'] = 0; 
          $info['collectId'] = ''; //获取收藏文章ID
          $info['loginTime'] = time();  //获取登陆时间
          $User->where(array('ID' => $info['id']))->setField('last_login',$info['loginTime']);
          session(array('name'=>'session_id' ,'expire' => 3600));
          $_SESSION = $info; 

          $blogUrl = U('Blog/proBlog?blog=1&id='.$_SESSION['id']);
          $this->ajaxReturn($blogUrl,'<span class="apply">恭喜您！注册成功！<a href="'.$blogUrl.'">页面将在 <b id="wait_time">2</b>秒后跳转，如不想等待请点击此处。</a></span>',1);
        }
  }

  //找回密码邮件设置
  Public function getMail() {
    if (md5(I('post.verify')) != session('verify')) {
      $this->ajaxReturn(0,'验证码错误',0);
    }

    $login = I('post.uname');    
    $info = M('Users')->where(array('user_login' => $login))->Field('user_email,ID')->find();
    if ($info) {

     $config = array('MAIL_ADDRESS' => 'admin@jellong.com', 'MAIL_SMTP'=>'mail.jellong.com', 'MAIL_LOGINNAME'=>'admin@jellong.com', 'MAIL_PASSWORD'=>'am@ri31n', 'MAIL_CHARSET'=>'UTF-8','MAIL_AUTH'=>true,'MAIL_HTML'=>true);
     C($config);
     //邮件处理类
     import('ORG.Util.Mail');
     $md5 = md5(mt_rand());
     $content = '亲爱的果冻写会员<font color="red">'.$login.'</font>:<br />&nbsp;&nbsp;&nbsp;您好！您正在申请找回果冻写博客的密码，请您在<font color="red">一个小时之内</font>请点击下面链接修改密码:<p>http://blog.jellong.com'.U('index/changePw',array('ver' => $md5)).'</p><font color="red">果冻写发件人邮箱为:admin@jellong.com,非此邮箱发出的邮件均为仿冒，请您注意！</font><br /><span style="float:right">诚挚的 <a href="http://blog.jellong.com">果冻写</a> 管理员</span>';
     setcookie('md5',$md5,time()+3600);
     setcookie('uid',$info['id'],time()+3600);
     SendMail($info['user_email'],'果冻写密码修改',$content,'果冻写管理员');
     $mail_web = explode('@', $info['user_email']);
     $this->ajaxReturn('http://mail.'.$mail_web[1],'验证成功!系统已经发送一封确认信到您的email,点击此处自动跳转到您的邮箱提供商的页面,请您登陆邮箱进行操作。',1);
    } else {
     $this->ajaxReturn(0,'账号错误或不存在！',0);
    }
  }

  //修改新密码界面
  Public function changePw() {
    if (I('get.ver') != $_COOKIE['md5'] || empty($_GET['ver'])) {
      $this->error('参数错误');
    } else {   
      $this->display();
    }
  }

  Public function newPw() { //修改密码操作
    if (I('post.pword') !== I('post.repword')) {
      $this->error('两次密码不一致');
    } else {
      $User = D('Users');
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

      if ($User->where(array('ID' => $_COOKIE['uid']))->setField('user_pass', md5(I('post.pword')))) {
       setcookie('md5','',time()-3600);
       setcookie('uid','',time()-3600);
       $this->ajaxReturn(U('index/index'),'密码修改成功',1);
      } else {
       $this->error('修改失败'); 
      }
    }
  }

  Public function resetType() {
    M('Remind')->where(array('receiver'=>1,'remind_status'=>2))->setField('remind_status',1);
    echo 'Done!';
  }

  Public function adjustment() {
    header('charset=UTF-8');
    $User = M('Users');
    $userCount = $User->count('ID');
    for ($i=1; $i < $userCount; $i++) { 
      $id[user] = $i;
      $data[user_friends] = M("Friends")->where($id)->count();
      $data[user_following] = M('Follow')->where($id)->count();
      $fid[follow_id] = $i;
      $data[user_followers] = M('Follow')->where($fid)->count();
      $uid[ID] = $i;
      $info = $User->field('user_nickname as nickname,user_followers as fans,user_friends as friends,user_following as follow')->where($uid)->find();

      if ($User->where($uid)->save($data)) {       
        echo 'Done!We already changed <font color="red"><b>'.$info[nickname].'</b></font>\'s info about this list:<br/>';
        echo 'Friends: '.$info[friends].' ---> '.$data[user_friends].'<br/>';
        echo 'Follow: '.$info[follow].' ---> '.$data[user_following].'<br/>';
        echo 'Fans: '.$info[fans].' ---> '.$data[user_followers].'<p><p/>';
      } else {
        echo 'There has some problem.';
      }
    }
  }

  Public function randThat() {
    header('charset=UTF-8');
    $User = M('Users');
    $userCount = $User->count('ID');
    for ($i=1; $i < $userCount; $i++) {
      $uid[ID] = $i;
      $info = $User->field('user_nickname as nickname,user_followers as fans,user_friends as friends,user_following as follow')->where($uid)->find();
      $data[user_followers] = mt_rand(100,999);
      $data[user_friends] = mt_rand(100,999);
      $data[user_following] = mt_rand(100,999);
      if ($User->where($uid)->save($data)) {
        $inc[fri] = $data[user_friends]-$info[friends];
        $inc[fol] = $data[user_following]-$info[follow];
        $inc[fans] = $data[user_followers]-$info[fans];        
        echo '<p>Congratulations! <font color="red"><b>'.$info[nickname].'</b></font>\'s:<br/>'; 
        echo 'Friends Increased <font color="red"><b>'.$inc[fri].'</b></font> to '.$data[user_friends].' !<br/>';
        echo 'Follow Increased <font color="red"><b>'.$inc[fol].'</b></font> to '.$data[user_following].' !<br/>';
        echo 'Fans Increased <font color="red"><b>'.$inc[fans].'</b></font> to '.$data[user_followers].' !<p/>';
      } else {
        echo 'Something wrong.';
      }      
    }
  }

  Public function adjustImg () {
    header('UTF-8');
    $Image = M('Images');
    $map = array('user' => $_SESSION['id'],'image_album' => 7);
    $imageCount = $Image->where($map)->count();
    for ($i=1; $i < $imageCount; $i++) { 
      $result = $Image->where($map)->setField('image_album', 3);
      if ($result) {
        echo '图片'.$i.'转移成功!<br/>';
        $resultCount++;
      } else {
        echo '图片'.$i.'转移<font color="red">失败</font>!<br/>';
      }     
    }
    echo '转移成功!本次共转移 '.$resultCount.' 张图片';
  }
}