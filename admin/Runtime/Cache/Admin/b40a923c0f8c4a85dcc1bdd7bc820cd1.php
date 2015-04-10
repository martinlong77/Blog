<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
if(typeof(ue) != 'undefined') ue.destroy();	//底部信息编辑器
var ue = UE.getEditor('bottom_info',{
	toolbars: [
	[ 'undo', 'redo', 'bold', 'italic', 'underline' ,'fontfamily', 'fontsize', 'link', 'unlink']
	]
	,initialFrameWidth:720  //初始化编辑器宽度,默认1000
    ,initialFrameHeight:420  //初始化编辑器高度,默认320
});

if(typeof(links) != 'undefined') links.destroy();	//友情链接
var links = UE.getEditor('links',{
	toolbars: [
	['fontfamily', 'fontsize', 'link', 'unlink']
	]
	,initialFrameWidth:720  //初始化编辑器宽度,默认1000
    ,initialFrameHeight:120  //初始化编辑器高度,默认320
});

if(typeof(illustration) != 'undefined') illustration.destroy();	//
var illustration = UE.getEditor('illustration',{
	toolbars: [
	[ 'undo', 'redo', 'bold', 'italic', 'underline' ,'fontfamily', 'fontsize']
	]
	,initialFrameWidth:720  //初始化编辑器宽度,默认1000
    ,initialFrameHeight:320  //初始化编辑器高度,默认320
});

$('#set_btn').click(function(){
	sitename = $('#sitename').val();
	keywords = $('#keywords').val();
	desc = $('#description').val();
	tel = $('#tel').val();
	s_blog = $('#sina_blog').val();
	n_blog = $('#ne163_blog').val();
	so_blog = $('#sohu_blog').val();
	s_weibo = $('#sina_weibo').val();
	slogan = $('#slogan').val();
	links = $('#links').val();
	bottom_info = ue.getContent();
	illustration = $('#illustration').val();
	img1 = $('#img1').attr('src');
	img2 = $('#img2').attr('src');
	img1_url = $('#img1_url').val();
	img2_url = $('#img2_url').val();
	$.post("<?php echo U('Setting/site');?>", {sitename:sitename,keywords:keywords,description:desc,tel:tel,sina_blog:s_blog,ne163_blog:n_blog,sohu_blog:so_blog,sina_weibo:s_weibo,slogan:slogan,links:links,bottom_info:bottom_info,illustration:illustration,img1:img1,img2:img2,img1_url:img1_url,img2_url:img2_url}, function(data){
		!data.status ? $.messager.alert('提示信息', data.info, 'error') : location.reload(); 
	});
});
</script>
<form id="SettingForm" style="font-size:13px"><table cellspacing="10">
	<tr>
		<td width="90">网站标题：</td>
		<td colspan="2"><input id="sitename" type="text" style="width:280px;height:22px" value="<?php echo ($info["sitename"]); ?>" /></td>
	</tr>
	<tr>
		<td width="90">网站关键字：</td>
		<td colspan="2"><input id="keywords" type="text" style="width:280px;height:22px" value="<?php echo ($info["keywords"]); ?>" /></td>
	</tr>	
	<tr>
		<td width="90">网站描述：</td>
		<td colspan="2"><textarea id="description"><?php echo (nl2br($info["description"])); ?></textarea></td>
	</tr>	
	<tr>
		<td width="90">公司电话：</td>
		<td colspan="2"><input id="tel" type="text" style="width:280px;height:22px" value="<?php echo ($info["tel"]); ?>" /></td>
	</tr>
	<tr>
		<td>新浪博客：</td>
		<td><input id="sina_blog" type="text" style="width:280px;height:22px" value="<?php echo ($info["sina_blog"]); ?>" /></td>
		<td><div id="old_passwordTip"></div></td>
	</tr>
	<tr>
		<td>网易博客：</td>
		<td><input id="ne163_blog" type="text" style="width:280px;height:22px"  value="<?php echo ($info["ne163_blog"]); ?>" /></td>
		<td><div id="new_passwordTip"></div></td>
	</tr>
	<tr>
		<td>搜狐博客：</td>
		<td><input id="sohu_blog" type="text" style="width:280px;height:22px"  value="<?php echo ($info["sohu_blog"]); ?>"/></td>
		<td><div id="new_pwdconfirmTip"></div></td>
	</tr>
	<tr>
		<td>新浪微博：</td>
		<td><input id="sina_weibo" type="text" style="width:280px;height:22px" value="<?php echo ($info["sina_weibo"]); ?>" /></td>
		<td><div id="new_pwdconfirmTip"></div></td>
	</tr>
	<tr>
		<td>网站顶部标语：</td>
		<td><input id="slogan" type="text" style="width:280px;height:22px" value="<?php echo ($info["slogan"]); ?>" /></td>
	</tr>
	<tr>
		<td>友情链接：</td>
		<td><textarea id="links" style="width:280px;height:22px"><?php echo ($info["links"]); ?></textarea></td>
	</tr>
	<tr>
		<td>底部信息：</td>
		<td><textarea id="bottom_info"><?php echo ($info["bottom_info"]); ?></textarea></td>
	</tr>
	<tr>
		<td>注册说明：</td>
		<td><textarea id="illustration"><?php echo ($info["illustration"]); ?></textarea></td>
	</tr>
			<tr>
	<td>首页图片1：</td>
	<td>
	<?php if(empty($info["img1"])): ?><div id="img1_area"><input id="index_img1" name="index_img1" type="file" multiple="true">
