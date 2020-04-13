<?php

/*
 * v1.4.17 - сокращение параметров form
 */

if ($get['u']=='edit'){
	unset($post['address_search']);
}

$table = array(
	'id'		=>	'rank:desc name id',
	'name'		=>	'',
	'lat'		=>	'right',
	'lng'		=>	'right',
	'rank'		=>	'right',
	'display'	=>	'boolean'
);

$where = '';

$form[] = array('input td7','name');
$form[] = array('input td2','rank');
$form[] = array('checkbox','display');
$form[] = array('input td9','address');

/*
$form[] = array('yandex_map','address_search',@$post);
html_sources('footer','yandex_map');
/* */

/* */
$form[] = array('google_map','address_search');
html_sources('footer','google_map');
/* */