<?php
class BlogAction extends Action {
  //获取个人博客信息
  Public function proBlog() { 
    $uid = I('get.id');    //页面ID
    $cid = I('get.cid');  //分类CID

    //如果没有传人ID返回 
    if ( !$uid ) {
      $this->error('参数错误');
    }

    $Post = M("Posts");
    //分类显示
    if ($cid) {
      //禁止访问非自己的私密博文
      if ($cid == 2) {
        if ($uid != $_SESSION['id']) {
          $this->error('参数错误');
        }
      }            
    
      //取出文章列表
      $postList = $Post->where(array('user' => $uid, 'post_category' => $cid))->field('post_id,post_title,post_content,post_category,post_tag,post_createtime,readcount,comcount,reproduced')->order('post_id DESC')->select();                              
    //不显示分类下的博客内容（包含置顶文章）   
    } else {           
     $Top = M('Top');
     $topId = $Top->where(array('user' => $uid))->getField('post_id');  

     //有置顶文章，取出文章列表
     if ($topId) {
      //获取置顶文章
      $top = $Post->field('post_status,comment_status,post_category,post_synopsis,post_modify_time,reproduced_count', true)->where(array('post_id' => $topId))->find();
      $top['post_tag'] = explode(' ', $top['post_tag']);
      $top['post_content'] = cut_str(strip_tags($top['post_content'], '<br><p><b><img>'),0, 500);
      $this->assign('top', $top);  
      //获取其他人 有置顶的 去掉了私密博文分类的 文章列表     
      if ($uid != $_SESSION['id']) {
        $postList = $Post->where(array('user' => $uid,'post_id' => array('NEQ',$topId), 'post_category' => array('NEQ', 2)))->field('post_id,post_title,post_content,post_tag,post_createtime,readcount,comcount,reproduced')->order('post_id DESC')->select();

      } else {
        //获取自己有置顶的文章列表
        $postList = $Post->where(array('user' => $uid,'post_id' => array('NEQ',$topId)))->field('post_id,post_title,post_category,post_content,post_tag,post_createtime,readcount,comcount,reproduced')->order('post_id DESC')->select();
      }
                                                                   
     } else {       
      //获取其他人 去掉了私密博文分类的 文章 
      if ($uid != $_SESSION['id']) {
       $postList = $Post->where(array('user' => $uid, 'post_category' => array('NEQ', 2)))->field('post_id,post_title,post_content,post_tag,post_createtime,readcount,comcount,reproduced')->order('post_id DESC')->select();
        
      //获取自己的文章
      } else {
        $postList = $Post->where(array('user' => $uid))->field('post_id,post_title,post_content,post_tag,post_category,post_createtime,readcount,comcount,reproduced')->order('post_id DESC')->select();
      }                   
     }
    }

    $postList = getPreview($postList,count($postList)); 

     $param = array(
        'result' => $postList,      //分页用的数组或sql
        'listvar' => 'postList',      //分页循环变量
        'listRows' => 10,     //每页记录数
        'target' => 'ajax_content',  //ajax更新内容的容器id
        'pagesId' => 'page',    //分页后页的容器id不带
        'template' => 'Blog:article',//ajax更新模板
        'content' => 'article'
     );
            
     R('Pub/AjaxPage',array($param)); 

     //判断是否是自己的页面
     $Category = M("Category");
     $Apply = M("Apply");
     if ($uid != $_SESSION['id']) {
      $info = getInfo($uid);
      $cateList = $Category->where(array('user' => $uid, 'category_id' => array('NEQ', 2)))->field('id', true)->order('category_id ASC')->select();    //查询出分类列表
      if ($_SESSION['isLoginIn'] == 1) {
        $rela = getRela($uid);
        $info['isFriend'] = $rela['isFriend'];
        $info['isFollow'] = $rela['isFollow'];      
      }

     } else {
      $info = $_SESSION;
      //查询出分类列表
      $map = array('user' => $uid);
      $cateList = $Category->where($map)->field('id',true)->order('category_id ASC')->select();
      $cate_group = getEditCategory($uid);
      $operaUrl = getUrlType('blog');           

      $this->assign('operaUrl', $operaUrl);
      $this->assign('cate_group', $cate_group);
     }

    $navi = getNaviInfo($uid,1); //导航条
    $navi['index'] = 1; //1显示登录后或特定页面的导航条 
    $navi['jump'] = 1;  //用于JS判断删除文章是否跳转

    if ($cid == 2) {
      $navi['log_jump'] = 1;  //用于JS判断登出是否跳转
    }    
   
    $this->assign('navi',$navi);                   
    $this->assign('recommend', getRecommend());   
    $this->assign('cateList', $cateList);
    $this->assign('info', $info);
    $this->display();     
  }

