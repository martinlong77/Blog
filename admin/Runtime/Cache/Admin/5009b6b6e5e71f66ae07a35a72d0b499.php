<?php if (!defined('THINK_PATH')) exit();?><!-- 菜单列表 -->
<table id="menuList" class="easyui-treegrid" title="<?php echo ($currentpos); ?>" data-options="fit:true,rownumbers:true,animate:true,fitColumns:true,url:'<?php echo U('Menu/index');?>',idField:'id',treeField:'name',toolbar:Menutoolbar,border:false">
<thead>
	<tr>
		<th data-options="field:'id_sort',formatter:MenuOrderBox,width:20,align:'center'">排序</th>
		<th data-options="field:'id',width:20,align:'center'">菜单ID</th>
		<th data-options="field:'name',width:200">菜单名称</th>
		<th data-options="field:'operateid',formatter:MenuOperateText,width:80,align:'center'">管理操作</th>
	</tr>
</thead>
</table>

<!-- 添加菜单 -->
<div id="addMenuBox" class="easyui-dialog" title="添加菜单" data-options="modal:true,closed:true,iconCls:'icon-add',buttons:[{text:'确定',iconCls:'icon-ok',handler:function(){$('#addForm').submit();}},{text:'取消',iconCls:'icon-cancel',handler:function(){$('#addMenuBox').dialog('close');}}]" style="width:500px;height:270px;"></div>

<!-- 编辑菜单 -->
<div id="editMenuBox" class="easyui-dialog" title="编辑菜单" data-options="modal:true,closed:true,iconCls:'icon-edit',buttons:[{text:'确定',iconCls:'icon-ok',handler:function(){$('#editForm').submit();}},{text:'取消',iconCls:'icon-cancel',handler:function(){$('#editMenuBox').dialog('close');}}]" style="width:500px;height:270px;"></div>


<script type="text/javascript">
//工具栏
var Menutoolbar = [
	{ text: '添加菜单', iconCls: 'icon-add', handler: addMenu },
	{ text: '刷新', iconCls: 'icon-reload', handler: MenuRefreshList },
	{ text: '排序', iconCls: 'icon-edit', handler: MenuListOrder }
];
//排序内容格式化
function MenuOrderBox(val){
	var arr = val.split("_");
	return '<input class="MenuListOrder" type="text" name="order['+arr[0]+']" value="'+ arr[1] +'" size="2" style="text-align:center">';
}
//操作内容格式化
function MenuOperateText(id){
	return '<a href="javascript:void(0);" onclick="addMenu('+id+')">添加子菜单</a> | <a href="javascript:void(0);" onclick="editMenu('+id+')">修改</a> | <a href="javascript:void(0);" onclick="deleteMenu('+id+')">删除</a>';
}
//刷新菜单列表
function MenuRefreshList(){
	$('#menuList').treegrid('reload');
}
//添加菜单
function addMenu(parentid){
	if(typeof(parentid) !== 'number') parentid = 0;
	var url = '<?php echo U('Menu/add');?>';
	url += url.indexOf('?') != -1 ? '&parentid='+parentid : '?parentid='+parentid;
	$('#addMenuBox').dialog({href:url});
	$('#addMenuBox').dialog('open');
}
//保存排序
function MenuListOrder(){
	$.post('<?php echo U('Menu/listorder');?>', $(".MenuListOrder").serialize(), function(data){
		if(!data.status){
			$.messager.alert('提示信息', data.info, 'error');
		}else{
			$.messager.alert('提示信息', data.info, 'info');
			MenuRefreshList();
		}
	}, 'json');
}
//编辑菜单
function editMenu(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择菜单', 'error');
		return false;
	}
	var url = '<?php echo U('Menu/edit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#editMenuBox').dialog({href:url});
	$('#editMenuBox').dialog('open');
}
//删除菜单
function deleteMenu(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择菜单', 'error');
		return false;
	}
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('Menu/delete');?>', {id: id}, function(data){
			if(!data.status){
				$.messager.alert('提示信息', data.info, 'error');
			}else{
				MenuRefreshList();
				$.messager.alert('提示信息', data.info, 'info');
			}
		}, 'json');
	});
}
</script>