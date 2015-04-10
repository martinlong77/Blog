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

<?php switch($_GET["set"]): case "avatar": ?><!--编辑头像时调用 -->
<title>修改个人头像_果冻写</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/jcrop/jquery.Jcrop.css"/>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/uploadify/uploadify2ava.css" media="all">
<script type="text/javascript" src="__PUBLIC__/js/jcrop/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/uploadify/jquery.uploadify.min.js"></script>
<!--结束调用 --><?php break;?>

<?php case "pass": ?><!--修改密码时调用 -->
<title>修改密码_果冻写</title>
<!--结束调用 --><?php break;?>

<?php default: ?>
<!--编辑个人信息时调用 -->
<title>修改个人信息_果冻写</title>
<script type="text/javascript" src="__PUBLIC__/js/jquery-ui/jquery.ui.datepicker-zh-CN.js"></script>
<!--结束调用 --><?php endswitch;?>
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
		</script>  </div>

  <div class="content">
    <div class="content_resize"><div class="content_resize2">
      <div class="mainbar">
        <div class="article">
          <div class="clr"></div>
		  <?php switch($_GET["set"]): case "avatar": ?><h3>个人头像设置<div style="float:right;">(请确认大小头像是否清晰)</div></h3>
		  <p class="spec"></p>
		  <div class="update-pic cf">
		  <p><input type="checkbox" id="keep" value="1" /> <span class="info">同时保留原图</span></p>	
	<div class="upload-btn">	
		<input type="file" id="user-pic">
		<span class="file-tips">支持JPG,PNG,GIF格式，小于1MB，尺寸不小于180*180的图片</span>			
	</div>
	<div class="preview-area">
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
	    <input type="hidden" id="h" name="h" />
		<input type="hidden" id="img_src" name="src" />		
		<div class="tcrop">当前头像</div>
		<div class="crop crop180"><img id="crop-preview-180" src="<?php echo (session('avatar')); ?>" alt=""></div>
		<div class="crop crop60"><img id="crop-preview-60" src="<?php echo (session('avatar')); ?>" width="60" alt=""></div>
		<a class="uppic-btn save-pic" href="javascript:;">保存</a>
		<a class="uppic-btn reupload-img" href="javascript:$('#user-pic').uploadify('cancel','*');">重新上传</a>
	</div>
	</div>
    <div class="upload-area">
	<div class="preview hidden" id="preview-hidden"></div>
	</div>	
