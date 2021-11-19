;(function ($) {

	var _analysis_posts = [],
		_analysis_errors = []
	;

	function analyse_posts() {
		if (!_analysis_posts.length) {
			return false;
		}

		var pid = _analysis_posts.shift();
		if (!pid) {
			return false;
		}

		return analyse_post(pid).always(function () {
			setTimeout(analyse_posts, Wds.get('post_list', 'analyse_posts_delay'));
		});
	}

	function analyse_post(pid, forced) {
		forced = !!forced;
		var $row = $(":checkbox[name='post[]'][value='" + pid + "']").closest('tr'),
			$seo = $row.find('td.seo.column-seo'),
			$rdb = $row.find('td.readability.column-readability'),
			action = 'wds-analysis-' + (forced ? 'recheck' : 'get-markup'),
			already_requested = _analysis_errors.indexOf(pid) >= 0,
			handle_error = function () {
				var in_queue = _analysis_posts.indexOf(pid) >= 0;
				if (!forced && !already_requested) {
					_analysis_errors.push(pid);
				}
				if (!forced && !in_queue && !already_requested) {
					_analysis_posts.push(pid);
				}
				if (forced || already_requested) {
					console.log(forced, already_requested);
					$seo.add($rdb).html(get_analysis_error_msg());
				}
			}
		;

		if (!$seo.length && !$rdb.length) {
			// No need to make the ajax call if the seo and readability columns are not even visible
			return $.Deferred().resolve();
		}

		if (!forced && !already_requested && !$seo.find(".wds-status-invalid").length && !$rdb.find(".wds-status-invalid").length) {
			var dfr = $.Deferred();
			setTimeout(dfr.resolve);
			return dfr.promise();
		}
		$row.find('[data-hasqtip]').qtip('destroy', true);
		$seo.add($rdb).html('<div class="wds-analysis-checking"><span>' + Wds.l10n('analysis', 'Checking') + '</span></div>');
		return $.post(ajaxurl, {
			action: action,
			post_id: pid,
			_wds_nonce: Wds.get('post_list', 'nonce')
		})
			.done(function (rsp) {
				if (!(rsp || {}).success) {
					handle_error();
					return false;
				}

				$seo.html(((rsp || {}).data || {}).seo || get_analysis_error_msg())
				$rdb.html(((rsp || {}).data || {}).readability || get_analysis_error_msg())

				create_analysis_qtips(
					$row.find('.wds-analysis-details')
				);
			})
			.fail(function () {
				handle_error();
			})
			;
	}

	function get_analysis_error_msg() {
		return '<div class="wds-analysis wds-status-error"><span>' +
			Wds.l10n('analysis', 'Error') +
			'</span></div>';
	}

	function create_analysis_qtips($element) {
		$element.each(function () {
			var $this = $(this);

			$this.siblings('.wds-analysis').qtip({
				style: {
					classes: 'wds-qtip qtip-rounded'
				},
				position: {
					my: 'top center',
					at: 'bottom center'
				},
				content: {
					text: $this
				}
			});
		});
	}

	function handle_analysis_click(e) {
		if (e && e.preventDefault) e.preventDefault();
		if (e && e.stopPropagation) e.stopPropagation();

		var $row = $(this).closest('tr'),
			pid = $row.find(':checkbox[name="post[]"]').val()
		;
		if (!pid) return false;

		analyse_post(pid, true);

		return false;
	}

	function initialize_analysis_post_ids() {
		var $posts = $(":checkbox[name='post[]']");
		$posts.each(function () {
			var $me = $(this),
				pid = $me.val()
			;

			var analysis_columns_available = $me.closest('tr').find('.wds-analysis:visible').length;
			if (analysis_columns_available && pid) {
				_analysis_posts.push(pid);
			}
		});
	}

	function populate_quick_edit_fields() {
		var id = inlineEditPost.getId(this),
			loading = Wds.l10n('post_list', 'loading'),
			$quick_edit_fields = $(".smartcrawl_title:visible, .smartcrawl_metadesc:visible, .smartcrawl_focus:visible")
		;

		if (!$quick_edit_fields.length) {
			return;
		}

		setTimeout(function () {
			$quick_edit_fields.attr("placeholder", loading);
		}); // Just move off stack
		$.post(ajaxurl, {
			"action": "wds_get_meta_fields",
			"id": id,
			"_wds_nonce": Wds.get('post_list', 'nonce')
		}, function (data) {
			$quick_edit_fields.attr("placeholder", "");
			if (!data) {
				return false;
			}
			if ("title" in data && data.title) {
				$(".smartcrawl_title:visible")
					.val(data.title)
				;
			}
			if ("description" in data && data.description) {
				$(".smartcrawl_metadesc:visible")
					.val(data.description)
				;
			}
			if ("focus" in data && data.focus) {
				$(".smartcrawl_focus:visible")
					.val(data.focus)
				;
			}
		}, "json");
	}

	function toggle_seo_details(e) {
		e.preventDefault();
		e.stopPropagation();

		var $link = $(this),
			$details = $link.closest('.wds-meta-details');

		$details.toggleClass('wds-meta-details-open');
	}

	function init() {
		initialize_analysis_post_ids();
		analyse_posts();
		create_analysis_qtips(
			$('.wds-analysis-details')
		);

		$(document)
			.on('click', '.wds-analysis', handle_analysis_click)
			.on('click', 'td.column-title', '.editinline', populate_quick_edit_fields)
			.on('click', '.wds-meta-details a', toggle_seo_details)
		;

		$(document).ajaxSuccess(function (event, jqXHR, ajaxOptions, data) {
			if (data.startsWith && data.trim().startsWith('<tr')) {
				var post_id = (new URLSearchParams(ajaxOptions.data)).get('post_ID');
				if (post_id) {
					setTimeout(function () {
						var $row = $('#post-' + post_id);
						create_analysis_qtips($row.find('.wds-analysis-details'));
					}, 500);
				}
			}
		});
	}

	return $(init);

})(jQuery);
