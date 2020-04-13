<?php

/*
 * сохранение товаров в файл
 * динамические параметры тут - /admin/modules/shop_parameters.php
 * история обновления
 * v1.1.19
 */

//папка куда будут складываться результаты
$dir = 'files/temp/shop_export';

$table = array(
	'article',
	'price',
	'price2',
	'brand',
	'category',
	'name',
	'special',
	'text',
);

//дополнительный массив для параметров
$table2 = array();

//добавляем динамические параметры у которых стоит галочка синхронизация
if ($shop_parameters = mysql_select("SELECT * FROM shop_parameters WHERE import=1 ORDER BY rank DESC,id",'rows')) {
	foreach ($shop_parameters as $k => $v) {
		$table2[] = 'p' . $v['id'];
		$a18n['p' . $v['id']] = $v['name'];
	}
}

if (isset($_POST['export'])) {
	if (is_dir(ROOT_DIR.$dir) || mkdir(ROOT_DIR.$dir,0755,true)) {
		$file = $dir.'/'.date('Y-m-d_H_i').'.'.$_POST['type'];
		$str = '';
		$content = '<h2>Содержание файла</h2>';
		$content.= '<a href="/'.$file.'">Скачать файл</a> &nbsp; <a href="">вернуться</a><br />';
		$content.= '<br /><table class="table"><tr>';
		$data = array();
		foreach ($table as $k=>$v) {
			$data[0][] = $a18n[$v];
			$content.= '<th>'.$a18n[$v].'</th>';
		}
		foreach ($table2 as $k=>$v) {
			$data[0][] = $a18n[$v];
			$content.= '<th>'.$a18n[$v].'</th>';
		}

		$content.= '</tr>';
		if ($shop_products = mysql_select("
			SELECT p.*
			FROM shop_products p
			WHERE 1
		",'rows')) {
			$i = 0;
			foreach ($shop_products as $q) {
				$i++;
				$content .= '<tr valign="top">';
				foreach ($table as $k => $v) {
					$data[$i][] = $q[$v];
					$content .= '<td>' . $q[$v] . '</td>';
				}
				if ($shop_parameters) {
					foreach ($shop_parameters as $k => $v) {
						$values = $v['values'] ? unserialize($v['values']) : array();
						//если выбор из вариантов, см $config['shop_parameters']['type']
						if (in_array($v['type'],array(1,3))) $str = @$values[$q['p'.$v['id']]];
						else $str = $q['p'.$v['id']];
						$data[$i][] = $str;
						$content .= '<td>' . $str . '</td>';
					}
				}
				$content .= '</tr>';
			}
		}
		$content.= '</table>';
		if ($_POST['type']=='csv') {
			foreach ($data as $key=>$val) {
				foreach ($val as $k=>$v) {
					$str .= '"' . str_replace('"', "&quot;", $v) . '";';
				}
				$str.= "\n";
			}
			$str = iconv("UTF-8", "cp1251//TRANSLIT", $str);
			$fp = fopen(ROOT_DIR.$file, 'w');
			fwrite($fp,$str);
			fclose($fp);
			unset($table);
		}
		else {
			include (ROOT_DIR.'plugins/phpexcel/PHPExcel.php');
			include (ROOT_DIR.'plugins/phpexcel/PHPExcel/Writer/Excel2007.php');
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
			$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
			$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
			$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
			$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
			$objPHPExcel->getActiveSheet()
			    ->fromArray(
			        $data,  // The data to set
			        NULL,        // Array values with this value will not be set
			        'A1'         // Top left coordinate of the worksheet range where
			                     //    we want to set these values (default is A1)
			    );
			$objPHPExcel->getActiveSheet()->setTitle('Simple');
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			//v1.2.40 запретить формулы при генерации excel
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save(ROOT_DIR.$file);

		}
	}
	else $content = 'не удалось создать каталог';
}
else {
	$content = '<br /><h2>Подтверждение создания файла</h2>';
	$content.= 'Будет сгенерирован файл excel c такой структурой:<br />';
	$content.= '<br /><table class="table"><tr>';
	foreach ($table as $k=>$v) {
		//$str.= '"'.str_replace('"',"&quot;",$fieldset[$v]).'";';
		$content.= '<th>'.$a18n[$v].'</th>';
	}
	foreach ($table2 as $k=>$v) {
		$content.= '<th>'.$a18n[$v].'</th>';
	}
	$content.= '</tr></table>';
	$content.= '<form method="post" action="">';
	$content.= '<br /><select name="type"><option value="csv">csv</option><option value="xlsx">xlsx</option></select> &nbsp; ';
	$content.= '<input type="submit" name="export" value=" Сгенерировать файл ">';
	$content.= '</form>';
}
unset($table);
