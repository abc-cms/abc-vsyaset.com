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
	"ALTER TABLE `statistics` ADD `p1` INT NOT NULL AFTER `total`, ADD `p2` INT NOT NULL AFTER `p1`, ADD `p3` INT NOT NULL AFTER `p2`, ADD `p4` INT NOT NULL AFTER `p3`, ADD `p5` INT NOT NULL AFTER `p4`, ADD `p6` INT NOT NULL AFTER `p5`, ADD `p7` INT NOT NULL AFTER `p6`, ADD `p8` INT NOT NULL AFTER `p7`, ADD `p9` INT NOT NULL AFTER `p8`, ADD `p10` INT NOT NULL AFTER `p9`",
	"ALTER TABLE `statistics` DROP `invitations`, DROP `1st`, DROP `1st_yes`, DROP `2nd`, DROP `2nd_yes`, DROP `total`",
	"ALTER TABLE `users` ADD `parameters` TEXT NOT NULL AFTER `fields`"
);

foreach ($queries as $query) {
	if ($query) {
		if (mysql_fn('query',$query,'affected_rows')) echo '<div style="color:#00f">'.$query.'</div>';
		else echo '<div style="color:#f00">'.$query.' - '.mysqli_error($config['mysql_connect']).'</div>';
	}
}

/* *

"ALTER TABLE `statistics` ADD `date` DATE NOT NULL AFTER `updated_at`"

"ALTER TABLE `users` DROP `date`",
	"ALTER TABLE `users` ADD `tree` INT NOT NULL AFTER `updated_at`, ADD `parent` INT NOT NULL AFTER `tree`, ADD `left_key` INT NOT NULL AFTER `parent`, ADD `right_key` INT NOT NULL AFTER `left_key`, ADD `level` INT NOT NULL AFTER `right_key`",
	"ALTER TABLE `users` DROP `referrer`",

"UPDATE users SET left_key=1,right_key=2,level=1,tree=id"
*/
