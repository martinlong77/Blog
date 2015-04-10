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

<title><?php echo ($info["image_title"]); ?>_JellyNotes</title>
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
        <div class="article image_max">                
		<div id="new_name" style="display:none">
			<input type="text" id="img_name" value="<?php echo ($info["image_title"]); ?>" class="mid"/>
			<input type="hidden" id="img_id" value="<?php echo ($info["image_id"]); ?>"/>
			<input type="button" value="保存" onclick="new_name()" class="button white"/> &nbsp;&nbsp; <input type="button" value="取消" id="close_name" class="button gray" /> <span id="name_error" class="result"></span></div><p class="spec"><span class="title"><?php echo ($info["image_title"]); ?></span> &nbsp;(<?php echo (date('Y-m-d H:i',$info["image_createtime"])); ?>)</p>
			<?php if(($info["user"]) == $_SESSION['id']): ?><a href="javascript:;" aid="<?php echo ($info["image_album"]); ?>" path="<?php echo ($info["image_path"]); ?>" onclick="set_cover(this)">设为封面</a><span id="modi_album"> &nbsp;|&nbsp; <a href="javascript:;">修改名称</a></span> &nbsp;|&nbsp; <a href="javascript:;" id="<?php echo ($info["image_id"]); ?>" path="<?php echo ($info["image_path"]); ?>" aid="<?php echo ($info["image_album"]); ?>" name="<?php echo ($info["image_title"]); ?>" onclick="del_img(this)">删除图片</a><?php endif; ?>
		 <p><?php if(empty($image["n"])): ?><a href="<?php echo U('Pic/image?pic=!&id='.$info['p']['image_id']);?>"><img src="<?php echo ($info["image_path"]); ?>" alt="<?php echo ($info["image_title"]); ?>" /></a><?php else: ?><a href="<?php echo U('Pic/image?pic=1&id='.$info['n']['image_id']);?>"><img src="<?php echo ($info["image_path"]); ?>" alt="<?php echo ($info["image_title"]); ?>" /></a><?php endif; ?></p>
		   
		   <div id="new_desc" style="display:none"><p><textarea rows="8" cols="50" id="desc"><?php echo ($info["image_description"]); ?></textarea><p/>
		   <input type="button" onclick="new_desc()" value="保存" class="button white"/>&nbsp;&nbsp;<input type="button" value="取消" id="close_desc" class="button gray"/></div>
		   <div id="display_desc"><span class="desc"><?php echo ($info["image_description"]); ?></span> <?php if(($info["user"]) == $_SESSION['id']): ?><a href="javascript:;" id="edit_desc">[编辑描述内容]</a><?php endif; ?></div>          		  
		  <p style="text-align:right"><a href="<?php echo ($info["image_path"]); ?>" target="_blank">原图</a> &nbsp;|&nbsp; 浏览(<?php echo ($info["readcount"]); ?>)  &nbsp;|&nbsp; <a href="#comment" target="_self">评论</a>(<?php echo ($info["comcount"]); ?>) &nbsp;|&nbsp; <a href="">举报</a></p>            
       <div class="clr"></div>		
          <h2>评论</h2>		  
          <div class="clr"></div>		  
          <div class="comment">
		  <div id="comment_list">		  
		  	  <?php if(is_array($commentList)): $i = 0; $__LIST__ = $commentList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><div>	  
		  <?php if(($c["uid"]) == $_SESSION['id']): ?><img src="<?php echo ($c["user_avatar"]); ?>" width="48" height="48" alt="user" class="userpic" />
           <p>我
		  <?php else: ?>
            <a href="<?php echo U('Blog/proBlog?blog=1&id='.$c['uid']);?>"><img src="<?php echo ($c["user_avatar"]); ?>" width="48" height="48" alt="user" class="userpic" /></a>
            <p><a href="<?php echo U('Blog/proBlog?blog=1&id='.$c['uid']);?>"><?php echo ($c["user_nickname"]); ?></a><?php endif; ?> 
		  (<?php echo (date('Y-m-d H:i',$c["comment_time"])); ?>) 说： <?php if(($c["uid"]) != $_SESSION['id']): ?><a href="javascript:;" onclick="set_reply(this)" reply_to="<?php echo ($c["uid"]); ?>" reply_name="<?php echo ($c["user_nickname"]); ?>" reply_url="<?php echo U('Blog/proBlog?blog=1&id='.$c['uid']);?>">[回复]</a>&nbsp;<a href="">[举报]</a><?php endif; ?>&nbsp;<?php if(($c["uid"]) == $_SESSION['id']): ?><a href="javascript:;" onclick="delete_comment(this)" cid="<?php echo ($c["comment_id"]); ?>" id="<?php echo ($_GET["id"]); ?>">[删除]</a><?php else: if(($c["hid"]) == $_SESSION['id']): ?><a href="javascript:;" onclick="del_com(this)" cid="<?php echo ($c["comment_id"]); ?>" id="<?php echo ($_GET["id"]); ?>">[删除]</a><?php endif; endif; ?><br />
            </p>
            <p><?php echo ($c["comment_content"]); ?></p>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>			
			<p class="pages" id="fpage"><small><?php echo ($page); ?></small></p>					  
		  </div>
		  <br />		  
          <h2 id="comment">留言</h2>
          <div class="clr"></div>
		  <?php if(empty($info["comment_status"])): if(($_SESSION["isLoginIn"]) != "1"): ?><span class="apply">请登录后回复</span>	
		 <?php else: ?> 
		 <span class="check_prompt">您还可以输入<span id="charcount">240</span> 字</span>			
            <textarea id="message" rows="6" cols="70" onkeyup="check_len()" onkeypress="check_len()"></textarea>
			<input type="hidden" id="id" value="<?php echo ($_GET["id"]); ?>" />
			<input type="hidden" id="reply_to" />
			<input type="hidden" id="reply_name" />
			<input type="hidden" id="reply_url" /><br/>
            <span id="send_error" class="result"></span><br>
			 <span class="cate_sel">验证码</span>:
              <input type="text" id="verify"/></span>&nbsp;
			<img id="ver_pic" onclick="change_pic()" src="<?php echo U('Pub/verify');?>" alt="点击刷新验证码" style="cursor:pointer" /> &nbsp;<a href="javascript:;" onclick="change_pic()" class="comprompt">看不清?换一张</a>
            <input type="button" value="发表" id="sub_btn" class="button orange  large check_prompt" onclick="send_comment()"/>
            <div class="clr"></div>	
			<script type="text/javascript">
