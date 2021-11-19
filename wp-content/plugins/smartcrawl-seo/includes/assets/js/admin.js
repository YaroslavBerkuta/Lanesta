(function ($) {

	$(function () {

		$('.toggle-contextual-help').on('click', function () {
			$('#contextual-help-link').trigger('click');
			return false;
		});

	});

})(jQuery);