<a id="img1_upload" class="easyui-linkbutton">上传</a> &nbsp;<a id="img1_re_choose" class="easyui-linkbutton">重选</a></div><div id="img1_uploadsuccess" style="display:none"><img id="img1" width="300px"  /><input type="hidden" id="img1_path" /><br/><a id="img1_delete" onclick="delete_it(this)" type="img1" class="easyui-linkbutton">删除这张</a></div>
	<?php else: ?>
	<div id="img1_area" style="display:none"><input id="index_img1" name="index_img1" type="file" multiple="true">
<a id="img1_upload" class="easyui-linkbutton">上传</a> &nbsp;<a id="img1_re_choose" class="easyui-linkbutton">重选</a></div><div id="img1_uploadsuccess"><img id="img1" src="<?php echo ($info["img1"]); ?>" width="300px"  /><input type="hidden" id="img1_path" value="<?php echo ($info["relative_path1"]); ?>" /><br/><a id="img1_delete" onclick="delete_it(this)" type="img1" class="easyui-linkbutton">删除这张</a></div><?php endif; ?>
</td>	
	</tr>	
	<tr>
		<td>图片1链接：</td>
		<td><input id="img1_url" type="text" style="width:280px;height:22px"  value="<?php echo ($info["img1_url"]); ?>"/></td>
		<td><div id="new_pwdconfirmTip"></div></td>
	</tr>
				<tr>
	<td>首页图片2：</td>
	<td>
	<?php if(empty($info["img2"])): ?><div id="img2_area"><input id="index_img2" name="index_img2" type="file" multiple="true">
<a id="img2_upload" class="easyui-linkbutton">上传</a> &nbsp;<a id="img2_re_choose" class="easyui-linkbutton">重选</a></div><div id="img2_uploadsuccess" style="display:none"><img id="img2" width="300px"  /><input type="hidden" id="img2_path" /><br/><a id="img2_delete" onclick="delete_it(this)" type="img2" class="easyui-linkbutton">删除这张</a></div>
	<?php else: ?>
	<div id="img2_area" style="display:none"><input id="index_img2" name="index_img2" type="file" multiple="true">
<a id="img2_upload" class="easyui-linkbutton">上传</a> &nbsp;<a id="img2_re_choose" class="easyui-linkbutton">重选</a></div><div id="img2_uploadsuccess"><img id="img2" src="<?php echo ($info["img2"]); ?>" width="300px"  /><input type="hidden" id="img2_path" value="<?php echo ($info["relative_path2"]); ?>" /><br/><a id="img2_delete" onclick="delete_it(this)" type="img2" class="easyui-linkbutton">删除这张</a></div><?php endif; ?>
</td>	
	</tr>	
	<tr>
		<td>图片2链接：</td>
		<td><input id="img2_url" type="text" style="width:280px;height:22px"  value="<?php echo ($info["img2_url"]); ?>"/></td>
		<td><div id="new_pwdconfirmTip"></div></td>
	</tr>
	<tr>
		<td colspan="3"><a id="set_btn" class="easyui-linkbutton">提交</a></td>
	</tr>
