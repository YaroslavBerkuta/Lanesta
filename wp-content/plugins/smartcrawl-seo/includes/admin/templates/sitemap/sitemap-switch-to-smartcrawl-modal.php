<?php

$this->_render( 'modal', array(
	'id'            => 'wds-switch-to-smartcrawl-modal',
	'title'         => esc_html__( 'Are you sure?', 'wds' ),
	'description'   => esc_html__( "Please confirm that you wish to switch to SmartCrawl's powerful sitemap. You can switch back to the WordPress core sitemap at anytime.", 'wds' ),
	'body_template' => 'sitemap/sitemap-switch-to-smartcrawl-modal-body',
	'small'         => true,
) );
