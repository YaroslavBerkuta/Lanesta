<style type="text/css">
	.wds-native-dismiss,
	.wds-inline-notice-link {
		vertical-align: -4px;
		font-size: 12px;
		margin-left: 8px;
		color: inherit;
		text-decoration: none;
	}

	.wds-native-dismiss {
		font-weight: bold;
	}

	.wds-native-dismissible-notice button.notice-dismiss[disabled] {
		cursor: wait;
	}

	.wds-native-dismissible-notice button.notice-dismiss[disabled]:before {
		color: #e7e7e7;
	}
</style>
<script type="application/javascript">
	jQuery(function ($) {
		$(document).on('click', '.wds-native-dismissible-notice .wds-native-dismiss,.wds-native-dismissible-notice .notice-dismiss', function (e) {
			e.preventDefault();
			var $notice = $(this).closest('.wds-native-dismissible-notice'),
				$dismiss_buttons = $('.wds-native-dismissible-notice .notice-dismiss'),
				message_key = $notice.data('messageKey');
			$notice.remove();

			$dismiss_buttons.prop('disabled', true);

			$.post(
				ajaxurl,
				{
					action: 'wds_dismiss_message',
					message: message_key,
					_wds_nonce: '<?php echo esc_js( wp_create_nonce( 'wds-admin-nonce' ) ); ?>'
				},
				function () {
					$dismiss_buttons.prop('disabled', false);
				},
				'json'
			);
		});
	});
</script>
