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

<title>果冻记_找回密码</title>
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
		<h2 class="star">请输入您的果冻账号</h2>
		  <p><span class="info">果冻账号:</span>
		    <input type="text" id="uname" class="mid" /></p>
			<p><span class="profileinfo">请输入验证码</span>(<span class="comprompt">看不清楚点击验证码刷新</span>):
              <input type="text" id="verify" size="6"/></span>
			<img id="ver_pic" src="<?php echo U('Pub/verify');?>" style="cursor:pointer" /></p>
		  <p><input type="button" value="提交" id="sub_form" class="button orange"/></p>
		  <span class="result" style="display:none;"></span>
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