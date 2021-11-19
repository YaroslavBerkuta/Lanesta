<?php
$active_tab = empty( $active_tab ) ? '' : $active_tab;

$this->_render( 'vertical-tabs-side-nav', array(
	'active_tab' => $active_tab,
	'tabs'       => array(
		array(
			'id'   => 'tab_general',
			'name' => esc_html__( 'General', 'wds' ),
		),
		array(
			'id'   => 'tab_advanced',
			'name' => esc_html__( 'Advanced', 'wds' ),
		),
		array(
			'id'   => 'tab_types',
			'name' => esc_html__( 'Types Builder', 'wds' ),
		),
		array(
			'id'   => 'tab_settings',
			'name' => esc_html__( 'Settings', 'wds' ),
		),
	),
) );
