<?php if (!defined('THINK_PATH')) exit();?><!-- 日志列表 -->
<table id="ArticleList" class="easyui-datagrid" title="<?php echo ($currentpos); ?>" data-options="border:false,fit:true,fitColumns:true,rownumbers:true,singleSelect:true,url:'<?php echo U('Content/article_list');?>',toolbar:ArticleToolbar,pagination:true">
<thead>
	<tr>
		<th data-options="field:'title',width:26,sortable:true">文章标题</th>
		<th data-options="field:'createtime',width:6,sortable:true">发表时间</th>		
		<th data-options="field:'type',width:3,sortable:true">所属分类</th>
		<th data-options="field:'keywords',width:15,sortable:true">关键字</th>
        <th data-options="field:'click',width:2,sortable:true">点击数</th>
        <th data-options="field:'id',width:5,formatter:ArticleOperateText">管理操作</th>
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
var ArticleToolbar = [
	{ text: '添加新文章', iconCls: 'icon-add', handler: ArticleAdd },
	{ text: '刷新', iconCls: 'icon-reload', handler: ArticleReload }
];
//生成操作内容
function ArticleOperateText(val){
	var btn = [];
	btn.push('<a href="javascript:void(0);" onclick="ArticleEdit('+val+')">编辑</a>');
	btn.push('<a href="javascript:void(0);" onclick="ArticleDelete('+val+')">删除</a>');
	return btn.join(' | ');
}
//添加
function ArticleAdd(val){
	//window.open('<?php echo U('Content/article_operate');?>');
	add = $('#pagetabs').tabs('exists','发表新文章'); 
	edit = $('#pagetabs').tabs('exists','修改文章');  			
	if(add || edit){ 
	 $('#pagetabs').tabs('select', '发表新文章');
	 $('#pagetabs').tabs('close', '修改文章');
	} 
	 openUrl("<?php echo U('Content/article_operate?type=article');?>", '发表新文章', 0);
/*
	
	$('#AdminAddBox').dialog({href:'<?php echo U('Content/article_operate');?>'});
	$('#AdminAddBox').dialog('open');*/
}
//编辑
function ArticleEdit(id){	
	edit = $('#pagetabs').tabs('exists','修改文章');
	add = $('#pagetabs').tabs('exists','发表新文章');
	if(edit || add) {
		$('#pagetabs').tabs('close', '发表新文章');
		$('#pagetabs').tabs('close', '修改文章');	
	}
	
	var url = "<?php echo U('Content/article_operate?type=article');?>";
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	openUrl(url, '修改文章', 0);
}
//删除菜单
function ArticleDelete(id){
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('Content/article_delete');?>', {id: id}, function(data){
			!data.status ? $.messager.alert('提示信息', data.info, 'error') : openUrl('<?php echo U('Content/article_list');?>', '文章列表', 0);
		}, 'json');
	});
}
//刷新
function ArticleReload(){
	$('#ArticleList').datagrid('reload');
}
</script>