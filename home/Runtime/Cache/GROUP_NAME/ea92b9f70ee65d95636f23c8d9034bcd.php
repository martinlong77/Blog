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

<title>果冻记_登陆果冻</title>
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
		<h2 class="star">欢迎来到果冻记！</h2>
		<p><span class="login_title">账号:</span>
		    <input type="text" id="uname" class="small" /></p>
		 <p><span class="login_title">密码:</span> 
		    <input type="password" id="pword" class="small" /></p>
		  <p><input type="button" id="login" value="登陆" class="button orange"/>		  
		  <a href="<?php echo U('index/findpw?find=1');?>" style="margin-left:10px;"><span class="login_title">忘记密码？</span></a> </p>
		  <div id="login_error" class="result"></div>
		</div>
		</div>
		<div class="sidebar">	 
	  <div class="gadget">
		<a href="<?php echo U('Index/register?join=1');?>"><span class="login_title">加入果冻!</span></a>
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
</body>
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
</html>