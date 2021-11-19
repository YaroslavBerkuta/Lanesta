<?php
$title_key = empty( $title_key ) ? '' : $title_key;
$description_key = empty( $description_key ) ? '' : $description_key;

$title_label_desc = empty( $title_label_desc )
	? esc_html__( 'Choose the variables from which SmartCrawl will automatically generate your SEO title from.', 'wds' ) : $title_label_desc;
$title_field_desc = empty( $title_field_desc )
	? '' : $title_field_desc;
$meta_label_desc = empty( $meta_label_desc )
	? esc_html__( 'A title needs a description. Choose the variables to automatically generate a description from.', 'wds' ) : $meta_label_desc;
$meta_field_desc = empty( $meta_field_desc )
	? '' : $meta_field_desc;

$options = empty( $_view['options'] ) ? array() : $_view['options'];

$title = $title_key
	? smartcrawl_get_array_value( $options, $title_key )
	: '';
$description = $description_key
	? smartcrawl_get_array_value( $options, $description_key )
	: '';
$macros = empty( $macros ) ? array() : $macros;

$this->_render( 'onpage/onpage-general-settings-inner', array(
	'title_key'        => $title_key,
	'description_key'  => $description_key,
	'title_label_desc' => $title_label_desc,
	'title_field_desc' => $title_field_desc,
	'meta_label_desc'  => $meta_label_desc,
	'meta_field_desc'  => $meta_field_desc,
	'title'            => $title,
	'description'      => $description,
	'macros'           => $macros,
) );
