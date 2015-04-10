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

<link type="text/css" href="__PUBLIC__/css/bubble.css" rel="stylesheet" />
<title><?php switch($_GET["list"]): case "friend": ?>好友请求<?php break; case "message": ?>留言<?php break; case "comment": ?>博客评论<?php break; case "pic_comment": ?>照片评论<?php break; endswitch;?></title>
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
          <?php if(($_GET["list"]) == "friend"): ?><h2>好友请求</h2>
          <div class="clr"></div>
      <?php if(is_array($applyList)): $i = 0; $__LIST__ = $applyList;if( count($__LIST__)==0 ) : echo "暂时没有申请" ;else: foreach($__LIST__ as $key=>$apply): $mod = ($i % 2 );++$i;?><div class="bubble_area" id="apply_<?php echo ($apply["apply_id"]); ?>"><a href="<?php echo U('Blog/problog?blog=1&id='.$apply['user']);?>" target="_blank"><img src="<?php echo ($apply["avatar"]); ?>" width="48" height="48" title="<?php echo ($apply["nickname"]); ?>" class="message_ava"/></a><div class="triangle-isosceles left"><a href="<?php echo U('Blog/problog?blog=1&id='.$apply['user']);?>" target="_blank"><span class="message_name"><?php echo ($apply["nickname"]); ?></span></a>：<?php if(in_array(($apply["apply_status"]), explode(',',"1,2"))): ?><span class="apply"><?php else: ?><span class="cate_sel"><?php endif; echo ($apply["apply_message"]); ?></span><br/>
        <?php if(in_array(($apply["apply_status"]), explode(',',"1,2"))): ?><a href="javascript:;" fid="<?php echo ($apply["user"]); ?>" app_id="<?php echo ($apply["apply_id"]); ?>" onclick="add_friend(this)"><span class="agree">同意</span></a> | <a href="javascript:;" app_id="<?php echo ($apply["apply_id"]); ?>" onclick="refuse_apply(this)"><span class="disagree">拒绝</span></a><br/><?php endif; echo (date('Y-m-d H:i',$apply["apply_time"])); ?> <?php if(in_array(($apply["apply_status"]), explode(',',"3,4"))): ?>| <a href="javascript:;" app_id="<?php echo ($apply["apply_id"]); ?>" onclick="delete_feedback(this)"><span class="disagree">删除</span></a><?php endif; ?></div><div class="clr"></div></div><?php endforeach; endif; else: echo "暂时没有申请" ;endif; ?>
	  <div class="clr"></div>
	  <p class="pages"><small>&nbsp;&nbsp;&nbsp;</small><?php echo ($fpage); ?></p>
		  <?php else: ?>
		  <h2><?php switch($_GET["list"]): case "friend": ?>好友请求<?php break; case "message": ?>留言列表<?php break; case "comment": ?>博客评论<?php break; case "pic_comment": ?>照片评论<?php break; endswitch;?></h2>
          <div class="clr"></div>
		        <?php if(is_array($remindList)): $i = 0; $__LIST__ = $remindList;if( count($__LIST__)==0 ) : echo "你还没有留言" ;else: foreach($__LIST__ as $key=>$remind): $mod = ($i % 2 );++$i;?><div class="bubble_area"><a href="<?php echo U('Blog/problog?blog=1&id='.$remind['sender']);?>" target="_blank"><img src="<?php echo ($remind["avatar"]); ?>" width="48" height="48" title="<?php echo ($remind["nickname"]); ?>" class="message_ava"/></a>
	  <div class="triangle-isosceles left">
	  <a href="<?php echo U('Blog/problog?blog=1&id='.$remind['sender']);?>" target="_blank"><span class="message_name"><?php echo ($remind["nickname"]); ?></span></a>：<span class="apply"><?php echo ($remind["comment_content"]); ?></span><br/>
        <?php echo (date('Y-m-d H:i',$remind["comment_time"])); ?> | <a href="javascript:;" orignal_id="<?php echo ($remind["orignal_id"]); ?>" reply_name="<?php echo ($remind["nickname"]); ?>" reply_url="<?php echo U('Blog/problog?blog=1&id='.$remind['sender']);?>" reply_to="<?php echo ($remind["sender"]); ?>" onclick="set_reply(this)" class="disagree">回复</a><?php if(($_SESSION["id"]) == $remind["hid"]): ?>| <a href="javascript:;" cid="<?php echo ($remind["from_id"]); ?>" id="<?php echo ($remind["orignal_id"]); ?>" onclick="delete_comment(this)" class="disagree">删除</a><?php endif; ?> | <a href="javascript:;" class="disagree">举报</a><br/>
	  <?php if(($_GET["list"]) != "message"): ?>评论来自<?php if(($_GET["list"]) == "comment"): ?>博客:<a href="<?php echo U('Blog/page?blog=1&id='.$remind['orignal_id']);?>" target="_blank"><?php else: ?>照片:<a href="<?php echo U('Pic/image?pic=1&id='.$remind['orignal_id']);?>" target="_blank"><?php endif; ?><span class="message_name">《<?php echo ($remind["title"]); ?>》</span></a><br/><?php endif; ?>
		</div>
        <div class="clr"></div></div><?php endforeach; endif; else: echo "你还没有留言" ;endif; ?>
	  <div class="clr"></div>	  
	  <p class="pages"><small>&nbsp;&nbsp;&nbsp;</small><?php echo ($fpage); ?></p><?php endif; ?>
		  <input type="hidden" id="id" />
	  <input type="hidden" id="reply_to" />
	  <input type="hidden" id="reply_name" />
	  <input type="hidden" id="reply_url" />
		 </div> 
		   </div> 
      <div class="sidebar">
        <div class="gadget">
		<ul class="ex_menu">
           <li><a href="<?php echo U('Profile/reminder?list=comment');?>"><?php if(($_GET["list"]) == "comment"): ?><span class="cate_sel"><?php else: ?>
           <span class="info"><?php endif; ?>博客评论</span></a></li>
		   <p class="spec"></p>
		   <li><a href="<?php echo U('Profile/reminder?list=pic_comment');?>"><?php if(($_GET["list"]) == "pic_comment"): ?><span class="cate_sel"><?php else: ?>
           <span class="info"><?php endif; ?>照片评论</span></a></li>
		   <p class="spec"></p>
		   <li><a href="<?php echo U('Profile/reminder?list=message');?>"><?php if(($_GET["list"]) == "message"): ?><span class="cate_sel"><?php else: ?>
		   <span class="info"><?php endif; ?>留言</span></a></li>
		   	<p class="spec"></p>
		   <li><a href="<?php echo U('Profile/reminder?list=friend');?>"><?php if(($_GET["list"]) == "friend"): ?><span class="cate_sel"><?php else: ?><span class="info"><?php endif; ?>好友请求</span></a></li>
		   <p class="spec"></p>
          </ul>
        </div>
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
<?php if(($_GET["list"]) == "friend"): ?><script type="text/javascript">
 function add_friend(param){  //同意并添加好友
   var fid = $(param).attr('fid');
   var aid = $(param).attr('app_id');     
   $.post('<?php echo U('profile/rela?set=addFriend');?>',
      {fid:fid,aid:aid},
        function(data){
        if(data.status == 1){
        var content = '<span class="apply">'+data.info+'</span>';
        $(param).parent().html(content);
        setTimeout(function(){ $('#apply_'+aid).hide(500)},1500);
      } else {
        alert(data.info);
      }
      });
 }
 
 function refuse_apply(param){  //拒绝申请
  var aid = $(param).attr('app_id');
  $.post('<?php echo U('Profile/rela?set=refuse');?>',
      {aid:aid},
      function(data){
        if (data.status == 1){
          $('#apply_'+aid).hide(500);
        } else {
          alert(data.info);
        }
  });
 }
 
 function delete_feedback(param){   //删除反馈信息
  var aid = $(param).attr('app_id');
  $.post('<?php echo U('Profile/rela?set=deleteFeedback');?>',
      {aid:aid},
      function(data){
        if (data.status == 1){
          $('#apply_'+aid).hide(500);
        } else {
          alert(data.info);
        }
  });
 }
</script>
<?php else: ?>
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
</html>