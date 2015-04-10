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

<title>果冻记_加入果冻</title>
</head>
<body>
<div class="main">
  <div class="header">
    <div class="header_resize">
      <div class="logo">
        <h1><a href="<?php echo U('index/index?hot=1');?>">果冻<span>记</span></a> <small><span>写生活</span>享生活</small></h1>
      </div>
      <div class="clr"></div>
      <div class="menu_nav">
        <ul>
          <li><a href="<?php echo U('index/index?hot=1');?>">返回首页</a></li>
		  <?php if(($_GET["login"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/login?login=1');?>">登陆果冻</a></li>
          <?php if(($_GET["find"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/findpw?find=1');?>">找回密码</a></li>
		  <?php if(($_GET["join"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/register?join=1');?>">加入果冻</a></li>
        </ul>
      </div>
      <div class="clr"></div>
    </div> 
  </div>

  <div class="content">
    <div class="content_resize"><div class="content_resize2">
      <div class="mainbar">
        <div class="article">       
            <div class="clr"></div>
		<h2 class="star">欢迎加入果冻记！</h2>
		 <p>简单快捷的注册 :) &nbsp;&nbsp;(以下项目都必填哦~)</p>	
         <p><span class="retitle">账号:</span> 
           <input type="text" id="reg_uname" class="small" style="margin-left:28px;"/>
(6-20 位长度)</p>
<p><span class="retitle">密码:</span> 
  <input type="password" id="reg_pword" class="small" style="margin-left:28px;"/>
(6-20 位长度)</p>
<p><span class="retitle">重复密码:</span> <input type="password" id="reg_npword" class="small"/>
  (重复上面的密码)</p>
<p><span class="retitle">果冻昵称:</span> <input type="text" id="reg_disname" class="small"/></p>				
<p><span class="retitle">Email:</span>
  <input type="text" id="reg_email" class="small" style="margin-left:20px;"/></p>
<p><span class="profileinfo">请输入验证码</span>:
    <input type="text" id="verify" size="6"/></span> <img id="ver_pic" src="<?php echo U('Pub/verify');?>" onClick="change_ver()" style="cursor:pointer" />  &nbsp;<a href="javascript:;" onclick="change_ver()" class="comprompt">看不清?换一张</a></p>
	<div id="reg_result" class="result"></div>
		<p><input type="button" value="加入果冻！" id="reg_form" class="button orange"/></p>		
        </div>
      </div>
      <div class="sidebar">
        <div class="gadget">
          <h2 class="star">已有果冻账号？<a href="javascript:;" id="open_login_dialog">直接登陆!</a></h2>
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
		  </div>		  
        </div>
        </div>
      <div class="clr"></div>
    </div></div>
  </div>

<div class="footer">
    <div class="footer_resize">
      <p class="lf">Copyright &copy; 2013 <a href="<?php echo U('index/index?hot=1');?>">www.Jellong.com</a>, All Rights Reserved</p>
      <ul class="fmenu">
          <li><a href="<?php echo U('index/index?hot=1');?>">返回首页</a></li>
		  <?php if(($_GET["login"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/login?login=1');?>">登陆果冻</a></li>
          <?php if(($_GET["find"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/findpw?find=1');?>">找回密码</a></li>
		  <?php if(($_GET["join"]) == "1"): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="<?php echo U('index/register?join=1');?>">加入果冻</a></li>
      </ul>
      <div class="clr"></div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function($){
 //找回密码
 $('#sub_form').click(function(){
  $('.result').text('正在验证账号，请稍等...').show();    
  $.post('<?php echo U('index/getMail');?>',
    {uname:$('#uname').val(),verify:$('#verify').val()},
    function(data){           
	 	 if (data.status == 1){      
      var content = '<a href="'+data.data+'"><span class="category">'+data.info+'</span></a>';
      $('.article').html(content);
	 	 } else {
	 	  $('.result').text(data.info).show();
		  setTimeout(function(){ $('.result').hide()},2000);
	     }
   });
 });
 
  //提交注册表单
 $('#reg_form').click(function(){
  $.post('<?php echo U('Index/registered');?>',
   	{uname:$('#reg_uname').val(),pword:$('#reg_pword').val(),disname:$('#reg_disname').val(),npword:$('#reg_npword').val(),email:$('#reg_email').val(),verify:$('#verify').val()},		
    function(data){
		 if (data.status == 1){
      $('.article').html(data.info);
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
	 	 } else {		 
	 	  $('#reg_result').html(data.info).show();
		  setTimeout(function(){ $('#reg_result').hide()},2000);
	   }	
  });
 });
});
 
//刷新验证码
 function change_ver(){
  var time = new Date().getTime();	
  $('#ver_pic').attr('src','__APP__/Pub-verify-'+time);
 }
 </script>
</body>
</html>