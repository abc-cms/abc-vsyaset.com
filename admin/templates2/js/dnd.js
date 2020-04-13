$.fn.dnd = function(sel,opt){
		targets:	$(),
		over:		false,
		below:		false,
		before:		false,
		after:		false,
	},opt);
			mouseDownAt,
			mouseOffset,
			dndElement,
			currTarget = $(),
			doc = $(document);

		function dndInit(e) {
			if (e.which!=1) return;
	 		mouseDownAt = e;
	 		//проверяем, наступило ли событие dnd после нажатия кнопки мыши
	 		doc.on('mousemove.dndCatch',dndCatch).on('mouseup.dndStop',dndStop);
			document.ondragstart = document.body.onselectstart = function(){return false}
			return;
		}

		function removeSelection(){
			if (window.getSelection) window.getSelection().removeAllRanges();
			else if (document.selection && document.selection.clear) document.selection.clear();
		}

		function dndCatch(e) {
			doc.off('mousemove.dndCatch');
			dndElement.trigger($.Event('dnd.catch',{pageX:e.pageX, pageY:e.pageY, which:e.which}));
			//запомнить, с каких относительных координат начался перенос
			mouseOffset = getMouseOffset(dndElement, mouseDownAt);
			mouseDownAt = null;
			dndElement.css('position','absolute').offset(mouseOffset);
			dndStart();
		}

		function dndStart() {
			doc.on('mousemove.dndExecute',dndExecute);
		}

		function dndExecute(e) {
			dndElement.css({
				top:	e.pageY - mouseOffset.y + 'px',
				left:	e.pageX - mouseOffset.x + 'px'
			});
			dndGetTarget(newTarget,e);
			removeSelection();
		}

		function dndStop(e) {
 			doc.off('.dndCatch .dndExecute .dndStop');
			dndElement.css({
				position:	'',
				cursor:		'auto'
			}).trigger('dnd.stop');
			if (currTarget.length > 0) dndElement.trigger($.Event('dnd.drop',{dropTarget:currTarget}));
			currTarget = $();
			opt.targets.removeClass('dndTarget dndTargetBefore dndTargetAfter dndTargetOver dndTargetBelow');
 			document.ondragstart = document.body.onselectstart = null;
		}

		function dndGetTarget(newTarget,e){
			if (newTarget.length > 0) {
				newTarget.trigger('dnd.newTarget');
				var cl = 'dndTarget';
				if (opt.before || opt.after || opt.over || opt.below) {
					var h = newTarget.height(),
						w = newTarget.width(),
						offset = newTarget.offset();
					if (opt.before && Math.abs(e.pageX-offset.left)<0.25*w) cl = 'dndTargetBefore';
					if (opt.after && Math.abs(e.pageX-offset.left-w)<0.25*w) cl = 'dndTargetAfter';
					if (opt.over && Math.abs(e.pageY-offset.top)<0.25*h) cl = 'dndTargetOver';
					if (opt.below && Math.abs(e.pageY-offset.top-h)<0.25*h) cl = 'dndTargetBelow';
				}
			}
			currTarget = newTarget.addClass(cl);
		}

	});


		dndElement.css('display','none');
		var el = document.elementFromPoint(e.pageX - doc.scrollLeft(), e.pageY - doc.scrollTop());
		dndElement.css('display','');

		return $(el).closest(opt.targets);
	}

	function getMouseOffset(target, event) {
		var docPos = target.offset(),
			parPos = target.offsetParent().offset();
		return {x:event.pageX-(docPos.left-parPos.left), y:event.pageY-(docPos.top-parPos.top)}
	}
}




$(document).ready(function(){
	$('table.table.tree').dnd('td.level',{
		targets:			$('table.table tr').not('.head'),
		over:				true,
	});

	$('table.table.tree').on('dnd.start','tr',function(){
	//при опускании строки на принимающую строку
	}).on('dnd.drop','tr',function(e){
		var m = $(e.delegateTarget).data('module'),
			curr = $(this),
			targ = $(e.dropTarget),
			id = targ.data('id'),
			selected = curr.data('id'),
			insType = targ.hasClass('dndTargetOver') ? 'prev' : 'parent';
		//создаем объект с данными для запроса
		var getData = {'m':m,'u':'nested_sets','insert':insType,'id':id,'select':selected};
		//добавляем значения фильтров
		$('.filter select').each(function(){
			getData[this.name] = this.value;
		});
		//запрос на сервер
		$.get("/admin.php", getData, function(d){
			d && alert(d);
		});

		//перемещение ветки
		var targBranch = getBranch(targ),
			currBranch = getBranch(curr),
			targLevel = targ.data('level'),
			currLevel = curr.data('level');
		if (insType=='prev') currBranch.insertBefore(targ);
		if (insType!='prev') shift++;
		currBranch.each(function(){
		//инициализируем событие изменения строк таблицы
		$(e.delegateTarget).trigger('rowsChanged');
	}).on('dnd.stop','tr',function(e){
		getBranch($(this)).css('display','');
	});

	//исправление четных/нечетных строк после изменения таблицы
	$('table.table').on('rowsChanged',function(){
		$(this).find('tr').not('.head').each(function(i){
			$(this).removeClass('odd even').addClass(i%2==0 ? 'odd' : 'even');
		});
	})

});

function getBranch(el){
		next = el.next(),
		level = el.data('level');
	while (next.data('level')>level) {
		branch = branch.add(next);
		next = next.next();
	}
	return branch;
