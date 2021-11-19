;(function ($, undefined) {

	Wds.Onboard = Wds.Onboard || {
		dialog: false,
		open_dialog: function () {
			setTimeout(function () {
				if (Wds.Onboard.get_root().length) {
					SUI.openModal(Wds.Onboard.get_id(), 'container');
				}
			});
		},
		get_box_content: function () {
			return Wds.Onboard.get_root().find('.sui-box-body');
		},
		get_box_footer: function () {
			return Wds.Onboard.get_root().find('.sui-box-footer');
		},
		get_id: function () {
			return 'wds-onboarding';
		},
		get_root: function () {
			return $("#wds-onboarding");
		},
		get_checks: function () {
			return Wds.Onboard.get_root().find(":checkbox");
		},
		process_all: function () {
			var $checks = Wds.Onboard.get_checks();
			Wds.Onboard.get_root().addClass('wds-onboarding-in-progress');
			Wds.Onboard.get_box_footer().hide();
			Wds.Onboard.get_box_content().html(
				Wds.template('onboard', 'progress')
			);
			Wds.Onboard.process_next($checks.toArray(), $checks.length);
		},
		process_next: function ($items, total, processed) {
			if (!$items.length) {
				Wds.Onboard.get_box_content().find(".sui-progress-state").text(Wds.l10n('onboard', 'All done'));
				window.location.reload();
				return false;
			}

			var $item = $($items.pop()),
				processed = processed || 0,
				pct = 0,
				dfr = $.Deferred()
			;
			processed++;
			pct = (processed / total) * 100;
			Wds.update_progress_bar(Wds.Onboard.get_box_content().find(".wds-progress"), pct);

			Wds.Onboard.get_box_content().find(".sui-progress-state").text(
				$item.attr("data-processing")
			);
			$.post(ajaxurl, {
				action: "wds-boarding-toggle",
				target: $item.attr("name"),
				enable: $item.is(":checked") ? 1 : 0,
				_wds_nonce: _wds_onboard.nonce
			}).always(dfr.resolve);

			dfr.done(function () {
				Wds.Onboard.process_next($items, total, processed);
			});
		},
		skip: function () {
			$(this).html('&hellip;');
			$.post(ajaxurl, {
				action: "wds-boarding-skip"
			}).always(function () {
				window.location.reload();
				SUI.closeModal();
			});
		},
	};

	$(document).on("click", "button.wds-onboarding-setup", Wds.Onboard.process_all);
	$(document).on("click", "button.onboard-skip", Wds.Onboard.skip);
	$(Wds.Onboard.open_dialog);

})(jQuery);
