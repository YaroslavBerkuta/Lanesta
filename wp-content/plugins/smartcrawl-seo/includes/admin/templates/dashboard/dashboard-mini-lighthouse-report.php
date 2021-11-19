<?php
/**
 * @var $lighthouse_report Smartcrawl_Lighthouse_Report
 */
$lighthouse_report = empty( $lighthouse_report ) ? false : $lighthouse_report;
if ( ! $lighthouse_report ) {
	return;
}
$page_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH );
$reporting_enabled = Smartcrawl_Lighthouse_Options::is_cron_enabled();
?>

<?php if ( ! $lighthouse_report->get_failed_audits_count() ): ?>
	<?php $this->_render( 'notice', array(
		'message' => esc_html__( 'You have no failed SEO audits. Awesome work!', 'wds' ),
		'class'   => 'sui-notice-success',
	) ); ?>
<?php else: ?>
	<div class="sui-accordion sui-accordion-flushed">
		<?php foreach ( $lighthouse_report->get_groups() as $group ): ?>
			<?php foreach ( $group->get_checks() as $check ): ?>
				<?php if ( $check->is_passed() ) {
					continue;
				} ?>

				<div id="<?php echo esc_attr( $check->get_id() ); ?>" class="sui-accordion-item sui-warning">
					<div class="sui-accordion-item-header">
						<div class="sui-accordion-item-title sui-accordion-col-4">
							<span aria-hidden="true"
							      class="sui-warning sui-icon-info"></span>
							<?php echo esc_html( $check->get_title() ); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="wds-view-report wds-space-between">
	<a href="<?php echo esc_attr( $page_url ); ?>"
	   aria-label="<?php esc_html_e( 'View report', 'wds' ); ?>"
	   class="sui-button sui-button-ghost">

		<span class="sui-icon-eye" aria-hidden="true"></span> <?php esc_html_e( 'View Report', 'wds' ); ?>
	</a>
	<small>
		<?php echo empty( $reporting_enabled )
			? esc_html__( 'Automatic checkups are disabled', 'wds' )
			: esc_html__( 'Automatic checkups are enabled', 'wds' ); ?>
	</small>
</div>
