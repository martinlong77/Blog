<?php if (!defined('THINK_PATH')) exit();?><!-- 日志列表 -->
<table id="BannerList" class="easyui-datagrid" title="<?php echo ($currentpos); ?>" data-options="border:false,fit:true,fitColumns:true,rownumbers:true,singleSelect:true,url:'<?php echo U('Content/banner_list');?>',toolbar:BannerToolbar,pagination:true">
<thead>
	<tr>
		<th data-options="field:'title',width:3,sortable:true">显示名称</th>
		<th data-options="field:'url',width:5,sortable:true">链接地址</th>
		<th data-options="field:'type',width:1,sortable:true">位置</th>
		<th data-options="field:'level',width:1,sortable:true">优先级</th>
        <th data-options="field:'id',width:1,formatter:BannerOperateText">管理操作</th>
	</tr>
</thead>
</table>
<div id="BannerToolbar" style="padding:5px;height:auto">
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
var BannerToolbar = [
	{ text: '添加', iconCls: 'icon-add', handler: BannerAdd },
	{ text: '刷新', iconCls: 'icon-reload', handler: BannerReload }
];

//生成操作内容
function BannerOperateText(val){
	var btn = [];
	btn.push('<a href="javascript:void(0);" onclick="BannerEdit('+val+')">编辑</a>');
	btn.push('<a href="javascript:void(0);" onclick="BannerDelete('+val+')">删除</a>');
	return btn.join(' | ');
}

//添加
function BannerAdd(){
	add = $('#pagetabs').tabs('exists','添加Banner'); 
	edit = $('#pagetabs').tabs('exists','修改Banner');  			
	if(add || edit){ 
	 $('#pagetabs').tabs('select', '添加Banner');
	 $('#pagetabs').tabs('close', '修改Banner');
	} 
	 openUrl("<?php echo U('Content/banner_operate?type=banner');?>", '添加Banner', 0);
}

function BannerEdit(id){	
	add = $('#pagetabs').tabs('exists','添加Banner'); 
	edit = $('#pagetabs').tabs('exists','修改Banner');  			
	if(add || edit){ 
	 $('#pagetabs').tabs('select', '添加Banner');
	 $('#pagetabs').tabs('close', '修改Banner');
	} 
	
	var url = "<?php echo U('Content/banner_operate?type=banner');?>";
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	openUrl(url, '修改Banner', 0);
}

//删除菜单
function BannerDelete(id){
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post("<?php echo U('Content/delete_bn');?>", {id: id}, function(data){
			!data.status ? $.messager.alert('提示信息', data.info, 'error') : openUrl("<?php echo U('Content/banner_list');?>", 'Banner列表', 0);
		}, 'json');
	});
}
//刷新
function BannerReload(){
	$('#BannerList').datagrid('reload');
}
</script>