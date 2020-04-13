<?php

$delete = array(
	'faqs'		=>	'category'
);

$table = array(
	'id'		=>	'rank:desc id',
	//'_view'      => 'news',
	'name'		=>	'',
	'rank'		=>	'rank',
	'display'	=>	'boolean'
);

$form[] = array('input td5','name');
$form[] = array('input td3','rank');
$form[] = array('checkbox','display');