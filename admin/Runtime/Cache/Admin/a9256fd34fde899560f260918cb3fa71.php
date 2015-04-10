<?php if (!defined('THINK_PATH')) exit();?><!-- 日志列表 -->
<table id="RecruitList" class="easyui-datagrid" title="<?php echo ($currentpos); ?>" data-options="border:false,fit:true,fitColumns:true,singleSelect:true,url:'<?php echo U('User/recruit_list');?>',toolbar:RecruitToolbar,pagination:true">
<thead>
	<tr>
		<th data-options="field:'cname',width:2,sortable:true">机构</th>
		<th data-options="field:'content',formatter:RecruitView,width:5,sortable:true">内容</th>
		<th data-options="field:'tel',width:2,sortable:true">电话</th>
		<!--<th data-options="field:'qq',width:1,sortable:true">QQ</th> -->
		<th data-options="field:'email',width:2,sortable:true">邮箱</th>
		<th data-options="field:'status',width:1,sortable:true">状态</th>
		<th data-options="field:'push_time',width:1,sortable:true">发送时间</th>
        <th data-options="field:'id',width:2,formatter:RecruitOperateText">管理操作</th>
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
<div id="RecruitDetailDialog" class="easyui-dialog word-wrap" title="详细信息" data-options="modal:true,closed:true,iconCls:'icon-help',buttons:[{text:'关闭',iconCls:'icon-cancel',handler:function(){$('#RecruitDetailDialog').dialog('close');}}]" style="width:400px;height:260px;padding:5px"></div>

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
function RecruitView(val){
	return '<a href="javascript:;" onclick="RecruitDetail(this);">'+val+'</a>';
}

function RecruitOperateText(val){
	var btn = [];
	btn.push('<a href="javascript:void(0);" onclick="RecruitStatus('+val+')">显示/取消</a>');
	btn.push('<a href="javascript:void(0);" onclick="RecruitEdit('+val+')">编辑</a>');
	btn.push('<a href="javascript:void(0);" onclick="RecruitDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

function RecruitStatus(id){
	$.messager.confirm('提示信息', '改变显示状态,确定吗？', function(result){
		if(!result) return false;		
		$.post("<?php echo U('User/recruit_operate?action=status');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('User/recruit_list');?>", '招聘信息管理', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}

//查看详细信息
function RecruitDetail(that){
	$('#RecruitDetailDialog').dialog({content: $(that).html()});
	$('#RecruitDetailDialog').dialog('open');
}

//工具栏
var RecruitToolbar = [
	{ text: '添加', iconCls: 'icon-add', handler: RecruitAdd },
	{ text: '刷新', iconCls: 'icon-reload', handler: RecruitReload }
];


//添加
function RecruitAdd(){
	add = $('#pagetabs').tabs('exists','添加招聘信息'); 
	edit = $('#pagetabs').tabs('exists','修改招聘信息');  			
	if(add || edit){ 
	 $('#pagetabs').tabs('select', '添加招聘信息');
	 $('#pagetabs').tabs('close', '修改招聘信息');
	} 
	 openUrl("<?php echo U('User/recruit_operate');?>", '添加招聘信息', 0);
}

function RecruitEdit(id){	
	add = $('#pagetabs').tabs('exists','添加招聘信息'); 
	edit = $('#pagetabs').tabs('exists','修改招聘信息');  			
	if(add || edit){ 
	 $('#pagetabs').tabs('select', '添加招聘信息');
	 $('#pagetabs').tabs('close', '修改招聘信息');
	} 
	
	var url = "<?php echo U('User/recruit_operate');?>";
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	openUrl(url, '修改招聘信息', 0);
}

//删除菜单
function RecruitDelete(id){
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post("<?php echo U('User/recruit_operate?action=del');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('User/recruit_list');?>", '招聘信息管理', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}
//刷新
function RecruitReload(){
	$('#RecruitList').datagrid('reload');
} 
</script>