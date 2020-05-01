<?php

$a18n['name'] = 'имя';
$a18n['parent'] = 'реферер';
$a18n['structure'] = 'структура';
$a18n['created_at'] = 'создан';

$delete = array('pages'=>'parent');

//пользователи
/*
 * v1.2.66 - добавлена
 * v1.4.14 - event_func
 * v1.4.16 - $delete удалил confirm
 * v1.4.24 - исправление ошибки
 */

//статусы пользователя
$user_types = mysql_select("SELECT id,ut_name name FROM user_types ORDER BY id",'array');

//исключение при редактировании модуля
if ($get['u']=='edit') {
	//изменение пароля
	if ($post['change']==1 OR @$_GET['id']=='new') {
		$post['salt'] = md5(time());
		//обрезаем пробелы у пароля
		$post['password'] = trim($post['password']);
		$post['hash'] = user_hash_db($post['salt'],$post['password']);
	}
	unset($post['password'],$post['change']);
	//изменения логина - если пустые то null, если нет то обрезаем пробелы
	$config['mysql_null'] = true; //v1.2.89
	if ($post['email']=='') $post['email'] = null;
	else $post['email'] = trim($post['email']);
	if ($post['phone']=='') $post['phone'] = null;
	else $post['phone'] = trim($post['phone']);
	//дополнительные параметры
	$post['fields'] = isset($post['fields']) ? serialize($post['fields']) : '';

	/*
	if ($post['parent']) {
		//проверяем существование родителя
		$parent = 0;
		if ($get['id']) {
			$parent = mysql_select("SELECT parent FROM users WHERE id=" . intval($get['id']), 'str');
		}
		//если родитель был определен ранее то нельзя перемещать дерево
		if ($parent==0) {
			$parent = mysql_select("SELECT id FROM users WHERE id=" . intval($post['parent']), 'str');
			if ($parent) {
				//перестраиваем дерево
				$post['tree'] = $post['parent'];
			}
			else {
				unset($post['parent']);
			}
		}
	}
	*/
}
//исключение для быстрого редактирования
if ($get['u']=='post') {
	//если пустые то null, если нет то обрезаем пробелы
	if (in_array($get['name'],array('phone','email'))) {
		$config['mysql_null'] = true; //v1.2.89
		if ($get['value']=='') $get['value'] = null;
		else $get['value'] = trim($get['value']);
	}
}

$a18n['date']	= 'регистрация';
$a18n['type']	= 'статус';
$a18n['remember_me']	= 'запомнить меня';

$table = array(
	'id'		=>	'id:desc last_visit',
	'structure'      => '<a href="?m=users&search=tree:{id}"><i data-feather="git-commit"></i></a>
						<a href="?m=users&search=tree:{parent}"><i data-feather="git-merge"></i></a>
						<a href="?m=users&search=tree:{tree}"><i data-feather="share-2"></i></a>',
	'level'     => '{level}',
	'avatar'    => 'img',
	'email'		=>	'::table_login',
	'phone'		=>  '::table_login',
	'name'		=>  '',
	'type'		=>	$user_types,
	//'last_visit'	=> 'date',
	'created_at'	=>	'smart',
);

function table_login($q,$k) {
	global $modules;
	$content = '';
	if ($q[$k]) {
		$login = $q[$k];
		$hash = user_hash($q);
		$content = '<a target="_blank" href="'.get_url('profile').'?email='.urlencode($login).'&hash='.$hash.'">'.$login.'</a>';
	}
	return '<td>'.$content.'</td>';
}


$where = (isset($get['type']) && $get['type']>0) ? "AND users.type = '".$get['type']."' " : "";
if (isset($get['search']) && $get['search']!='') {
	$smart = explode(':',$get['search']);
	if ($smart[0]=='tree') {
		$tree = mysql_select("SELECT * FROM users WHERE id=".intval($smart[1]),'row');
		if ($tree) {
			$where .= "
				AND tree=" . $tree['tree'] . "
				AND left_key>=" . $tree['left_key'] . "
				AND right_key<=" . $tree['right_key'] . "
			";
			//ставим сортировку по умолчанию
			$table['id'] = 'left_key:asc id:desc last_visit';
		}
	}
	else {
		$where.= "
			AND (
				LOWER(users.email) like '%".mysql_res(mb_strtolower($get['search'],'UTF-8'))."%'
				OR LOWER(users.fields) like '%".mysql_res(mb_strtolower($get['search'],'UTF-8'))."%'
			)
		";
	}
}
//v1.2.28 другие пользователи не видят суперадмина
if ($user['id']!=1) $where.= ' AND users.id!=1';

$query = "
	SELECT users.*
	FROM users
	WHERE 1 ".$where;

$filter[] = array('type',$user_types,'-статус-');
$filter[] = array('search');

//v1.2.28 запрет на удаления себя
//v1.4.24 - исправление ошибки
if ($get['id']==$user['id']) {
	$delete = array('users' => "SELECT id FROM users WHERE id=" . $user['id']);
}

