<?php

$categories = mysql_select("SELECT id,name FROM faq_categories ORDER BY rank DESC",'array');

$filter[] = array('category',$categories,'-категории-');

$table = array(
	'id'		=>	'rank:desc id',
	//'_view'      => 'news',
	'category'=> $categories,
	'question'		=>	'',
	'rank'		=>	'',
	'display'	=>	'boolean'
);

$where = @$_GET['category'] ? " AND category = '".intval(@$_GET['category'])."' " : "";


$form[] = array('select td3','category',array(
	'value'=>array(true,$categories)
));
$form[] = array('input td5','question');
$form[] = array('input td2','rank');
$form[] = array('checkbox','display');
$form[] = array('textarea td12','answer');