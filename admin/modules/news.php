<?php

/*
 * v1.3.37 - created_at - mysql_fn
 * v1.4.17 - сокращение параметров form
 */

//todo hypertext
/*
if ($get['u']=='edit') {
	$post['hypertext'] = serialize($post['hypertext']);
}
*/

$table = array(
	'id'		=>	'date:desc name url user title id',
	//'_view'      => 'news',
	'name'		=>	'',
	//'h1'        =>  '',
	'title'		=>	'',
	'url'		=>	'',
	'date'		=>	'date',
	'display'	=>	'boolean'
);

$query = "
	SELECT news.*,
		u.email login
	FROM news
	LEFT JOIN users u ON u.id = news.user
	WHERE 1
";

$tabs = array(
	1=>a18n('common'),
	2=>a18n('media'),
);


$form[1][] = array('input td7','name');
$form[1][] = array('input td3 datetimepicker','date');
$form[1][] = array('checkbox','display');
//$form[1][] = array('input td7','h1');
$form[1][] = array('tinymce td12','text',array('name'=>a18n('help_text_img&video')));
//$form[1][] = array('hypertext td12','hypertext');
$form[1][] = array('seo','seo url title description');

$form[2][] = array('file_multi','imgs',array(
	'name'=>a18n('help_imgs'),
	'sizes'=>array(''=>'resize 1000x1000')
));
$form[2][] = array('textarea td12','video',array('name'=>a18n('help_video')));