<?php
$is_member = empty( $_view['is_member'] ) ? false : true;
$active_tab = empty( $active_tab ) ? '' : $active_tab;
?>
<form id="wds-reporting-form">
	<?php
	$this->_render( 'vertical-tab', array(
		'tab_id'             => 'tab_reporting',
		'tab_name'           => esc_html__( 'Reporting', 'wds' ),
		'is_active'          => 'tab_reporting' === $active_tab,
		'title_actions_left' => 'lighthouse/lighthouse-reporting-title-tag',
		'tab_sections'       => array(
			array(
				'section_template' => 'lighthouse/lighthouse-reporting',
			),
		),
		'button_text'        => false,
	) );
	?>
</form>