  //获取博客页面信息
  Public function page() {
    $id = I('get.id');
    $postMap = array('post_id' => $id);

    $Post = D("Posts");
    //获取文章信息
    $article = $Post->relation(true)->where($postMap)->field('post_modify_time', true)->find();
    $uid = $article['user'];    //查找文章作者的条件
        
    //若为空
    if (!$article || !$id ) {
      $this->error('您要查看的页面不存在');
    }

    //切割标签
    $article['post_tag'] = explode(' ', $article['post_tag']);   
    $article['prev'] = $Post->where(array('user' => $uid, 'post_id' => array('lt',$id)))->order('post_id DESC')->field('post_id as id,post_title as title')->find();  //查询此user的上一篇文章post_id
    $article['next'] = $Post->where(array('user' => $uid, 'post_id' => array('gt',$id)))->field('post_id as id,post_title as title')->order('post_id ASC')->find();   //查询此user下的下一篇文章post_id

    if ($article['comcount']) {                      
      $commentList = D('CommentView')->where($postMap)->select();  //获取评论内容，时间，评论人ID

      $param = array(
        'result' => $commentList,      //分页用的数组或sql
        'listvar' => 'commentList',      //分页循环变量
        'listRows' => 10,     //每页记录数
        'target' => 'comment_list',  //ajax更新内容的容器id
        'pagesId' => 'fpage',    //分页后页的容器id不带
        'template' => 'Pub:comment_content',//ajax更新模板
      );
            
      R('Pub/AjaxPage',array($param));  
    }
              
    if (session('click') != $id) {  //判断刷新一次增加一次记录
      $Post->where($postMap)->setInc('readcount');   //否则则增加1
      session('click', $id);  //同时赋上文章ID到click 
    }

    //取得收藏数量   
    if ($_SESSION['id'] != $uid) {
      $rela = getRela($uid);
      $article['isFriend'] = $rela['isFriend'];
      $article['isFollow'] = $rela['isFollow'];
    }
    
    $navi = getNaviInfo($uid,1); //导航条
    $navi['comment_url'] = U('Pub/comment?do=article'); //评论URL
    $navi['del_com_url'] = U('Pub/deleteCom?del=article');  //删除评论URL
    $navi['index'] = 1; //1显示登录后或特定页面的导航条
    $article['message'] = $_SESSION['message'];

    if ($article['post_category'] == 2) {
      $navi['log_jump'] = 1;  //用于JS判断登出是否跳转
    }

    $this->assign('navi',$navi); //用于导航条ID 
    $this->assign('recommend', getRecommend());
    $this->assign('info', $article);  //文章内容
    $this->display();
  }

  //转载文章
  Public function reproduced() {
        //判断是否自行转载
        if (!session('isLoginIn')) {
            $this->ajaxReturn(U('Index/index'),'正在返回主页',0);
        }

        $pid = I('post.pid');
        $Post = M("Posts");
        $User = M('Users');
        $article = $Post->field('post_title,user,post_content,post_synopsis')->where(array('post_id' => $pid))->find();
        $uid = $article['user'];
        $title = $article['post_title'];
        $content = $article['post_content'];
        $author = $User->where(array('ID' => $uid))->getField('user_nickname');

        //添加转载后的相关版权信息
        $article['post_content'] = '<h3>原文：<a href='.U('Blog/Page?blog=1&id='.$pid).'>'.$title.'</a> 作者：<a href='.U('Blog/proBlog?blog=1&id='.$uid).'>'.$author.'</a></h3><p></p>'.$content;
        $article['post_title'] = '<span>[转载]</span>'.$title;       
        $article['post_createtime'] = time();
        $article['post_tag'] = '转载';
        $article['reproduced'] = 1;
        $article['user'] = $_SESSION['id'];
        $Post->where(array('post_id' => $pid))->setInc('reproduced_count');

        if ($Post->add($article)) {
            $this->success('转载成功!');
        } else {
            $this->error('转载失败');
        }
  }

