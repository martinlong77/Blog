//点击输入框提示消失
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else field.value = field.defaultValue;
}