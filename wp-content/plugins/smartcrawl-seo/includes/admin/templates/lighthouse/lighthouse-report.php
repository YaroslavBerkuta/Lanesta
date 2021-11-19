<?php
/**
 * @var $lighthouse_report Smartcrawl_Lighthouse_Report
 */
$lighthouse_report = empty( $lighthouse_report ) ? false : $lighthouse_report;
if ( ! $lighthouse_report ) {
	return;
}
$active_tab = empty( $active_tab ) ? '' : $active_tab;
$is_member = empty( $_view['is_member'] ) ? false : true;
?>

<?php if ( $lighthouse_report->is_cooling_down() ) {
	$remaining_minutes = $lighthouse_report->get_remaining_cooldown_minutes(); ?>
	<div data-remaining-minutes="<?php echo esc_attr( $remaining_minutes ); ?>">
		<?php $this->_render( 'notice', array(
			'class'   => 'sui-notice-grey wds-cooldown-notice',
			'message' => sprintf(
				_n(
					esc_html__( 'SmartCrawl is just catching her breath - you can run another test in %s minute.', 'wds' ),
					esc_html__( 'SmartCrawl is just catching her breath - you can run another test in %s minutes.', 'wds' ),
					$remaining_minutes
				),
				"<span>$remaining_minutes</span>"
			),
		) ); ?>
	</div>
<?php } ?>

<div id="tab_lighthouse"
     class="wds-vertical-tab-section wds-lighthouse-device-<?php echo $lighthouse_report->get_device(); ?>">

	<?php $this->_render( 'lighthouse/lighthouse-screenshot-zoom-modal' ); ?>

	<?php foreach ( $lighthouse_report->get_groups() as $group_id => $lighthouse_group ) {
		$this->_render( 'vertical-tab', array(
			'tab_id'       => $group_id,
			'tab_name'     => $lighthouse_group->get_label(),
			'is_active'    => 'tab_lighthouse' === $active_tab,
			'button_text'  => false,
			'tab_sections' => array(
				array(
					'section_template' => 'lighthouse/lighthouse-report-group',
					'section_args'     => array(
						'lighthouse_group' => $lighthouse_group,
					),
				),
			),
		) );
	} ?>

	<?php if ( ! $is_member ): ?>
		<div class="wds-vertical-tab-section <?php echo 'tab_lighthouse' === $active_tab ? '' : 'hidden'; ?>">
			<div id="wds-lighthouse-report-upsell-notice">
				<?php $this->_render( 'notice', array(
					'class'   => 'sui-notice-purple',
					'message' => sprintf(
						'%s<br/> <a target="_blank" class="sui-button sui-button-purple" href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_lighthouse_report_upsell_notice">%s</a>',
						esc_html__( 'Upgrade to Pro to schedule automated tests and send white label email reports directly to your clients. Never miss a beat with your search engine optimization.', 'wds' ),
						esc_html__( 'Try it for FREE today', 'wds' )
					),
				) ); ?>
			</div>
		</div>
	<?php endif; ?>
</div>
