<?php
      //$sourcestr 是要处理的字符串
  //$cutlength 为截取的长度(即字数)
  //$startstr 开始截取的位置
function cut_str($sourcestr,$startstr,$cutlength) {
    $returnstr='';
    //$i=0;
    $i = $startstr;
    $n=0;
    $str_length=strlen($sourcestr);//字符串的字节数
    
    while (($n<$cutlength) and ($i<=$str_length)) {
        $temp_str=substr($sourcestr,$i,1);
        $ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码
        if ($ascnum>=224) { //如果ASCII位高与224，
            $returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i=$i+3; //实际Byte计为3
            $n++; //字串长度计1
        } elseif ($ascnum>=192) { //如果ASCII位高与192，
            $returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i=$i+2; //实际Byte计为2
            $n++; //字串长度计1
        } elseif ($ascnum>=65 && $ascnum<=90) {//如果是大写字母，
            $returnstr=$returnstr.substr($sourcestr,$i,1);
            $i=$i+1; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        } else {//其他情况下，包括小写字母和半角标点符号，
            $returnstr=$returnstr.substr($sourcestr,$i,1);
            $i=$i+1; //实际的Byte数计1个
            $n=$n+0.5; //小写字母和半角标点等与半个高位字符宽...
        }
    }

        $str_length>$cutlength+8 ? $returnstr = $returnstr . "..." : $returnstr = $returnstr;//超过长度时在尾处加上省略号
        
        return $returnstr;
}

//获取关键字
function getKeywordsStr($content) { 
   Vendor('Pscwc.PhpAnalysis','','.class.php');  
   PhpAnalysis::$loadInit = false;
   $pa = new PhpAnalysis('utf-8', 'utf-8', false);
   $pa->LoadDict();
   $pa->SetSource($content);
   $pa->StartAnalysis(false);
   $tags = $pa->GetFinallyResult();
   //return $pa->GetFinallyResult();
   return $tags;
}

//获取推荐
function getRecommend() {
    return M("Posts")->where(array('readcount' => array('gt', 3), 'post_category' => array('NEQ', 2), 'reproduced' => 0))->field('post_author,user,post_category,post_tag,post_content,post_createtime,post_modify_time,readcount,comcount', true)->limit(8)->order('comcount DESC')->select();    
}

  //获取收藏ID
function checkCollect($id) {
    $collect_id = M("Collect")->field('id,user,post_author', true)->where(array('user' => $id))->select();

    //遍历取出的收藏列表的文章ID
    foreach ($collect_id as $collect) {
      foreach ($collect as $value) {
         $collectId .= $value.',';
      }
    }
    return substr($collectId, 0, -1); //去掉最后的','
} 

//登陆验证
function checkLogin() {
    if (session('isLoginIn') != 1) {
      $this->error('请先登录',U('index/login'));
    }
}

//获取用户信息
function getInfo($id,$mode='part') {
    if ($mode == 'part') {
      $field = 'user_avatar as avatar,user_nickname as nickname,user_following as following,user_followers as followers,user_visits as visits';
    } elseif ($mode == 'all') {
      $field = 'user_avatar as avatar,user_nickname as nickname,user_following as following,user_followers as followers,user_birthday as birthday,user_location as location,user_intro as intro,user_visits as visits,user_friends as friends,user_sex as sex,sex_secret as secret';
    }

    $info = M("Users")->field($field)->where(array('ID' => $id))->find();
    if ($mode == 'all') {
      $info['location'] = explode(' ', $info['location']);
      $info['address'] = M('Areas')->field('area_name')->where(array('area_id' => array('in', array($info['location'][0],$info['location'][1]))))->select();
    }
    return $info;
}

//获取关系
function getRela($uid) {
  $id = $_SESSION['id'];
  $friendMap = array('user' => $id, 'friend_id' => $uid);
  $applyMap = array('user' => $id, 'applicant_id' => $uid,'apply_type' => array('in','1,2'));
  $followMap = array('user' => $id, 'follow_id' => $uid);
  if ( M("Friends")->where($friendMap)->count()) {
    $rela['isFriend'] = 1;     
  } elseif (M('Apply')->where($applyMap)->count()) {
    $rela['isFriend'] = -1;
  } else {
    $rela['isFriend'] = 0;
  }

  //确认是否已关注 
  if ( M("Follow")->where($followMap)->count()) {
    $rela['isFollow'] = 1;    
  } else {
    $rela['isFollow'] = 0;
  }
  return $rela;
}

