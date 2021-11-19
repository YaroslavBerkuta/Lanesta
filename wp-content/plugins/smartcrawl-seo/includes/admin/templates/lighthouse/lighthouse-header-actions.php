<?php
$lighthouse_report = empty( $lighthouse_report ) ? false : $lighthouse_report;
if ( ! $lighthouse_report ) {
	return;
}

if ( $lighthouse_report->has_data() && ! $lighthouse_report->has_errors() ) {
	$disabled = $lighthouse_report->is_cooling_down();
	$remaining_minutes = $lighthouse_report->get_remaining_cooldown_minutes();
	$tooltip = $lighthouse_report->is_cooling_down()
		? sprintf( esc_html__( 'SmartCrawl is just catching her breath, you can run another test in %s minutes.', 'wds' ), $remaining_minutes )
		: false;
} else {
	$disabled = false;
	$tooltip = false;
}
?>

<?php if ( $lighthouse_report->has_data() || $lighthouse_report->has_errors() ): ?>
	<span class="<?php echo $tooltip ? 'sui-tooltip sui-tooltip-constrained' : ''; ?>"
	      style="--tooltip-width: 240px;"
	      data-tooltip="<?php echo esc_attr( $tooltip ); ?>">

		<button type="submit" <?php disabled( $disabled ); ?>
		        id="wds-new-lighthouse-test-button"
		        class="sui-button sui-button-blue">
	
			<span class="sui-loading-text"><?php esc_html_e( 'New Test', 'wds' ); ?></span>
			<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
		</button>
	</span>

	<span id="wds-lighthouse-feedback-link">
		<span>
			<?php echo smartcrawl_format_link(
				esc_html__( '%s', 'wds' ),
				'https://docs.google.com/forms/d/e/1FAIpQLSde5-QH82DVBmabLqHbgImQYYjgaF0uhhAZ_LDBk81suAabIg/viewform',
				'<strong>' . esc_html__( 'Have some feedback?', 'wds' ) . '</strong>',
				'_blank'
			); ?>
		</span>
		<span><?php esc_html_e( 'Help us improve this beta feature.', 'wds' ); ?></span>		
	</span>
<?php endif; ?>
