<?php
$active_tab = empty( $active_tab ) ? '' : $active_tab;
$is_member = empty( $is_member ) ? false : true;
$checkup_cron_enabled = Smartcrawl_Checkup_Options::is_cron_enabled() && $is_member;
$last_checked_timestamp = empty( $last_checked_timestamp ) ? '' : $last_checked_timestamp;
?>

<div class="wds-seo-checkup-stats-container">
	<?php $this->_render( 'checkup/checkup-top' ); ?>
</div>

<div class="wds-vertical-tabs-container sui-row-with-sidenav">

	<?php $this->_render( 'checkup/checkup-side-nav' ); ?>

	<?php
	$this->_render( 'vertical-tab', array(
		'tab_id'       => 'tab_checkup',
		'tab_name'     => esc_html__( 'SEO Checkup', 'wds' ),
		'button_text'  => false,
		'is_active'    => 'tab_checkup' === $active_tab,
		'tab_sections' => array(
			array(
				'section_template' => empty( $last_checked_timestamp )
					? 'checkup/checkup-no-data'
					: 'checkup/checkup-checkup',
			),
		),
	) );
	?>

	<form id="wds-reporting-form">
		<?php
		$this->_render(
			'vertical-tab-upsell',
			array(
				'tab_id'             => 'tab_reporting',
				'tab_name'           => esc_html__( 'Reporting', 'wds' ),
				'is_active'          => 'tab_reporting' === $active_tab,
				'button_text'        => false,
				'title_actions_left' => 'checkup/checkup-reporting-title-pro-tag',
				'tab_sections'       => array(
					array(
						'section_template' => 'checkup/checkup-reporting',
						'section_args'     => array(
							'checkup_cron_enabled' => $checkup_cron_enabled,
						),
					),
				),
			)
		);
		?>
	</form>

	<?php
	$this->_render( 'health/health-section-settings' );
	?>

</div>
