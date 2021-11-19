<?php $this->_render( 'modal', array(
	'id'                      => 'wds-lighthouse-progress-modal',
	'title'                   => esc_html__( 'SEO Test in progress', 'wds' ),
	'description'             => esc_html__( "Your SEO test is in progress, please wait a few moments …", 'wds' ),
	'header_actions_template' => 'noop',
	'body_template'           => 'lighthouse/lighthouse-progress-modal-body',
) );
