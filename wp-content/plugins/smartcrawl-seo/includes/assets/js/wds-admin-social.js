(function ($) {
	window.Wds = window.Wds || {};

	function init() {
		window.Wds.hook_conditionals();
		window.Wds.hook_toggleables();
		window.Wds.media_item_selector($('#organization_logo'));
		window.Wds.vertical_tabs();
	}

	$(init);
})(jQuery);
