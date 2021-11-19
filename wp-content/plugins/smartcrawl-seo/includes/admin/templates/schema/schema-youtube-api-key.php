<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$schema_enable_yt_api = empty( $schema_enable_yt_api ) ? false : $schema_enable_yt_api;
$schema_yt_api_key = empty( $schema_yt_api_key ) ? '' : $schema_yt_api_key;

$this->_render( 'side-tabs', array(
	'id'    => 'wds-yt-api-key-tabs',
	'name'  => "{$option_name}[schema_enable_yt_api]",
	'value' => empty( $schema_enable_yt_api ) ? '' : '1',
	'tabs'  => array(
		array(
			'value' => '',
			'label' => esc_html__( 'Disconnect Youtube', 'wds' ),
		),
		array(
			'value'         => '1',
			'label'         => esc_html__( 'Connect with Youtube', 'wds' ),
			'template'      => 'schema/schema-youtube-api-key-field',
			'template_args' => array(
				'schema_yt_api_key' => $schema_yt_api_key,
			),
		),
	),
) );
