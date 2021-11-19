<?php
/**
 * @var string $for_type
 */
$section_enabled_field_id = empty( $section_enabled_field_id ) ? '' : $section_enabled_field_id;
$section_enabled = empty( $section_enabled ) ? '' : $section_enabled;
$section_title = empty( $section_title ) ? '' : $section_title;
$section_description = empty( $section_description ) ? '' : $section_description;
$option_name = empty( $option_name ) ? '' : $option_name;
$title_field_id = empty( $title_field_id ) ? '' : $title_field_id;
$current_title = empty( $current_title ) ? '' : $current_title;

$description_field_id = empty( $description_field_id ) ? '' : $description_field_id;
$current_description = empty( $current_description ) ? '' : $current_description;

$images_field_id = empty( $images_field_id ) ? '' : $images_field_id;
$current_images = empty( $current_images ) ? array() : $current_images;
$images_available = ! empty( $current_images ) && is_array( $current_images );
$single_image = empty( $single_image ) ? false : true;

$title_placeholder = ( ! empty( $_view['options']["title-{$for_type}"] ) ? $_view['options']["title-{$for_type}"] : '' );
$description_placeholder = ( ! empty( $_view['options']["metadesc-{$for_type}"] ) ? $_view['options']["metadesc-{$for_type}"] : '' );

$disable_first_image_id = empty( $disable_first_image_id ) ? false : $disable_first_image_id;
$disable_first_image = empty( $disable_first_image ) ? false : true;
$macros = empty( $macros ) ? array() : $macros;

$this->_render( 'toggle-item', array(
	'field_name'                 => sprintf( '%s[%s]', $option_name, $section_enabled_field_id ),
	'field_id'                   => $section_enabled_field_id,
	'checked'                    => $section_enabled,
	'item_label'                 => $section_title,
	'item_description'           => $section_description,
	'sub_settings_template'      => 'onpage/onpage-social-meta-tags-sub-settings',
	'sub_settings_template_args' => array(
		'for_type' => $for_type,

		'option_name'    => $option_name,
		'title_field_id' => $title_field_id,
		'current_title'  => $current_title,

		'description_field_id' => $description_field_id,
		'current_description'  => $current_description,

		'images_field_id'  => $images_field_id,
		'current_images'   => $current_images,
		'images_available' => $images_available,
		'single_image'     => $single_image,

		'title_placeholder'       => $title_placeholder,
		'description_placeholder' => $description_placeholder,

		'disable_first_image_id' => $disable_first_image_id,
		'disable_first_image'    => $disable_first_image,
		'macros'                 => $macros,
	),
) );
