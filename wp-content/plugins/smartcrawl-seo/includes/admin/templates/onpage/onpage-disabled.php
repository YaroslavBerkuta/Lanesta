<?php
$this->_render( 'disabled-component', array(
	'content'     => sprintf(
		'%s<br/>',
		esc_html__( 'Change the title and meta settings for your pages.', 'wds' )
	),
	'image'       => 'sitemap-disabled.svg',
	'component'   => 'onpage',
	'button_text' => esc_html__( 'Activate', 'wds' ),
) );
