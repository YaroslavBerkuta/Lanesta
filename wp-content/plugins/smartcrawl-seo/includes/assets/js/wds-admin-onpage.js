;(function ($) {

	var selectors = {
		title_field: ':text[name*="[title-"]',
		desc_field: 'textarea[name*="[metadesc-"]',
		preview: '.wds-preview-container'
	};

	/**
	 * Wraps a raw notice string with appropriate markup
	 *
	 * @param {String} str Raw notice
	 *
	 * @return {String} Notice markup
	 */
	function to_warning_string(str) {
		if (!str) return '';
		var template = Wds.tpl_compile(Wds.template('onpage', 'notice'));
		return template({
			message: str
		});
	}

	function toggle_archive_status() {
		var $checkbox = $(this),
			$accordion_section = $checkbox.closest('.sui-accordion-item'),
			disabled_class = 'sui-accordion-item--disabled',
			open_class = 'sui-accordion-item--open';

		if (!$checkbox.is(':checked')) {
			$accordion_section.removeClass(open_class).addClass(disabled_class);
		} else {
			$accordion_section.removeClass(disabled_class);
		}
	}

	function save_static_home_settings() {
		var $button = $(this),
			form_data = $(':input', '#tab_static_homepage').serialize(),
			params = add_query_params(form_data, {
				action: "wds-onpage-save-static-home",
				_wds_nonce: _wds_onpage.nonce
			});

		$button.addClass('sui-button-onload');
		$.post(ajaxurl, params, 'json').done(function (rsp) {
			$button.removeClass('sui-button-onload');
			window.location.href = add_query_params(window.location.href, {
				"settings-updated": "true"
			});
		});
	}

	function add_query_params(base, params) {
		return base + '&' + $.param(params);
	}

	function init_onpage() {
		$(document).on('click', '.wds-save-static-home-settings', save_static_home_settings);

		// Also update on init, because of potential hash change
		window.Wds.macro_dropdown();
		window.Wds.vertical_tabs();

		var $tab_status_checkboxes = $('.sui-accordion-item-header input[type="checkbox"]');
		$tab_status_checkboxes.each(function () {
			toggle_archive_status.apply($(this));
		});
		$tab_status_checkboxes.on('change', toggle_archive_status);
	}

	function handle_accordion_item_click() {
		var $accordion_item = $(this).closest('.sui-accordion-item');

		// Keep one section open at a time
		$('.sui-accordion-item--open').not($accordion_item).removeClass('sui-accordion-item--open');
	}

	function update_sitemap_warning() {
		var $checkbox = $(this);
		var $notice = $checkbox
			.closest('.wds-toggle')
			.find('.sui-description .sui-notice');

		if (!$notice.length) {
			return;
		}

		$notice.toggleClass('hidden', $checkbox.is(':checked'));
	}

	function hook_preview_and_indicators() {
		var has_static_homepage = (Wds.randomPosts || {})['static-home'] || false;
		if (has_static_homepage) {
			hook_preview_and_indicators_for_tab('tab_static_homepage', function (static_homepage) {
				return function (value) {
					return Wds.macroReplacement.replace(value, static_homepage);
				};
			}, Wds.randomPosts);
		} else {
			var home_url = Wds.get('onpage', 'home_url');
			hook_preview_and_indicators_for_tab('tab_homepage', function () {
				return function (value) {
					return Wds.macroReplacement.do_replace(value, {});
				};
			}, {home: {url: home_url}});
		}
		hook_preview_and_indicators_for_tab('tab_post_types', function (random_post) {
			return function (value) {
				return Wds.macroReplacement.replace(value, random_post)
			};
		}, Wds.randomPosts);
		hook_preview_and_indicators_for_tab('tab_taxonomies', function (random_term) {
			return function (value) {
				return Wds.macroReplacement.replace_term_macros(value, random_term);
			};
		}, Wds.randomTerms);
		hook_preview_and_indicators_for_tab('tab_archives', function (random_item) {
			return function (value) {
				return Wds.macroReplacement.do_replace(value, {}, random_item.replacements);
			};
		}, Wds.get('onpage', 'random_archives'));
		hook_preview_and_indicators_for_tab('tab_buddypress', function (random_item) {
			return function (value) {
				return Wds.macroReplacement.do_replace(value, {}, random_item.replacements);
			};
		}, Wds.get('onpage', 'random_buddypress'));
	}

	function hook_preview_and_indicators_for_tab(tab_id, get_replacement_function, random_items) {
		$('#' + tab_id + ' input[type="text"][id^="title-"]').each(function (index, input) {
			var $title = $(input),
				id = $title.attr('id'),
				$container = $title.closest('[data-type]'),
				$description = $('[id^="metadesc-"]', $container),
				type = id.replace('title-', ''),
				random_item = (random_items || {})[type];

			if (random_item) {
				var replace_macros = get_replacement_function(random_item, type),
					title_indicator = new Wds.OptimumLengthIndicator($title, replace_macros, {
						lower: parseInt(_wds_onpage.title_min, 10),
						upper: parseInt(_wds_onpage.title_max, 10),
						default_value: $title.attr('placeholder')
					}),
					desc_indicator = new Wds.OptimumLengthIndicator($description, replace_macros, {
						lower: parseInt(_wds_onpage.metadesc_min, 10),
						upper: parseInt(_wds_onpage.metadesc_max, 10),
						default_value: $description.attr('placeholder')
					});

				title_indicator.update_indicator();
				desc_indicator.update_indicator();

				var update_preview = get_update_preview_function(replace_macros, get_random_item_url(random_item));

				update_preview.apply($title);
				update_preview.apply($description);

				$title.on('input propertychange', update_preview);
				$description.on('input propertychange', update_preview);
			}
		});
	}

	function get_random_item_url(item) {
		if (item.url) {
			return item.url;
		}

		if (item.get_permalink) {
			return item.get_permalink();
		}

		return '';
	}

	function get_update_preview_function(replace_macros, url) {
		return function () {
			var $container = $(this).closest('[data-type]'),
				$title = $container.find('[id^="title-"]'),
				$description = $container.find('[id^="metadesc-"]'),
				template = Wds.tpl_compile(Wds.template('onpage', 'preview')),
				title_max_length = Wds.get('onpage', 'title_max_length'),
				metadesc_max_length = Wds.get('onpage', 'metadesc_max_length'),
				promises = [];

			var title_value = $title.val();
			if (!title_value) {
				title_value = $title.attr('placeholder');
			}
			promises.push(replace_macros(title_value));

			var desc_value = $description.val();
			if (!desc_value) {
				desc_value = $description.attr('placeholder');
			}
			promises.push(replace_macros(desc_value));

			Promise.all(promises).then(function (values) {
				var markup = template({
					link: url,
					title: Wds.String_Utils.process_string(values[0], title_max_length),
					description: Wds.String_Utils.process_string(values[1], metadesc_max_length)
				});

				$container.find('.wds-preview-container').replaceWith(markup);
			});
		};
	}

	function init() {
		init_onpage();
		hook_preview_and_indicators();
		$('.sui-accordion-item-header')
			.off('click.sui.accordion')
			.on('click.sui.accordion', handle_accordion_item_click);
		Wds.hook_conditionals();
		$('[value^="meta_robots-noindex-"]').on('change', update_sitemap_warning);
	}

	// Boot
	$(init);

})(jQuery);