<?php if(empty($_GET["list"])): ?>function check_len(){	  //检查留言长度
  input_len = $('#message').val().length;
  input_len = 240-input_len;	
  if (input_len < 0) {
    $('#sub_btn').css({background:'#FF6600'});  	
  	$('#sub_btn').attr('onclick','enter_error()');
  	$('#charcount').css('color','red');    
  	enter_error();
  } else {
  	 if ($('#sub_btn').attr('onclick') == 'enter_error()') {
  	  $('#sub_btn').css({background:''}); 
  	  $('#sub_btn').attr('onclick','send_comment()');
  	  $('#charcount').css('color','#999999');
  	 } 
  }	  
  $('#charcount').html(input_len);
}

function send_comment(){   //留言
  var id = $('#id').val();
  var uid = <?php echo ($_SESSION["id"]); ?>;
  var message = $('#message').val();
  var verify = $('#verify').val();
  $.post('<?php echo ($navi["comment_url"]); ?>',
         {id:id,uid:uid,message:message,verify:verify},
		 function(data){
		 if (data.status == 1) {
		  com = data.data;
      var com_con = '<div><img src="<?php echo (session('avatar')); ?>" width="48" height="48" alt="user" class="userpic"/><p>我 (' + com.comment_time + ') 说： <a href="javascript:;" onclick="del_com(this)" id="<?php echo ($_GET["id"]); ?>" cid="'+ com.new_id +'">[删除]</a><br /></p><p>' + com.comment_content + '</p></div>';		  
		  $('#comment_list').append(com_con);
      var time = new Date().getTime();
		  $('#ver_pic').attr('src','__APP__/Pub-verify-'+time);
      $('#message').val('');
      $('#charcount').text('240');
		 } else {
		  $('#send_error').html(data.info).show();
		  setTimeout(function(){
  		  $('#send_error').hide();
 		  },2000);
		 }
  });
}

function enter_error() {    //留言输入错误提示
  str = $('#message').val();
  content = str.substr(0,240);
  $('#message').val(content);
  $('#message').css({background:'#FF3300'});
  setTimeout(function(){ $('#message').css({background:'#FFFFFF'})},200);
}