<!-- 修改头像 --><?php break;?>
		  <?php case "pass": ?><form action="javascript:;" method="post" id="pass">
		  <p><label for="old_pword" id="profileinfo">当前密码：</label><input type="text" id="old_pword" class="small" /></p>
		  <p><label for="new_pword" id="profileinfo" style="margin-left:16px;">新密码：</label><input type="password" id="new_pword" class="small" /></p>
		  <p><label for="new_repword" id="profileinfo">确认密码：</label><input type="password" id="new_repword" class="small" /></p>
		  <input type="hidden" name="uid" value="<?php echo (session('ID')); ?>" />
		  <input type="submit" value="修改" class="button orange"/>
		  </form>
		  <p><span class="result"></span></p>
		  <!-- 修改密码 --><?php break;?>
		  <?php default: ?>
		  		  <p><label for="disname" id="profileinfo">昵称：</label><input type="text" id="disname" value="<?php echo ($_SESSION["nickname"]); ?>" class="small" style="margin-left:18px" /></p>
		  <p><label for="email" id="profileinfo">Email：</label><input type="text" id="email" value="<?php echo ($_SESSION["email"]); ?>" class="small" style="margin-left:10px" /></p>
		  <p><label id="profileinfo" for="birth">生日：</label> <input type="text" value="<?php echo ($_SESSION["birthday"]); ?>" id="birth" readonly style="margin-left:16px" /></p>
		 <p><label for="province" id="profileinfo">所在地：</label><select name="province" id="province" onChange="loadArea(this.value,'city')">
        <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prov): $mod = ($i % 2 );++$i; if(($prov["area_id"]) == $_SESSION["location"]["0"]): ?><option value="<?php echo ($prov["area_id"]); ?>" selected><?php echo ($prov["area_name"]); ?></option><?php else: ?><option value="<?php echo ($prov["area_id"]); ?>" ><?php echo ($prov["area_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </select>
           
    <select name="city" id="city">
		<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ci): $mod = ($i % 2 );++$i; if(($ci["area_id"]) == $_SESSION["location"]["1"]): ?><option value="<?php echo ($ci["area_id"]); ?>" selected><?php echo ($ci["area_name"]); ?></option><?php else: ?><option value="<?php echo ($ci["area_id"]); ?>"><?php echo ($ci["area_name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </select></p>
		 <p><label for="sex" id="profileinfo">性别：<?php if(($_SESSION["sex"]) == "男"): ?><input type="radio" name="sex" value="男" checked="checked" style="margin-left:20px" />男 <input type="radio" name="sex" value="女"/>女<?php else: ?><input type="radio" name="sex" value="男" style="margin-left:20px" />男 <input type="radio" name="sex" value="女" checked="checked"/>女<?php endif; ?></label> <?php if(($_SESSION["secret"]) == "1"): ?><input type="checkbox" id="secret" value="1" style="margin-left:30px;" checked/><?php else: ?><input type="checkbox" id="secret" value="1" style="margin-left:30px;" /><?php endif; ?>
		 显示为保密</p>
		  <label for="intro" id="profileinfo">简介：</label>	  
          <p><textarea id="intro" cols="85" rows="5" onkeyup="intro_max()" onkeypress="intro_max()"><?php echo ($_SESSION["intro"]); ?></textarea></p>
		  (30字以内)<button id="submit" class="button orange bigrounded check_prompt" />保存</button>
		  <span class="result"></span>
		   <!-- 修改个人信息 --><?php endswitch;?>
		</div>
		</div>
      <div class="sidebar">
        <div class="gadget">
          <h2 class="star">个人设置</h2>
          <div class="clr"></div>
          <ul class="sb_menu">
		  <li><a href="<?php echo U('Profile/editprofile');?>"><span class="info">基本资料</span></a></li>	
		  <li><a href="<?php echo U('Profile/editprofile?set=avatar');?>"><span class="info">修改头像</span></a></li>
		  <li><a href="<?php echo U('Profile/editprofile?set=pass');?>"><span class="info">修改密码</span></a></li>	  			
		  </ul>
        </div>
        </div>
      <div class="clr"></div>
    </div></div>
  </div>
  <div id="edit_result" title="操作进程">	
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
</script></div>
   </body>
   <?php switch($_GET["set"]): case "avatar": ?><script type="text/javascript">
$(function($){
//上传头像(uploadify插件)
		$("#user-pic").uploadify({
			'queueSizeLimit' : 1,
			'removeTimeout' : 0.5,
			'preventCaching' : true,
			'multi'    : false,
			'swf' 			: '__PUBLIC__/js/uploadify/uploadify.swf',
			'uploader' 		: '<?php echo U('profile/editInfo?edit=avatar&step=1&uid='.$_SESSION['id']);?>',
			'buttonText' 	: '<i class="userup-icon"></i>上传头像',
			'width' 		: '200',
			'height' 		: '200',
			'fileTypeExts'	: '*.jpg; *.png; *.gif; *.jpeg;',
			'onUploadSuccess' : function(file, data, response){			 
				var data = $.parseJSON(data);
				if(data['status'] == 0){
					$('#edit_result').text(data['info']);
					$('#edit_result').dialog('open');
					return;
				}
				$('.tcrop').text('头像预览');
				$('.upload-btn').hide(1000);
				var preview = $('.upload-area').children('#preview-hidden');
				preview.show().removeClass('hidden');
				//两个预览窗口赋值
				$('.crop').children('img').attr('src',data['data']+'?random='+Math.random());
				//隐藏表单赋值
				$('#img_src').val(data['data']);
				//绑定需要裁剪的图片
				var img = $('<img />');
				preview.append(img);
				preview.children('img').attr('src',data['data']+'?random='+Math.random());
				var crop_img = preview.children('img');
				crop_img.attr('id',"cropbox").show();
				var img = new Image();
				img.src = data['data']+'?random='+Math.random();
				//根据图片大小在画布里居中
				img.onload = function(){
					var img_height = 0;
					var img_width = 0;
					var real_height = img.height;
					var real_width = img.width;
					if(real_height > real_width && real_height > 500){
						var persent = real_height / 500;
						real_height = 500;
						real_width = real_width / persent;
					}else if(real_width > real_height && real_width > 500){
						var persent = real_width / 500;
						real_width = 500;
						real_height = real_height / persent;
					}

					if(real_height < 500){
						img_height = (500 - real_height)/2;	
					}
					if(real_width < 500){
						img_width = (500 - real_width)/2;
					}	
					preview.css({width:(500-img_width)+'px',height:(500-img_height)+'px'});
					preview.css({paddingTop:img_height+'px',paddingLeft:img_width+'px'});		
				}
				//裁剪插件
				$('#cropbox').Jcrop({
		            bgColor:'#333',   //选区背景色
		            bgFade:true,      //选区背景渐显
		            fadeTime:1000,    //背景渐显时间
		            allowSelect:false, //是否可以选区，
		            allowResize:true, //是否可以调整选区大小
		            aspectRatio: 1,     //约束比例
		            minSize : [180,180],//可选最小大小
		            boxWidth : 500,		//画布宽度
		            boxHeight : 500,	//画布高度
		            onChange: showPreview,//改变时重置预览图
		            onSelect: showPreview,//选择时重置预览图
		            setSelect:[ 0, 0, 180, 180],//初始化时位置
		            onSelect: function (c){	//选择时动态赋值，该值是最终传给程序的参数！
			            $('#x').val(c.x);//需裁剪的左上角X轴坐标
			            $('#y').val(c.y);//需裁剪的左上角Y轴坐标
			            $('#w').val(c.w);//需裁剪的宽度
			            $('#h').val(c.h);//需裁剪的高度
		           }
		        });
				//提交裁剪好的图片
				$('.save-pic').click(function(){					
					if($('#preview-hidden').html() == ''){
						$('#edit_result').text('请先选择图片！');
						$('#edit_result').dialog('open');
					}else{
						//由于GD库裁剪gif图片很慢，所以长时间显示弹出框
						$('#edit_result').text('正在保存头像请稍等...');
						$( "#edit_result" ).on('dialogopen', function(event,ui) { } );
						$('#edit_result').dialog('open');

						//是否保留原图
						if ( $('#keep:checked').val() == 1){
				  		 keep_ori = 1;
				 		} else {
				  		 keep_ori = 0;
				 		}

				 		var x = $('#x').val();
				 		var y = $('#y').val();
				 		var w = $('#w').val();
				 		var h = $('#h').val();
				 		var src = $('#img_src').val();
				 		
				 		//alert('x:'+x+', y:'+y+', w:'+w+', h:'+h);
						
						//提交位置参数
						$.post('<?php echo U('profile/editInfo?edit=avatar&step=2&uid='.$_SESSION['id']);?>',
						   {x:x,y:y,w:w,h:h,keep_ori:keep_ori,src:src},
						   function(data){
						   	$('#edit_result').text(data.info);
						   	if (data.status == 1) {
							 setTimeout('location.reload()',1000);							
							}; 
						});											
					}
				});
								 
				//重新上传,清空裁剪参数
				var i = 0;
				$('.reupload-img').click(function(){
					var current_avatar = '<?php echo ($_SESSION["avatar"]); ?>';
				    $.post('<?php echo U('profile/editInfo?edit=avatar&step=r&uid='.$_SESSION['id']);?>');
				    $('.tcrop').text('当前头像');
				    $('.crop').children('img').removeAttr('style');	//移除css属性
				    $('.crop').children('img').attr('src',current_avatar+'?random='+Math.random());
					$('.upload-btn').show(1000);
					$('#preview-hidden').find('*').remove();
					$('#preview-hidden').hide().addClass('hidden').css({'padding-top':0,'padding-left':0});
				});
		     }
		});
		//预览图

		function showPreview(coords){
			var img_width = $('#cropbox').width();
			var img_height = $('#cropbox').height();
			  //根据包裹的容器宽高,设置被除数
			  var rx = 180 / coords.w;
			  var ry = 180 / coords.h; 
			  $('#crop-preview-180').css({
			    width: Math.round(rx * img_width) + 'px',
			    height: Math.round(ry * img_height) + 'px',
			    marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			    marginTop: '-' + Math.round(ry * coords.y) + 'px'
			  });
			  rx = 60 / coords.w;
			  ry = 60 / coords.h;
			  $('#crop-preview-60').css({
			    width: Math.round(rx * img_width) + 'px',
			    height: Math.round(ry * img_height) + 'px',
			    marginLeft: '-' + Math.round(rx * coords.x) + 'px',
			    marginTop: '-' + Math.round(ry * coords.y) + 'px'
			  });
		}

 		//编辑结果对话框
 		$('#edit_result').dialog({
		 autoOpen: false,
		 resizable: false,
		 modal: true,
		 open: function(event,ui){		
			setTimeout(function(){ $('#edit_result').dialog('close')},2000);
		 }
 		});			
});
</script>
<!-- 修改头像 --><?php break;?>
		  <?php case "pass": ?><script type="text/javascript">
  $(function($){
  //改密
 $('#pass').submit(function(){
  $.post('<?php echo U('profile/editInfo?edit=pass');?>',
         {old_pword:$('#old_pword').val(),pword:$('#new_pword').val(),repword:$('#new_repword').val()},
		function(data){
          if (data.status != 1){
  	       $('.result').html(data.info).show();
		   setTimeout(function(){ $('.result').hide()},2000);
		  } else {
		   $.ThinkBox.success(data.info,{'delayClose':1000});
		   setTimeout(function(){location.href = data.data},2000);
		  }
  });
 });
});
		  </script>
		  <!-- 修改密码 --><?php break;?>
		  <?php default: ?>
		  <script type="text/javascript">
$(function($){ 
 $('#submit').click(function(){   //提交修改
  name = $('#disname').val();
  email = $('#email').val();
  birth = $('#birth').val();
  sex = $('input[name=sex]:checked').val();
  intro = $('#intro').val();
   
  if ( $('#secret:checked').val() == 1) {
   secret = 1;
  } else {
   secret = 0;
  }
  
  $.post('<?php echo U('profile/editInfo?edit=info&uid='.$_SESSION['id']);?>',
         {disname:name,email:email,birth:birth,sex:sex,intro:intro,secret:secret,location:$('#province').val() +' '+ $('#city').val()},
		 function(data){
		  if (data.status != 1){
  	    $('.result').text(data.info).show();
		    setTimeout(function(){ $('.result').hide()},2000);
		  } else {
        $('#edit_result').text(data.info);
		    $('#edit_result').dialog('open');
		    setTimeout('location.reload()',1000);
		  }
  });
 });

 //Jquery ui日期控件调用
 $("#birth").datepicker({
  changeMonth: true,
  changeYear: true,
  yearRange: '1950:2012'
 });
 
 //编辑结果对话框
 $('#edit_result').dialog({
  autoOpen: false,
  resizable: false,
  modal: true,
  open: function(event,ui){		
	setTimeout(function(){ $('#edit_result').dialog('close')},2000);
  }
 });
});

//AJAX获取数据,areaId是所在地的area_id,areaType是传递的类型
function loadArea(areaId,areaType) {
    $.post('<?php echo U('profile/getArea');?>',{'areaId':areaId},function(data){
	       $('#'+areaType).empty();
		   $.each(data,function(no,items){
                $('#'+areaType).append('<option value="'+items.area_id+'">'+items.area_name+'</option>');
            });
    });
}

function intro_max() {  //检查回复长度
  input_len = $('#intro').val().length;
  input_len = 30-input_len;
  if (input_len < 0) {
    str = $('#intro').val();
    content = str.substr(0,30);
    $('#intro').val(content);
  }
}
</script>
		   <!-- 修改个人信息 --><?php endswitch;?>
</html>