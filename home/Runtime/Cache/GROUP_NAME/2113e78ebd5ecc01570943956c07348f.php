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

<title>上传图片_<?php echo ($_SESSION["nickname"]); ?>_JellyNotes</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/js/uploadify/uploadify.css" />
<script src="__PUBLIC__/js/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
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
    <div class="content_resize3">
		  <p><span class="info">选择专辑：</span> 
		    <select id="cid" style="font-size:16px;"><?php if(is_array($albumList)): $i = 0; $__LIST__ = $albumList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?><option value="<?php echo ($a["album_id"]); ?>"/><?php echo ($a["album_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>
		    &nbsp; <button class="button orange large" id="edit_cg">查看列表</button>
<script type="text/javascript">
$(function($){
 $('#edit_dialog').dialog({
	autoOpen: false,
	resizable: false,
	modal: true
 });
		
 //打开对话框		
 $('#edit_cg').click(function() {
  $('#edit_dialog').dialog('open');
 });
 
 //转移分组
 $('#select_cg').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
			  '确认':function(){
			   var pf_id = $('#pf_id').val();
			   var cg_id = $('#cg_id').val();
			   $.post('<?php echo ($operaUrl["move"]); ?>',
        		{pf_id:pf_id,cg_id:cg_id},
      			function(data){
	 	   			if (data.status == 1){
	 	   		<?php if(!empty($_GET['aid'])): ?>$('#id_'+pf_id).hide(800);//图片转移效果<?php endif; ?>
	 	   		<?php if(!empty($_GET['cid'])): ?>$('#id_'+pf_id).hide(800);//文章转移效果<?php endif; ?>
	 	   		<?php if(!empty($_GET['gid'])): ?>$('#id_'+pf_id).hide(800);//好友或关注转移效果<?php endif; ?>	 	   			 					
					$('#pf_id_'+pf_id).attr('cg_id',cg_id);	//其他转移赋值
					$('#move_result').text(data.info);
		    		$('#move_result').dialog('open');			
		   		}	
  			  });
			  $(this).dialog('close');
			},
			  '取消':function(){ $(this).dialog('close')}
	}
 });
 
 //转移结果
 $('#move_result').dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	draggable: false,
	open: function(event,ui){
		$('#select_cg').dialog('close');
		setTimeout(function(){ $('#move_result').dialog('close')},1000);
	}
 });
});

 //转移分组
 function move_cg(param) {
  var pf_id = $(param).attr('pf_id');
  var cg_id = $(param).attr('cg_id');
  if(cg_id == 0){	//判断是否没有分组
    var option = '<option value="0">请选择分组</option>';
	var first_option = $('#cg_id').find('option:first').val();	//获取第一个选项值
	if (first_option != 0){	//第一个选项不是0分组则添加
	 $('#cg_id').prepend(option);
	}
	$('#cg_id').val(0);
  } else {
  	$('#cg_id').val(cg_id);
  }
    
  $('#pf_id').val(pf_id);  
  $('#select_cg').dialog('open');
 }

		  </script>
		  <div id="edit_dialog" title="所有列表">
		  <div id="list">
		  <?php if(is_array($cate_group)): $i = 0; $__LIST__ = $cate_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cg): $mod = ($i % 2 );++$i;?><p><span class="desc"><?php echo ($cg["name"]); ?></span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="<?php echo ($cg["name"]); ?>" cg_id="<?php echo ($cg["cg_id"]); ?>">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="<?php echo ($cg["cg_id"]); ?>">删除</a></p><?php endforeach; endif; else: echo "" ;endif; ?>
		  </div>		
		<span id="error_result" class="result"></span>		       	   
		  <p><button class="button orange large" id="add_cg">添加</button></p>
		  
		  <div id="new_cg" style="display:none;">
		  <p><span class="category">分组名:</span><input type="text" id="new_name" class="small"/></p>
		  <input type="button" onclick="create_cg()" class="button white" value="保存"/>
		  <input type="button" id="cancel_add" class="button black" value="取消" />
		  </div>
		  
<div id="delete_cg" title="请选择">
</div>
<script type="text/javascript">
  //显示新增分类表
 $('#add_cg').click(function(){
  $('#new_cg').show();
  $('#add_cg').hide();
 });
 
 //隐藏新增表
 $('#cancel_add').click(function(){
  $('#new_cg').hide();
  $('#add_cg').show();
 }); 

