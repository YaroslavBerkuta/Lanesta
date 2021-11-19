(function ($) {
	$(init);

	function init() {
		var field_template = Wds.tpl_compile(Wds.template('link_rel_attr', 'field')),
			$link_target_container = $('#wp-link-target')
				.closest('.link-target');

		if (!$link_target_container.length) {
			return;
		}

		$link_target_container
			.after(field_template({}));

		var $rel_field = $('#wp-link-rel-attributes');
		var get_link = function () {
			if (typeof tinymce !== 'undefined') {
				ed = tinymce.get(wpActiveEditor);
				if (ed && !ed.isHidden()) {
					return ed.$('a[data-wplink-edit="true"]');
				}
			}
			return null;
		};

		var originalGetAttrs = wpLink.getAttrs;
		originalGetAttrs = originalGetAttrs.bind(wpLink);
		wpLink.getAttrs = function () {
			var attrs = originalGetAttrs();
			attrs.rel = $rel_field.val().trim();

			return attrs;
		};

		var originalMceRefresh = wpLink.mceRefresh;
		originalMceRefresh = originalMceRefresh.bind(wpLink);
		wpLink.mceRefresh = function (searchStr, text) {
			originalMceRefresh(searchStr, text);

			var linkNode = get_link();
			$rel_field.val(
				linkNode ? linkNode.attr('rel') : ''
			);
		};
	}
})(jQuery);
