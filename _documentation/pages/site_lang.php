Все служебные слова и фразы (подробнее, продолжить, введите свое имя и т.д.) должны быть в словаре.<br>
Словарь формируется в админке в модуле admin/modules/<strong>languages</strong>.php<br>
<pre>$form[0][] = lang_form('input td12','common|site_name','название сайта');</pre>
common|site_name - это ключ слова, он всегда состоит из двух частей<br>
- common - это общая группа слова<br>
- site_name - ключ слова в группе<br>
<br>
словарь хранится в файлах в папке files<br>
значение common|site_name находится в /files/languages/1/dictionary/common.php<br>
слова разделены на группы с целью экономии ресурсов, чтобы подключались не все существующие слова а только группа слов<br>
<br>
чтобы слово использовать в шаблоне, нужно вызывать функцию i18n()<br>
она сама проверяет подключена ли нужная группа слов и если не подключена то сама подключает
<pre>&lt;?=i18n('common|site_name')?></pre>

<div  class="bs-callout bs-callout-warning">
	Для быстрого заполнение словаря используем урл <code>/admin.php?m=languages&fuel=1</code>
	<br>который подставит во все пустые поля дефолтные значения
</div>


<h3>Быстрое редактирование</h3>
В админпанели в настройках /admin.php?m=config есть галочка включить быстрое редактирование<br>
Она отвечает за глобальное включение на сайте возможности администратору редактировать слова через интерфейс сайта просто кликнув на само слово<br>
<br>
Примеры использования:
<ol>
<li>Выключенное редактирование
<pre>&lt;?=i18n('common|site_name')?>
&lt;?=i18n('common|site_name',false)?>
</pre>
</li><li>Включенное редактирование
<pre>&lt;?=i18n('common|site_name',true)?> //полноценный нтмл редактор
&lt;?=i18n('common|site_name','text')?> //полноценный нтмл редактор
&lt;?=i18n('common|site_name','str')?> //инлайновый редактор
</pre>
</li><li>Автозамена шаблонов на переменные
<pre>&lt;?=i18n('common|txt_footer',array('Y'=>date('Y')))?></pre>
такая конструкция позволит код
<code>&copy; {Y}</code>
вывести как
		<strong>© <?=date('Y')?></strong>
</li></ol>

<div  class="bs-callout bs-callout-danger">Нельзя ставить true если слово обернуто в ссылку
	<pre>&lta href="/">&lt;?=i18n('common|site_name',true)?>&lt;a></pre>
</div>

<div  class="bs-callout bs-callout-warning">
	Так же нужно учитывать, что когда мы поставили параметр true, то слово будет дополнительно обернуто в тег span
</div>

