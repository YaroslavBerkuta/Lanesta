;(function ($) {
	$(init);

	function init() {
		window.Wds.reporting_schedule();
		window.Wds.vertical_tabs();
		window.Wds.hook_toggleables();

		$(document)
			.on('click', '.wds-disabled-component input[type="submit"]', open_progress_dialog)
			.on('click', '#wds-new-lighthouse-test-button', start_new_test)
			.on('click', '.wds-copy-audit', copy_audit)
			.on('click', '.wds-lighthouse-thumbnail-container .wds-lighthouse-screenshot', open_screenshot_zoom_dialog)
			.on('click', '.wds-lh-toggle', function (e) {
				e.stopPropagation();
				e.preventDefault();
				$(this).closest('.wds-lh-toggle-container').toggleClass('open');
			})
		;

		open_target_check_item();
		maybe_show_progress();
		setTimeout(update_remaining_cooldown_time, 60000);

		$('.wds-structured-data-check .sui-accordion-item-header .sui-button').on('click', function (event) {
			event.stopPropagation();
		});
		$('.wds-vertical-tabs').on('wds_vertical_tabs:tab_change', function (event, active_tab) {
			$(active_tab)
				.find('.wds-vertical-tab-section')
				.removeClass('hidden');
		});
	}

	function update_remaining_cooldown_time() {
		var $cooldown_notice = $('.wds-cooldown-notice');
		if (!$cooldown_notice.is(':visible')) {
			return;
		}

		var $container = $('[data-remaining-minutes]'),
			remaining_minutes = $container.data('remainingMinutes') - 1,
			$actions = $('.sui-header .sui-actions-left'),
			$tooltip = $('.sui-tooltip', $actions);

		if (remaining_minutes > 0) {
			setTimeout(update_remaining_cooldown_time, 60000);

			var cooldown_message = Wds.l10n('lighthouse', remaining_minutes === 1 ? 'cooldown_message_singular' : 'cooldown_message');
			cooldown_message = cooldown_message.replace('%s', remaining_minutes);

			$tooltip.attr('data-tooltip', cooldown_message);
			$('p', $cooldown_notice).html(cooldown_message);

			$container.data('remainingMinutes', remaining_minutes);
		} else {
			$cooldown_notice.hide();
			$tooltip.removeClass('sui-tooltip');
			$('button', $actions).prop('disabled', false);
		}
	}

	function open_screenshot_zoom_dialog(event) {
		event.preventDefault();

		var $thumbnail = $(this),
			html = $thumbnail.closest('td').find('.wds-lighthouse-screenshot-container').html();

		Wds.open_dialog('wds-lighthouse-screenshot-zoom');

		$('#wds-lighthouse-screenshot-zoom .sui-box-body').html(html);
	}

	function open_target_check_item() {
		var query = new URLSearchParams(window.location.search),
			check_id = query.get('check');
		if (!check_id) {
			return;
		}

		var $check_item = $('#' + check_id);
		if ($check_item.length && $check_item.is('.sui-accordion-item')) {
			var $admin_bar = $('#wpadminbar'),
				scroll_top = $admin_bar.length
					? $check_item.offset().top - $admin_bar.height()
					: $check_item.offset().top;

			$check_item.addClass('sui-accordion-item--open');
			$([document.documentElement, document.body]).animate({
				scrollTop: scroll_top
			}, 500);
		}
	}

	function copy_audit(event) {
		event.preventDefault();

		var $textarea = $(this).closest('.sui-box').find('.sui-box-body textarea');
		$textarea.show().trigger('select');

		try {
			document.execCommand("copy");
			Wds.show_floating_message(
				'wds-lighthouse-audit-copied',
				Wds.l10n('lighthouse', 'audit_copied'),
				'success'
			);
			return true;
		} catch (ex) {
			console.warn("Copy to clipboard failed.", ex);
			Wds.show_floating_message(
				'wds-lighthouse-audit-copied',
				Wds.l10n('lighthouse', 'audit_copy_failed'),
				'error'
			);
			return false;
		} finally {
			$textarea.hide();
		}
	}

	function start_new_test() {
		var $button = $(this);
		$button.addClass('sui-button-onload');

		post('wds-lighthouse-start-test').then(function () {
			window.location.reload();
		});
	}

	function maybe_show_progress() {
		var start_time = Wds.get('lighthouse', 'start_time');
		if (!start_time) {
			return;
		}

		Wds.open_dialog(get_progress_dialog_id());
		update_progress();
	}

	function get_progress_dialog_id() {
		return 'wds-lighthouse-progress-modal';
	}

	function open_progress_dialog(e) {
		e.preventDefault();
		Wds.open_dialog(get_progress_dialog_id());
		update_progress();
	}

	function post(action) {
		return new Promise(function (resolve, reject) {
			var request = {
				action: action,
				_wds_nonce: Wds.get('lighthouse', 'nonce')
			};

			$.post(ajaxurl, request)
				.done(function (response) {
					if (response.success) {
						resolve(
							(response || {}).data
						);
					} else {
						reject();
					}
				})
				.fail(reject);
		});
	}

	function update_progress_text(text) {
		var $dialog = $('#' + get_progress_dialog_id()),
			$progress_bar = $('.wds-progress', $dialog);

		$('span', $progress_bar.next('.sui-progress-state')).html(text);
	}

	function update_progress() {
		var progress = 0,
			remote_call_pending = false,
			$dialog = $('#' + get_progress_dialog_id()),
			$progress_bar = $('.wds-progress', $dialog);

		var interval = setInterval(function () {
			if (remote_call_pending) {
				return;
			}

			progress++;
			var visible_progress = progress > 99 ? 99 : progress;

			Wds.update_progress_bar($progress_bar, visible_progress);
			if (visible_progress === 75) {
				update_progress_text(Wds.l10n('lighthouse', 'analyzing'));
			} else if (visible_progress === 3) {
				update_progress_text(Wds.l10n('lighthouse', 'running'));
			}

			if (
				progress === 1
				|| (progress > 30 && progress % 9 === 0)
			) {
				remote_call_pending = true;
				post('wds-lighthouse-run')
					.then(function (data) {
						if ((data || {}).finished) {
							clearInterval(interval);
							Wds.update_progress_bar($progress_bar, 100);
							update_progress_text(Wds.l10n('lighthouse', 'refreshing'));
							window.location.reload();
						}
					})
					.finally(function () {
						remote_call_pending = false;
					});
			}
		}, 200);
	}
})(jQuery);
