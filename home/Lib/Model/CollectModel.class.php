<?php
class CollectModel extends RelationModel {
	Protected $_link = array(
		'Posts' => array(
			'mapping_type' => HAS_ONE,
			'class_name' => 'Posts',
			'foreign_key' => 'post_id',
			//'mapping_fields' => 'post_id,post_title,post_createtime,post_tag,post_content,readcount,comcount',
			'as_fields' => 'post_id,post_title,post_createtime,post_tag,readcount,comcount',
			),
	);		
}