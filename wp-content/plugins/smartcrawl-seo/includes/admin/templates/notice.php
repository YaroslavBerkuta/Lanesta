<?php
$message = empty( $message ) ? '' : $message;
$class = empty( $class ) ? 'sui-notice-warning' : $class;
?>
<div class="wds-notice sui-notice <?php echo esc_attr( $class ); ?>">
	<div class="sui-notice-content">
		<div class="sui-notice-message">
			<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
			<p><?php echo wp_kses_post( $message ); ?></p>
		</div>
	</div>
</div>
