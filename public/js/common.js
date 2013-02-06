$(function(){
	// показать полный текст
	$('.show-full-text').click(function() {
		var $this = $(this);
		var $parent = $this.parents('.block-box-body');

		// скрываем обрезанный текст
		$parent.find('.cut-text').hide();

		// показываем полный текст
		$parent.find('.full-text').show();

		return false;
	});

	// скрыть полный текст
	$('.show-cut-text').click(function() {
		var $this = $(this);
		var $parent = $this.parents('.block-box-body');

		// показываем обрезанный текст
		$parent.find('.cut-text').show();

		// скрываем полный текст
		$parent.find('.full-text').hide();

		return false;
	});

	// подсветка блока вопроса при наведении
	$('.item-wrap').live('hover', function(){
		var $this = $(this);
		if ($this.hasClass('item-over')) {
			$(this).removeClass('item-over');
			return;
		}
		$(this).addClass('item-over');
	});

	// подсветка заголовка вопроса при наведении
	$('.item-title').live('hover', function() {
		var $this = $(this);
		if ($this.hasClass('item-title-over')) {
			$(this).removeClass('item-title-over');
			return;
		}
		$(this).addClass('item-title-over');
	});

	// показывать или скрывать подробности вопроса
	$('.item-title, .block-box-active').live('click', function() {
		if ($(this).hasClass('item-title')) {
            var $parent = $(this).parent();
            var $question = $parent.find('.item-small-body');
            var $questionMore = $parent.find('.item-full-body');
        } else {
            var $question = $(this).find('.item-small-body');
            var $questionMore = $(this).find('.item-full-body');
        }

		if ($question.hasClass('hide')) {
			$question.removeClass('hide');
			$questionMore.addClass('hide');
		} else {
			$question.addClass('hide');
			$questionMore.removeClass('hide');
		}
	});

	/**
	 * Админ действие. Обработка нового отзыва.
	 *
	 * @todo: перенести в layout.html и показывать по условию если админ
	 */
	$('.item-contractors-action-process').click(function(){
		/**
		 * Объект по которому произошел щелчок
		 */
		var $self = $(this);

		/**
		 * Объект позиции
		 */
		var $parentRoot = $self.parents('.item-wrap');

		/**
		 * Объект полного тела позиции
		 */
		var $parentFullBody = $self.parents('.item-full-body');

		/**
		 * Объект блока управления
		 */
		var $parentControl = $self.parents('.item-control');

		/**
		 * Ссылки управления
		 */
		var $actionsControl = $parentControl.find('a');

		/**
		 * Объект поля ввода компании
		 */
		var $company = $parentFullBody.find('.company-name');

		/**
		 * Объект поля ввода отзыва
		 */
		var $comment = $parentFullBody.find('.comment');
		
		/**
		 * Объект оценки
		 */
		var $rating = $parentFullBody.find('.rating-value');

		/**
		 * Объект блока информационного сообщения
		 */
		var $infoBlock = $parentControl.find('.information-message');

		/**
		 * Объект прелоадера
		 */
		var $preloader = $parentControl.find('.preloader');

		// Проверяем на пустые значения
		// Наименование компании, описание компании и сам отзыв о компании
		// обязательны для заполнения
		if ($company.val() == '') {
			$company.focus();
			$infoBlock.html('Пожалуйста укажите название компании').show(200);
			return false;
		}

		if ($comment.val() == '') {
			$comment.focus();
			$infoBlock.html('Пожалуйста укажите отзыв о компании').show(200);
			return false;
		}

		// Если все поля заполненны то,
		// скрываем информационный блок и ссылки управления,
		// показываем прелоадер
		$infoBlock.hide();
		$actionsControl.hide();
		$preloader.show();

		// Посылаем аякс запрос на обработку отзыва.
		// Если обработка прошла успешна то информируем об этом.
		// Через 3 секунды скрываем блок.
		var xhr = $.getJSON($self.attr('href'), {
			'company_name': $company.val(),
			'comment': $comment.val(),
			'rating': $('option:selected', $rating).text()
		}, function(response) {
			if (response.message) {
				$actionsControl.show();
				$preloader.hide();
				$infoBlock.html(response.message).show(200);
				return;
			}
			var $html = '<div class="item-success-process">Пункт успешно обработан</div>';
			$parentRoot.html($html);
			setTimeout(function() {
				$parentRoot.slideUp(300);
			}, 3000);
		});

		// В случае если аякс запрос произошел с ошибкой
		// скрываем прелоадер и показываем контролы управления
		// с сообщением о повторной попытке
		xhr.error(function() {
			$actionsControl.show();
			$preloader.hide();
			$infoBlock.html('Ошибка при обработке запроса, пожалуйста повторите').show(200);
		});

		// Возвращаем false что-бы не переходить по ссылке
		return false;
	});

	/**
	 * Админ действие, удаление нового отзыва
	 */
	$('.item-contractors-action-delete').click(function() {
		/**
		 * Объект по которому произошел щелчок
		 */
		var $self = $(this);

		/**
		 * Объект позиции
		 */
		var $parentRoot = $self.parents('.item-wrap');

		/**
		 * Объект полного тела позиции
		 */
		var $parentFullBody = $self.parents('.item-full-body');

		/**
		 * Объект блока управления
		 */
		var $parentControl = $self.parents('.item-control');

		/**
		 * Ссылки управления
		 */
		var $actionsControl = $parentControl.find('a');

		/**
		 * Объект поля ввода компании
		 */
		var $company = $parentFullBody.find('.company-name');

		/**
		 * Объект поля ввода отзыва
		 */
		var $comment = $parentFullBody.find('.comment');

		/**
		 * Объект блока информационного сообщения
		 */
		var $infoBlock = $parentControl.find('.information-message');

		/**
		 * Объект прелоадера
		 */
		var $preloader = $parentControl.find('.preloader');

		// скрываем информационный блок и ссылки управления,
		// показываем прелоадер
		$infoBlock.hide();
		$actionsControl.hide();
		$preloader.show();

		var xhr = $.getJSON($self.attr('href'), function(response) {
			if (response.rating_delete == 'ok') {
				$parentRoot.fadeOut(300).slideUp(300);
			}
		});

		// В случае если аякс запрос произошел с ошибкой
		// скрываем прелоадер и показываем контролы управления
		// с сообщением о повторной попытке
		xhr.error(function() {
			$actionsControl.show();
			$preloader.hide();
			$infoBlock.html('Ошибка при обработке запроса, пожалуйста повторите').show(200);
		});

		// Возвращаем false что-бы не переходить по ссылке
		return false;
	});

	// ответ на вопрос
	$('.item-question-action-answer').click(function() {
		/**
		 * Объект по которому произошел щелчок
		 */
		var $self = $(this);

		/**
		 * Объект позиции
		 */
		var $parentRoot = $self.parents('.item-wrap');

		/**
		 * Объект полного тела позиции
		 */
		var $parentFullBody = $self.parents('.item-full-body');

		/**
		 * Объект блока управления
		 */
		var $parentControl = $self.parents('.item-control');

		/**
		 * Ссылки управления
		 */
		var $actionsControl = $parentControl.find('a');

		/**
		 * Объект поля ввода вопроса
		 */
		var $question = $parentFullBody.find('.question-edit');

		/**
		 * Объект поля ввода ответа
		 */
		var $answer = $parentFullBody.find('.answer-edit');

        console.log($question.val(), $answer);

		/**
		 * Объект блока информационного сообщения
		 */
		var $infoBlock = $parentControl.find('.information-message');

		/**
		 * Объект прелоадера
		 */
		var $preloader = $parentControl.find('.preloader');

		if ($question.val() == '') {
			$question.focus();
			$infoBlock.html('Пожалуйста укажите вопрос.').show(200);
			return false;
		}

		if ($answer.val() == '') {
			$answer.focus();
			$infoBlock.html('Пожалуйста укажите ответ.').show(200);
			return false;
		}

		// скрываем информационный блок и ссылки управления,
		// показываем прелоадер
		$infoBlock.hide();
		$actionsControl.hide();
		$preloader.show();

		var xhr = $.getJSON($self.attr('href'), {question: $question.val(), answer: $answer.val()}, function(response) {
			if (!$.isEmptyObject(response)) {
				if (response.message) {
					$actionsControl.show();
					$preloader.hide();
					$infoBlock.html(response.message).show(200);
				} else {
					$parentRoot.fadeOut(300).slideUp(300);
				}
			}
		});

		// В случае если аякс запрос произошел с ошибкой
		// скрываем прелоадер и показываем контролы управления
		// с сообщением о повторной попытке
		xhr.error(function() {
			$actionsControl.show();
			$preloader.hide();
			$infoBlock.html('Ошибка при обработке запроса, пожалуйста повторите').show(200);
		});

		// Возвращаем false что-бы не переходить по ссылке
		return false;
	});

	// удаление вопроса
	$('.question-action-delete').click(function() {
		/**
		 * Объект по которому произошел щелчок
		 */
		var $self = $(this);

		/**
		 * Объект позиции
		 */
		var $parentRoot = $self.parents('.item-wrap');

		/**
		 * Объект полного тела позиции
		 */
		var $parentFullBody = $self.parents('.item-full-body');

		/**
		 * Объект блока управления
		 */
		var $parentControl = $self.parents('.item-control');

		/**
		 * Ссылки управления
		 */
		var $actionsControl = $parentControl.find('a');

		/**
		 * Объект поля ввода вопроса
		 */
		var $question = $parentFullBody.find('.question-edit');

		/**
		 * Объект поля ввода ответа
		 */
		var $answer = $parentFullBody.find('.answer-edit');

		/**
		 * Объект блока информационного сообщения
		 */
		var $infoBlock = $parentControl.find('.information-message');

		/**
		 * Объект прелоадера
		 */
		var $preloader = $parentControl.find('.preloader');

		if ($question.val() == '') {
			$question.focus();
			$infoBlock.html('Пожалуйста укажите вопрос.').show(200);
			return false;
		}

		if ($answer.val() == '') {
			$answer.focus();
			$infoBlock.html('Пожалуйста укажите ответ.').show(200);
			return false;
		}

		// скрываем информационный блок и ссылки управления,
		// показываем прелоадер
		$infoBlock.hide();
		$actionsControl.hide();
		$preloader.show();

		var xhr = $.getJSON($self.attr('href'), function(response) {
			if (response.question_delete == 'ok') {
				$parentRoot.fadeOut(300).slideUp(300);
			}
		});

		// В случае если аякс запрос произошел с ошибкой
		// скрываем прелоадер и показываем контролы управления
		// с сообщением о повторной попытке
		xhr.error(function() {
			$actionsControl.show();
			$preloader.hide();
			$infoBlock.html('Ошибка при обработке запроса, пожалуйста повторите').show(200);
		});

		// Возвращаем false что-бы не переходить по ссылке
		return false;
	});

	// подсветка элемента в меню сортировки
	$('.sort-control').hover(function(){
		$(this).parent().addClass('navigation-menu-item-over');
	}, function() {
		$(this).parent().removeClass('navigation-menu-item-over');
	});

	/**
	 * Обработка событий для сортировки
	 */
	$('.sort-control label, .sort-control input').click(function() {
		/**
		 * Объект по которому произошел щелчок
		 */
		var $self = $(this);

		/**
		 * Родитель всего блока сортировки
		 */
		var $parentRoot = $self.parents('.navigation-menu');

		/**
		 * Родитель контрола
		 */
		var $parentControl = $self.parent();

		/**
		 * Контрол
		 */
		var $control = $parentControl.find('.control');

		/**
		 * Тип сортировки
		 */
		var $sortType = $control.attr('class');

		/**
		 * Значение сортировки
		 */
		var $value = $control.attr('id');

		/**
		 * Параметры строки
		 */
		var $locationSearch = location.search.substr(1).split('&');

		/**
		 * Новая строка для запроса
		 */
		var $newQuery = '';

		/**
		 * Отчекиваем все инпуты кроме текущего
		 */
		$parentRoot.find('.control').each(function() {
			var $thisId = $(this).parent().find('.control').attr('id');
			if ($thisId != $control.attr('id')) {
				$(this).removeAttr('checked');
			}
		});

		$parentControl.find('.preloader').show();

		if (typeof $control.attr('checked') != 'undefined') {
			$control.removeAttr('checked');
			$.get('/rating-contractors', function(response) {
				$('.content').find('.container-content').html(response);
				$parentControl.find('.preloader').hide();
			});
			return;
		}

		$control.attr('checked', true);
		$.get('/rating-contractors', {rating: $control.attr('id')}, function(response) {
			$('.content').find('.container-content').html(response);
			$parentControl.find('.preloader').hide();
			$('.pagination a').each(function() {
				$(this).attr('href', $(this).attr('href') + '&rating=' + $control.attr('id'));
			});
		});
	});
});