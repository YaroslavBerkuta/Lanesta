<?php
/**
 * @var $check Smartcrawl_Lighthouse_Check
 */
$check = empty( $check ) ? false : $check;
if ( ! $check ) {
	return;
}
$testing_tool = sprintf( 'https://search.google.com/test/rich-results?url=%s&user_agent=2', urlencode( home_url() ) );
$is_structured_data_check = $check->get_id() === Smartcrawl_Lighthouse_Structured_Data_Check::ID;
if ( $is_structured_data_check ) {
	$check_style_class = 'sui-default wds-structured-data-check';
	$check_icon_class = 'sui-icon-info';
} else {
	$check_style_class = $check->is_passed() ? 'sui-success' : 'sui-warning';
	$check_icon_class = $check->is_passed() ? 'sui-icon-check-tick' : 'sui-icon-warning-alert';
}

if ( ! $check->get_weight() && ! $is_structured_data_check ) {
	return;
}
$action_button = $check->get_action_button();
?>

<div id="<?php echo esc_attr( $check->get_id() ); ?>"
     class="sui-accordion-item <?php echo esc_attr( $check_style_class ); ?>">
	<div class="sui-accordion-item-header">
		<div class="sui-accordion-item-title sui-accordion-col-4">
			<span aria-hidden="true"
			      class="<?php echo esc_attr( $check_style_class ); ?> <?php echo esc_attr( $check_icon_class ); ?>"></span>
			<?php echo esc_html( $check->get_title() ); ?>
		</div>

		<div class="sui-accordion-col-4">
			<?php if ( $is_structured_data_check ): ?>
				<a href="<?php echo esc_attr( $testing_tool ); ?>"
				   target="_blank"
				   class="sui-button sui-button-ghost">
					<span class="sui-icon-target" aria-hidden="true"></span>
					<?php esc_html_e( 'Testing Tool', 'wds' ); ?>
				</a>
			<?php endif; ?>

			<span class="sui-accordion-open-indicator">
				<span aria-hidden="true" class="sui-icon-chevron-down"></span>
				<button type="button"
				        class="sui-screen-reader-text">

					<?php printf(
						esc_html__( 'Expand %s check', 'wds' ),
						esc_html( $check->get_title() )
					); ?>
				</button>
			</span>
		</div>
	</div>

	<div class="sui-accordion-item-body">
		<div class="sui-box">
			<div class="sui-box-body">
				<?php echo $check->get_description(); ?>
				<label>
					<textarea style="display: none;"><?php
						echo esc_textarea( $check->get_copy_description() );
						?></textarea>
				</label>
			</div>

			<?php if ( ! $check->is_passed() ): ?>
				<div class="sui-box-footer">
					<button class="sui-button sui-button-ghost sui-tooltip wds-copy-audit"
					        data-tooltip="<?php esc_html_e( 'Copy audit details', 'wds' ); ?>">
						<?php esc_html_e( 'Copy Audit', 'wds' ); ?>
					</button>

					<div class="sui-actions-right">
						<?php if ( $action_button ): ?>
							<?php echo wp_kses_post( $action_button ); ?>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
