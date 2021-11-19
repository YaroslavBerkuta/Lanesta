<?php
$active_tab = empty( $active_tab ) ? '' : $active_tab;
$last_checked_timestamp = empty( $last_checked_timestamp ) ? '' : $last_checked_timestamp;
if ( empty( $last_checked_timestamp ) ) {
	$issue_count = 0;
} else {
	$issue_count = empty( $issue_count ) ? 0 : $issue_count;
}
$is_member = empty( $is_member ) ? false : true;
$checkup_cron_enabled = Smartcrawl_Checkup_Options::is_cron_enabled() && $is_member;

$this->_render( 'vertical-tabs-side-nav', array(
	'active_tab' => $active_tab,
	'tabs'       => array(
		array(
			'id'        => 'tab_checkup',
			'name'      => esc_html__( 'SEO Checkup', 'wds' ),
			'tag_value' => $issue_count > 0 ? $issue_count : false,
			'tag_class' => 'sui-tag-warning',
		),
		array(
			'id'   => 'tab_reporting',
			'name' => esc_html__( 'Reporting', 'wds' ),
			'tick' => $checkup_cron_enabled,
		),
		array(
			'id'   => 'tab_settings',
			'name' => esc_html__( 'Settings', 'wds' ),
		),
	),
) );
