<?php if (!defined('THINK_PATH')) exit();?><!-- 日志列表 -->
<table id="LogList" class="easyui-datagrid" title="<?php echo ($currentpos); ?>" data-options="border:false,fit:true,fitColumns:true,rownumbers:true,singleSelect:true,pagination:true,url:'<?php echo U('Log/index');?>',toolbar:'#LogToolbar'">
<thead>
	<tr>
		<th data-options="field:'username',width:20,sortable:true">用户名</th>
		<th data-options="field:'module',width:15,sortable:true">模块</th>
		<th data-options="field:'action',width:15,sortable:true">方法</th>
		<th data-options="field:'querystring',formatter:LogView,width:100">参数</th>
		<th data-options="field:'time',width:30,sortable:true">时间</th>
		<th data-options="field:'ip',width:25,sortable:true">IP</th>
	</tr>
</thead>
</table>
<div id="LogToolbar" style="padding:5px;height:auto">
	<form id="LogSearchForm">
		用户名: 
		<select name="search[username]" class="easyui-combobox" panelHeight="auto" style="width:100px">
			<option value="">全部用户</option>
			<?php if(is_array($adminList)): foreach($adminList as $key=>$admin): ?><option value="<?php echo ($admin["username"]); ?>"><?php echo ($admin["username"]); ?></option><?php endforeach; endif; ?>
		</select>
		模块: 
		<select name="search[module]" class="easyui-combobox" panelHeight="auto" style="width:100px">
			<option value="">所有模块</option>
			<?php if(is_array($moduleList)): foreach($moduleList as $key=>$module): ?><option value="<?php echo ($module["m"]); ?>"><?php echo ($module["m"]); ?></option><?php endforeach; endif; ?>
		</select>
		时 间: <input name="search[begin]" class="easyui-datebox" style="width:100px">
		至: <input name="search[end]" class="easyui-datebox" style="width:100px">
		
		<a href="javascript:;" onclick="LogSearch();" class="easyui-linkbutton" iconCls="icon-search">搜索</a>
		<a href="javascript:;" onclick="LogDelete();" class="easyui-linkbutton" iconCls="icon-no">删除一月前数据</a>
	</form>
</div>

<!-- 查看详细信息 -->
<div id="LogDetailDialog" class="easyui-dialog word-wrap" title="详细参数" data-options="modal:true,closed:true,iconCls:'icon-help',buttons:[{text:'关闭',iconCls:'icon-cancel',handler:function(){$('#LogDetailDialog').dialog('close');}}]" style="width:400px;height:260px;padding:5px"></div>

<script type="text/javascript">
//参数格式化
function LogView(val){
	return '<a href="javascript:;" onclick="LogDetail(this);">'+val+'</a>';
}
//查看详细信息
function LogDetail(that){
	$('#LogDetailDialog').dialog({content: $(that).html()});
	$('#LogDetailDialog').dialog('open');
}
//搜索日志
function LogSearch(){
	var queryParams = $('#LogList').datagrid('options').queryParams;
	$.each($("#LogSearchForm").form().serializeArray(), function (index) {
		queryParams[this['name']] = this['value'];
	});
	$('#LogList').datagrid('reload');
}
//删除日志
function LogDelete(){
	$.post('<?php echo U('Log/delete');?>', {week: 4}, function(data){
		if(!data.status){
			$.messager.alert('提示信息', data.info, 'error');
		}else{
			$('#LogList').datagrid('reload');
			$.messager.alert('提示信息', data.info, 'info');
		}
	}, 'json');
}
</script>