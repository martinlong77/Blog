<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
if(ue) ue.destroy();
var ue = UE.getEditor('a_content');
$(function(){	
	$('#sub_art').click(function(){	//提交修改
	  title = $('#a_title').val();
	  content = ue.getContent();
	  keywords = $('#a_keywords').val();
	  desc = $('#a_desc').val();
	  aid = $('#a_aid').val();
	  type = $('#a_type').val();
	  cover = $('#a_cover').attr('src');
	  
	  <?php if(empty($_GET["id"])): ?>activity = $('#activity').is(':checked');
	  <?php else: ?>
	  activity = false;<?php endif; ?>
	
	  $.post('<?php echo U('Content/article_operate');?>',{title:title,type:type,cover:cover,content:content,keywords:keywords,description:desc,id:aid,activity:activity},function(data){
		  if(data.status == 1){
		    $.messager.alert('操作成功',data.info,'info',function(){
			edit = $('#pagetabs').tabs('exists','修改文章');
			add = $('#pagetabs').tabs('exists','发表新文章');   	
			if(edit) $('#pagetabs').tabs('close', '修改文章');	//如果存在则关闭该页		
			if(add) $('#pagetabs').tabs('close', '发表新文章');
			openUrl(data.data,'文章列表',0);   
		   });
		  } else {
		   $.messager.alert('操作失败',data.info,'error'); 
		  }
	  });	
	});	
});

</script>
<form id="operat_edit" style="font-size:13px">
<table cellspacing="10">
	<tr>
		<td width="90">文章标题：</td>
        <td><input id="a_title" name="a_title" type="text" style="width:800px;height:22px" value="<?php echo ($article["title"]); ?>" /></td>
	</tr>
    	<input type="hidden" id="a_aid" value="<?php echo ($article["id"]); ?>" />

	<tr>
		<td>文章内容：</td>
		<td><textarea id="a_content" style="width:800px;height:600px;"><?php echo ($article["content"]); ?></textarea>
</td>
	</tr>
	<tr>
		<td>文章关键字：</td>
		<td><input id="a_keywords" name="a_keywords" type="text" value="<?php echo ($article["keywords"]); ?>" style="width:800px;height:22px" /></td>
	</tr>
	<tr>
		<td>文章描述：</td>
		<td><textarea id="a_desc" style="width:800px;height:52px" ><?php echo ($article["description"]); ?></textarea> </td>
	</tr>
    <tr>
		<td>文章分类：</td>
		<td><select id="a_type" style="width:180px;height:22px"><?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i; if(($t["number"]) == $article["type"]): ?><option value="<?php echo ($t["number"]); ?>" selected><?php echo ($t["name"]); ?></option><?php else: ?><option value="<?php echo ($t["number"]); ?>"><?php echo ($t["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?></select></td>
	</tr>
	<tr>
	<td>选择封面：</td>
	<td>
	<?php if(empty($article["cover"])): ?><div id="upload_area"><input id="file_upload" name="file_upload" type="file" multiple="true">
<a id="upload_now" class="easyui-linkbutton">上传</a> &nbsp;<a id="re_choose" class="easyui-linkbutton">重选</a></div><div id="uploadsuccess" style="display:none"><img id="a_cover" width="300px"  /><input type="hidden" id="save_path" /><br/><a id="delete_it" onclick="delete_it(this)" type="a" class="easyui-linkbutton">删除这张</a></div>
	<?php else: ?>
	<div id="upload_area" style="display:none"><input id="file_upload" name="file_upload" type="file" multiple="true">
<a id="upload_now" class="easyui-linkbutton">上传</a> &nbsp;<a id="re_choose" class="easyui-linkbutton">重选</a></div><div id="uploadsuccess"><img id="a_cover" src="<?php echo ($article["cover"]); ?>" width="300px"  /><input type="hidden" id="save_path" value="<?php echo ($article["relative_path"]); ?>" /><br/><a id="delete_it" onclick="delete_it(this)" type="a" class="easyui-linkbutton">删除这张</a></div><?php endif; ?>
</td>	
	</tr>
	<?php if(empty($_GET["id"])): ?><tr>
		<td>同步：</td>
		<td>同时发布到活动——读书社 <input type="checkbox" id="activity"> </td>
	</tr>
    <tr><?php endif; ?>
	<tr>
		<td colspan="3"><a id="sub_art" class="easyui-linkbutton">发表</a></td>
	</tr>
</table>
</form>
</div>
<!--上传图片-->
<script type="text/javascript">
		$('#file_upload').uploadify({
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
				$('#a_cover').attr('src',img['dis_path']);
				$('#save_path').val(img['save_path']); 
				$('#uploadsuccess').show();
				$('#upload_area').hide();
				$("#delete_it").show();				  
			}
		});
		
	//上传	
    $("#upload_now").click(function(){
	  $('#file_upload').uploadify('upload');	  
	});
	
	//取消
	$("#re_choose").click(function(){
	  $('#file_upload').uploadify('cancel');
	});
	
	//删除
	$('#delete_it').click(function(){
	 if(confirm('确定删除吗？')){
	  var path = $('#save_path').val();
	  var id = $('#a_aid').val();
	  url = "<?php echo U('Content/delete_cover?type='.$_GET['type'].'');?>";
	  $.post(url,{path:path,id:id},function(data){
	    if(data.status == 1){
			$('#uploadsuccess').hide();
	  	  	$("#upload_area").show();
		  	$("#delete_it").hide();
			$('#file_upload').uploadify('cancel');
			$('#a_cover').removeAttr('src');
			$('#save_path').val('');
		} else {
			$.messager.alert('操作失败',data.info,'error');
		}
	  });
	 }
    });		
</script>