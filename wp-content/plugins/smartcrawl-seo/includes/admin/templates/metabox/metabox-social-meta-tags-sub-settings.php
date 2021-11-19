<?php
$field_name = empty( $field_name ) ? '' : $field_name;
$current_title = empty( $current_title ) ? '' : $current_title;
$title_placeholder = empty( $title_placeholder ) ? '' : $title_placeholder;
$current_description = empty( $current_description ) ? '' : $current_description;
$description_placeholder = empty( $description_placeholder ) ? '' : $description_placeholder;
$images = empty( $images ) ? array() : $images;
$images_available = ! empty( $images ) && is_array( $images );
$single_image = empty( $single_image ) ? false : true;
$images_description = empty( $images_description ) ? false : $images_description;
?>

<div class="<?php echo esc_attr( $field_name ); ?>-meta">
	<div class="sui-form-field">
		<label for="<?php echo esc_attr( $field_name ); ?>-title"
		       class="sui-label"><?php esc_html_e( 'Title', 'wds' ); ?></label>
		<input type="text"
		       class="sui-form-control"
		       id="<?php echo esc_attr( $field_name ); ?>-title"
		       name="<?php echo esc_attr( $field_name ); ?>[title]"
		       placeholder="<?php echo esc_attr( $title_placeholder ); ?>"
		       value="<?php echo esc_attr( $current_title ); ?>"/>
	</div>

	<div class="sui-form-field">
		<label for="<?php echo esc_attr( $field_name ); ?>-description" class="sui-label">
			<?php esc_html_e( 'Description', 'wds' ); ?>
		</label>
		<textarea name="<?php echo esc_attr( $field_name ); ?>[description]"
		          class="sui-form-control"
		          placeholder="<?php echo esc_attr( $description_placeholder ); ?>"
		          id="<?php echo esc_attr( $field_name ); ?>-description"><?php echo esc_textarea( $current_description ); ?></textarea>
	</div>

	<div class="sui-form-field">
		<label for="<?php echo esc_attr( $field_name ); ?>-images" class="sui-label">
			<?php echo $single_image ? esc_html__( 'Featured Image', 'wds' ) : esc_html__( 'Featured Images', 'wds' ); ?>
		</label>
		<div class="og-images"
		     data-singular="<?php echo $single_image ? 'true' : 'false'; ?>"
		     data-name="<?php echo esc_attr( $field_name ); ?>[images]">
			<div class="add-action-wrapper sui-tooltip"
			     data-tooltip="<?php esc_attr_e( 'Add featured image', 'wds' ); ?>"
			     style="<?php echo $single_image && $images_available ? 'display:none;' : ''; ?>">
				<a href="#add" id="<?php echo esc_attr( $field_name ); ?>-images"
				   title="<?php esc_attr_e( 'Add image', 'wds' ); ?>">
					<span class="sui-icon-upload-cloud" aria-hidden="true"></span>
				</a>
			</div>
			<?php if ( $images_available ) : ?>
				<?php foreach ( $images as $img ) : ?>
					<?php $this->_render( 'social-image-item', array(
						'id'         => $img,
						'field_name' => "{$field_name}[images][]",
					) ); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<p class="sui-description">
			<?php if ( $images_description ): ?>
				<?php echo esc_html( $images_description ); ?>
			<?php elseif ( $single_image ): ?>
				<?php esc_html_e( 'This image will be used as the featured image when the post is shared.', 'wds' ); ?>
			<?php else: ?>
				<?php esc_html_e( 'Each of these images will be available to use as the featured image when the post is shared.', 'wds' ); ?>
			<?php endif; ?>
		</p>
	</div>
</div>
