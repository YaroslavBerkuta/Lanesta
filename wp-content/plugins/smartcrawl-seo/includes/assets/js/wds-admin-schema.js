;(function ($) {
	$(init);

	function init() {
		window.Wds.media_item_selector($('#person_portrait'));
		window.Wds.media_item_selector($('#organization_logo'));
		window.Wds.media_item_selector($('#schema_default_image'));
		window.Wds.media_item_selector($('#schema_website_logo'));
		window.Wds.media_item_selector($('#person_brand_logo'));
		window.Wds.vertical_tabs();
		window.Wds.hook_toggleables();
		window.Wds.hook_conditionals();

		$(document)
			.on('change', '.wds-schema-toggleable input[type="checkbox"]', update_schema_sub_section_visbility)
			.on('click', '.wds-disabled-component [type="submit"]', activate_schema_component)
			.on('click', '#wds-deactivate-schema-component', deactivate_schema_component)
			.on('click', '#wds-authorize-api-key', authorize_youtube_api_key)
		;

		$(update_schema_sub_section_visbility);
	}

	function deactivate_schema_component(e) {
		$(this).addClass('disabled');
		set_schema_status(e, false);
	}

	function activate_schema_component(e) {
		$(this).addClass('disabled');
		set_schema_status(e, true);
	}

	function set_schema_status(e, status) {
		e.preventDefault();
		e.stopPropagation();

		post(
			'wds-change-schema-status',
			{status: status ? '1' : '0'},
			function () {
				window.location.reload();
			}
		);
	}

	function post(action, data, callback) {
		return $.post(ajaxurl, $.extend({}, data, {
			action: action,
			_wds_nonce: _wds_schema.nonce,
		}), callback);
	}

	function authorize_youtube_api_key(e) {
		e.preventDefault();
		e.stopPropagation();

		var $key_field = $('#schema_yt_api_key'),
			$button = $(this);
		$button.addClass('disabled');
		post(
			'wds-authorize-yt-api-key',
			{key: $key_field.val()},
			function (data) {
				$button.removeClass('disabled');
				if (data.success) {
					show_yt_notice(_wds_schema.youtube_key_valid, 'success');
				} else {
					show_yt_notice(_wds_schema.youtube_key_invalid, 'error');
				}
			}
		);
	}

	function show_yt_notice(message, type) {
		Wds.show_floating_message('wds-youtube-api-key-valid', message, type);
	}

	function update_schema_sub_section_visbility() {
		$('.wds-schema-toggleable').each(function () {
			var $toggleable = $(this),
				$nested_table = $toggleable.next('tr');

			if ($toggleable.find('input[type="checkbox"]').is(':checked')) {
				$nested_table.show();
			} else {
				$nested_table.hide();
			}
		});
	}
})(jQuery);