function getRelaList($id,$count=4,$rela=1){
  $userMap = array('user' => $id);
  $fansMap = array('follow_id' => $id);
  //$rela:1、好友。2、关注。3、粉丝
  switch ($rela) {
    case '1':
      //好友列表
      $friendList = D("FriendsView")->where($userMap)->field('friend_id,nickname,avatar')->limit($count)->order('id DESC')->select();
      //截取名称显示长度
      return $friendList = cutInfo($friendList,$count,6);
        break;

    case '2':
      //关注列表
      $followList = D("ProFollowView")->field('nickname,avatar,following,followers,follow_id')->where($userMap)->limit($count)->order('id DESC')->select();
      return $followList = cutInfo($followList,$count);
        break;  
    
    default:
      //粉丝列表
      $fansList = D("FollowerView")->field('nickname,avatar,following,followers,user')->where($fansMap)->limit($count)->order('id DESC')->select();

      return $fansList = cutInfo($fansList,$count);
        break;
  }
}

//剪裁个人信息以更好显示,$relaList为信息列表,$count显示次数,$length剪切名称长度,$intro_len剪切信息长度
function cutInfo($relaList,$count,$length=6,$intro_len=5) {
  for ($i=0; $i < $count; $i++) { 
    if ($relaList[$i]['nickname']) {
      $relaList[$i]['cut_nickname'] = cut_str($relaList[$i]['nickname'],0,$length);  
    }

    if ($relaList[$i]['intro']) {
      $relaList[$i]['cut_intro'] = cut_str($relaList[$i]['intro'],0,$intro_len);             
    }
  }
  return $relaList;
}

//获取博客预览字段
function getPreview($list,$count) {
  for ($i=0; $i< $count; $i++) {
    if ($list[$i]['post_tag']) {
      $list[$i]['post_tag'] = explode(' ', $list[$i]['post_tag']);
    }

    if ($list[$i]['post_content']) {
      $list[$i]['post_content'] = cut_str(strip_tags($list[$i]['post_content'],'<br><p><b><img>'), 0, 300);
    }  
  }
  return $list;
}

function getEditCategory($uid) {
  $map = array('user' => $uid,'category_id' => array('GT',2));  //大于2才能编辑
  $cate_group = M('Category')->where($map)->field('user,category_id as cg_id,category_name as name')->order('category_id ASC')->select();
      //分类操作地址 
  return $cate_group;
}

function getUrlType($type) {
  switch ($type) {
    case 'blog':
      $operaUrl['edit'] = U('Blog/selectC?action=edit');
      $operaUrl['add'] = U('Blog/selectC?action=add');
      $operaUrl['del'] = U('Blog/selectC?action=del');
      $operaUrl['move'] = U('Blog/selectC?action=move');
      return $operaUrl;
      
      break;
    
    case 'friend':
      $operaUrl['edit'] = U('profile/makeGroup?action=name&meta=2');
      $operaUrl['add'] = U('profile/makeGroup?action=add&meta=2');
      $operaUrl['del'] = U('profile/makeGroup?action=del&meta=2');
      $operaUrl['move'] = U('profile/makeGroup?action=move&meta=2');
      return $operaUrl;

      break;

    case 'follow':
      $operaUrl['edit'] = U('profile/makeGroup?action=name&meta=1');
      $operaUrl['add'] = U('profile/makeGroup?action=add&meta=1');
      $operaUrl['del'] = U('profile/makeGroup?action=del&meta=1');
      $operaUrl['move'] = U('profile/makeGroup?action=move&meta=1');

      return $operaUrl;
      break;

    case 'pic':
      $operaUrl['edit'] = U('Pic/editAlbum?action=name');
      $operaUrl['add'] = U('Pic/editAlbum?action=add');
      $operaUrl['del'] = U('Pic/editAlbum?action=del');
      $operaUrl['move'] = U('Pic/editAlbum?action=move');
      return $operaUrl;
      break;    
  }   
}

function getNaviInfo($id,$type) {
  if ($id == $_SESSION['id']) {
    if ($type == 1) {
      $navi['new'] = U('Blog/newBlog?new=1'); //新博客URL
      $navi['name'] = '写博客'; //名称
    } else {
      $navi['new'] = U('Pic/poNew?new=1');  //上传图片URL
      $navi['name'] = '发照片'; //名称
    }             
  }

  $navi['user'] = $id;
  return $navi;
}
