/*
 v1.4.4 - html_array для таблицы
 v1.4.7 - admin/template2
 v1.4.18 - api_response
 v1.4.24 - исправление ошибки
 */

//v1.4.18 - манипуляции с ответом апи
function api_response(response) {
	//console.log('2');
	if (response.data) {
		response.data.forEach(function (item) {
			if (item.method == 'append') {
				$(item.selector).append(item.content);
			}
			else if (item.method == 'show') {
				$(item.selector).show();
			}
			else if (item.method == 'hide') {
				$(item.selector).hide();
			}
			else if (item.method == 'remove') {
				$(item.selector).remove();
			}
			else if (item.method == 'html') {
				$(item.selector).html(item.content);
			}
			else if (item.method == 'text') {
				$(item.selector).text(item.content);
			}
			else if (item.method == 'replaceWith') {
				$(item.selector).replaceWith(item.content);
			}
			else if (item.method == 'alert') {
				alert(item.content);
			}
			//переадресация
			else if (item.method == 'location') {
				window.location.href = item.content;
			}
			//обновление
			else if (item.method == 'reload') {
				window.location.reload();
			}
			//окно, тут заменить на свое окно если не бутстрап
			else if (item.method == 'modal') {
				$(item.selector).modal();
			}
			else if (item.method == 'scroll') {
				//скрол
				$('html, body').animate({
					scrollTop: $(item.selector).offset().top
				}, 1000);
			}
			//выполнение любого скрипта
			else if (item.method == 'script') {
				$('body').append(item.content);
			}
			else {
				alert(item.method);
			}
		});
	}
	//todo код с ошибкой, сделать в всплывающем окне
	if (response.error_text) {
		alert(response.error_text);
	}
	else {
		if (response._error) alert(response._error);
	}
}


