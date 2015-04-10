<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/easyui/themes/bootstrap/easyui.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/easyui/themes/icon.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/easyui/locale/easyui-lang-zh_CN.js"></script>

<script type="text/javascript" src="__PUBLIC__/js/formvalidator/formValidator.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/formvalidator/formValidatorRegex.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/admin/bootstrap.css" />
<script type="text/javascript" src="__PUBLIC__/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/ueditor/lang/zh-cn/zh-cn.js"></script>


<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/uploadify_admin/uploadify.css" />
<script src="__PUBLIC__/js/uploadify_admin/jquery.uploadify.min.js" type="text/javascript"></script>
</head>
<body class="easyui-layout">
<!-- 头部 -->
<div id="toparea" data-options="region:'north',border:false">
	<div id="topmenu" class="easyui-panel" data-options="fit:true,border:false">
		<a class="logo">后台管理系统v1.0</a>
		<ul class="nav">
			<?php if(is_array($menu_array)): foreach($menu_array as $key=>$_value): ?><li><a <?php if(($_value["id"]) == "1"): ?>class="focus"<?php endif; ?> href="javascript:void(0);" onclick="getLeft(<?php echo ($_value["id"]); ?>,'<?php echo ($_value["name"]); ?>', this)"><?php echo ($_value["name"]); ?></a></li><?php endforeach; endif; ?>
		</ul>
		<ul class="nav-right">
			<li>
				<span>您好！ <?php echo ($admin_username); ?> [<?php echo ($rolename); ?>] | <a href="javascript:logout();">[退出]</a></span> | 
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto',onChange:onChangeTheme">
					<option value='default' >Default</option>
					<option value='gray' >Gray</option>
					<option value='bootstrap' selected>Bootstrap</option>
					<option value='metro' >Metro</option>
				</select>
			</li>
		</ul>
	</div>
</div>
    
<!-- 左侧菜单 -->
<div id="leftarea" data-options="iconCls:'icon-tip',region:'west',title:'我的面板',split:true">
	<div id="leftmenu" class="easyui-accordion" data-options="fit:true,border:false"></div>
</div>  
    
<!-- 内容 -->
<div id="mainarea" data-options="region:'center'">
    <div id="pagetabs" class="easyui-tabs" data-options="tabPosition:'bottom',fit:true,border:false,plain:false">
    	<div title="后台首页" href="<?php echo U('Index/public_main');?>" data-options="cache:false"></div>
    </div>
</div>

<script type="text/javascript">
$(function(){
	getLeft(1, '我的面板');
	$.messager.show({			//登录默认提示
		title:'登录提示',
		msg:'您好！<?php echo ($admin_username); ?> 欢迎回来！<br/>最后登录时间：<?php if($info.lastlogintime): echo (date('Y-m-d H:i:s',$info["lastlogintime"])); else: ?>-<?php endif; ?><br/>最后登录IP：<?php echo ($info["lastloginip"]); ?>',
		timeout:5000,
		showType:'slide'
	});
})
//退出登录
function logout(){
	$.messager.confirm('提示信息', '确定要退出登录吗？', function(result){
		if(result) window.location.href = '<?php echo U('Index/public_logout');?>';
	})
}
//切换主题
function onChangeTheme(theme){
	$('head').find('link:first').attr('href', '__PUBLIC__/js/easyui/themes/'+theme+'/easyui.css');
	$('head').find('link:last').attr('href', '__PUBLIC__/css/admin/'+theme+'.css');
}
//显示左侧栏目
function getLeft(menuid, title, that){
	//更新标题名称
	$('body').layout('panel', 'west').panel({title: title});
	//获取左侧内容
	$.post('<?php echo U('Index/public_menuLeft');?>', {menuid: menuid}, function(data){
		removeLeft();
		//增加
		$.each(data, function(i, menu) {
	        var menulist = '<dl class="sonmenu">';
			$.each(menu.son, function(i, son) {
				menulist += '<dd><a href="javascript:void(0);" onclick="openUrl(\''+son.url+'\', \''+son.name+'\', this)">'+son.name+'</a></dd>';
			});
			menulist += '</dl>';
			$("#leftmenu").accordion("add", {title: menu.name, content: menulist});
		});
	})
	//默认选中头部菜单
	if(that){
		$('#topmenu .nav li').each(function(){
			$(this).children().removeClass('focus');
		})
		$(that).addClass('focus');
	}
}
//移除左侧菜单
function removeLeft(stop){
	var pp = $("#leftmenu").accordion("panels");
	$.each(pp, function(i, n) {
		if(n){
			var t = n.panel("options").title;
			$("#leftmenu").accordion("remove", t);
		}
    });
	var pp = $('#leftmenu').accordion('getSelected');
    if(pp) {
        var t = pp.panel('options').title;
        $('#leftmenu').accordion('remove', t);
    }
    if(!stop) removeLeft(true)//发现执行两次才能彻底移除
}
//显示打开内容
function openUrl(url, title, that){
	if($('#pagetabs').tabs('exists', title)){
		$('#pagetabs').tabs('select', title);
	}else{
		$('#pagetabs').tabs('add',{
			title: title,
			href: url,
			closable: true,
			cache: false
		});
	}
	//默认选择左侧菜单
	if(that){
		$('#leftmenu .sonmenu a').each(function(){
			$(this).removeClass('focus');
		})
		$(that).addClass('focus');
	}
}
//防止登录超时和后台在线人数统计
setInterval(function(){
	$.get('<?php echo U('Index/public_sessionLife');?>');
}, 180000);
</script>
</body>
</html>