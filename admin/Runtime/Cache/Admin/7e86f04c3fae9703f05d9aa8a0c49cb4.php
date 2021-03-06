<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/easyui/themes/bootstrap/easyui.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/easyui/themes/icon.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/easyui/locale/easyui-lang-zh_CN.js"></script>

<style type="text/css">
form{width:280px;height:120px;margin:30px auto 0;}
form div label{float:left;display:block;width:65px;font-size:16px;padding-top:6px;}
form div{margin:8px auto;}
form div.input input{border:1px solid #DDDDDD;background:#fff;height:22px;padding:2px 3px;}
#username,#password{width:200px;}
#code{width:68px}
</style>
</head>
<body>
<div class="easyui-dialog" title="用户登录" style="width:380px;height:240px" data-options="closable:false,buttons:[{text:'登录',handler:login}]">
	<form id='form' method="post">
		<div class="input">
			<label for="username">用户名:</label>  
	        <input type="text" name="username" id="username" />  
		</div>
		<div class="input">
			<label for="password">密&nbsp;&nbsp;码:</label>  
	        <input type="password" name="password" id="password" />  
		</div>
		<!--<div class="input">
			<label for="code">验证码:</label>  
	        <input type="text" name="code" id="code" size="4" />
	        <span style="margin-left:10px"><img id="code_img" align="top" onclick="this.src=this.src+'&'+Math.random();" src="<?php echo U('Index/code?code_len=4&font_size=13&width=100&height=28&now='.time());?>"></span> 
		</div>-->
	</form> 
</div>

</div>
<script type="text/javascript">
$(function(){
	$('input:text:first').focus();
	$('form').keyup(function(event){
		if(event.keyCode ==13){
			login();
		}
	});
})
var login = function(){
	if(!$('#username').val()){
		$.messager.alert('提示信息', '请填写用户名', 'error');
		return false;
	}
	if(!$('#password').val()){
		$.messager.alert('提示信息', '请填写密码', 'error');
		return false;
	}/*
	if(!$('#code').val()){
		$.messager.alert('提示信息', '请填写验证码', 'error');
		return false;
	}*/
	$.post('<?php echo U('Index/login?dosubmit=1');?>', $("form").serialize(), function(data){
		if(!data.status){
			$.messager.alert('提示信息', data.info, 'error');
		}else{
			$.messager.progress({text:'加载中，请稍候...'});
			window.location.href = data.url;
		}
	},'json');
	return false;
}
</script>
</body>
</html>