$(document).ready(function(){
	var table = $('table.table'),
		doc = $(this);

	//v1.4.7 - admin/template2 - сохранено/ошибка
	toastr.options = {
		timeOut: 3000,
		progressBar: true,
		showMethod: "slideDown",
		hideMethod: "slideUp",
		showDuration: 200,
		hideDuration: 200,
		positionClass: "toast-top-left"
	};

	//tooltip
	$('td a.edit',table).attr('title','редактировать запись');
	$('td a.delete',table).attr('title','удалить запись');
	$('td span.level',table).attr('title','для перемещение нажмите и передвиньте в нужное место');
	$('td span.sorting',table).attr('title','для перемещение нажмите и передвиньте в нужное место');
	$('td a.js_display',table).attr('title','показать/скрыть на сайте');
	$('td.post',table).attr('title','двойной клик для редактирования');
	$('td img.img',table).attr('title','просмотр картинки');

	init();

	//v1.4.7 - admin/template2
	function init() {
		//v1.4.7 - admin/template2 тултип
		$('[data-toggle="tooltip"]').tooltip({container:"body"});

		//сортировка
		$('.sortable').sortable();
		console.log('sortable');

		//v1.4.7 - admin/template2 - заменяем иконки
		feather.replace();

		//v1.4.7 - admin/template2
		$('.select2').select2({
			//placeholder: 'Select'
		});
		//v1.4.7 - admin/template2
		$('.clockpicker').clockpicker({
			donetext: 'Done',
			autoclose: true
		});
		//v1.4.7 - admin/template2
		$('.datepicker input').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			locale: {
				format: 'YYYY-MM-DD',
				"applyLabel": "Принять",
				"cancelLabel": "Отклонить",
				"fromLabel": "От",
				"toLabel": "До",
				"customRangeLabel": "Custom",
				"daysOfWeek": [
					"Вс",
					"Пн",
					"Вт",
					"Ср",
					"Чт",
					"Пт",
					"Сб"
				],
				"monthNames": [
					"Январь",
					"Февраль",
					"Март",
					"Апрель",
					"Май",
					"Июнь",
					"Июль",
					"Август",
					"Сентябрь",
					"Октябрь",
					"Ноябрь",
					"Декабрь"
				],
				"firstDay": 1
			}
		});
		$('.datepicker .clear').click(function(){
			var input = $(this).closest('.datepicker').find('input');
			if ($(input).data('daterangepicker')) {
				$(input).data('daterangepicker').remove();
			}
			$(input).val('');
			$(this).hide();
			return false;
		});
		//v1.4.7 - admin/template2
		$('.datetimepicker input').daterangepicker({
			timePicker: true,
			timePicker24Hour: true,
			timePickerSeconds: true,
			singleDatePicker: true,
			//startDate: moment().startOf('hour'),
			//endDate: moment().startOf('hour').add(32, 'hour'),
			locale: {
				format: 'YYYY-MM-DD HH:mm:ss',
				"applyLabel": "Принять",
				"cancelLabel": "Отклонить",
				"fromLabel": "От",
				"toLabel": "До",
				"customRangeLabel": "Custom",
				"daysOfWeek": [
					"Вс",
					"Пн",
					"Вт",
					"Ср",
					"Чт",
					"Пт",
					"Сб"
				],
				"monthNames": [
					"Январь",
					"Февраль",
					"Март",
					"Апрель",
					"Май",
					"Июнь",
					"Июль",
					"Август",
					"Сентябрь",
					"Октябрь",
					"Ноябрь",
					"Декабрь"
				],
				"firstDay": 1
			}
		});
		//v1.4.7 - admin/template2
		$('.image-popup').magnificPopup({
			type: 'image',
			zoom: {
				enabled: true,
				duration: 300,
				easing: 'ease-in-out',
				opener: function(openerElement) {
					return openerElement.is('img') ? openerElement : openerElement.find('img');
				}
			}
		});
	}

	//операции для записей таблицы
	//клик по чекбоксу в шапке
	$(table).on('change','tr th.table_checkbox input[type=checkbox]',function(){
		var checked = $(this).prop('checked');
		$('tr td.table_checkbox input[type=checkbox]',table).prop('checked',checked);
		table_check();
	});
	//клик по чекбоксу в таблице
	$(table).on('change','tr td.table_checkbox input[type=checkbox]',function(){
		table_check();
	});
	//функция подсчета проставленных чекбоксов
	function table_check () {
		var ids = [];
		$('tr td.table_checkbox input[type=checkbox]:checked',table).each(function(){
			var id = $(this).val();
			ids.push(id);
		});
		//ставим все ид через запятую
		$('.table_check input[name="_check[ids]"]').val(ids);
	}


	//изменение nested_sets в форме
	$(document).on('change','.form select[name^="nested_sets"]',function(){
		var s = $(this),
			form = s.closest('.form');
		if (s.attr('name')=='nested_sets[parent]') {
			var parent = this.value || 0;
			$('select[name="nested_sets[previous]"]',form).html('<option value="0">В конце списка</option>').append(s.find('option[data-parent='+parent+']').clone());
		}
		$('input[name="nested_sets[on]"]',form).val(1);
		return false;
	});

	//показать сеополя
	$(document).on('click','.seo-optimization a',function(){
		$(this).parent('div').next('div').slideToggle('fast');
		return false;
	});

	//multicheckbox
	$(document).on('change','.multicheckbox input',function(){
		$(this).closest('li').find('input').prop('checked',this.checked);
	});

	//открыть форму редактирования
	$(document).on('click','.table .open',function(){
		//закрываем окно
		$('#window').modal('hide');
		var opener = $(this),
			m = table.data('module'),
			tr = opener.closest('tr'),
			id = opener.closest('tr').data('id'),
			url = opener.attr('href');
		//подсвечиваем строку
		$('.is_open',table).removeClass('is_open');
		$(tr,table).addClass('is_open');
		//v1.4.20 - если шапка то снимаем подсветку
		$('thead tr',table).removeClass('is_open');

		$.get(
			url,
			{'m':m,'u':'form','id':id},
			function(data){ //alert(data);
				$(data).appendTo('body').find('.form').trigger('form.open');
				//открываем окно
				$('#window').modal();
			}
		);
		return false;
	});

	//закрытие окна (формы)
	$(document).on('hide.bs.modal','#window',function(){
		console.log('hide.bs.modal');
		//если были изменения
		if ($('#window .form').data('changed')) {
			swal({
				title: "Вы не сохранили данные!",
				text: "При закрытии формы данные введенные будут утеряны!",
				icon: "warning",
				//buttons: true,
				buttons: ["Отмена", "ОК"],
				dangerMode: true
			}).then((willDelete) => {
				if (willDelete) {
					console.log('closeForm');
					//снимаем флаг изменена
					$('#window .form').data('changed',false);
					//закрываем
					$('#window').modal('hide');
				}
			});
			//не даем закрывать форму
			return false;
		}
		else {
			//tinymce - удаление
			$('.tinymce textarea', this).each(function () {
				var id = $(this).attr('id');
				//tinymce.execCommand('mceRemoveControl', true,id);
				tinymce.EditorManager.execCommand('mceRemoveEditor', true, id);
			});
		}
	//после закрытия окна
	}).on('hidden.bs.modal', '#window',function () {
		//удаляем окно
		$('#window').remove();
	});

	//открытие формы
	$(document).on('form.open','.form',function(){
		//v1.4.7 - admin/template2
		init();

		$('a.delete',this).attr('title','удалить');

		//tinymce 4.x - https://www.tinymce.com/docs/
		tinymce.init({
			selector: ".tinymce textarea",
			language: "ru",
			plugins: [
				"advlist autolink lists link image charmap anchor",//preview  print
				"visualblocks code",//fullscreen
				"media table contextmenu paste textcolor moxiemanager",//
				"hr","stylebuttons"
			],
			/*
			plugins: [
				"advlist autolink link image lists charmap hr anchor pagebreak spellchecker",
				"searchreplace visualblocks visualchars code insertdatetime media nonbreaking",
				"table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern moxiemanager"
			],*/
			//проверка на ошибки
			browser_spellcheck:true,
			//разрешенные элементы
			extended_valid_elements: "div[itemtype|itemscope|itemprop|style|class|id],span[itemtype|itemscope|itemprop|style|class],@[itemtype|itemscope|itemprop|id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup],hr[id|title|alt|class|width|size|noshade|style],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],a[id|class|name|href|target|title|onclick|rel|style],script[type|src]",
			//тулбар
			//toolbar: "undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | anchor link image | table | code template",
			toolbar1: "bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | styleselect fontselect fontsizeselect | bullist numlist ",
			toolbar2: "undo redo | hr | style-h2 style-h3 | link unlink anchor image media code | table | removeformat | subscript superscript | charmap emoticons | visualchars visualblocks nonbreaking | outdent indent blockquote",
			menubar: false,
			image_title: true,
			/*
			//собственные блоки
			style_formats: [
			 	{title: "Заголовок", block: "h2"},
			 	{title: "Example", block: "div", classes: "example"},
			 ],*/
			//стили тинумайса
			content_css : "/templates/css/tinymce.css?",
			relative_urls: false,
			//устанавливаем значение что форма была изменена - для предупреждения при закрытии окна
			setup : function(ed) {
				ed.on("blur", function(e) {
					//$(".form").data("changed",true);
					$(".form").trigger("input");
				});
			}
		});
		tinymce.init({
			selector: ".hypertext_html",
			language: "ru",
			inline: true,
			plugins: [
				"advlist autolink lists link image charmap anchor",//preview  print
				"visualblocks code ",//fullscreen
				"media table contextmenu paste textcolor"//
			],
			toolbar2: "bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist  | table | removeformat",
			toolbar1: "styleselect fontsizeselect | code",
			menubar: false,
			style_formats: [
				{title: "Заголовок", block: "h2"},
				{title: "Example", block: "div", classes: "example"},
			],
			//content_css : "/templates/css/tinymce.css",
			relative_urls: false,
			setup: function(ed) {
				//content = tinymce.activeEditor.getContent();
			}
		});
		//открывать вкладку, на бутстрапе не работает
		if (location.hash) {
			//$('.nav-tabs a.nav-link[href='+location.hash+']',this).triggerHandler('click');
		}

		//v1.2.70 - yandex карта
		if ($('.yandex_map_box',this).length>0) {
			ymaps.ready(yandex_map);
		}
		//v1.2.72 - google карта
		if ($('.google_map_box',this).length>0) {
			google_map();
			//v1.4.7 - admin/template2
			$('.google_map_search select').select2({
				//placeholder: 'Enter address',
				language: "ru",
				ajax: {
					url: "/api/google_autocomplete/",
					type: "get",
					dataType: 'json',
					delay: 250,
					data: function (params) {
						return {
							search: params.term
						};
					},
					processResults: function (response) {
						return {
							results: response.list
						};
					},
					cache: true
				}
			});
			//меняем город
			$('.google_map_search select').on("select2:selecting", function(e) {
				console.log(e.params.args.data.text);
			});
		}

	//обработчик изменения данных в форме
	}).on('input','.form',function() {
		$(this).data('changed', true);
		console.log('form.input');
	//отправка формы редактирования
	}).on('form.submit','.form',function(e,close){
		var form = $(this).trigger('form.disable'),
			id = form.prop('id').substr(4),
			url = form.attr('action');
		if (close.sa) $('input[name*="nested_sets"]',form).val(1); //учитываем вложенность при сохранить как
		//обновить текстареа, так как он обновляется при отправке формы
		form.find('.tinymce textarea').each(function(){
			$(this).val(tinyMCE.get($(this).attr('id')).getContent());
		});
		form.ajaxSubmit({
			iframe:		true,
			url			: url+'&id='+(close.sa ? 'new&save_as='+id : id), //имитируем создание новой записи когда сохранить как
			dataType:	'json',
			success:	function (data){
				if (data) { //alert(data);
					//обновление загруженый файлов
					if (data.files) {
						for (var key in data.files) form.find('.files[data-i="'+key+'"]').replaceWith(data.files[key]);
						$('.files.simple ul').sortable();
					}
					//генерация seo-полей
					if (data.seo) {
						form.find('input[name="seo"]').prop('checked',false);
						for (var i in data.seo) form.find('input[name="'+i+'"]').val(data.seo[i]);
					}
					//успешный запрос
					if (data.error==0) {
						//v1.4.7 - admin/template2
						toastr.success('изменения сохранены успешно');
						//тут заменяется вся таблица если было дерево
						if (data.table) {
							$('#table').replaceWith(data.table);
						}
						//заменяем один ряд
						else if (data.tr) {
							//обновляем
							if (id > 0 && !close.sa) {
								$('tr[data-id=' + id + ']', table).replaceWith(data.tr);
							}
							//добавляем новый
							else {
								table.append(data.tr);
								form.attr('id', 'form' + data.id).find('span[data-name="id"]').text(data.id);
							}
						}
						//снимаем пометку что форма редактировалась
						form.data('changed',false);
						if (close.yep) {
							//form.trigger('form.close');
							$('#window').modal('hide');
						}
						//v1.4.7 - admin/template2
						init();
					}
					//ошибка запроса
					else {
						//v1.4.7 - admin/template2
						toastr.error(data.error);
						//v1.4.20 - заккоментировал ненужный код
						//form.find('.button input').removeAttr('disabled').parent(".button").removeClass('disabled');
					}
				}
				else alert('Ошибка отправки формы');
				//v1.4.20 - включаем форму
				form.trigger('form.enable');
			},
			error:	function(xhr,txt,err){
				alert('Ошибка ('+txt+(err&&err.message ? '/'+err.message : '')+')');
				//v1.4.20 - включаем форму
				form.trigger('form.enable');
			}
		});

	//отключить отправку формы
	}).on('form.disable','.form',function(){
		console.log('form.disable');
		$(this).find('.modal-footer button').prop('disabled',true);

	//включить отправку формы
	}).on('form.enable','.form',function(){
		console.log('form.enable');
		$(this).find('.modal-footer button').prop('disabled',false);

	//нажатие на кнопку для отправки формы
	}).on('click','.form .modal-footer button',function() {
		var submit = $(this),
			close = {
				'yep': submit.hasClass('close_form'),
				'sa': submit.hasClass('save_as')
			};
		!submit.prop('disabled') && submit.closest('form').trigger('form.submit',close);
		return false;
	});

	//tinymce - без этого кода не работает
	tinyMCE.PluginManager.add("stylebuttons", function(editor, url) {
		["pre", "p", "code", "h1", "h2", "h3", "h4", "h5", "h6"].forEach(function(name){
			editor.addButton("style-" + name, {
				tooltip: "Toggle " + name,
				text: name.toUpperCase(),
				onClick: function() { editor.execCommand("mceToggleFormat", false, name); },
				onPostRender: function() {
					var self = this, setup = function() {
						editor.formatter.formatChanged(name, function(state) {
							self.active(state);
						});
					};
					editor.formatter ? setup() : editor.on("init", setup);
				}
			})
		});
	});
	//потеря фокуса гипертекстом
	$(document).on('focusout','.hypertext_html',function(){
		var content = tinymce.activeEditor.getContent();
		$(this).next().val(content);
	});
	//v1.4.24 костыль для тинумайса, бутстраповское окно снимает фокус
	$(document).on('click','.mce-textbox',function(){
		$(this).focus();
	});

	//если форма загружена со страницей
	$('.form').trigger('form.open');


	//БЫСТРОЕ РЕДАКТИРОВАНИЕ ===================================================
	table.on('dblclick','td.post',function(){
		sendRequest = true;
		var td = $(this);
		if (!td.has('input').length) {
			var m = table.data('module'),
				id = td.parent('tr').data('id'),
				width = td.width(),
				name = td.data('name'),
				value = td.html();
			var i = td.width(width).html('<input type="text" value="'+value.replace(/["]/g,'&quot;')+'" />').find('input').focus().width(width-6).data('value',value).get(0);
			i.setSelectionRange && i.setSelectionRange(0,value.length);
		}
	//нажатие на клавиши
	}).on('keydown','td input',function(e) {
		var i = $(this);
		//Enter или Tab
		if (e.which==13 || e.which==9) {
			sendRequest = false;
			e.preventDefault();
			var td = i.closest('td'),
				tr = td.closest('tr'),
				eq = td.index(),
				next;
			switch (e.which) {
				case 9:
					next = e.shiftKey ? td.prevAll('.post').first() : td.nextAll('.post').first();
					if (next.length == 0) next = e.shiftKey ? tr.prev().find('.post').last() : tr.next().find('.post').first();
					break;
				case 13:
					next = e.shiftKey ? tr.prev().children().eq(eq) : tr.next().children().eq(eq);
					break;
			}
			applyChanges(i);
			next.trigger('dblclick');
			return false;
		//Esc
		} else if (e.keyCode==27) {
			sendRequest = false;
			e.preventDefault();
			i.closest('td').html(i.data('value')).width('auto');
			return false;
		}
	//потеря фокуса инпутом
	}).on('blur','td input[type=text]',function() {
		console.log('td input');
		if (sendRequest) applyChanges($(this));
	//редактирование в селекте
	}).on('dblclick','td.select',function(){
		var i = $(this),
			id = i.data('id'),
			name = i.data('name'),
			value = i.text(),
			width = i.closest('td').width(),
			select = table.find('th select[name="'+name+':"]').val(id).html();
		i.html('<select>'+select+ '</select>');
		i.find('select').val(id);
		//i.find('select').triggerHandler('click');
	}).on('change','td select',function() {
		var i = $(this),
			m = table.data('module'),
			name = i.closest('td').data('name'),
			id = i.closest('tr').data('id'),
			value = i.val(),
			str = i.find('option:selected').text();
		i.closest('td').html(str).data('id',value);
		$.get(
			'/admin.php',
			{'m':m,'u':'post','id':id,'name':name,'value':value},
			function(data) {
				//показываем ошибку
				if (data) {
					alert(data);
				}
			}
		);
	});

	//v1.2.70 - инициализация карты яндекса
	function yandex_map() {
		$.each($('.yandex_map_box'), function (i, el) {
			map_box = $(this).closest('.yandex_map');
			map_id = $(el).attr('id');
			map_lat = $(el).data('lat');
			map_lng = $(el).data('lng');
			map_lat_default = $(el).data('lat_default');
			map_lng_default = $(el).data('lng_default');
			var uluru = map_lat ? [map_lat, map_lng] : [map_lat_default, map_lng_default];
			myMap = new ymaps.Map(map_id, {
				center: uluru, //[47.271975074248026,39.69305799999998],
				zoom: 16,
				controls: []
			});

			myMap.behaviors.disable('scrollZoom');

			myMap.controls.add("zoomControl", {
				position: {top: 15, left: 15}
			});
			//добавление точки на карту
			if (map_lat) {
				//создание точки
				myPlacemark = new ymaps.Placemark(uluru, {
					//preset: 'islands#icon',
					//iconColor: '#0095b6'
				});
				myMap.geoObjects.add(myPlacemark);
			}
			//перемещение точки
			myMap.events.add("click", function (e) {
				$('.lat input',map_box).val(e.get("coords")[0]);
				$('.lng input',map_box).val(e.get("coords")[1]);
				if (typeof myPlacemark == 'undefined') {
					//создание точки
					myPlacemark = new ymaps.Placemark(uluru, {
						//preset: 'islands#icon',
						//iconColor: '#0095b6'
					});
					myMap.geoObjects.add(myPlacemark);
				}
				myPlacemark.geometry.setCoordinates(e.get("coords"));
			});
			//поиск по карте
			$(this).closest('.yandex_map').find('.yandex_map_button').click(function() {
				var str = $(this).closest('.yandex_map').find('.yandex_map_search input').val();
				var myGeocoder = ymaps.geocode(str);
				myGeocoder.then(
					function (res) {
						//alert('Координаты объекта :' + res.geoObjects.get(0).geometry.getCoordinates());
						$('.lat input',map_box).val(res.geoObjects.get(0).geometry.getCoordinates()[0]);
						$('.lng input',map_box).val(res.geoObjects.get(0).geometry.getCoordinates()[1]);
						myMap.panTo(res.geoObjects.get(0).geometry.getCoordinates(), {duration: 1000});
						//создание точки
						if (typeof myPlacemark == 'undefined') {
							myPlacemark = new ymaps.Placemark(uluru, {
								//preset: 'islands#icon',
								//iconColor: '#0095b6'
							});
							myMap.geoObjects.add(myPlacemark);
						}
						myPlacemark.geometry.setCoordinates(res.geoObjects.get(0).geometry.getCoordinates());
					},
					function (err) {
						alert('Ошибка');
					}
				);
				return false;
			});
		});
	}

	//v1.2.73 - инициализация карты гугл
	function google_map() {
		$.each($('.google_map_box'), function (i, el) {
			map_box = $(this).closest('.google_map');
			map_id = $(el).attr('id');
			map_lat = $(el).data('lat');
			map_lng = $(el).data('lng');
			map_lat_default = $(el).data('lat_default');
			map_lng_default = $(el).data('lng_default');
			var uluru = map_lat ? {lat: map_lat, lng: map_lng} : {lat: map_lat_default, lng: map_lng_default} ;
			var map = new google.maps.Map(document.getElementById(map_id), {
				zoom: 15,
				center: uluru
			});
			//массив с маркерами
			markersArray = [];
			//если есть координаты то ставим курсор
			if (map_lat) {
				var marker = new google.maps.Marker({position: uluru, map: map});
				//marker.setMap(map);
				markersArray.push(marker);
			}
			//клик по карте
			google.maps.event.addListener(map, 'click', function(event) {
				//удаляем все маркеры
				for (i in markersArray) markersArray[i].setMap(null);
				markersArray.length = 0;
				//добавляем маркер по новым координатам
				var marker = new google.maps.Marker({
					position:event.latLng,
					map:map
				});
				markersArray.push(marker);
				//вставляем данные на страницу
				$('.lat input',map_box).val(event.latLng.lat);
				$('.lng input',map_box).val(event.latLng.lng);
			});
			//поиск по карте
			//$(this).closest('.google_map').find('.google_map_button').click(function() {
			//	var str = $(this).closest('.google_map').find('.google_map_search input').val();
			$(this).closest('.google_map').find('.google_map_search select').change(function() {
				var str = $(this).val();
				//alert(str);
				geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': str}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						//удаляем все маркеры
						for (i in markersArray) markersArray[i].setMap(null);
						//центрируем карту по новым координатам
						map.setCenter(results[0].geometry.location);
						//добавляем маркер по новым координатам
						var marker = new google.maps.Marker({
							position: results[0].geometry.location,
							map: map
						});
						markersArray.push(marker);
						//вставляем данные на страницу
						$('.lat input',map_box).val(results[0].geometry.location.lat);
						$('.lng input',map_box).val(results[0].geometry.location.lng);
					}
					else {
						alert('Geocode was not successful for the following reason: ' + status);
					}
				});
				return false;
			});
		});
	}

	//функция применения изменений для быстрого редактирования
	function applyChanges(i) {
		var td = i.closest('td'),
			m = table.data('module'),
			name = td.data('name'),
			id = td.closest('tr').data('id'),
			value = i.val(),
			oldVal = i.data('value');
		td.html(value).width('auto');
		if (value!=oldVal) {
			$.get(
				'/admin.php',
				{'m':m,'u':'post','id':id,'name':name,'value':value},
				function(data) {
					//показываем ошибку
					if (data) {
						td.html(oldVal);
						alert(data);
					}
				}
			);
		}
	}

	//ПЕРЕКЛЮЧАТЕЛИ ============================================================
	table.on('change','.js_boolean',function(){
		var a = $(this),
			m = table.data('module'),
			id = a.closest('tr').data('id'),
			name = a.closest('td').data('name'),
			value = $(a).prop('checked') ? 1:0
		$.get('/admin.php', {'m':m,'u':'post','id':id,'name':name,'value':value});
	});

	//обработчик нажатия кнопок Enter или Esc
	/*
	doc.on('keydown',function(e){
		if (e.keyCode==13 || e.keyCode==27) {
			//нажатие Esc при открытой форме
			if ($('.form').length) {
				//Esc
				if (e.keyCode==27) {
					$('.form').trigger('form.close');
				}
			}
		}
	});
	*/

	//УДАЛЕНИЕ id
	//v1.4.7 - admin/template2
	table.on('click','tr td .delete',function(){
		swal({
			title: "Вы уверены?",
			text: "После удаления запись нельзя будет восстановить!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
			.then((willDelete) => {
			if (willDelete) {
				var tr = $(this).closest('tr'),
					m = table.data('module'),
					id = tr.data('id'),
					path = '/admin.php?u=delete&type=id&m='+m+'&id='+id;
				$.getJSON(path, {},
					function (data) {
						if (data.error_text) {
							//v1.4.24 - исправление ошибки
							swal("ERROR", data.error_text, "error");
						}
						else {
							if (data.success) $(tr).remove();
							else {
								swal("ERROR", 'unknown error', "error");
							}
							/*swal("Poof! Item has been deleted!", {
								icon: "success",
							});*/
						}
					}
				);
			}
		});
		return false;
	});



	//ПОИСК ====================================================================
	$('#filter .sprite.search').click(function(){
		var url = $(this).attr('href'),
			search = $(this).parent('div').find('input').blur().val();
		search = encodeURIComponent(search);
		top.location = url+search;
		return false;
	});
	//отправка энтером
	$('#filter input[name=search]').keyup(function(e){
		if (e.which==13) {
			$(this).next('a').trigger('click');
		}
	});

	//v1.4.9 вставки с шаблоном
	//добавление в текущую таблицу
	$(document).on("click",".js_table_plus",function(){
		var i = $(this).parents("table").find("tr:last").data("i"),
			template = $(this).data('template');
		i++;
		var content = $(template).html();
		content = content.replace(/{i}/g,i);
		content = content.replace(/{[\w]*}/g,"");
		$(this).parents("table").append(content);
		return false;
	});
	//удаление текущей таблице
	$(document).on("click",".js_table_del",function(){
		$(this).parents("tr").remove();
		return false;
	});
});
