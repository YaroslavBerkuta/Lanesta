<?php
$options = empty( $_view['options'] ) ? array() : $_view['options'];
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$test_mode = smartcrawl_get_array_value( $options, 'health-test-mode' );
?>
<div class="sui-box-settings-row" id="seo-test-mode">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'SEO test mode', 'wds' ); ?>
		</label>
		<span class="sui-description">
			<?php esc_html_e( 'Choose which mode you want to use to analyze your website and get a detailed SEO report with recommendations for improvement.', 'wds' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<?php
		$this->_render( 'side-tabs', array(
			'id'    => 'wds-health-test-mode-tabs',
			'name'  => "{$option_name}[health-test-mode]",
			'value' => $test_mode,
			'tabs'  => array(
				array(
					'label'    => esc_html__( 'Lighthouse Audits', 'wds' ),
					'value'    => 'lighthouse',
					'template' => 'health/health-test-mode-lighthouse',
				),
				array(
					'label'         => esc_html__( 'Smartcrawl Checkup', 'wds' ),
					'value'         => 'seo-checkup',
					'template'      => 'description',
					'template_args' => array(
						'description' => esc_html__( 'SmartCrawl will look at your website from the perspective of a search engine (like Google) and then give you a detailed SEO report with recommendations for improvements.', 'wds' ),
					),
				),
			),
		) );
		?>
	</div>
</div>

<div class="sui-box-footer">
	<button class="sui-button sui-button-blue">
		<span class="sui-loading-text">
			<span class="sui-icon-save" aria-hidden="true"></span>
			<?php esc_html_e( 'Save Settings', 'wds' ); ?>
		</span>
		<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
	</button>
</div>
