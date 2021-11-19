(function ($, undefined) {
	window.Wds = window.Wds || {};

	$(init);

	function init() {
		if ($('#wds-welcome-modal').length) {
			Wds.open_dialog('wds-welcome-modal');
		}

		$(document)
			.on('click', '#wds-welcome-modal-get-started', welcome_modal_get_started)
			.on('click', '#wds-welcome-modal-skip', welcome_modal_skip);
	}

	function welcome_modal_get_started(e) {
		e.preventDefault();
		e.stopPropagation();

		var $button = $(this);
		$button.addClass('sui-button-onload');
		$button.prop('disabled', true);

		$.post(ajaxurl, {
			action: 'wds-try-latest-features',
			_wds_nonce: _wds_welcome.nonce,
		}, function (response) {
			if (response.success) {
				window.location.href = response.data.redirect_url;
			}
		});
	}

	function welcome_modal_skip(e) {
		e.preventDefault();
		e.stopPropagation();

		var $link = $(this).text('...');

		$.post(ajaxurl, {
			action: 'wds-skip-latest-features',
			_wds_nonce: _wds_welcome.nonce,
		}, function (response) {
			if (response.success) {
				Wds.close_dialog();
			}
		});
	}
})(jQuery);
