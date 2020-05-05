<?php

//статистика

$config['style'] = 'admin/templates2';

require_once(ROOT_DIR.'functions/form_func.php');	//функции почты

//определение значений формы
$fields = array(
	//'statistics'=>array(
		'from'		=>	'date',
		'to'		=>	'date',
		'type'		=>	'int',
		'user'      =>  'int',
	//)
);

$post = form_smart($fields,stripslashes_smart($_POST)); //print_r($post);
$tree = array();
$statistics = array(
	'results'=>array(),
	'parameters'=>array()
);

$where = '';
if ($post['user']) {
	$usr = mysql_select("SELECT * FROM users WHERE id=" . $post['user'], 'row');
	if ($usr) {
		if ($usr['id'] == $usr['tree']) {
			$tree = $usr;
		}
		else {
			$tree = mysql_select("SELECT * FROM users WHERE id=" . $usr['tree'], 'row');
		}
		//один пользователь
		if ($post['type'] == 1) {
			$where = " AND user='" . $post['user'] . "'";
		}
		//ветка пользователя
		else {
			$where = " AND user IN (
				SELECT id
				FROM users
				WHERE left_key>=" . $usr['left_key'] . " AND right_key<=" . $usr['right_key'] . "
			) ";

		}
	}
}

if ($post['from']) {
	$where .= " AND date>='" . $post['from'] . "'";
}
if ($post['to']) {
	$where .= " AND date<='" . $post['to'] . "'";
}

$select = array();
if ($tree) {
	$parameters = $tree['parameters'] ? unserialize($tree['parameters']) : array();
	foreach ($parameters as $k=>$v) {
		if (@$v['display']==1) {
			$select[] = "SUM(p" . $k . ") p" . $k;
			$statistics['parameters'][$k] = $v['name'];
		}
	}
}
else {
	for ($i = 1; $i < 11; $i++) {
		$select[] = "SUM(p" . $i . ") p" . $i;
		$statistics['parameters'][$i] = 'p'.$i;
	}
}

$query = '';
if ($select) {
	$select = implode(', ', $select);
	$query = "
		SELECT $select
		FROM statistics
		WHERE 1 $where
		";
	$statistics['results'] = mysql_select($query, 'row');
}
//$api['tree'] = $tree;
$api['query'] = $query;
$api['statistics'] = $statistics;
$api['post'] = $post;
$api['html'] = html_array('common/statistics_result',$statistics);
