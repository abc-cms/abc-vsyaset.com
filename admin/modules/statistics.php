<?php

$a18n['invitations'] = 'приглашений';
$a18n['1st'] = '1-я встреча';
$a18n['1st_yes'] = 'да на 1-ю встречу';
$a18n['2nd'] = '2-я страеча';
$a18n['2nd_yes'] = 'да на 2-ю встречу';
$a18n['total'] = 'сумма сделок';


$table = array(
	'id'		=>	'id:desc',
	'user'		=>	'{login}',
	/*
	'invitations'=>	'',
	'1st_yes'=>	'',
	'1st'=>	'',
	'2nd_yes'=>	'',
	'2nd'=>	'',
	'total'=>	'',
	*/
	'date'		=>	'date',
);


$query = "
	SELECT statistics.*,
		u.email login
	FROM statistics
	LEFT JOIN users u ON u.id = statistics.user
	WHERE 1 $where
";

$form[] = array('input td3','user');
/*
$form[] = array('input td3','invitations');
$form[] = array('input td3','1st_yes');
$form[] = array('input td3','1st');
$form[] = array('input td3','2nd_yes');
$form[] = array('input td3','2nd');
$form[] = array('input td3','total');
*/
$form[] = array('input td3 datepicker','date');
$form[] = 'clear';
$tree = array();
if (@$post['user']) {
	$usr = mysql_select("SELECT * FROM users WHERE id=" . $post['user'], 'row');
	if ($usr) {
		if ($usr['id'] == $usr['tree']) {
			$tree = $usr;
		}
		else {
			$tree = mysql_select("SELECT * FROM users WHERE id=" . $usr['tree'], 'row');
		}
	}
}

if ($tree) {
	$parameters = $tree['parameters'] ? unserialize($tree['parameters']) : array();
	foreach ($parameters as $k=>$v) {
		if (@$v['display']==1) {
			$form[] = array('input td3', 'p'.$k,array('name'=>$v['name']));
		}
		else {
			$form[] = array('input td3', 'p'.$k);
		}
	}
}
else {
	for ($i=1; $i<11;$i++) {
		$form[] = array('input td3', 'p'.$i);
	}
}


