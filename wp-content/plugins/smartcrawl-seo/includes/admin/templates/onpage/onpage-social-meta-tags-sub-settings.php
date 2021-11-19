<?php
/**
 * @var string $for_type
 */
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
?>
<div class="sui-form-field">
	<label for="<?php echo esc_attr( $title_field_id ); ?>" class="sui-label">
		<?php esc_html_e( 'Title', 'wds' ); ?>
	</label>
	<div class="sui-insert-variables wds-allow-macros">
		<input id='<?php echo esc_attr( $title_field_id ); ?>'
		       name='<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $title_field_id ); ?>]'
		       size='' type='text' class='sui-form-control'
		       placeholder="<?php echo esc_attr( $title_placeholder ); ?>"
		       value='<?php echo esc_attr( $current_title ); ?>'/>

		<?php $this->_render( 'macros-dropdown', array( 'macros' => $macros ) ); ?>
	</div>
</div>

<div class="sui-form-field">
	<label for="<?php echo esc_attr( $description_field_id ); ?>" class="sui-label">
		<?php esc_html_e( 'Description', 'wds' ); ?>
	</label>
	<div class="sui-insert-variables wds-allow-macros">
        <textarea id='<?php echo esc_attr( $description_field_id ); ?>'
                  name='<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $description_field_id ); ?>]'
                  placeholder="<?php echo esc_attr( $description_placeholder ); ?>"
                  type='text'
                  class='sui-form-control'><?php echo esc_textarea( $current_description ); ?></textarea>

		<?php $this->_render( 'macros-dropdown', array( 'macros' => $macros ) ); ?>
	</div>
</div>

<div>
	<label for="<?php echo esc_attr( $images_field_id ); ?>" class="sui-label">
		<?php if ( $single_image ): ?>
			<?php esc_html_e( 'Default Featured Image', 'wds' ); ?>
		<?php else: ?>
			<?php esc_html_e( 'Default Featured Images', 'wds' ); ?>
		<?php endif; ?>
	</label>
	<div class="og-images <?php echo esc_attr( $images_field_id ); ?>"
	     data-singular="<?php echo $single_image ? 'true' : 'false'; ?>"
	     data-name='<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $images_field_id ); ?>]'>

		<div class="add-action-wrapper sui-tooltip"
		     data-tooltip="<?php esc_attr_e( 'Add featured image', 'wds' ); ?>"
		     style="<?php echo $single_image && $images_available ? 'display:none;' : ''; ?>">
			<a href="#add" id="<?php echo esc_attr( $images_field_id ); ?>"
			   title="<?php esc_attr_e( 'Add image', 'wds' ); ?>">
				<span class="sui-icon-upload-cloud" aria-hidden="true"></span>
			</a>
		</div>
		<?php foreach ( $current_images as $image ): ?>
			<?php $this->_render( 'social-image-item', array(
				'id'         => $image,
				'field_name' => "{$option_name}[{$images_field_id}][]",
			) ); ?>
		<?php endforeach; ?>
	</div>
	<p class="sui-description">
		<?php if ( $single_image ): ?>
			<?php esc_html_e( "Choose featured image that will be used when sharing on Twitter.", 'wds' ); ?>
		<?php else: ?>
			<?php esc_html_e( "Choose featured images that will be used when sharing on Facebook or other platforms that support OpenGraph.", 'wds' ); ?>
		<?php endif; ?>
	</p>
</div>

<div>
	<label class="sui-checkbox wds-inverted-checkbox">
		<input <?php checked( $disable_first_image ); ?>
				type="checkbox"
				value="1"
				name='<?php echo esc_attr( $option_name ); ?>[<?php echo esc_attr( $disable_first_image_id ); ?>]'
				aria-label="<?php esc_html_e( 'Disable use of first image in content.', 'wds' ); ?>"/>

		<span aria-hidden="true"></span>

		<span id="<?php echo esc_attr( $disable_first_image_id ); ?>-label">
			<?php esc_html_e( 'If no feature or custom cover image is added, use the first image in the page content.', 'wds' ); ?>
		</span>
	</label>
</div>

<?php wp_enqueue_media(); ?>
