<?php

/*
 * добавление товаров из файла в базу сайта
 * идентификация товара идет по полю article которое должно быть самым первым
 * история обновления
 * v1.1.19
 */

//папка куда будут складываться результаты
$dir = 'files/temp/shop_import';

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
//количество значений без параметров
$table_count = count($table);

//добавляем динамические параметры у которых стоит галочка синхронизация
if ($shop_parameters = mysql_select("SELECT * FROM shop_parameters WHERE import=1 ORDER BY rank DESC,id",'rows_id')) {
	foreach ($shop_parameters as $k => $v) {
		$table[] = 'p' . $v['id'];
		$a18n['p' . $v['id']] = $v['name'];
	}
}

$content = '<br /><h2>Загрузка файла excel (csv, xls, xlsx)</h2>';
$content.= '<form method="post" enctype="multipart/form-data" action="">';
$content.= '<br /><input type="file" name="i">';
$content.= '<input type="submit" name="upload" value="Загрузить файл">';
$content.= '</form>';

if (count($_POST)>0) {
	$file = $exc = $data = false;
	//загрузка файла
	if (isset($_POST['upload'])) {
		if (is_file($_FILES["i"]["tmp_name"])) {
			$file = strtolower(trunslit($_FILES['i']['name']));
			$arr = explode('.',$file);
			$exc = end($arr);
			$file = ROOT_DIR.$dir.'/'.$file; //die($file);
			if (is_dir(ROOT_DIR.$dir) || mkdir(ROOT_DIR.$dir,0755,true)) {
				copy($_FILES["i"]["tmp_name"],$file);
			}
		}
		else $content.= '<br /><b>ошибка загрузки файла</b>';
	}
	//импорт файла
	elseif (isset($_POST['import'])) {
		if (is_file($_POST['file'])) {
			$file = $_POST['file'];
			$arr = explode('.',$file);
			$exc = end($arr);
		}
		else {
			$content.= '<br /><b>ошибка загрузки файла</b>';
		}
	}
	//обработка файла
	if ($file AND is_file($file)) {
		//загрузка csv
		$i = 0;
		if ($exc=='csv') {
			$handle = fopen($file, 'r');
			while (($value = fgetcsv($handle, 8000, ';')) !== FALSE) {
				$i++;
				foreach ($table as $k=>$v) {
					if ($k==0) {
						$data[$i][$k] = iconv("cp1251", "UTF-8",current($value));
						//next($value);
					}
					else $data[$i][$k] = iconv("cp1251", "UTF-8",next($value));
				}
			}
			fclose($handle);
		}
		//загрузка excel
		elseif ($exc=='xls' OR $exc=='xlsx') {
			include ROOT_DIR.'plugins/phpexcel/PHPExcel/IOFactory.php';
			$inputFileName = $file;
			//echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
			$data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		}
	}
	else {
		$content.= '<br /><b>ошибка типа файла</b>';
	}
	//вывод данных на экран
	if (is_array($data)) { //print_r($data);
		if (isset($_POST['upload'])) {
			$content = '<br /><h2>Подтверждение загрузки</h2>';
			$content.= '<form method="post" action=""><input name="file" type="hidden" value="'.$file.'"><input type="submit" name="import" value="Загрузить данные на сайт?"> &nbsp; <a href="">назад</a></form><br />';
			$content.= '<br /><h2>Содержание загруженого файла</h2>';
			$content.= 'Товары на зеленом фоне будут обновлены, товары на красном фоне будут добавлены<br />';
			$content.= 'На оранжевом фоне отображены несуществующие значение динамических параметров, которые будут добавлены<br /><br />';
			$content.= '<table class="table"><tr>';
			foreach ($table as $k=>$v) {
				$content.= '<th>'.$a18n[$v].'</th>';
			}
		}
		else {
			$insert = $update = 0;
			$content = '<h3>Результаты загрузки</h3>';
		}
		//алфавит для экселя
		$a = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		foreach ($data as $key=>$value) {
			if ($exc=='csv') $val = $value[0];
			//для экселя используем алфавит
			else $val = $value['A'];
			if ($val>0) {
				$id = mysql_select("SELECT * FROM shop_products WHERE `article` = '".mysql_res($val)."' LIMIT 1",'string');
				$color = $id==0 ? 'darkred' : 'green';
				if (isset($_POST['upload'])) $content.= '<tr class="bg_'.$color.'">';
				$post = array();
				foreach ($table as $k=>$v) {
					if ($exc=='csv') $str = $value[$k];
					else $str = $value[$a[$k]];
					if (isset($_POST['upload'])) {
						//проверка есть ли у параметра такое значение
						$color = '';
						if ($str AND $k>=$table_count) {
							//отрезаем p и получаем ИД параметра
							$p = substr($v,1);
							if (in_array($shop_parameters[$p]['type'], array(1, 3))) {
								$values = $shop_parameters[$p]['values'] ? unserialize($shop_parameters[$p]['values']) : array();
								if (!in_array($str, $values)) {
									$color = ' class="error"';
								}
							}
						}
						$content.= '<td '.$color.'>'.$str.'</td>';
					}
					//формирование массива для обновления БД
					else {
						$post[$v] = $str;
					}
				}
				//обработка параметров
				if (isset($_POST['import'])) {
					if ($shop_parameters) {
						foreach ($shop_parameters as $k => $v) {
							$values = $v['values'] ? unserialize($v['values']) : array();
							//если выбор из вариантов, см $config['shop_parameters']['type']
							if (in_array($v['type'], array(1, 3))) {
								if (in_array($post['p' . $v['id']], $values)) {
									$post['p' . $v['id']] = array_search($post['p' . $v['id']], $values);
								} //если значение нет то добавляем его
								else {
									$values[] = $post['p' . $v['id']];
									$post['p' . $v['id']] = array_search($post['p' . $v['id']], $values);
									$shop_parameters[$k]['values'] = serialize($values);
									mysql_fn('update', 'shop_parameters', $shop_parameters[$k]);
								}
							}

						}
					}
				}

				if (isset($_POST['import'])) {
					if ($id==0) {
						mysql_fn('insert','shop_products',$post);
						$insert++;
					}
					else {
						$post['id'] = $id;
						mysql_fn('update','shop_products',$post);
						$update++;
					}
				}
				if (isset($_POST['upload']))  $content.= '</tr>';
			}
			//else echo '-'.$value[$a[0]].'-'.$value[0].'-';
		}
		if (isset($_POST['upload'])) $content.= '</table>';
		else {
			$content.= '<br />Количество обновленных товаров:'.$update;
			$content.= '<br />Количество добавленных товаров:'.$insert;
		}
	}
	else {
		$content.= '<br /><b>ошибка обработки файла</b>';
	}
}
else {
	$content.= '<br /><h2>Файл должен быть с такой структоруй</h2>';
	$content.= '<br /><table class="table"><tr>';
	foreach ($table as $k=>$v) {
		//$str.= '"'.str_replace('"',"&quot;",$fieldset[$v]).'";';
		$content.= '<th>'.$a18n[$v].'</th>';
	}
	$content.= '</tr></table>';
}
unset($table);
