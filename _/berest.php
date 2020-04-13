<?php

/*
 * скрипт для выполнения прямых запросов к базе
 */

define('ROOT_DIR', dirname(__FILE__).'/../');
include_once (ROOT_DIR.'_config2.php');
include_once (ROOT_DIR.'functions/image_func.php');
include_once (ROOT_DIR.'functions/common_func.php');
include_once (ROOT_DIR.'functions/mysql_func.php');

$config['mysql_database'] = 'abc-berest';

mysql_connect_db();

$query = "SHOW TABLES";
if ($tables = mysql_select($query,'rows')) {
	$i = 0;
	foreach ($tables as $table) {
		$query = "ALTER TABLE `" . array_shift($table) . "` ENGINE = MYISAM";
		mysql_fn('query',$query,'affected_rows');
	}
}