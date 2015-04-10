<?php
return array(
	//'配置项'=>'配置值'
	'URL_HTML_SUFFIX'       => '.html',
	
 	/* 项目设定 */
	'APP_GROUP_LIST'        => 'Home,Admin',      // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
    'APP_GROUP_MODE'        =>  1,                // 分组模式 0 普通分组 1 独立分组
    'APP_GROUP_PATH'        =>  'Modules',        // 分组目录 独立分组模式下面有效
	
	/* Cookie设置 */
    'COOKIE_EXPIRE'         => 0,                 // Coodie有效期
    'COOKIE_DOMAIN'         => '',                // Cookie有效域名
    'COOKIE_PATH'           => '/',               // Cookie路径
    'COOKIE_PREFIX'         => '',                // Cookie前缀 避免冲突
	
	/* SESSION设置 */
    'SESSION_AUTO_START'    => true,              // 是否自动开启Session
    'SESSION_OPTIONS'       => array(),           // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_TYPE'          => '',                // session hander类型 默认无需设置 除非扩展了session hander驱动
    'SESSION_PREFIX'        => '',                // session 前缀
	
	/* 数据库设置 */
    'DB_TYPE'               => 'mysql',           // 数据库类型
    'DB_HOST'               => 'localhost',       // 服务器地址
    'DB_NAME'               => 'tutor',             // 数据库名
    'DB_USER'               => 'root',            // 用户名
    'DB_PWD'                => '1234',                // 密码
    'DB_PORT'               => '',                // 端口
    'DB_PREFIX'             => 'tutor_',            // 数据库表前缀
	
	/* URL设置 */
    'URL_CASE_INSENSITIVE'  => true,              // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'             => 1,                 // URL访问模式,可选参数0、1、2、3
    'URL_404_REDIRECT'      =>  '',               // 404 跳转页面 部署模式有效
	
	'TMPL_L_DELIM'          => '<!--{',			// 模板引擎普通标签开始标记
	'TMPL_R_DELIM'          => '}-->',			// 模板引擎普通标签结束标记
	/*
	
	'TMPL_PARSE_STRING'     => array(
		'__PUBLIC__' => '../Public',
	)*/
);
?>