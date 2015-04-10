<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
if(gh) gh.destroy();
var gh = UE.getEditor('gh_content');
$(function(){	
	$('#gh_sub').click(function(){	//提交修改	
	  var title = $('#gh_title').val();
	  var content = gh.getContent();
	  var id = $('#gh_id').val();
	  var atype = $('#atype').val();
	  var akind = $('#akind').val();
	  var s_t = $('#s_t').datebox('getValue');
	  var e_t = $('#e_t').datebox('getValue');
	  var type1 = $('#type1').val();
	  var type2 = $('#type2').val();
	  var payed_count = $('#payed_count').val();	
	  var cover = $('#a_cover').attr('src');
	  if($('#continued').is(':checked')){
	  	 var continued = 1;
	  } else {
	  	 var continued = 0;
	  }	   
  
	  $.post("<?php echo U('Official/hd_operate');?>",{title:title,content:content,id:id,atype:atype,akind:akind,s_t:s_t,e_t:e_t,cover:cover,sel_type1:type1,sel_type2:type2,payed_count:payed_count,continued:continued},function(data){
		  if(data.status == 1){
		    $.messager.alert('操作成功',data.info,'info',function(){
			edit = $('#pagetabs').tabs('exists','修改活动');
			add = $('#pagetabs').tabs('exists','添加活动');   	
			if(edit || add) {
				$('#pagetabs').tabs('close', '修改活动');	//如果存在则关闭该页		
				$('#pagetabs').tabs('close', '添加活动');
			}	
			openUrl(data.data,'活动列表',0);   
		   });
		  } else {
		   $.messager.alert('操作失败',data.info,'error'); 
		  }
	  });	
	});	
	
	$('#akind').change(function(){
		var kind = $('#akind').val();
		kind == 1 ? $('#regtime').hide() : $('#regtime').show();
	});
	
	<?php if(!empty($official["continued"])): ?>$('#continued').attr('checked','checked');<?php endif; ?>
	$('#atype').val(<?php echo ($official["atype"]); ?>);
	$('#akind').val(<?php echo ($official["akind"]); ?>);
});

</script>
<form id="operat_edit" style="font-size:13px">
<table cellspacing="10">
	<tr>
		<td width="90">活动标题：</td>
        <td><input id="gh_title" type="text" style="width:600px;height:22px" value="<?php echo ($official["title"]); ?>" /></td>
	</tr>
	<tr>
		<td>活动详情：</td>
		<td><textarea id="gh_content" style="width:800px;height:600px;"><?php echo ($official["content"]); ?></textarea></td>
	</tr>
	<tr>
		<td>活动分类：</td>
		<td><select id="atype" style="width:180px;height:22px"><option value="1">美食汇</option><option value="2">读书社</option><option value="3">旅游</option><option value="4">电影剧场</option><option value="5">二手置换</option></select></td>
	</tr>
	<tr>
		<td>发布类别：</td>
		<td><select id="akind" style="width:180px;height:22px"><option value="1">资讯</option><option value="2">策划</option></select></td>
	</tr>
	<?php if(($official["akind"]) == "2"): ?><tr id="regtime"><?php else: ?><tr id="regtime" style="display:none"><?php endif; ?>
		<td>活动时间：</td>
		<td> <?php if(empty($official["begin"])): ?><input type="text" class="easyui-datebox"  id="s_t" /><?php else: ?><input type="text" class="easyui-datebox"  id="s_t" value="<?php echo ($official["begin"]); ?>" /><?php endif; ?>
            -
            <?php if(empty($official["end"])): ?><input type="text" class="easyui-datebox"  id="e_t" /><?php else: ?><input type="text" class="easyui-datebox"  id="e_t" value="<?php echo ($official["end"]); ?>" /><?php endif; ?></td>
	</tr>
	<tr>
		<td width="90">活动项目1（没有的留空）：</td>
        <td><input id="type1" type="text" style="width:600px;height:22px" value="<?php echo ($official["sel_type1"]); ?>" /></td>
	</tr>
	<tr>
		<td width="90">活动项目2（没有的留空）：</td>
        <td><input id="type2" type="text" style="width:600px;height:22px" value="<?php echo ($official["sel_type2"]); ?>" /></td>
	</tr>
	<tr>
		<td width="90">已缴费人数：</td>
        <td><input id="payed_count" type="text" style="height:22px" value="<?php echo ($official["payed_count"]); ?>" /></td>
	</tr>
	<tr>
		<td width="90">是否持续进行：</td>
        <td><input id="continued" type="checkbox" value="1" />活动持续进行</td>
	</tr>
		<tr>
	<td>宣传图片：</td>
	<td>
	<?php if(empty($official["cover"])): ?><div id="upload_area"><input id="file_upload" name="file_upload" type="file" multiple="true">
<a id="upload_now" class="easyui-linkbutton">上传</a> &nbsp;<a id="re_choose" class="easyui-linkbutton">重选</a></div><div id="uploadsuccess" style="display:none"><img id="a_cover" width="300px"  /><input type="hidden" id="save_path" /><br/><a id="delete_it" onclick="delete_it(this)" type="a" class="easyui-linkbutton">删除这张</a></div>
	<?php else: ?>
	<div id="upload_area" style="display:none"><input id="file_upload" name="file_upload" type="file" multiple="true">
<a id="upload_now" class="easyui-linkbutton">上传</a> &nbsp;<a id="re_choose" class="easyui-linkbutton">重选</a></div><div id="uploadsuccess"><img id="a_cover" src="<?php echo ($official["cover"]); ?>" width="300px"  /><input type="hidden" id="save_path" value="<?php echo ($official["relative_path"]); ?>" /><br/><a id="delete_it" onclick="delete_it(this)" type="a" class="easyui-linkbutton">删除这张</a></div><?php endif; ?>
</td>	
	</tr>
	<input type="hidden" id="gh_id" value="<?php echo ($official["id"]); ?>" />
	<tr>
		<td colspan="3"><a id="gh_sub" class="easyui-linkbutton">发表</a></td>
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