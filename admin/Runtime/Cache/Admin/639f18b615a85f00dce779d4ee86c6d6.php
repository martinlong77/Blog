<?php if (!defined('THINK_PATH')) exit();?><!-- 日志列表 -->
<table id="TeacherList" class="easyui-datagrid" title="<?php echo ($currentpos); ?>" data-options="border:false,fit:true,fitColumns:true,singleSelect:true,url:'<?php echo U('User/teacher_list');?>',toolbar:TeacherToolbar,pagination:true">
<thead>
	<tr>
		<th data-options="field:'un',width:3,sortable:true">账号</th>
		<th data-options="field:'name',width:1,sortable:true">真实姓名</th>
		<th data-options="field:'sex',width:1,sortable:true">性别</th>
		<th data-options="field:'reg_time',width:4,sortable:true">注册时间</th>
		<th data-options="field:'log_time',width:4,sortable:true">最后登录</th>
		<th data-options="field:'tel',width:3,sortable:true">电话</th>
		<th data-options="field:'qq',width:2,sortable:true">QQ</th>
		<th data-options="field:'email',width:4,sortable:true">邮箱</th>
		<th data-options="field:'birth',width:2,sortable:true">生日</th>
		<th data-options="field:'status',width:2,sortable:true">状态</th>
		<th data-options="field:'detail',formatter:TeacherView,width:2,sortable:true">详情</th>
        <th data-options="field:'id',width:2,formatter:TeacherOperateText">管理操作</th>
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

<!-- 查看详细信息 -->
<div id="TeacherDetailDialog" class="easyui-dialog word-wrap" title="详细信息" data-options="modal:true,closed:true,iconCls:'icon-help',buttons:[{text:'关闭',iconCls:'icon-cancel',handler:function(){$('#TeacherDetailDialog').dialog('close');}}]" style="width:400px;height:260px;padding:5px"></div>

<script type="text/javascript">
/*
//搜索日志
function ArtSearch(){
	var queryParams = $('#ArtList').datagrid('options').queryParams;
	$.each($("#ArtSearchForm").form().serializeArray(), function (index) {
		queryParams[this['name']] = this['value'];
	});
	$('#ArtList').datagrid('reload');
}*/
//参数格式化
function TeacherView(val){
	return '<a href="javascript:;" onclick="TeacherDetail(this);">'+val+'</a>';
}
//查看详细信息
function TeacherDetail(that){
	$('#TeacherDetailDialog').dialog({content: $(that).html()});
	$('#TeacherDetailDialog').dialog('open');
}

//工具栏
var TeacherToolbar = [
	{ text: '刷新', iconCls: 'icon-reload', handler: TeacherReload }
];

//生成操作内容
function TeacherOperateText(val){
	var btn = [];
	btn.push('<a href="javascript:void(0);" onclick="TeacherStatus('+val+')">封禁/解封</a>');
	btn.push('<a href="javascript:void(0);" onclick="TeacherDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

function TeacherStatus(id){
	$.messager.confirm('提示信息', '改变用户状态,确定吗？', function(result){
		if(!result) return false;		
		$.post("<?php echo U('User/user_operate?type=m&action=status');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('User/teacher_list');?>", '个人用户管理', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}

//删除菜单
function TeacherDelete(id){
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post("<?php echo U('User/user_operate?type=m&action=delete');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('User/teacher_list');?>", '个人用户管理', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}
//刷新
function TeacherReload(){
	$('#TeacherList').datagrid('reload');
}
</script>