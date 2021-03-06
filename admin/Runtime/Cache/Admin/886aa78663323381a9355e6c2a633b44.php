<?php if (!defined('THINK_PATH')) exit();?><!-- 日志列表 -->
<table id="LessonList" class="easyui-datagrid" title="<?php echo ($currentpos); ?>" data-options="border:false,fit:true,fitColumns:true,rownumbers:true,singleSelect:true,url:'<?php echo U('Content/preferential_list');?>',toolbar:LessonToolbar,pagination:true">
<thead>
	<tr>
		<th data-options="field:'name',width:5,sortable:true">课程名称</th>
		<th data-options="field:'c_name',width:3,sortable:true">发布机构</th>
		<th data-options="field:'createtime',width:2,sortable:true">发布时间</th>
		<th data-options="field:'t_name',width:2,sortable:true">课程分类</th>	
		<!--<th data-options="field:'p_lesson',width:1,sortable:true">优惠课</th>-->
		<th data-options="field:'status',width:2,sortable:true">课程状态</th>		
        <th data-options="field:'id',width:5,formatter:LessonOperateText">管理操作</th>
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
var LessonToolbar = [
	{ text: '刷新', iconCls: 'icon-reload', handler: LessonReload }
];

//生成操作内容
function LessonOperateText(val){
	var btn = [];
	btn.push('<a href="javascript:void(0);" onclick="LessonStatus('+val+')">启用/停用</a>');
	btn.push('<a href="javascript:void(0);" onclick="LessonShow('+val+')">首页/取消</a>');
	btn.push('<a href="javascript:void(0);" onclick="LessonRecommend('+val+')">推荐/取消</a>');
	btn.push('<a href="javascript:void(0);" onclick="LessonSetP('+val+')">优惠课/取消</a>');
	btn.push('<a href="javascript:void(0);" onclick="LessonDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

function LessonStatus(id){	//停用课程
	$.messager.confirm('提示信息', '修改课程状态,确定吗？', function(result){
		if(!result) return false;		
		$.post("<?php echo U('Content/lesson_operate?type=status');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('Content/preferential_list');?>", '辅导课列表', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}

function LessonShow(id){	//首页展示
	$.messager.confirm('提示信息', '修改首页显示,确定吗？', function(result){
		if(!result) return false;		
		$.post("<?php echo U('Content/lesson_operate?type=show');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('Content/preferential_list');?>", '辅导课列表', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}

function LessonRecommend(id){	//Recommend
	$.messager.confirm('提示信息', '修改推荐显示,确定吗？', function(result){
		if(!result) return false;		
		$.post("<?php echo U('Content/lesson_operate?type=recommend');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('Content/preferential_list');?>", '辅导课列表', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}

function LessonSetP(id){	//Recommend
	$.messager.confirm('提示信息', '修改推荐显示,确定吗？', function(result){
		if(!result) return false;		
		$.post("<?php echo U('Content/lesson_operate?type=setp');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('Content/preferential_list');?>", '辅导课列表', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}

//删除菜单
function LessonDelete(id){
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post("<?php echo U('Content/lesson_operate?type=delete');?>", {id: id}, function(data){
			data.status ? openUrl("<?php echo U('Content/preferential_list');?>", '辅导课列表', 0) : $.messager.alert('提示信息', data.info, 'error');
		}, 'json');
	});
}
//刷新
function LessonReload(){
	$('#LessonList').datagrid('reload');
}
</script>