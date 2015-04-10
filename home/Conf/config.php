<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE' => 'Index',
	'URL_MODEL' => 2,
	'URL_PATHINFO_DEPR'=>'-',	//设置pathinfo里URL的分隔符
	'URL_CASE_INSENSITIVE' => true,	//不区分大小写
	'URL_HTML_SUFFIX'=>'html',  //伪静态
	//'SHOW_PAGE_TRACE' => true,	//显示trace
	//'SHOW_RUN_TIME'=>true,
	//'SHOW_ADV_TIME'=>true,
	//'SHOW_DB_TIMES'=>true,
	//'SHOW_USE_MEM'=>true,
	//'SHOW_LOAD_FILE'=>true,
	'TMPL_VAR_IDENTIFY'=>'array',
	'DB_TYPE'=>'pdo',
	'DB_PREFIX'=>'jelly_',
	'DB_USER'=>'jellongc_king',
	'DB_PWD'=>'am@ri31n',
	'DB_DSN'=>'mysql:host=localhost;dbname=jellongc_blog',
);
?>