function create_cg(){
   //新增分组
   var name = $('#new_name').val();   
   $.post('<?php echo ($operaUrl["add"]); ?>',
        {name:name},
      	function(data){
	 	 if (data.status == 1){
		   cg = data.data;
		   var new_cg = '<p><span class="category">' + name + '</span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="' + name + '" cg_id="' + cg.id + '">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="' + cg.id + '">删除</a></p>';		   		   
		  $('#list').append(new_cg);
		  
		  <?php if(($_GET["blog"]) == "1"): ?>//博客页面添加选择分组列表
		    var new_cg = '<li id="cg_id_'+ cg.id +'"><a href="/index.php/blog-proBlog-blog-1-id-<?php echo ($_GET["id"]); ?>-cid-'+cg.id+'"><span class="category">'+ name +'</span></a></li>';
		   	$('#category_list').append(new_cg);<?php endif; ?>

		  <?php if(($_GET["follow"]) == "1"): ?>if ($('#fg_list')) {	//判断是查看关注博客页面还是查看关注页面
			//关注博客页面到选择分组列表
		    var new_cg = '<li id="cg_id_'+ cg.id +'"><a href="/index.php/Profile-followList-follow-1-id-1-gid-'+cg.id+'"><span class="category">'+ name +'</span></a></li>';
		   	$('#fg_list').append(new_cg);
		   } else {
		   	var new_cg = '<li id="cg_id_'+ cg.id +'"><a href="/index.php/blog-followBlog-follow-1-id-1-gid-'+cg.id+'"><span class="category">'+ name +'</span></a></li>';
		   	$('#fgb_list').append(new_cg);
		   }<?php endif; ?>

		  <?php if(($_GET["about"]) == "1"): ?>var new_cg = '<li id="cg_id_'+cg.id+'"><a href="/index.php/blog-id=<?php echo ($_GET["id"]); ?>-gid-'+cg.id+'"><span class="category">'+ name +'</span></a></li>';
		   	$('#friend_list').append(new_cg);<?php endif; ?>
		   
		  if($('#cid')){	//编辑和添加博客页面的添加分组列表
		    var new_c = '<option value="'+cg.id+'" >'+ name +'</option>';
		   	$('#cid').append(new_c);
		  }
		  
		  $('#new_name').val('');	//清空输入框
		  $('#new_cg').hide();
		  $('#add_cg').show();
		 } else {
		  $('#error_result').html(data.info).show();
		   setTimeout(function(){ $('#error_result').hide()},2000);
		 } 	    
   });
}

//删除分组
function del_cg(param){
  if(confirm('确定删除这个分组？')){
   var cg_id = $(param).attr('cg_id');
   $.post('<?php echo ($operaUrl["del"]); ?>',
    {cg_id:cg_id},
    function(data){
     if(data.status == 1){
      $(param).parent().hide(300);
	  if ($('#cg_id_'+cg_id)){
	   $('#cg_id_'+cg_id).hide();
	  }
	  
	  if ($('#cid')){	//移除添加的分组
	   $('#cid').val(cg_id);
	   $('#cid option:selected').remove();
	   $('#cid').val(1); 
	  }
	  
     } else {
	  alert(data.info);
	 }
   });
  }
}

//显示编辑输入框
function modi_name(info){
  var cg_id = $('#cg_id').val(); 
  if (cg_id) {
  var name = $('#save_name').attr('alt');
  //还原正在编辑的表单
  var ori_ele = '<span class="category">' + name + '</span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="' + name + '" cg_id="'+ cg_id +'">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="'+ cg_id +'">删除</a>';
  $('#editing').replaceWith(ori_ele);
 }
 
 //注意内容多也不要换行！
 var name = $(info).attr('name');
 var cg_id = $(info).attr('cg_id');
 var edit_form = '<div id="editing"><input type="text" id="save_name" alt="'+ name +'" value="'+ name +'" class="small"/><input type="hidden" id="cg_id" value="'+ cg_id +'" /><br/><input type="button" class="button white" value="保存" onclick="edit_name(this)" /> &nbsp;<input type="button" onclick="cancel_modi(this)" class="button black" value="取消" /></div>';
 $(info).parent().html(edit_form);
}

