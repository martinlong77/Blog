<?php if (!defined('THINK_PATH')) exit();?><!-- 日志列表 -->
<table id="EnrollList" class="easyui-datagrid" title="<?php echo ($currentpos); ?>" data-options="border:false,fit:true,fitColumns:true,rownumbers:true,singleSelect:true,url:'<?php echo U('Enroll/enroll_list');?>',toolbar:EnrollToolbar,pagination:true">
<thead>
	<tr>
		<th data-options="field:'name',width:1,sortable:true">姓名</th>
		<th data-options="field:'qq',width:1,sortable:true">QQ</th>
		<th data-options="field:'phone',width:1,sortable:true">电话</th>
		<th data-options="field:'email',width:2,sortable:true">电子邮箱</th>	
		<th data-options="field:'time',width:2,sortable:true">提交时间</th>
		<th data-options="field:'title',width:6,sortable:true">活动标题</th>
		<th data-options="field:'url',width:4,formatter:EnrollUrlText">活动链接</th>				
        <th data-options="field:'id',width:1,formatter:EnrollOperateText">管理操作</th>
	</tr>
</thead>
</table>
<div id="ArtToolbar" style="padding:5px;height:auto">
	<form id="ArtSearchForm">
		用户名: 
		<select name="search[username]" class="easyui-combobox" panelHeight="auto" style="width:100px">
			<option value="">全部用户</option>
			<?php if(is_array($adminList)): foreach($adminList as $key=>$admin): ?><option value="<?php echo ($admin["username"]); ?>"><?php echo ($admin["username"]); ?></option><?php endforeach; endif; ?>
		</select><a href="../Menu/list.html">list</a>
		模块: 
		<select name="search[module]" class="easyui-combobox" panelHeight="auto" style="width:100px">
			<option value="">所有模块</option>
			<?php if(is_array($moduleList)): foreach($moduleList as $key=>$module): ?><option value="<?php echo ($module["m"]); ?>"><?php echo ($module["m"]); ?></option><?php endforeach; endif; ?>
		</select>
		时 间: <input name="search[begin]" class="easyui-datebox" style="width:100px">
		至: <input name="search[end]" class="easyui-datebox" style="width:100px">
		
		<a href="javascript:;" onclick="ArtSearch();" class="easyui-linkbutton" iconCls="icon-search">搜索</a>
		<a href="javascript:;" onclick="ArtDelete();" class="easyui-linkbutton" iconCls="icon-no">删除一月前数据</a>
	</form>
</div>

<script type="text/javascript">
//工具栏
var EnrollToolbar = [
	{ text: '刷新', iconCls: 'icon-reload', handler: EnrollReload }
];

//生成操作内容
function EnrollOperateText(val){
	var btn = [];
	btn.push('<a href="javascript:void(0);" onclick="EnrollDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

//生成操作内容
function EnrollUrlText(val){
	var btn = [];
	btn.push('<a href="'+val+'" target="_blank">'+val+'</a>');
	return btn.join(' | ');
}

//删除菜单
function EnrollDelete(id){
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post("<?php echo U('Enroll/del');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('Enroll/enroll_list');?>", '活动报名管理', 0) : $.messager.alert('提示信息', data.info, 'error');		

		}, 'json');
	});
}
//刷新
function EnrollReload(){
	$('#EnrollList').datagrid('reload');
}
</script>