<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
$(function(){	
	$('#kw_sub').click(function(){	//提交修改	
	  name = $('#kw_name').val();
	  pinyin = $('#kw_pinyin').val();
	  id = $('#kw_id').val();
	  pid = $('#kw_pid').val();
  
	  $.post("<?php echo U('Setting/keyword_operate');?>",{name:name,pinyin:pinyin,id:id,parent_id:pid},function(data){
		  if(data.status == 1){
		    $.messager.alert('操作成功',data.info,'info',function(){
			edit = $('#pagetabs').tabs('exists','修改条件');
			add = $('#pagetabs').tabs('exists','设置新条件');   	
			if(edit) $('#pagetabs').tabs('close', '修改条件');	//如果存在则关闭该页		
			if(add) $('#pagetabs').tabs('close', '设置新条件');
			openUrl(data.data,'搜索条件设置',0);   
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
		<td width="90">关键字：</td>
        <td><input id="kw_name" type="text" style="width:180px;height:22px" value="<?php echo ($keyword["name"]); ?>" /></td>
	</tr>
	<tr>
		<td>拼音缩写：</td>
		<td><input id="kw_pinyin" type="text" value="<?php echo ($keyword["pinyin"]); ?>" style="width:180px;height:22px" /></td>
	</tr>
	<tr>
		<td>所属分类：</td>
		<td><select id="kw_pid" style="width:180px;height:22px"><?php if(is_array($parent_list)): $i = 0; $__LIST__ = $parent_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i; if(($p["id"]) == $keyword["parent_id"]): ?><option value="<?php echo ($p["id"]); ?>" selected><?php echo ($p["name"]); ?></option><?php else: ?><option value="<?php echo ($p["id"]); ?>"><?php echo ($p["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?></select></td>
	</tr>
	
	<input type="hidden" id="kw_id" value="<?php echo ($keyword["id"]); ?>" />
	<tr>
		<td colspan="3"><a id="kw_sub" class="easyui-linkbutton">发表</a></td>
	</tr>
</table>
</form>
</div>