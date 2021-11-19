<?php
$this->_render( 'disabled-component', array(
	'content'      => sprintf(
		'%s<br/>',
		esc_html__( 'Quickly add Schema to your pages to help Search Engines understand and show your content better.', 'wds' )
	),
	'image'        => 'sitemap-disabled.svg',
	'component'    => 'schema',
	'button_text'  => esc_html__( 'Activate', 'wds' ),
	'nonce_action' => 'wds-schema-nonce',
) );
