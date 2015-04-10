<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="__PUBLIC__/images/common/main/favicon.ico" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="__PUBLIC__/css/style.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/css/btn.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/css/backtotop.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/jquery-ui/swanky-purse/jquery-ui-1.10.3.custom.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery-ui/jquery-ui-1.10.3.custom.min.js"></script>

<title>果冻写_欢迎来到果冻写</title>
</head>
<body>
<div class="main">
  <div class="header">
   <div class="header_resize">
      <div class="logo">
        <h1><a href="<?php echo U('index/index?hot=1');?>">果冻<span>写</span></a> <small><span>写生活</span>享生活</small></h1>
      </div>
      <div class="clr"></div>
      <div class="menu_nav">
        <ul>
          <?php if(($_GET['hot']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/index?hot=1');?>">热门博客</a></li>		  
		 <?php if(($navi["index"]) == "1"): if(($navi["user"]) == $_SESSION['id']): if(($_GET["follow"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/followBlog?follow=1');?>">关注</a></li><?php endif; ?>
		  <?php if(($_GET["blog"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/proBlog?blog=1&id='.$navi['user']);?>">博客</a></li>
		  
          <?php if(($_GET["pic"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Pic/proPic?pic=1&id='.$navi['user']);?>">照片</a></li>
          <?php if(($_GET["about"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Profile/index?about=1&id='.$navi['user']);?>">关于我</a></li>
		  
		<?php if(($navi["user"]) == $_SESSION['id']): if(($_GET["new"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo ($navi["new"]); ?>"><?php echo ($navi["name"]); ?></a></li><?php endif; ?>
		<?php else: ?>		
		<li><a href="<?php echo U('Index/login?login=1');?>">登陆</a></li>
		  <li><a href="<?php echo U('Index/register?join=1');?>">加入果冻</a></li><?php endif; ?> 
        </ul>
      </div>
      <div class="searchform">
        <form id="formsearch" method="get" action="<?php echo U('Pub/search');?>">
          <span><input name="search" class="editbox_search" type="text" value="搜博客" onFocus="clearText(this)" onBlur="clearText(this)" /></span>
          <input type="submit" class="button orange bigrounded" value="搜一下" />
        </form>
      </div>
      <div class="clr"></div>
    </div>
			<script type="text/javascript">
		//点击输入框提示消失
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}
		</script>
  </div>

  <div class="content">
    <div class="content_resize"><div class="content_resize2">
      <div class="mainbar">
        <div class="article">
		<h3>测试账号:<span>admin1</span>——<span>admin3</span>,密码和账号<span>相同</span></h3>
         <?php if(is_array($postList)): $i = 0; $__LIST__ = $postList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m): $mod = ($i % 2 );++$i;?><h2><a href="<?php echo U('Blog/Page?blog=1&id='.$m['post_id']);?>" target="_blank"><?php echo ($m["post_title"]); ?></a></h2>
            <div class="clr"></div>
          <p><span class="date"> 博主: <a href="<?php echo U('Blog/proBlog?blog=1&id='.$m['user']);?>"><?php echo ($m["nickname"]); ?></a> &nbsp;</span> &nbsp;|&nbsp; 时间: <?php echo (date('Y-m-d H:i',$m["post_createtime"])); ?></p>
		  <div class="clr"></div>
		  <p><span class="date">标签: 
		      <?php if(is_array($m["post_tag"])): foreach($m["post_tag"] as $key=>$sub): ?><a href="<?php echo U('Pub/search?search='.$sub);?>"><?php echo ($sub); ?></a>&nbsp;&nbsp;<?php endforeach; endif; ?></span></p>
          <p><?php echo ($m["post_content"]); ?></p>
         <p class="spec"><span>(<?php echo ($m["readcount"]); ?>) 阅读</span> &nbsp;|&nbsp; <?php if(!empty($_SESSION['isLoginIn'])): if(in_array(($m["post_id"]), is_array($_SESSION["collectId"])?$_SESSION["collectId"]:explode(',',$_SESSION["collectId"]))): ?><a href="javascript:;" onClick="uncol_art(this)" aid="<?php echo ($m["user"]); ?>" pid="<?php echo ($m["post_id"]); ?>">取消收藏</a><?php else: ?> <a href="javascript:;" onClick="col_art(this)" aid="<?php echo ($m["user"]); ?>" pid="<?php echo ($m["post_id"]); ?>">收藏</a><?php endif; ?> &nbsp;|&nbsp;<?php endif; ?><a href="<?php echo U('Blog/Page?blog=1&id='.$m['post_id']);?>">查看全文 &raquo;</a> <a href="<?php echo U('Blog/Page?blog=1&id='.$m['post_id']);?>#comment" class="com"><span><?php echo ($m["comcount"]); ?> 评论</span></a></p><?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="clr"></div>
        <p class="pages"><small>&nbsp;&nbsp;&nbsp;</small><?php echo ($page); ?></p>
		<script type="text/javascript">
//取消收藏
function uncol_art(param){
  if (confirm('确定取消吗?')) {  
  var pid = $(param).attr('pid');
  var action = $(param).attr('action');    
  $.post('<?php echo U('Blog/setCollect?action=uncollect');?>',
        {pid:pid},
        function(data){
         if (action == 1) {
          $(param).parent().parent().hide(500);          
         } else {
          var aid = $(param).attr('aid');  
          $(param).replaceWith('<a href="javascript:;" onclick="col_art(this)" aid="'+ aid +'" pid="'+ pid +'">收藏</a>'); 
         }            
  });
  } 
}
 
//收藏
function col_art(param){
  var pid = $(param).attr('pid');
  var aid = $(param).attr('aid');  
  $.post('<?php echo U('Blog/setCollect?action=collect');?>',
        {pid:pid,aid:aid},
        function(data){        
        $(param).replaceWith('<a href="javascript:;" onclick="uncol_art(this)" aid="'+ aid +'" pid="'+ pid +'">取消收藏</a>');      
  });
}
</script> 
		</div>
      </div>
      <div class="sidebar">
	  <?php if(empty($_SESSION['isLoginIn'])): ?><div class="gadget">
          <div class="clr"></div>
		  <ul class="sb_menu">
		            	<p><a href="<?php echo U('Index/register?join=1');?>" style="float:right"><span class="login_title">加入果冻!</span></a></p><br/>
			<li><span class="login_title">账号:</span>
		    <input type="text" class="small" id="uname"/></li>
		  <li><span class="login_title">密码:</span>
		    <input type="password" class="small" id="pword"/></li>
		  <li><input type="button" value="登陆果冻" class="button orange bigrounded" id="login"/>
		    <a href="<?php echo U('index/findpw?find=1');?>" style="margin-left:10px;"><span class="login_title">忘记密码?</span></a></p>		    
			<div id="login_error" class="result"></div>
			</li>
<script type="text/javascript">
$(function($){
 $('#login').click(function(){	//登陆
  $.post('<?php echo U('Index/loginin');?>',
        {uname:$('#uname').val(),pword:$('#pword').val()},
      	function(data){
	 	 if (data.status == 1){
	 	  <?php if(($_GET["login"]) == "1"): ?>//判断是否登陆页面登陆	
	 	  var content = '<a href="'+data.data+'" id="href"><span class="cate_sel">登陆成功！页面将在 <b id="wait_time">2</b> 秒后跳转，如不想等待请点击此处。</span></a>';
	 	  $('.article').html(content);
	 	  $('.menu_nav').html(' ');
	 	  $('.fmenu').html(' ');
	 	  var wait = document.getElementById('wait_time');
		  var interval = setInterval(function(){			
		   var time = --wait.innerHTML;
		   if(time <= 0) {
		   	location.href = data.data;
			clearInterval(interval);
		   }
		  },1000);
	 	  <?php else: ?>
	 	  var prompt = '<a href="javascript:location.reload();" id="href">页面将在 <b id="wait_time">2</b>秒后跳转，如不想等待请点击此处。</a>';						
	 	  $('#login_success').html(prompt);
	 	  $('#login_success').dialog('open');<?php endif; ?>		  		  
	 	 } else {
	 	  $('#login_error').text(data.info).show();
		  setTimeout(function(){ $('#login_error').hide()},2000);
	     }
   });
 });
});
</script>
		  </ul>
		  </div>
		  <?php else: ?>
		  <div class="gadget">
<h2 class="star"><?php echo ($info["nickname"]); ?></h2>
          <div class="clr"></div>
          <ul class="sb_menu">		  
		    <?php if(!empty($info["unread_apply"])): ?><li><a href="<?php echo U('Profile/reminder?list=friend');?>"><span class="prompt">您有 <span class="apply"><?php echo ($info["unread_apply"]); ?></span> 条未读好友申请</span></a></li><?php endif; ?>
		  <?php if(!empty($info["unread_message"])): ?><li><a href="<?php echo U('Profile/reminder?list=message');?>"><span class="prompt">您有 <span class="apply"><?php echo ($info["unread_message"]); ?></span> 条未读留言</span></a></li><?php endif; ?>
		  <?php if(!empty($info["unread_comment"])): ?><li><?php if(empty($comment)): ?><a href="<?php echo U('Profile/reminder?list=pic_comment');?>"><?php else: ?><a href="<?php echo U('Profile/reminder?list=comment');?>"><?php endif; ?><span class="prompt">您有 <span class="apply"><?php echo ($info["unread_comment"]); ?></span> 条未读评论</span></a></li><?php endif; ?>
		  <li><?php if(($navi["user"]) == $_SESSION['id']): ?><a href="<?php echo U('Profile/editProfile?about=1&set=avatar');?>"><img id="avatar" src="<?php echo ($info["avatar"]); ?>" /></a><?php else: ?><img id="avatar" src="<?php echo ($info["avatar"]); ?>" /><?php endif; ?></li>
		  <li><span class="info">关注博客:<a href="<?php echo U('Profile/followlist?follow=1&id='.$navi['user']);?>" target="_blank"><?php echo ($info["following"]); ?></a></span></li>	
		  <?php if(($navi["user"]) == $_SESSION['id']): ?><li><span class="info">我的收藏:<a href="<?php echo U('Blog/collectBlog?blog=1');?>" target="_blank"><?php echo ($info["collect"]); ?></a></span></li><?php endif; ?>
		  <li><span class="info">博客访问:<?php echo ($info["visits"]); ?></span></li>
		  <li><span class="info">关注人气:<a href="<?php echo U('Profile/fanslist?about=1&id='.$navi['user']);?>" target="_blank"><?php echo ($info["followers"]); ?></a></span></li>
		  <?php if(!empty($_SESSION['isLoginIn'])): if(($navi["user"]) == $_SESSION['id']): ?><li><a href="<?php echo U('Profile/reminder?list=friend');?>"><span class="apply">消息中心(<?php if(empty($unread_news)): ?><span><?php else: ?><span class="retitle"><?php endif; echo ($info["unread_news"]); ?></span>)</span></a></li>
		  <li><button class="button gray large" onClick="logout()">登出</button></li>		
		  <?php else: ?>
		  <li><?php if(($info["isFollow"]) == "0"): ?><a href="javascript:;" id="follow" fid="<?php echo ($navi["user"]); ?>"><span class="rela_info">关注Ta</span></a>
			<a href="javascript:;" id="unfollow" fid="<?php echo ($navi["user"]); ?>"><span id="new_fol" style="display:none"><span id="followed" class="rela_info">√已关注</span><span id="unf_btn" class="prompt" style="display:none">取消关注</span></span></a>
		      <?php else: ?>
		      <a href="javascript:;" id="unfollow" fid="<?php echo ($navi["user"]); ?>"><span id="new_fol"><span id="followed" class="rela_info">√已关注</span><span id="unf_btn" class="prompt" style="display:none">取消关注</span></span></a>
			  <a href="javascript:;" id="follow" fid="<?php echo ($navi["user"]); ?>" style="display:none"><span class="rela_info">关注Ta</span></a><?php endif; ?>
		  </li>
		  <li><?php switch($info["isFriend"]): case "0": ?><a href="javascript:;" id="add_fri"><span class="rela_info">加好友</span></a>
		  <span id="wait_app" style="display:none"><span class="info">等待对方验证</span> &nbsp;您可以 <a href="javascript:;" id="un_app" fid="<?php echo ($navi["user"]); ?>"><span class="prompt">取消申请</span></a></span><?php break;?>
		  <?php case "-1": ?><span id="wait_app"><span class="info">等待对方验证</span> &nbsp;您可以 <a href="javascript:;" id="un_app" fid="<?php echo ($navi["user"]); ?>"><span class="prompt">取消申请</span></a></span>
		  <a href="javascript:;" id="add_fri" style="display:none"><span class="rela_info">加好友</span></a><?php break;?>
		  <?php default: ?>
		  <span id="now_fri">√<span class="info">朋友</span> &nbsp;<a href="javascript:;" id="break" fid="<?php echo ($navi["user"]); ?>"><span class="prompt">解除关系</span></a></span>
		  <a href="javascript:;" id="add_fri" style="display:none"><span class="rela_info">加好友</span></a>
		  <span id="wait_app" style="display:none"><span class="info">等待对方验证</span> &nbsp;您可以 <a href="javascript:;" id="un_app" fid="<?php echo ($navi["user"]); ?>"><span class="prompt">取消申请</span></a></span><?php endswitch;?>
		  <div id="apply_area" style="display:none;">
		  <input type="hidden" id="fri_id" value="<?php echo ($navi["user"]); ?>" />
		  <p><span class="prompt">打个招呼吧</span>(15字以内):
		  <input type="text" id="app_mes" class="small" value="Hi！能交个朋友吗？"/><br/>
		  <input type="button" value="发送" class="button white" id="send_apply"/>
		  <input type="button" value="取消" id="cancel" class="button gray" /></p>
		  </div>
		  </li>
		  <script type="text/javascript">
$(function($){
 //加关注
 $('#follow').click(function(){
 var fid = $('#follow').attr('fid');
  $.post('<?php echo U('Profile/rela?set=follow');?>',
     {fid:fid},
     function(data){
      $('#follow').hide();
      $('#new_fol').show();
  });
 });
 
 //鼠标移动到关注上面显示效果
 $('#new_fol').mouseenter(function(){
  $('#followed').hide();
  $('#unf_btn').show();
 });
 
 $('#new_fol').mouseleave(function(){
  $('#followed').show();
  $('#unf_btn').hide();
 });
 
 //取消关注
 $('#unfollow').click(function(){
  var fid = $('#unfollow').attr('fid');
  $.post('<?php echo U('Profile/rela?set=unFollow');?>',
     {fid:fid},
     function(data){
      $('#new_fol').hide();
      $('#follow').show();
  });
 });
 
  //显示加好友表单
 $('#add_fri').click(function(){
  $('#apply_area').show(500);
  $('#add_fri').hide();
 });
 
  //关闭加好友表单
 $('#cancel').click(function(){
  $('#add_fri').show(500);
  $('#apply_area').hide();
 });
 
 //提交添加好友申请
 $('#send_apply').click(function(){
  var fid = $('#fri_id').val();
  var app_mes = $('#app_mes').val();
  $.post('<?php echo U('profile/rela?set=apply');?>',
        {friend:fid,message:app_mes},
		 function(data){
		  $('#apply_area').hide();
		  $('#wait_app').show();
  });
 });
 
 //取消申请
 $('#un_app').click(function(){
  var fid = $('#un_app').attr('fid');
  $.post('<?php echo U('Profile/rela?set=unApply');?>',
        {fid:fid},
  		function(data){
	     $('#wait_app').hide();
		   $('#add_fri').show();	
  });
 });
 
 //解除关系
 $('#break').click(function(){
  if(confirm('确定和TA解除朋友关系吗？')){
   var fid = $('#break').attr('fid');
   $.post('<?php echo U('Profile/rela?set=reFriend');?>',
    {fid:fid},
	function(){
     $('#now_fri').hide();
	   $('#add_fri').show();
   });
  }
 }); 
});
</script><?php endif; endif; ?> 
          </ul>
		  </div><?php endif; ?>
        
        <div class="gadget">
          <h2 class="star">推荐博文</h2>
          <div class="clr"></div>
          <ul class="ex_menu">
            <?php if(is_array($recommend)): $i = 0; $__LIST__ = $recommend;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$re): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Blog/page?blog=1&id='.$re[post_id]);?>" target="_blank"><span class="retitle"><?php echo ($re["post_title"]); ?></span></a><br />
              <span class="resynopsis"><?php echo ($re["post_synopsis"]); ?>...</span></li>
			  <p class="spec"></p><?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
        </div>
		</div>
      <div class="clr"></div>
    </div></div>
  </div>
<div class="footer">
    <div class="footer_resize">
      <p class="lf"><?php if(empty($_SESSION['isLoginIn'])): ?><button class="button orange bigrounded" id="open_login_dialog">登陆</button><?php else: ?><button class="button gray" onclick="logout()">登出</button><?php endif; ?> 
        Copyright &copy; 2013 <a href="<?php echo U('index/index?hot=1');?>">www.Jellong.com</a>, All Rights Reserved</p>
      <ul class="fmenu">
	  <?php if(($_GET['hot']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/index?hot=1');?>">热门博客</a></li>
	  <?php if(($_SESSION['isLoginIn']) == "1"): if(($_GET['follow']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/followBlog?follow=1');?>">我的关注</a></li>
          <?php if(($_GET['blog']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/proBlog?blog=1&id='.$_SESSION['id']);?>">我的博客</a></li>	  
          <?php if(($_GET['pic']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Pic/proPic?pic=1&id='.$_SESSION['id']);?>">我的照片</a></li>
          <?php if(($_GET['about']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Profile/index?about=1&id='.$_SESSION['id']);?>">关于我</a></li>
		  <?php if(($_GET['new']) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('Blog/newblog?new=1');?>">写博客</a></li>
		  <div id="login_out" title="登出成功！"></div>	
<script type="text/javascript">
$(function($){
 //登出成功显示
 $('#login_out').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	draggable: false,
	open: function(event,ui){	
		var wait = document.getElementById('wait_time');
		var interval = setInterval(function(){			
			var time = --wait.innerHTML;
			if(time <= 0) {
			 location.href = $('#href').attr('href');
			 clearInterval(interval);
			}
		},1000);						
	}
 });
});

//登出
function logout(){
 var jump = '<?php echo ($navi["log_jump"]); ?>';
 $.post('<?php echo U('Index/loginOut');?>',
        function(data){
		 if(data.status == 1){
		  if (jump == 1) {
		  	var prompt = '<a href="'+data.data+'" id="href">页面将在 <b id="wait_time">1</b> 秒后跳转，如不想等待请点击此处。</a>';
		  } else {
		  	var prompt = '<a href="javascript:location.reload();" id="href">页面将在 <b id="wait_time">1</b> 秒后跳转，如不想等待请点击此处。</a>';
		  }
		  $('#login_out').on('dialogclose', function( event, ui ) {location.href = $('#href').attr('href')}); 	
		  $('#login_out').html(prompt);	 
		  $('#login_out').dialog('open');
		 } else {
		  alert(data.info);
		 }
 });
}
</script>	
		  <?php else: ?>
		  <li><a href="<?php echo U('Index/login?login=1');?>">登陆</a></li>
		  <li><a href="<?php echo U('Index/register?join=1');?>">加入果冻</a></li>
		  <div id="login_dialog" title="用户登陆">
		  <p><span class="login_title">账号：</span><input type="text" class="small" id="login_name"/></p>
<p><span class="login_title">密码：</span><input type="password" class="small" id="login_pword"/></p>
<input type="button" value="登陆果冻" class="button orange bigrounded" id="dialog_login" />
			<a href="<?php echo U('index/findpw?find=1');?>" class="btn btn-inverse" style="margin-left:10px;">忘记密码?</a><br/>
		    <a href="<?php echo U('Index/register?join=1');?>" style="float:right">还没有账号?立刻加入果冻！</a></p>
		<span id="login_dialog_error" class="result"></span>
		<div id="login_success" title="登陆成功！"></div>
		<script type="text/javascript">
$(function($){	//登录框方法
 $('#login_dialog').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	draggable: false
 });
		
 //打开对话框		
 $('#open_login_dialog').click(function() {
  $('#login_dialog').dialog('open');
 });

 //登陆成功显示
 $('#login_success').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	draggable: false,
	close: function(event,ui) {location.href = $('#href').attr('href')},
	open: function(event,ui){				
		//var wait = document.getElementById('wait_time');
		var interval = setInterval(function(){
			var wait = $('#wait_time').text();			
			var time = --wait;
			$('#wait_time').text(time);
			//var time = --wait.innerHTML;	//就是这个地方dialog和jq有关的地方老是不能刷新
			if(time <= 0) {
			<?php if(($_GET["join"]) == "1"): ?>location.href = $('#href').attr('href'); //注册页登陆跳转
			<?php else: ?>	
			 location.reload();		//不是注册页登陆直接刷新<?php endif; ?>
			 clearInterval(interval);
			}
		},1000);						
	}
 });
 
 $('#dialog_login').click(function(){	//登陆
  $.post('<?php echo U('Index/loginin');?>',
        {uname:$('#login_name').val(),pword:$('#login_pword').val()},
      	function(data){
	 	 if (data.status == 1){
	 	 <?php if(($_GET["join"]) == "1"): ?>var prompt = '<a href="'+data.data+'" id="href">页面将在 <b id="wait_time">2</b>秒后跳转，如不想等待请点击此处。</a>'; //不是注册页登陆则刷新	 	 
	 	 <?php else: ?>
	 	  var prompt = '<a href="javascript:location.reload();" id="href">页面将在 <b id="wait_time">2</b>秒后跳转，如不想等待请点击此处。</a>';//注册页登陆则跳转<?php endif; ?>		
	 	  $('#login_success').html(prompt);
	 	  $('#login_dialog').dialog('close');
		  $('#login_success').dialog('open');		  
	 	 } else {
	 	  $('#login_dialog_error').html(data.info).show();
		  setTimeout(function(){ $('#login_dialog_error').hide()},2000);	
	     }
   });
 });
}); 
</script>
		  </div><?php endif; ?>
      </ul>
      <div class="clr"></div>
    </div>
  </div>
  <p id="back-to-top"><a href="javascript:;"><span></span>回顶部</a></p>
<script type="text/javascript">
$(function(){
 $(window).scroll(function(){	//当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
    if ($(window).scrollTop()>100){
      $('#back-to-top').fadeIn(200);
    } else {
      $('#back-to-top').fadeOut(200);
    }
  });            

  $('#back-to-top').click(function(){	//当点击跳转链接后，回到页面顶部位置
   $('body,html').animate({scrollTop:0},1000);
   return false;
  });
});
</script>
</div>
</body>
</html>