<?php
return array(
	'SHOW_PAGE_TRACE'       => false,

	/* 模板引擎设置 */
    'TMPL_ACTION_ERROR'     => APP_PATH.C('APP_GROUP_PATH').'/'.GROUP_NAME.'/Tpl/Public/dispatch_jump.html',   // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => APP_PATH.C('APP_GROUP_PATH').'/'.GROUP_NAME.'/Tpl/Public/dispatch_jump.html',   // 默认成功跳转对应的模板文件
	'TMPL_EXCEPTION_FILE'   => APP_PATH.C('APP_GROUP_PATH').'/'.GROUP_NAME.'/Tpl/Public/think_exception.html', // 异常页面的模板文件

	/* 后台自定义设置 */
	'SAVE_LOG_OPEN'         => false,       //开启后台日志记录
	'MAX_LOGIN_TIMES'       => 9,          //最大登录失败次数，防止为0时不能登录，因此不包含第一次登录
	'LOGIN_WAIT_TIME'       => 60,         //登录次数达到后需要等待时间才能再次登录，单位：分钟
);
?>
