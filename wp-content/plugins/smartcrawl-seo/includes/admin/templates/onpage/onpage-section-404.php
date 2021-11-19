<?php
$macros = Smartcrawl_Onpage_Settings::get_general_macros();
$this->_render( 'onpage/onpage-preview' );

$this->_render( 'onpage/onpage-general-settings', array(
	'title_key'       => 'title-404',
	'description_key' => 'metadesc-404',
	'macros'          => $macros,
) );
