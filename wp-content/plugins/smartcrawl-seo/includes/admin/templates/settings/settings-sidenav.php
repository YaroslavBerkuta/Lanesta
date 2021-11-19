<?php
$show_data_settings = empty( $show_data_settings ) ? false : $show_data_settings;
$active_tab = empty( $active_tab ) ? '' : $active_tab;
$configs_available = empty( $configs_available ) ? false : $configs_available;
$import_available = empty( $import_available ) ? false : $import_available;

$tabs = array_merge(
	array(
		array(
			'id'   => 'tab_general_settings',
			'name' => esc_html__( 'General Settings', 'wds' ),
		),
	),
	$configs_available ? array(
		array(
			'id'   => 'tab_configs',
			'name' => esc_html__( 'Configs', 'wds' ),
		),
	) : array(),
	array(
		array(
			'id'   => 'tab_user_roles',
			'name' => esc_html__( 'User Roles', 'wds' ),
		),
	),
	$import_available ? array(
		array(
			'id'   => 'tab_import_export',
			'name' => esc_html__( 'Import', 'wds' ),
		),
	) : array(),
	$show_data_settings ? array(
		array(
			'id'   => 'tab_data',
			'name' => esc_html__( 'Data & Settings', 'wds' ),
		),
	) : array(),
	array(
		array(
			'id'   => 'tab_accessibility',
			'name' => esc_html__( 'Accessibility', 'wds' ),
		),
	)
);

$this->_render( 'vertical-tabs-side-nav', array(
	'active_tab' => $active_tab,
	'tabs'       => $tabs,
) );
