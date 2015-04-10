<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
if(gh) gh.destroy();
var gh = UE.getEditor('gh_content');
$(function(){	
	$('#gh_sub').click(function(){	//提交修改	
	  var title = $('#gh_title').val();
	  var content = gh.getContent();
	  var id = $('#gh_id').val();
  
	  $.post("<?php echo U('Official/gg_operate');?>",{title:title,content:content,id:id},function(data){
		  if(data.status == 1){
		    $.messager.alert('操作成功',data.info,'info',function(){
			edit = $('#pagetabs').tabs('exists','修改公告');
			add = $('#pagetabs').tabs('exists','添加公告');   	
			if(edit || add) {
				$('#pagetabs').tabs('close', '修改公告');	//如果存在则关闭该页		
				$('#pagetabs').tabs('close', '添加公告');
			}	
			openUrl(data.data,'公告列表',0);   
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
		<td width="90">公告标题：</td>
        <td><input id="gh_title" type="text" style="width:600px;height:22px" value="<?php echo ($official["title"]); ?>" /></td>
	</tr>
	<tr>
		<td>公告详情：</td>
		<td><textarea id="gh_content" style="width:800px;height:600px;"><?php echo ($official["content"]); ?></textarea></td>
	</tr>
	<input type="hidden" id="gh_id" value="<?php echo ($official["id"]); ?>" />
	<tr>
		<td colspan="3"><a id="gh_sub" class="easyui-linkbutton">发表</a></td>
	</tr>
</table>
</form>
</div>