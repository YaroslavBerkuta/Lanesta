<?php
/**
 * @var $lighthouse_report Smartcrawl_Lighthouse_Report
 */
$lighthouse_report = empty( $lighthouse_report ) ? false : $lighthouse_report;
if ( ! $lighthouse_report ) {
	return;
}
$active_tab = empty( $active_tab ) ? '' : $active_tab;

$this->_render( 'vertical-tab', array(
	'tab_id'       => 'tab_lighthouse',
	'tab_name'     => esc_html__( 'SEO audits', 'wds' ),
	'is_active'    => 'tab_lighthouse' === $active_tab,
	'button_text'  => false,
	'tab_sections' => array(
		array(
			'section_template' => 'notice',
			'section_args'     => array(
				'class'   => 'sui-notice-error',
				'message' => $lighthouse_report->get_error_message(),
			),
		),
	),
) );
