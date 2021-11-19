(function ($, undefined) {
	window.Wds = window.Wds || {};

	function reload_box(box_id) {
		return $.post(ajaxurl, {
			action: 'wds-reload-box',
			box_id: box_id,
			_wds_nonce: _wds_dashboard.nonce
		}, function (data) {
			if ((data || {}).success) {
				if (!Array.isArray(box_id)) {
					box_id = [box_id];
				}

				$.each(box_id, function (index, value) {
					var $box = $('#' + value);

					if ($box.length && data[value]) {
						$box.replaceWith(data[value]);
					}
				});
			}
		}, 'json').always(function () {
			update_page_status();
			load_accordions();
			load_score_circles();
		});
	}

	function activate_component(e) {
		e.preventDefault();
		var $button = $(this),
			$box = $button.closest('.wds-dashboard-widget'),
			box_id = $box.attr('id');

		before_ajax_request($button);

		$.post(ajaxurl, {
			action: 'wds-activate-component',
			option: $button.data('optionId'),
			flag: $button.data('flag'),
			value: $button.get(0).hasAttribute('data-value') ? $button.data('value') : 1,
			_wds_nonce: _wds_dashboard.nonce
		}, function (data) {
			if ((data || {}).success) {
				reload_box_and_dependents(box_id);
			}
		}, 'json');
	}

	function box_exists(box_id) {
		return !!$('#' + box_id).length;
	}

	function reload_box_and_dependents(box_id) {
		var $box = $('#' + box_id),
			dependent = $box.data('dependent'),
			reload_boxes = [box_id];

		if (dependent) {
			var dependents = dependent.split(';');
			reload_boxes = reload_boxes.concat(dependents);
		}

		return reload_box(
			_.filter(reload_boxes, box_exists)
		);
	}

	function update_page_status() {
		$('.wds-disabled-during-request').prop('disabled', false);
		$('.sui-button-onload').removeClass('sui-button-onload');
	}

	function before_ajax_request($target_element) {
		if (!$target_element.is('.sui-button-onload')) {
			$target_element.addClass('sui-button-onload');
			$('.wds-disabled-during-request').prop('disabled', true);
		}
	}

	function reload_boxes() {
		var $boxes_requiring_refresh = $('.wds-box-refresh-required'),
			box_ids = [];

		if ($boxes_requiring_refresh.length) {
			$.each($boxes_requiring_refresh, function () {
				var $box = $(this).closest('.wds-dashboard-widget'),
					box_id = $box.attr('id');

				if (!box_ids.includes(box_id)) {
					box_ids.push(box_id);
				}
			});

			reload_box(box_ids);
		}

		setTimeout(reload_boxes, 20000);
	}

	function load_accordions() {
		$('.wds-page .sui-accordion').each(function () {
			SUI.suiAccordion(this);
		});
	}

	function load_score_circles() {
		$('.sui-circle-score:not(.loaded)').each(function () {
			SUI.loadCircleScore(this);
		});
	}

	function lighthouse_accordion_item_click(event) {
		event.preventDefault();
		event.stopPropagation();

		var health_url = _wds_dashboard.health_page_url + '&device=' + _wds_dashboard.lighthouse_widget_device;
		redirect_to_check(event, health_url);
	}

	function checkup_accordion_item_click(event) {
		event.preventDefault();
		event.stopPropagation();

		redirect_to_check(event, _wds_dashboard.health_page_url);
	}

	function redirect_to_check(event, health_page) {
		var checkup_item = $(event.target).closest('.sui-accordion-item');

		if (checkup_item.length && health_page) {
			window.location.href = health_page + '&check=' + checkup_item.attr('id');
		}
	}

	function get_checkup_box_el() {
		return $('#wds-seo-checkup');
	}

	function update_checkup_progress() {
		var $checkup_box = get_checkup_box_el(),
			$progress_bar = $('.wds-progress', $checkup_box);

		if (!$progress_bar.length) {
			return;
		}

		window.Wds.update_checkup_progress(
			$progress_bar,
			function () {
				reload_box_and_dependents($checkup_box.attr('id'))
					.then(hook_checkup_accordion_item_click);
			}
		);
	}

	function hook_checkup_accordion_item_click() {
		$('div.sui-accordion-item-header', get_checkup_box_el())
			.off('click')
			.on('click', checkup_accordion_item_click);
	}

	function hook_lighthouse_accordion_item_click() {
		$('#wds-lighthouse div.sui-accordion-item-header')
			.off('click')
			.on('click', lighthouse_accordion_item_click);
	}

	function start_new_checkup() {
		var $button = $(this);
		$button.addClass('wds-run-test-onload');
		return $.post(ajaxurl, {
			action: 'wds-start-checkup',
			_wds_checkup_nonce: _wds_dashboard.checkup_nonce
		}, function (data) {
			if (data.success) {
				window.location.href = _wds_dashboard.health_page_url;
			} else {
				$button.removeClass('wds-run-test-onload');
			}
		}, 'json');
	}

	function start_new_lighthouse_test() {
		var $button = $(this);
		$button.addClass('wds-run-test-onload');

		return $.post(ajaxurl, {
			action: 'wds-lighthouse-start-test',
			_wds_nonce: _wds_dashboard.lighthouse_nonce
		}, function (data) {
			if (data.success) {
				window.location.href = _wds_dashboard.health_page_url;
			} else {
				$button.removeClass('wds-run-test-onload');
			}
		}, 'json');
	}

	function init() {
		reload_boxes();
		load_accordions();

		window.Wds.accordion();
		window.Wds.dismissible_message();

		$(document)
			.on('click', '.wds-start-checkup', start_new_checkup)
			.on('click', '.wds-lighthouse-start-test', start_new_lighthouse_test)
			.on('click', '.wds-activate-component', activate_component);

		$(update_checkup_progress);
		hook_checkup_accordion_item_click();
		hook_lighthouse_accordion_item_click();
	}

	$(init);
})(jQuery);
