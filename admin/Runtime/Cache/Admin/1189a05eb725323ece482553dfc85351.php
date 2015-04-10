<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
$(function(){
	$('#AdminInfoSubbtn').click(function(){$('#AdminEditInfoForm').submit()});
	$.formValidator.initConfig({formID:"AdminEditInfoForm",theme:'App',onSuccess:AdminInfoSave,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
	$("#realname").formValidator({onShow:"请输入真实姓名",onFocus:"真实姓名应该为2-20位之间"}).inputValidator({min:2,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"姓名两边不能有空格"},onError:"真实姓名应该为2-20位之间"});
	$("#email").formValidator({onShow:"请输入E-mail",onFocus:"请输入E-mail"}).regexValidator({regExp:"email",dataType:"enum",onError:"E-mail格式错误"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Admin/public_checkEmail');?>",
		data : {email: function(){return $("#email").val()}, default: '<?php echo ($info["email"]); ?>'},
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? false : true;
		},
		onError : "该邮箱已经存在",
		onWait : "请稍候..."
	});
})
function AdminInfoSave(){
	$.post('<?php echo U('Admin/public_editInfo');?>', $("#AdminEditInfoForm").serialize(), function(data){
		if(!data.status){
			$.messager.alert('提示信息', data.info, 'error');
		}else{
			$.messager.alert('提示信息', data.info, 'info');
		}
	})
}
</script>
<form id="AdminEditInfoForm" style="font-size:13px">
<table cellspacing="10">
	<tr>
		<td width="90">用户名：</td>
		<td><?php echo ($info["username"]); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>最后登录时间</td> 
		<td><?php if($info["lastlogintime"] > 0): echo (date('Y-m-d H:i:s',$info["lastlogintime"])); else: ?>-<?php endif; ?></td>
		<td></td>
	</tr>
	<tr>
		<td>最后登录IP</td> 
		<td><?php echo (($info["lastloginip"])?($info["lastloginip"]):'-'); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>真实姓名</td>
		<td><input type="text" name="info[realname]" id="realname" value="<?php echo ($info["realname"]); ?>" style="width:180px;height:22px" /></td>
		<td><div id="realnameTip"></div></td>
	</tr>
	<tr>
		<td>E-mail：</td>
		<td><input type="text" name="info[email]" id="email" value="<?php echo ($info["email"]); ?>" style="width:180px;height:22px" /></td>
		<td><div id="emailTip"></div></td>
	</tr>
	<tr>
		<td colspan="3"><a id="AdminInfoSubbtn" class="easyui-linkbutton">提交</a></td>
	</tr>
</table>
</form>
</div>