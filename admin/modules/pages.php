<?php

//дерево сайта, отвечает за урл первого уровня
/*
 * v1.2.21 - $languages перенес в /admin/config_multilingual.php
 * v1.4.16 - $delete удалил confirm
 * v1.4.17 - сокращение параметров form
 */

$modules_site = array(
	'pages'			=> 'Текстовая страница',
	'index'			=> 'Главная',
	//'articles'		=> 'Статьи',
	'news'			=> 'Новости',
	'gallery'		=> 'Галерея',
	'shop'			=> 'Каталог',
	'basket'		=> 'Корзина',
	'feedback'		=> 'Обратная связь',
	'sitemap'		=> 'Карта сайта',
	'profile'		=> 'Личный кабинет',
	'login'			=> 'Авторизация',
	'registration'	=> 'Регистрация',
	'remind'		=> 'Восстановление пароля',
	'subscribe'		=> 'Подписка'
);

$a18n['menu2'] = 'меню 2';

if ($get['u']=='form') {
	if (empty($post['module'])) $post['module'] = 'pages';
	foreach ($modules_site as $k=>$v)
		if (!file_exists(ROOT_DIR.'modules/'.$k.'.php'))
			unset($modules_site[$k]);
}

$table = array(
	'_tree'		=> true,
	'_edit'		=> true,
	'id'		=> '',
	'_view'      => 'page',
	'name'		=> '',
	'h1'		=> '',
	'title'		=> '',
	'url'		=> '',
	'module'	=> $modules_site,
	'menu'		=> 'boolean',
	'menu2'		=> 'boolean',
	'noindex'  => 'boolean',
	'display'	=> 'display'
);

$tabs = array(
	1=>a18n('common'),
	2=>a18n('media'),
);

//только если многоязычный сайт
if ($config['multilingual']) {
	//приоритет пост над гет
	if (isset($post['language'])) $get['language'] = $post['language'];
	if (@$get['language'] == 0) $get['language'] = key($languages);
	$query = "
		SELECT pages.*
		FROM pages
		WHERE pages.language = '".$get['language']."'
	";
	$filter[] = array('language', $languages);
	$form[1][] = '<input name="language" type="hidden" value="'.$get['language'].'" />';
}

//v1.4.16 - $delete удалил confirm
$delete = array('pages'=>'parent');

$form[1][] = array('input td7','name');
$form[1][] = array('select td3','module',array(
	'value'=>array(true,$modules_site),
	'help'=>'Модуль отвечает за тип информации на странице. Например, на странице модуля &quot;Новости&quot; будет отображатся список новостей.'
));
$form[1][] = array('checkbox','display');
$form[1][] = array('input td7','h1');
$form[1][] = array('checkbox','menu');
$form[1][] = array('checkbox','menu2',array('help'=>'второе меню, обычно отображается в подвале сайта'));
$form[1][] = 'clear';
$form[1][] = array('parent td3 td4','parent');
$form[1][] = array('tinymce td12','text');//,array('attr'=>'style="height:500px"'));
$form[1][] = array('seo','seo url title description noindex');

$form[2][] = array('file_multi','imgs',array(
	'name'=>'Картинки',
	'sizes'=>array(''=>'resize 1000x1000')
));
$form[2][] = array('textarea td12','video',array('name'=>a18n('video')));
