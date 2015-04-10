<?php if (!defined('THINK_PATH')) exit();?><!-- 日志列表 -->
<table id="QnaList" class="easyui-datagrid" title="<?php echo ($currentpos); ?>" data-options="border:false,fit:true,fitColumns:true,rownumbers:true,singleSelect:true,url:'<?php echo U('Other/teacher_list');?>',toolbar:TeacherToolbar,pagination:true">
<thead>
	<tr>
		<th data-options="field:'name',width:1,sortable:true">教师名称</th>
		<th data-options="field:'cname',width:2,sortable:true">所属机构</th>
		<th data-options="field:'experience',width:2,sortable:true">经验</th>
		<th data-options="field:'subject',width:2,sortable:true">擅长科目</th>				
        <th data-options="field:'tid',width:1,formatter:TeacherOperateText">管理操作</th>
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
//参数格式化
/*
function ArtView(val){
	var id = '<?php echo U('Content/article_add');?>';
	var tit = '我是标题你是谁';
	return '<a href="javascript:;" onclick="openUrl(id,tit,0);">'+id+'</a>';
}

//搜索日志
function ArtSearch(){
	var queryParams = $('#ArtList').datagrid('options').queryParams;
	$.each($("#ArtSearchForm").form().serializeArray(), function (index) {
		queryParams[this['name']] = this['value'];
	});
	$('#ArtList').datagrid('reload');
}*/
//工具栏
var TeacherToolbar = [
	{ text: '刷新', iconCls: 'icon-reload', handler: TeacherReload }
];

//生成操作内容
function TeacherOperateText(val){
	var btn = [];
	btn.push('<a href="javascript:void(0);" onclick="TeacherDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

//删除菜单
function TeacherDelete(id){
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post("<?php echo U('Other/delete?type=teacher');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('Other/teacher_list');?>", '师资管理', 0) : $.messager.alert('提示信息', data.info, 'error');		

		}, 'json');
	});
}
//刷新
function TeacherReload(){
	$('#TeacherList').datagrid('reload');
}
</script>