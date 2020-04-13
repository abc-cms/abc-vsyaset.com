<?php

//404 если есть $u[2]
if ($u[2]) {
	$error++;
}
else {
	//список внутренних страниц
	$abc['children'] = mysql_select("
		SELECT * FROM pages
		WHERE parent = '" . $abc['page']['id'] . "' AND display = 1
		ORDER BY left_key
	",'rows'); //echo $query;

	//список страниц этого же уровня
	$abc['siblings'] = mysql_select("
		SELECT * FROM pages
		WHERE parent = '" . $abc['page']['parent'] . "' AND display = 1
		ORDER BY left_key
	",'rows',''); //echo $query;

	//заменяем коды картинок и видео на нтмл
	$abc['page']['text'] = template_img('pages',$abc['page']);
	$abc['page']['text'] = template_video($abc['page']['text'],$abc['page']['video']);
}