//v1.4.16 - $delete удалил confirm
function event_delete_users ($q) {
	//v1.2.66 - удаление социальных профилей
	//v1.4.24 - исправление ошибки
	mysql_fn('query','DELETE FROM user_socials WHERE user='.$q['id']);
}

$tabs = array(
	1=>'Данные',
	2=>'Статистика',
	3=>'Структура',
);

$form[1][] = array('input td3','email');
$form[1][] = array('input td3','phone');
$form[1][] = array('input td3','name');
$form[1][] = array('input td3 datepicker','birthday');

$form[1][] = array('select td3','type',array(
	'value'=>array(true,$user_types,'')
));
//$form[1][] = array('input td2','last_visit');
$form[1][] = array('input td2','password',array(
	'value'=>'',
	'attr'=>'disabled="disabled"'
));

$form[1][] = array('checkbox td2','change',array(
	'value'=>'',
	'name'=>'изменить пароль',
	'attr'=>'onchange=$(this).closest(\'form\').find(\'input[name=email],input[name=password]\').prop(\'disabled\',!this.checked)'
));

//$form[1][] = array('checkbox td3','remember_me');





$form[1][] = array('file td6','avatar',array(
	'sizes'=>array(''=>'resize 1000x1000')
));

$form[1][] = 'clear';
if ($get['u']=='form' OR $get['id']>0) {
	$fields = @$post['fields'] ? (@unserialize($post['fields']) ?: []) : [];
	if ($parameters = mysql_select("
		SELECT *
		FROM user_fields
		WHERE display = 1
		ORDER BY rank DESC
	",'rows')) {
		$form[1][] = '<h2>Дополнительные параметры</h2>';
		foreach ($parameters as $q) {
			$values = $q['values'] ? unserialize($q['values']) : '';
			if (!isset($fields[$q['id']][0])) $fields[$q['id']][0] = '';
			switch ($q['type']) {
				case 1:
					$form[1][] = array('input td3', 'fields[' . $q['id'] . '][]', array(
						'value'=>$fields[$q['id']][0],
						'name' => $q['name']
					));
					break;
				case 2:
					$form[1][] = array('select td3', 'fields[' . $q['id'] . '][]', array(
						'value'=>array($fields[$q['id']][0], $values,''),
						'name' => $q['name']
					));
					break;
				case 3:
					$form[1][] = array('textarea td12', 'fields[' . $q['id'] . '][]', array(
						'value'=>$fields[$q['id']][0],
						'name' => $q['name']
					));
			}
		}
	}
}

$form[2][] = array('statistic','user');

if (@$post['parent']==0) {
	$form[3][] = array('input', 'parent',array(
		'name'=>'реферер'
	));
}
else {
	$form[3][] = array('user', 'parent',array(
		'name'=>'реферер'
	));
}
$form[3][] = array('structure', '');


$content.= '<script src="/admin/templates2/vendors/charts/chartjs/chart.min.js"></script>';
$content.= '<script src="/admin/templates2/js/chartjs.js?1"></script>';

//v1.4.14 - event_func
function event_change_users($q,$old=false) {
	global $get,$user,$post;
	//переавторизация после сохранения своих данных
	if ($q['id']==$user['id']) {
		user('re-auth');
	}

	//если есть старая запись
	if ($old) {
		if ($old['parent'] != 0 AND $q['parent'] != $old['parent']) {
			//возврашаем назад если есть поптыка сменить родителя
			mysql_fn('update', 'users', array(
				'id' => $q['id'],
				'parent' => $old['parent']
			));
		}
	}

	if ($old['parent']==0 AND $q['parent']) {
		$parent = mysql_select("SELECT * FROM users WHERE id=" . intval($q['parent']), 'row');
		$tree = mysql_select("SELECT * FROM users WHERE id='" . $parent['tree']."'", 'row');
		if ($parent AND $tree) {
			//перестраиваем дерево
			$q['tree'] = $q['parent'];
			mysql_fn('update', 'users', array(
				'id' => $q['id'],
				'tree' => $parent['tree'],
				'parent'=>0,
				'level'=>1,
				'left_key'=>$tree['right_key']+1,
				'right_key'=>$tree['right_key']+2,
			));
			nested_sets('users',$q['parent'],$q['id'],'parent',array(array('tree')));
		}
		else {
			mysql_fn('update', 'users', array(
				'id' => $q['id'],
				'tree' => $q['id'],
				'parent'=>0,
				'level'=>1,
				'left_key'=>1,
				'right_key'=>2,
			));
		}
	}
	//добавляем нового без ветки
	if ($old==false AND $q['parent']==0) {
		mysql_fn('update', 'users', array(
			'id' => $q['id'],
			'tree' => $q['id'],
			'parent'=>0,
			'level'=>1,
			'left_key'=>1,
			'right_key'=>2,
		));
	}
}