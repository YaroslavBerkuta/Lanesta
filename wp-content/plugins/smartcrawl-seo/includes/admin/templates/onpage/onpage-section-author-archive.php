<?php
$meta_robots_author = empty( $meta_robots_author ) ? '' : $meta_robots_author;
$macros = array_merge(
	Smartcrawl_Onpage_Settings::get_author_macros(),
	Smartcrawl_Onpage_Settings::get_general_macros()
);

$this->_render( 'onpage/onpage-preview' );

$this->_render( 'onpage/onpage-general-settings', array(
	'title_key'       => 'title-author',
	'description_key' => 'metadesc-author',
	'macros'          => $macros,
) );

$this->_render( 'onpage/onpage-og-twitter', array(
	'for_type' => 'author',
	'macros'   => $macros,
) );

$this->_render( 'onpage/onpage-meta-robots', array(
	'items' => $meta_robots_author,
) );
