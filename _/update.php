<?php

/*
 * скрипт для выполнения прямых запросов к базе
 */

define('ROOT_DIR', dirname(__FILE__).'/../');
include_once (ROOT_DIR.'_config2.php');
include_once (ROOT_DIR.'functions/image_func.php');
include_once (ROOT_DIR.'functions/common_func.php');
include_once (ROOT_DIR.'functions/mysql_func.php');

mysql_connect_db();



//список скл запросов
$queries = array(
	"ALTER TABLE `statistics` ADD `date` DATE NOT NULL AFTER `updated_at`"

);

foreach ($queries as $query) {
	if ($query) {
		if (mysql_fn('query',$query,'affected_rows')) echo '<div style="color:#00f">'.$query.'</div>';
		else echo '<div style="color:#f00">'.$query.' - '.mysqli_error($config['mysql_connect']).'</div>';
	}
}

/* *
"ALTER TABLE `users` DROP `date`",
	"ALTER TABLE `users` ADD `tree` INT NOT NULL AFTER `updated_at`, ADD `parent` INT NOT NULL AFTER `tree`, ADD `left_key` INT NOT NULL AFTER `parent`, ADD `right_key` INT NOT NULL AFTER `left_key`, ADD `level` INT NOT NULL AFTER `right_key`",
	"ALTER TABLE `users` DROP `referrer`",

"UPDATE users SET left_key=1,right_key=2,level=1,tree=id"
*/
