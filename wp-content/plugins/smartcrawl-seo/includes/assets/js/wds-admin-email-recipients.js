;(function ($) {
	Wds.EmailRecipients = Wds.EmailRecipients || {
		add_new: function () {
			var $container = $(this).closest('.wds-recipients'),
				$modal = $(this).closest('.sui-modal'),
				field_name = $container.data('fieldName'),
				index = $container.find('.wds-recipient').length,
				$name = $modal.find('.wds-recipient-name'),
				$email = $modal.find('.wds-recipient-email'),
				name = $name.val(),
				email = $email.val(),
				template = Wds.tpl_compile(Wds.template('email_recipients', 'recipient'));

			var is_valid = Wds.EmailRecipients.validate($container);
			if (is_valid) {
				var markup = template({
					field_name: field_name,
					index: index,
					name: name,
					email: email
				});
				$container.prepend(markup);
				$modal.find('[data-modal-close]').first().trigger('click');
				$name.val('');
				$email.val('');
				Wds.show_floating_message('wds-email-recipient-notice', name + window.Wds.l10n('email_recipients', 'recipient_added'));
				Wds.EmailRecipients.toggle_notice_visibility();
			}
		},

		validate: function ($container) {
			var is_valid = true;
			$('.sui-form-field', $container).each(function () {
				var $form_field = $(this);
				var input_value = $('input', $form_field).val();
				if (!input_value.trim() || $('<div>').html(input_value).text() !== input_value) {
					is_valid = false;
					$form_field.addClass('sui-form-field-error');
				}
			});

			return is_valid;
		},

		remove: function (e) {
			e.preventDefault();
			$(this).closest('.wds-recipient').remove();

			Wds.EmailRecipients.toggle_notice_visibility();
		},

		toggle_notice_visibility: function () {
			$('.wds-recipients-notice').toggleClass('hidden', !!$('.wds-recipient').length);
		},

		remove_error: function () {
			$(this).closest('.sui-form-field').removeClass('sui-form-field-error');
		},

		submit_dialog_form_on_enter: function (e) {
			var $button = $(this).find('.wds-add-email-recipient'),
				key = e.which;

			if ($button.length && 13 === key) {
				e.preventDefault();
				e.stopPropagation();

				$button.trigger('click');
			}
		},

		init: function () {
			$(document)
				.on('click', '.wds-add-email-recipient', Wds.EmailRecipients.add_new)
				.on('click', '.wds-remove-email-recipient', Wds.EmailRecipients.remove)
				.on('focus', '.wds-recipient-name,.wds-recipient-email', Wds.EmailRecipients.remove_error)
				.on('keydown', '.wds-recipients .sui-modal', Wds.EmailRecipients.submit_dialog_form_on_enter);
		}
	};

	Wds.EmailRecipients.init();
})(jQuery);