</table>
</form>
</div>
<!--上传图片-->
<script type="text/javascript">
	//if(typeof($('#index_img1').uploadify()) != 'undefined') $('#index_img1').uploadify('destroy');
	$('#index_img1').uploadify({
			'swf'      : '__PUBLIC__/js/uploadify/uploadify.swf',
			'uploader' : '<?php echo U('Content/upload_cover');?>',
			'auto'     : false,//关闭自动上传
			//'uploadLimit' : 1,
			'fileSizeLimit' : '1MB',			
			'buttonText' : '选择图片',
			'fileTypeExts'	: '*.jpg; *.png; *.gif; *.jpeg;',
			'onSelectError' : function(file, errorCode, errorMsg) { this.queueData.errorMsg = '文件类型错误，或是您的图片超过大小限制。'},
			'onUploadSuccess' : function(file, data, response) { 
				//$('#uploadsuccess').html(data).show();				
				img = jQuery.parseJSON(data);
				$('#img1').attr('src',img['dis_path']);
				$('#img1_path').val(img['save_path']); 
				$('#img1_uploadsuccess').show();
				$('#img1_area').hide();
				$("#img1_delete").show();				  
			}
	});

	//上传	
    $("#img1_upload").click(function(){
	  $('#index_img1').uploadify('upload');	  
	});
	
	//取消
	$("#img1_re_choose").click(function(){
	  $('#index_img1').uploadify('cancel');
	});
	
	$('#index_img2').uploadify({
			'swf'      : '__PUBLIC__/js/uploadify/uploadify.swf',
			'uploader' : '<?php echo U('Content/upload_cover');?>',
			'auto'     : false,//关闭自动上传
			//'uploadLimit' : 1,
			'fileSizeLimit' : '1MB',			
			'buttonText' : '选择图片',
			'fileTypeExts'	: '*.jpg; *.png; *.gif; *.jpeg;',
			'onSelectError' : function(file, errorCode, errorMsg) { this.queueData.errorMsg = '文件类型错误，或是您的图片超过大小限制。'},
			'onUploadSuccess' : function(file, data, response) { 
				//$('#uploadsuccess').html(data).show();				
				img = jQuery.parseJSON(data);
				$('#img2').attr('src',img['dis_path']);
				$('#img2_path').val(img['save_path']); 
				$('#img2_uploadsuccess').show();
				$('#img2_area').hide();
				$("#img2_delete").show();				  
			}
	});
		
	//上传	
    $("#img2_upload").click(function(){
	  $('#index_img2').uploadify('upload');	  
	});
	
	//取消
	$("#img2_re_choose").click(function(){
	  $('#index_img2').uploadify('cancel');
	});
	
//删除
function delete_it(param){
	 if(confirm('确定删除吗？')){
	  img = $(param).attr('type');
	  img == 'img1' ? path = $('#img1_path').val() : path = $('#img2_path').val();
	  	  
	  url = "<?php echo U('Setting/delete_img');?>";
	  $.post(url,{path:path,img:img},function(data){
	    if(data.status == 1){
		  if(img == 'img1'){
			$('#img1_uploadsuccess').hide();
	  	  	$("#img1_area").show();
		  	$("#img1_delete").hide();
			$('#index_img1').uploadify('cancel');
			$('#img1').attr('src','');
			$('#img1_path').val('');
		  } else {
		  	$('#img2_uploadsuccess').hide();
	  	  	$("#img2_area").show();
		  	$("#img2_delete").hide();
			$('#index_img2').uploadify('cancel');
			$('#img2').attr('src','');
			$('#img2_path').val('');
		  }	
		} else {
			$.messager.alert('操作失败',data.info,'error');
		}
	  });
	 }
}		
</script>