function change_pic(){  //刷新验证码
  var time = new Date().getTime();  
  $('#ver_pic').attr('src','__APP__/Pub-verify-'+time);
}<?php endif; ?>
//发送回复
function send_reply() {   
  var id = $('#id').val();
  var uid = <?php echo ($_SESSION["id"]); ?>;
  var reply_content = $('#reply_content').val();
  var name = $('#reply_name').val();
  var url = $('#reply_url').val();
  var reply_to = $('#reply_to').val();  
  $.post('<?php echo ($navi["comment_url"]); ?>',
         {id:id,uid:uid,message:reply_content,reply_to:reply_to,name:name,url:url},
     function(data){
     if (data.status == 1) {
      <?php if(empty($_GET["list"])): ?>com = data.data;
        var com_con = '<div><img src="<?php echo (session('avatar')); ?>" width="48" height="48" alt="user" class="userpic"/><p>我 (' + com.comment_time + ') 说： <a href="javascript:;" onclick="del_com(this)" id="<?php echo ($_GET["id"]); ?>" cid="'+ com.new_id +'">[删除]</a><br /></p><p>回复<a href="'+ url +'" target="_blank">'+ name +'</a>: ' + com.comment_content + '</p></div>';
    
      $('#comment_list').append(com_con);
      <?php else: ?>
      $('#login_out').dialog('option','title','回复结果');      
      $('#login_out').on('dialogopen', function( event, ui ) {setTimeout(function(){ $('#login_out').dialog('close')},1500);});
      $('#login_out').html(data.info);  
      $('#login_out').dialog('open');<?php endif; ?>
       $('#reply_area').remove();
     } else {
      $('#reply_error').html(data.info).show();
      setTimeout(function(){
        $('#reply_error').hide();
      },2000);
     }
  });  
}

function set_reply(param) {  //回复操作  
  var reply_name = $(param).attr('reply_name');
  var reply_url = $(param).attr('reply_url');
  var reply_to = $(param).attr('reply_to');
  <?php if(!empty($_GET["list"])): ?>var id = $(param).attr('orignal_id');
  $('#id').val(id);<?php endif; ?>
  $('#reply_name').val(reply_name);
  $('#reply_url').val(reply_url);
  $('#reply_to').val(reply_to);
  $('#reply_area').remove(); 
  var reply_area = '<span id="reply_area">回复<span class="retitle">'+ reply_name +':</span> <input type="text" id="reply_content" class="big" onkeyup="reply_max()" onkeypress="reply_max()" /><br/><input type="button" class="button white" value="回复" onclick="send_reply()" /><input type="button" class="button gray" value="关闭" onclick="close_reply()"><span class="result" id="reply_error"></span></span>';
  <?php if(!empty($_GET["list"])): ?>$(param).parent().append(reply_area);
  <?php else: ?>
  $(param).parent().parent().append(reply_area);<?php endif; ?>
  $('html,body').animate({scrollTop: $(param).offset().top}, 1000); //平滑效果
}

function reply_max() {  //检查回复长度
  input_len = $('#reply_content').val().length;
  input_len = 140-input_len;
  if (input_len < 0) {
    str = $('#reply_content').val();
    content = str.substr(0,140);
    $('#reply_content').val(content);
  };  
}

function close_reply(){ //关闭回复框
  $('#reply_area').remove();
}
		  
