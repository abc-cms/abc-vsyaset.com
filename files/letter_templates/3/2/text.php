Здравствуйте!
<br /><br />Данное сообщение автоматически отправлено почтовым роботом сервера <a href="<?=$config['http_domain']?>/"><?=$config['http_domain']?></a> и не требует ответа.
<br /><br />На электронный адрес <?=$q['email']?> был осуществлён запрос на восстановление пароля;
<br /><br />Для восстановления пароля, перейдите по <a href="<?=$config['http_domain'].get_url('profile')?>?email=<?=$q['email']?>&hash=<?=user_hash($q)?>">этой ссылке</a>
<br /><br />Если это письмо попало к вам случайно, либо вы не хотите менять пароль, просто проигнорируйте его.