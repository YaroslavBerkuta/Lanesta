import {render} from "react-dom";
import React from "react";
import ErrorBoundary from "./components/error-boundry";
import DataResetButton from "./components/settings/data-reset-button";
import MultisiteResetButton from "./components/settings/multisite-reset-button";

(function ($) {
	const resetButton = document.getElementById('wds-data-reset-button-placeholder');
	if (resetButton) {
		render(
			<ErrorBoundary><DataResetButton/></ErrorBoundary>,
			resetButton
		);
	}

	const multisiteResetButton = document.getElementById('wds-multisite-reset-button-placeholder');
	if (multisiteResetButton) {
		render(
			<ErrorBoundary><MultisiteResetButton/></ErrorBoundary>,
			multisiteResetButton
		);
	}

	window.Wds = window.Wds || {};

	function add_custom_meta_tag_field() {
		var $this = $(this),
			$container = $this.closest('.wds-custom-meta-tags'),
			$new_input = $container.find('.wds-custom-meta-tag:first-of-type').clone();

		$new_input.insertBefore($this);
		$new_input.find('input').val('').trigger('focus');
	}

	function init() {
		window.Wds.styleable_file_input();
		$(document).on('click', '.wds-custom-meta-tags button', add_custom_meta_tag_field);

		Wds.vertical_tabs();
	}

	$(init);
})(jQuery);