//删除评论
function delete_comment(param){
 if (confirm('删除这条评论吗？')) {	
 var id = $(param).attr('id');
 var cid = $(param).attr('cid'); 
 $.post('<?php echo ($navi["del_com_url"]); ?>',
 {id:id,cid:cid},
 function(data){
  if(data.status != 1){
   alert(data.info);
  } else {
   $(param).parent().parent().hide(500);
  }
 });
 }  
}
</script><?php endif; ?>
		 <?php else: ?>
		  <font color="#0000FF">评论暂不开放</font><?php endif; ?>
        </div>
        </div>
      </div>
      <div class="sidebar">
	  <div class="gadget">
          <h2><?php echo ($album["0"]["album_name"]); ?></h2>           	  
		  <div class="sb_border"><p><?php if(empty($info["p"])): ?><img src="__PUBLIC__/Images/Common/Main/no_cover.gif" alt="没有了" width="200" />没有了<?php else: ?><a href="<?php echo U('Pic/image?pic=1&id='.$info['p']['image_id']);?>"><img src="<?php echo ($info["p"]["image_path"]); ?>" alt="上一张"/>上一张</a><?php endif; ?></p></div>
			<div class="sb_border"><p class="img_side_right"><?php if(empty($info["n"])): ?><img src="__PUBLIC__/Images/Common/Main/no_cover.gif" alt="没有了" width="200" />没有了<?php else: ?><a href="<?php echo U('Pic/image?pic=1&id='.$info['n']['image_id']);?>"><img src="<?php echo ($info["n"]["image_path"]); ?>" alt="下一张"/>下一张</a><?php endif; ?></p></div>
			<div class="clr"></div>
			
			<p class="spec"><h2>公开专辑</h2></p>
			<div class="sb_album"><p><a href="<?php echo U('Pic/proPic?pic=1&id='.$info['user'].'&aid='.$album[0]['album_id']);?>"><?php if(empty($album["0"]["album_cover"])): ?><img src="__PUBLIC__/Images/Common/Main/no_cover.gif" alt="暂无" /><?php else: ?><img src="<?php echo ($album["0"]["album_cover"]); ?>" alt="<?php echo ($album["0"]["album_name"]); ?>" /><?php endif; echo ($album["0"]["album_name"]); ?></a></p></div>
			<div class="sb_album"><p class="img_side_bottom"><a href="<?php echo U('Pic/proPic?pic=1&id='.$info['user'].'&aid='.$album[1]['album_id']);?>"><?php if(empty($album["1"]["album_cover"])): ?><img src="__PUBLIC__/Images/Common/Main/no_cover.gif" alt="暂无" width="200" /><?php else: ?><img src="<?php echo ($album["1"]["album_cover"]); ?>" alt="<?php echo ($album["1"]["album_name"]); ?>" width="200"/><?php endif; ?><span class="img_side_font"><?php echo ($album["1"]["album_name"]); ?></span></a></p></div>
			<div class="clr"></div>			
		<a href="<?php echo U('Pic/proPic?image=1&id='.$info['user']);?>"><h3 style="float:right;">全部专辑</h3></a>
		</div>		        
	  <div class="clr"></div>
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
<script type="text/javascript">
$(function($){
 //显示名称修改框
 $('#modi_album').click(function(){
  $('#modi_album').hide();
  $('.title').hide();
  $('#new_name').show();
 });
 
 //关闭名称修改框
 $('#close_name').click(function(){
  $('#modi_album').show();
  $('.title').show();
  $('#new_name').hide();
 });
  
  //显示描述修改框
 $('#edit_desc').click(function(){
  $('#display_desc').hide();
  $('#new_desc').show();
 });
 
 //关闭描述修改框
  $('#close_desc').click(function(){
  $('#display_desc').show();
  $('#new_desc').hide();
 });
});

 //修改名称
 function new_name(){
  var id = $('#img_id').val();
  var name = $('#img_name').val();
  $.post('<?php echo U('Pic/edit?set=name');?>',
         {id:id,name:name},
         function(data){
		  if (data.status == 1) {
  		   $('.title').text(name).show();
  		   $('#modi_album').show();
   		   $('#new_name').hide();
		  } else {
		   $('#name_error').text(data.info).show();
		   setTimeout(function(){ $('#name_error').hide()},2000);
		  }
  });
 }
 
 //修改描述
 function new_desc(){
  var id = $('#img_id').val();
  var desc = $('#desc').val();
  $.post('<?php echo U('Pic/edit?set=desc');?>',
         {id:id,desc:desc},
         function(data){
		  if (data.status == 1){
  		   $('.desc').text(desc);
  		   $('#display_desc').show();
  		   $('#new_desc').hide();
		  } else {
		   alert(data.info);
		  }
  });
 }
</script>
<div id="delete_confirm" title="删除确认">
删除后不可恢复,确定要删除 <span id="del_name"></span> 吗？
<input type="hidden" id="pic_id" />
<input type="hidden" id="pic_aid" />
<input type="hidden" id="pic_path" />
<input type="hidden" id="del_jump" value="<?php echo ($navi["jump"]); ?>" />
</div>  
<script type="text/javascript">
$(function($){   
  //删除确认
 $('#delete_confirm').dialog({  
  autoOpen: false,
  resizable: false,
  modal: true,
  draggable: false,
  buttons: {
        '确定':function(){
         var id = $('#pic_id').val();
         var path = $('#pic_path').val();  
         var aid = $('#pic_aid').val();
         var jump = $('#del_jump').val();  
         $.post('<?php echo U('pic/edit?set=del');?>',
          {id:id,path:path,aid:aid},
          function(data){ 
          if (data.status == 1) {
           if (jump != 1) {
            location.href = data.data;
           } else {
            $('#id_'+id).hide(500);
            applyLayout();         
            //setTimeout('location.reload()',1000);
           }
          } else {
           alert(data.info);
          }
         }); 
        $(this).dialog('close');
      },
        '取消':function(){ $(this).dialog('close')}
  }
 });
});

//设置封面
function set_cover(param){
 var path = $(param).attr('path');
 var aid = $(param).attr('aid');
 $.post('<?php echo U('pic/edit?set=cover');?>',
  {path:path,aid:aid},
  function(data){
   $('#move_result').text(data.info);
   $('#move_result').dialog('open');
   $('.sb_album:first').children().children().children('img').attr('src',path);
 });									
}
 
//用作头像
function set_avatar(param){
 var path = $(param).attr('path');
 $.post('<?php echo U('Pic/edit?set=avatar');?>',
   {path:path},
   function(data){
	$('#avatar').attr('src',path);
    $('#move_result').text(data.info);
	$('#move_result').dialog('open');
 });
}
		
//删除图片		
function del_img(param){ 
  var id = $(param).attr('id');
  var path = $(param).attr('path');  
  var aid = $(param).attr('aid');
  var name= $(param).attr('name');

  $('#pic_id').val(id);
  $('#pic_aid').val(aid);  
  $('#pic_path').val(path);  
  $('#del_name').text(name);
  $('#delete_confirm').dialog('open'); 						
}
</script>
</body>
</html>