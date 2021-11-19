<?php
$message = empty( $message ) ? '' : $message;
$class = empty( $class ) ? 'sui-notice-warning' : $class;
$key = empty( $key ) ? '' : $key;

if ( ! $message ) {
	return;
}

$dismissed_messages = get_user_meta( get_current_user_id(), 'wds_dismissed_messages', true );
$is_message_dismissed = smartcrawl_get_array_value( $dismissed_messages, $key ) === true;
?>
<?php if ( ! $is_message_dismissed ) : ?>
	<div class="wds-notice sui-notice <?php echo esc_attr( $class ); ?> <?php echo esc_attr( $key ); ?>"
	     data-key="<?php echo esc_attr( $key ); ?>">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
				<p>
					<?php echo wp_kses_post( $message ); ?>
					<span class="wds-notice-wrap">
						<a href="#" class="wds-notice-dismiss"
						   aria-label="<?php esc_html_e( 'Dismiss notice', 'wds' ); ?>">
							<?php esc_html_e( 'Dismiss', 'wds' ); ?>
						</a>
					</span>
				</p>
			</div>
		</div>
	</div>
<?php endif; ?>
