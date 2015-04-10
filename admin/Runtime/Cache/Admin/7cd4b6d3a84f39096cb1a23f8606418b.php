<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
$(function(){	
	$('#banner_sub').click(function(){	//提交修改	
	  title = $('#banner_title').val();
	  url = $('#banner_url').val();
	  type = $('#banner_type').val();
	  level = $('#banner_level').val();
	  img = $('#a_cover').attr('src');
	  id = $('#a_aid').val();

  	  if(check_input(title,'标题不能为空')) return false;
	  if(check_input(url,'链接不能为空')) return false;
	  $.post("<?php echo U('Content/banner_operate');?>",{title:title,url:url,img:img,type:type,level:level,id:id},function(data){
		  if(data.status == 1){
		    $.messager.alert('操作成功',data.info,'info',function(){
			edit = $('#pagetabs').tabs('exists','修改Banner');
			add = $('#pagetabs').tabs('exists','添加Banner');   	
			if(edit) $('#pagetabs').tabs('close', '修改Banner');	//如果存在则关闭该页		
			if(add) $('#pagetabs').tabs('close', '添加Banner');
			openUrl(data.data,'Banner列表',0);   
		   });
		  } else {
		   $.messager.alert('操作失败',data.info,'error'); 
		  }
	  });	
	});	
	
	$('#banner_type').change(function(){
		type = $('#banner_type').val();
		$.post("<?php echo U('Content/get_level');?>",{type:type},function(data){
			$('#banner_level').html(data);
		});
	});
	
	$('#banner_level').val(<?php echo ($banner["level"]); ?>);
	$('#banner_type').val(<?php echo ($banner["type"]); ?>);			
});

function check_input(val,text){
	if(val.length==0) {
		$.messager.alert('检验输入',text,'error');
		return 1;
	}
}
</script>
<form id="operat_edit" style="font-size:13px">
<table cellspacing="10">
	<tr>
		<td width="90">标题：</td>
        <td><input id="banner_title" type="text" style="width:180px;height:22px" value="<?php echo ($banner["title"]); ?>" /></td>
	</tr>
	<tr>
		<td>链接：</td>
		<td><input id="banner_url" type="text" value="<?php echo ($banner["url"]); ?>" style="width:180px;height:22px" /></td>
	</tr>
	<tr>
		<td>分类：</td>
		<td><select id="banner_type" style="width:180px;height:22px">
		<option value="0">机构</option>
		<option value="1">优惠课</option>
		<option value="2">活动</option>
		</select></td>
	</tr>
	<tr>
		<td>显示顺序：</td>
		<td><select id="banner_level" style="width:180px;height:22px">
		<?php $__FOR_START_1579020800__=0;$__FOR_END_1579020800__=$level;for($i=$__FOR_START_1579020800__;$i < $__FOR_END_1579020800__;$i+=1){ ?><option value="<?php echo ($i+1); ?>"><?php echo ($i+1); ?></option><?php } ?>
		</select></td>
	</tr>
		<tr>
	<td>选择封面：</td>
	<td>
	<?php if(empty($banner["img"])): ?><div id="upload_area"><input id="file_upload" name="file_upload" type="file" multiple="true">
<a id="upload_now" class="easyui-linkbutton">上传</a> &nbsp;<a id="re_choose" class="easyui-linkbutton">重选</a></div><div id="uploadsuccess" style="display:none"><img id="a_cover" width="300px"  /><input type="hidden" id="save_path" /><br/><a id="delete_it" onclick="delete_it(this)" type="a" class="easyui-linkbutton">删除这张</a></div>
	<?php else: ?>
	<div id="upload_area" style="display:none"><input id="file_upload" name="file_upload" type="file" multiple="true">
<a id="upload_now" class="easyui-linkbutton">上传</a> &nbsp;<a id="re_choose" class="easyui-linkbutton">重选</a></div><div id="uploadsuccess"><img id="a_cover" src="<?php echo ($banner["img"]); ?>" width="300px"  /><input type="hidden" id="save_path" value="<?php echo ($banner["relative_path"]); ?>" /><br/><a id="delete_it" onclick="delete_it(this)" type="a" class="easyui-linkbutton">删除这张</a></div><?php endif; ?>
</td>	
	</tr>	
	<input type="hidden" id="a_aid" value="<?php echo ($banner["id"]); ?>" />
	<tr>
		<td colspan="3"><a id="banner_sub" class="easyui-linkbutton">发表</a></td>
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