<?php
$main_title = empty( $main_title ) ? '' : $main_title;
$main_description = empty( $main_description ) ? '' : $main_description;
$field_name = empty( $field_name ) ? '' : $field_name;
$disabled = empty( $disabled ) ? false : true;
$current_title = empty( $current_title ) ? '' : $current_title;
$title_placeholder = empty( $title_placeholder ) ? '' : $title_placeholder;
$current_description = empty( $current_description ) ? '' : $current_description;
$description_placeholder = empty( $description_placeholder ) ? '' : $description_placeholder;
$images = empty( $images ) ? array() : $images;
$images_available = ! empty( $images ) && is_array( $images );
$single_image = empty( $single_image ) ? false : true;
$images_description = empty( $images_description ) ? false : $images_description;
$toggle_label = empty( $toggle_label ) ? esc_html__( 'Enable for this post', 'wds' ) : $toggle_label;
?>
<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label"><?php echo esc_html( $main_title ); ?></label>
		<p class="sui-description"><?php echo esc_html( $main_description ); ?></p>
	</div>
	<div class="sui-box-settings-col-2">
		<?php
		$this->_render( 'toggle-item', array(
			'inverted'                   => true,
			'field_name'                 => $field_name . '[disabled]',
			'field_id'                   => $field_name . '-disabled',
			'checked'                    => $disabled,
			'item_label'                 => $toggle_label,
			'sub_settings_template'      => 'metabox/metabox-social-meta-tags-sub-settings',
			'sub_settings_template_args' => array(
				'field_name'              => $field_name,
				'current_title'           => $current_title,
				'title_placeholder'       => $title_placeholder,
				'current_description'     => $current_description,
				'description_placeholder' => $description_placeholder,
				'images'                  => $images,
				'images_available'        => $images_available,
				'single_image'            => $single_image,
				'images_description'      => $images_description,
			),
		) );
		?>
	</div>
</div>
