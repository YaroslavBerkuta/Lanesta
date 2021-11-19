<?php
$meta_robots_date = empty( $meta_robots_date ) ? array() : $meta_robots_date;
$macros = array_merge(
	Smartcrawl_Onpage_Settings::get_date_macros(),
	Smartcrawl_Onpage_Settings::get_general_macros()
);

$this->_render( 'onpage/onpage-preview' );

$this->_render( 'onpage/onpage-general-settings', array(
	'title_key'       => 'title-date',
	'description_key' => 'metadesc-date',
	'macros'          => $macros,
) );

$this->_render( 'onpage/onpage-og-twitter', array(
	'for_type' => 'date',
	'macros'   => $macros,
) );

$this->_render( 'onpage/onpage-meta-robots', array(
	'items' => $meta_robots_date,
) );
