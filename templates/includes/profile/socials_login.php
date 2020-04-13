<?php
//v1.2.66 - добавлена
?>
<div style="padding:10px 0 0">
<?php
$images = array(
	'vk'=>'/templates/images/socicon-custom/vkontakte.png',
	'facebook'=>'/templates/images/socicon-custom/facebook.png',
	'google'=>'/templates/images/socicon-custom/google.png',
	'yandex'=>'/templates/images/socicon-custom/yandex.png',
	'mailru'=>'/templates/images/socicon-custom/mailru.png',
);
foreach ($config['user_socials']['types'] as $k=>$v) {
	//урл для редиректа
	$redirect = urlencode($config['http_domain'].get_url('login','social'));
	$url = 'http://auth.abc-cms.com/' . $v . '/?redirect='.$redirect;
	$title = htmlspecialchars(i18n('socials|'.$k));
	echo '<a href="'.$url.'" title="'.$title.'"><img alt="'.$v.'" src="'.$images[$v].'"/></a> ';
}
?>
</div>
