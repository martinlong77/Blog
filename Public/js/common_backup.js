//点击输入框提示消失
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else field.value = field.defaultValue;
}

//检测输入长度
function checkLen(term){
    document.all.termLen.value=240-term.value.length;
    if (document.all.termLen.value<=0){
        term.value=term.value.substring(0,240);
    alert("您输入的字数超过了限定，可能会导致显示不完全。"); 
    }
}

//检测输入不为空
function Check(){
 var fm = document.FormMsg; 
 fm.message.value = fTrim(fm.message.value); //Trim the input value. 
 if( fm.message.value == "") {
  alert("输入不能为空！");
  fm.message.focus();
  return false;
 }  
 return true;
}

//去掉空格
function fTrim(str)
{
 return str.replace(/(^\s*)|(\s*$)/g, ' '); 
}

//打开选择页
function pick(openwindow) { 
var str = window.showModalDialog(openwindow, window, "dialogWidth=440px;dialogHeight=480px;center=yes;"); 
if (!str) 
return; 
} 

//生成图片预览
function setImagePreview(fileObj, previewObj, localImg) {
    var docObj=document.getElementById(fileObj);
    var imgObjPreview=document.getElementById(previewObj);
    if(docObj.files && docObj.files[0]){
        //火狐下，直接设img属性
        imgObjPreview.style.display = 'block';
        imgObjPreview.style.width = '300px';
        //imgObjPreview.src = docObj.files[0].getAsDataURL();


        //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式  
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
    }else{
        //IE下，使用滤镜
        docObj.select();
        var imgSrc = document.selection.createRange().text;
        var localImagId = document.getElementById(localImg);
        //必须设置初始大小
        localImagId.style.width = "300px";
        //图片异常的捕捉，防止用户修改后缀来伪造图片
        try{
            localImagId.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
            localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
        }catch(e){
            alert("您上传的图片格式不正确，请重新选择!");
            return false;
        }
        imgObjPreview.style.display = 'none';
        document.selection.empty();
    }
    return true;
}