//修改分组名
function edit_name(info){
  var name = $('#save_name').val();
  var cg_id = $('#cg_id').val();
  $.post('<?php echo ($operaUrl["edit"]); ?>',
        {name:name,cg_id:cg_id},
      	function(data){
		 if (data.status == 1) {
		  var get_back = '<span class="category">' + name + '</span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="' + name + '" cg_id="' + cg_id + '">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="'+ cg_id +'">删除</a>';
		  $(info).parent().replaceWith(get_back);
		 } else {
		  $('#error_result').html(data.info).show();
		  setTimeout(function(){ $('#error_result').hide();},2000);
		 }
  });
}

//点击取消修改按钮
function cancel_modi(info){
 var cg_id = $('#cg_id').val();
 var name = $('#save_name').attr('alt');
 var content = '<span class="category">' + name + '</span> &nbsp;<a href="javascript:;" onclick="modi_name(this)" name="' + name + '" cg_id="' + cg_id + '">修改</a> &nbsp;<a href="javascript:;" onclick="del_cg(this)" cg_id="'+ cg_id +'">删除</a>';
 $(info).parent().replaceWith(content);
}

/*
window.onbeforeunload = on_handle;
function on_handle(){
 return '您将关闭本页面';
}*/ 
</script>

		  </div>
		  <div id="select_cg" title="请选择">
		  		  <h3>转移到:
		  <select id="cg_id">
		  <?php if(empty($cateList)): if(empty($selectAlbum)): if(is_array($cate_group)): $i = 0; $__LIST__ = $cate_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cg): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cg["cg_id"]); ?>"><?php echo ($cg["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		  <?php else: ?>
		  <?php if(is_array($selectAlbum)): $i = 0; $__LIST__ = $selectAlbum;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sa): $mod = ($i % 2 );++$i;?><option value="<?php echo ($sa["aid"]); ?>"><?php echo ($sa["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		  <?php else: ?>
		  <?php if(is_array($cateList)): $i = 0; $__LIST__ = $cateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ca): $mod = ($i % 2 );++$i;?><option value="<?php echo ($ca["category_id"]); ?>"><?php echo ($ca["category_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		  </select></h3>	  	
		  <input type="hidden" id="pf_id" value="" />
		  </div>
		  <div id="move_result" title="操作结果">	
		  </div>	 			</p>(每次最多上传10张图片，每张图片大小最大2M)
		    <input id="file_upload" name="file_upload" type="file" multiple="true">
			<span class="result" style="display:none"></span>
			<!--显示结果--><div id="uploadsuccess" style="display:none;"></div>
      		<div class="clr"></div>
			<p class="spec"></p>
		   <p><input type="button" value="上传图片" id="upload_now" class="button blue" style="margin-left:40%"/> &nbsp;<input type="button" value="重新选择" id="re_choose" class="button white"/></p>
		   
    </div>
  </div>
</div>
<div id="uploading" title="上传进程">	
</div>
<!--上传图片-->
<script type="text/javascript">
$(function($) {
		$('#file_upload').uploadify({
			'swf'      : '__PUBLIC__/js/uploadify/uploadify.swf',
			'uploader' : '<?php echo U('pic/poNew?do=po&uid='.$_SESSION['id']);?>',
			'auto'     : false,//关闭自动上传
			'uploadLimit' : 10,
			'fileSizeLimit' : '2MB',			
			'buttonText' : '选择图片',
			'fileTypeExts'	: '*.jpg; *.png; *.gif; *.jpeg;',
			'onUploadStart' : function(file) { 
			$('#uploading').text('正在上传中,请不要关闭浏览器.....'); 
			$('#uploading').dialog('open')},
			'onSelectError' : function(file, errorCode, errorMsg) { this.queueData.errorMsg = '文件类型错误，或是您的图片超过大小限制。'},
			'onQueueComplete' : function(queueData){
			       $('#uploading').text('上传成功');
				   setTimeout(function(){ $('#uploading').dialog('close')},1000)},
			'onUploadSuccess' : function(file, data, response) { 
				$('#uploadsuccess').html(data).show();				  
			}
		});
		
	//上传	
    $("#upload_now").click(function(){
	 $('#file_upload').uploadify('settings', 'formData', {cid:$('#cid').val()});	//这里传值才能成功
	  $('#file_upload').uploadify('upload', '*');	  
	});
	
	//取消
	$("#re_choose").click(function(){
	  $('#file_upload').uploadify('cancel','*');
	});
	
	//编辑结果对话框
 	$('#uploading').dialog({
	 autoOpen: false,
	 resizable: false,
	 modal: true
 	});
});
</script>
</body>
</html>