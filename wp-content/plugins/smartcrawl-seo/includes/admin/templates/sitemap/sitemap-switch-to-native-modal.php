<?php

$this->_render( 'modal', array(
	'id'            => 'wds-switch-to-native-modal',
	'title'         => esc_html__( 'Are you sure?', 'wds' ),
	'description'   => esc_html__( 'The powerful SmartCrawl sitemap ensures search engines index all of your posts and pages. Are you sure you wish to switch to the WordPress core sitemap?', 'wds' ),
	'body_template' => 'sitemap/sitemap-switch-to-native-modal-body',
	'small'         => true,
) );
