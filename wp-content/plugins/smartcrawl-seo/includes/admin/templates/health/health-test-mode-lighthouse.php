<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$dashboard_device = Smartcrawl_Lighthouse_Options::dashboard_widget_device();
?>
	<p class="sui-description" style="margin-bottom: 30px;">
		<?php echo smartcrawl_format_link(
			esc_html__( '%s is an open-source tool from Google for improving the quality of web pages. Lighthouse runs its audits against the Homepage and will generate reports focused on improving SEO results.', 'wds' ),
			'https://developers.google.com/web/tools/lighthouse',
			esc_html__( 'Lighthouse', 'wds' ),
			'_blank'
		); ?>
	</p>

	<strong><?php esc_html_e( 'Dashboard Widget', 'wds' ); ?></strong>

	<p class="sui-description" style="margin-bottom: 10px;">
		<?php esc_html_e( 'Choose which device you want to show the SEO test results for on the Dashboard widget.', 'wds' ); ?>
	</p>

<?php $this->_render( 'side-tabs', array(
	'id'    => 'wds-lighthouse-dashboard-widget-device',
	'name'  => "{$option_name}[lighthouse-dashboard-widget-device]",
	'value' => $dashboard_device,
	'tabs'  => array(
		array(
			'label' => esc_html__( 'Desktop', 'wds' ),
			'value' => 'desktop',
		),
		array(
			'label' => esc_html__( 'Mobile', 'wds' ),
			'value' => 'mobile',
		),
	),
) );