  //发表
  Public function newBlog() {      
   //判断是否自动获取关键字
     $do = I('get.do');               
     if ($do == 'put') {
      $content = strip_tags($_POST['content']);
      $_SESSION['tags'] = getKeywordsStr($content);  //问题出在这里

     } elseif ($do == 'get') {
      if (isset($_SESSION['tags'])) {
        $this->ajaxReturn($_SESSION['tags'],'获取成功',1);
      } else {
        $this->ajaxReturn(0,'获取失败,请手动输入',0);
      }
       
     } else {
      $uid = $_SESSION['id'];
      $category = M("Category")->where(array('user' => $uid))->field('id,user', true)->order('category_id ASC')->select();
      $cate_group = getEditCategory($uid);  //获取编辑列表
      $operaUrl = getUrlType('blog');   //获取编辑地址
      $operaUrl['submit'] = U('Blog/postNew');
      $navi = getNaviInfo($uid,1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条
      $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

      $this->assign('navi',$navi); //用于导航条ID
      $this->assign('operaUrl', $operaUrl);
      $this->assign('cate_group', $cate_group);
      $this->assign('category', $category);
      $this->display();
     }
  }

  //发表文章
  Public function postNew() {
      $Post = D("Posts");
      if (!$Post->create()) {
       $this->error($Post->getError());
      }

      $Post->post_synopsis = cut_str(trim(strip_tags($Post->post_content)),0,50);
      $Post->post_createtime = time();
      $Post->user = $_SESSION['id'];
            
      $newId = $Post->add();   

      if ($newId) {
        $this->ajaxReturn(U("Blog/page?blog=1&id=".$newId),'添加文章成功',1);
      } else {
        $this->error('添加文章失败');
      }
  }

  //获取要修改的文章信息
  Public function editArticle() {
      $Post = M("Posts");
      $uid = $_SESSION['id'];
      $pid = I('get.id');
      $check = $Post->field('user,reproduced')->where(array('post_id' => $pid))->find();

      
      if ($uid != $check['user'] || $check['reproduced'] != 0) {
        $this->error('参数错误'); //作者才可以修改且不是转载的文章
      } else {            
        $Category = M("Category");
        $category = $Category->where(array('user' => $uid))->field('id,user', true)->order('category_id ASC')->select();

        $article = $Post->where(array('post_id' => $pid))->field('user_createtime,readcount,comcount', true)->find();
            
        $cate_group = getEditCategory($uid);  //获取编辑列表
        $operaUrl = getUrlType('blog');   //获取编辑地址
        $operaUrl['submit'] = U('Blog/edit?id='.$pid);
        $navi = getNaviInfo($uid,1); //导航条
        $navi['index'] = 1; //1显示登录后或特定页面的导航条
        $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

        $this->assign('navi',$navi);
        $this->assign('operaUrl', $operaUrl);
        $this->assign('cate_group', $cate_group);
        $this->assign('category', $category);
        $this->assign('article', $article);
        $this->display();
      }      
  }

  //修改文章内容
  Public function edit() {
      $Post = D("Posts");
      $pid = I('get.id');
        if (!$Post->create()) {
          $this->error($Post->getError());
        }

        $Post->post_synopsis = trim(strip_tags($Post->post_content));
        $Post->post_modify_time = time();
      
        if ($Post->where(array('post_id' => $pid))->save()) {
            $this->ajaxReturn(U('Blog/page?blog=1&id='.$pid),'修改文章成功',1);
        } else {
            $this->ajaxReturn(0,'修改文章失败',0);
        }
  }

  //查看关注
  Public function followBlog() {  
      if ($_SESSION['isLoginIn'] != 1) {
            $this->error('请重新登陆', U('index/index?hot=1'));
      }    
      
      $uid = $_SESSION['id'];
      $gid = I('get.gid');
      $followView = D('FollowView');
      //分组查看           
      if ($gid) {
        $map = array('user' => $uid, 'group_id' => $gid, 'post_category' => array('NEQ', 2));
        $postList = $followView->where($map)->order('post_id DESC')->select();

      } else { 
        $map = array('user' => $uid, 'post_category' => array('NEQ', 2));      
        $postList = $followView->where($map)->order('post_id DESC')->select();       
      }
          
      $param = array(
          'result' => $postList,      //分页用的数组或sql
          'listvar' => 'postList',      //分页循环变量
          'listRows' => 20,     //每页记录数
          'target' => 'fb_content',  //ajax更新内容的容器id
          'pagesId' => 'fpage',    //分页后页的容器id不带
          'template' => 'Blog:fb_content',//ajax更新模板
      );
            
      R('Pub/AjaxPage',array($param));  
      $Group = M('Group');
      //分组列表
      $groupList = $Group->field('group_id,group_name')->where(array('user' => $uid, 'group_meta' => 1))->order('group_id ASC')->select();
      $cate_group = $Group->where(array('user' => $uid, 'group_meta' => 1))->order('group_id ASC')->field('group_id as cg_id,group_name as name')->select(); //生成分组列表
      $operaUrl = getUrlType('follow'); //分类操作url

      $info = $_SESSION;
      $navi = getNaviInfo($uid,1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条
      $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

      $this->assign('operaUrl', $operaUrl);
      $this->assign('cate_group', $cate_group);   //输出分组列表
      $this->assign('navi',$navi);
      $this->assign('groupList', $groupList);
      $this->assign('recommend', getRecommend());
      $this->assign('info',$info);
      $this->display();
  }

  //查看收藏
  Public function collectBlog () {
      if ($_SESSION['isLoginIn'] != 1) {
        $this->error('请登陆', U('index/index'));
      }

      $uid = $_SESSION['id'];
      //文章列表                  
      $postList = D('CollectView')->where(array('user' => $uid))->order('collect_id DESC')->select();

      $param = array(
          'result' => $postList,      //分页用的数组或sql
          'listvar' => 'postList',      //分页循环变量
          'listRows' => 20,     //每页记录数
          'target' => 'cb_content',  //ajax更新内容的容器id
          'pagesId' => 'fpage',    //分页后页的容器id不带
          'template' => 'Blog:cb_content',//ajax更新模板
      );

      $navi = getNaviInfo($uid,1); //导航条
      $navi['index'] = 1; //1显示登录后或特定页面的导航条
      $navi['log_jump'] = 1;  //用于JS判断登出是否跳转

      $info = $_SESSION;      
      R('Pub/AjaxPage',array($param));
      $this->assign('info',$info);
      $this->assign('navi',$navi); //用于导航条ID 
      $this->assign('recommend', getRecommend());              
      $this->display();
  }

  //设置收藏
  Public function setCollect () {
        $Collect = M("Collect");
        $Users = M("Users");
        $uid = $_SESSION['id'];
        $aid = I('post.aid');
        $pid = I('post.pid');
        $action = $_GET['action'];

        //如果不是取消收藏则执行收藏动作
        if ($action != 'uncollect') {
            if ($Collect->add(array('user' => $uid ,'collect_id' => $pid, 'post_author' => $aid))) {      
                $Users->where(array('ID' => $uid))->setInc('user_collect');
                $_SESSION['collect']++;
                $_SESSION['collectId'] = $_SESSION['collectId'].",".$pid;     
                $this->ajaxReturn(1,'收藏成功',1);

            } else {
                $this->ajaxReturn(0,'收藏失败',0);
            }

        } else {
            if ($Collect->where(array('collect_id' => $pid, 'user' => $uid))->delete()) {
              $Users->where(array('ID' => $id))->setDec('user_collect');
              $_SESSION['collect']--;
              $_SESSION['collectId'] = checkCollect($uid); 
              $this->ajaxReturn(1,'取消收藏成功',1);
            } else {
              $this->ajaxReturn(0,'取消失败',0);
            }  
        }     
  }

  //删除文章
  Public function deleteArticle() {
    $Top = M("Top");
    $pid = I('post.pid');
    $uid = $_SESSION['id'];

    if (I('post.uid') != $uid) {
      $this->error('操作失败'); //若不是自己的文章则禁止删除
    }

    if (M("Posts")->where(array('post_id' => $pid))->delete()) {
      //删除收藏目录中对应的文件
      M("Collect")->where(array('collect_id' => $pid))->delete();
      //判断是否置顶文章
      if ($Top->where(array('user' => $uid,'post_id' => $pid))->count()) {
        $Top->where(array('user' => $uid))->setField('post_id',0);
      }

      $this->ajaxReturn(U('Blog/proBlog?blog=1&id='.$_SESSION['id']),'删除文章成功',1);           
        
      } else {
        $this->error('文章删除失败');
      }  
  }

  //设置置顶
  Public function setTop() {
        $Top = M('Top');
        $uid = $_SESSION['id'];
        $pid = I('post.pid');
        $set = I('get.set');
        $user = $Top->field('user')->where(array('user' => $uid))->find();
        
        if ($set != 'untop') { //判断是置顶还是取消置顶
          
          if ($user) {  //判断用户之前是否已经设置过置顶
            if ($Top->where($user)->setField('post_id', $pid)) {
                $this->success('置顶成功！');
            } else {
                $this->error('置顶失败');
            }

          } else {  //没有设置过则添加
            if ($Top->add(array('user' => $uid,'post_id' => $pid))) {
                $this->success('置顶成功！');
            } else {
                $this->error('置顶失败');
            }
          }

        } else {  //取消置顶操作
           if ($Top->where($user)->setField('post_id', 0)) {
                  $this->success('取消置顶成功！');
              } else {
                  $this->error('取消置顶失败');
              }
        }                 
  }

  //分类操作和显示
  Public function selectC() {
      $Category = D("Category");
      $Post = M("Posts");
      $cateInfo = $Category->create();

      if (!$cateInfo) {
       $this->error($Category->getError());
      }

      $cid = I('post.cg_id');
      $pid = I('post.pf_id');
      $uid = $_SESSION['id'];

      $action = I('get.action');
      //若没有参数则是设置分类操作
      switch ($action) {
        //移动
        case 'move': 

          if ($Post->where(array('post_id' => $pid))->setField(array('post_category' => $cid, 'post_modify_time' => time()))) {
              $this->ajaxReturn(1,'转移成功！',1);
          } else {
              $this->ajaxReturn(0,'转移失败',0);
          }  

          break;

        //新增
        case 'add':

          $max = $Category->where(array('user' => $uid))->max('category_id');     //获取用户分类最大的category_id
          $cateInfo['id'] = $Category->category_id = ++$max;
          $Category->user = $uid;    //生成新的category_id为基数+1

          if ($Category->add()) {
            $this->ajaxReturn($cateInfo,'新增分类成功',1);
          } else {
            $this->ajaxReturn(0,'新增分类失败',0);
          }

          break;

        //删除
        case 'del': 

         if ($Category->where(array('user' => $uid, 'category_id' => $cid))->delete()) {           
            $Post->where(array('user' => $uid, 'post_category' => $cid))->setField('post_category', 1);
            //将该文章下的分组清零
            $this->ajaxReturn(1,'删除成功',1);
         } else {
            $this->ajaxReturn(0,'删除失败',0);
         }    

          break;

        //修改分组名
        case 'edit': 

         if ($Category->where(array('user' => $uid, 'category_id' => $cid))->save()) {
          $this->ajaxReturn(1,'修改分类名成功',1);
         } else {
          $this->error('修改失败');
         } 

          break;
      }           
  }
}