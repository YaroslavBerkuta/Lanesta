<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$field_name = empty( $field_name ) ? '' : $field_name;
$field_label = empty( $field_label ) ? '' : $field_label;
$field_description = empty( $field_description ) ? '' : $field_description;
$selected_post_id = empty( $selected_post_id ) ? '' : $selected_post_id;
$post_type = empty( $post_type ) ? '' : $post_type;
$placeholder = empty( $placeholder ) ? '' : $placeholder;
$allow_clear = empty( $allow_clear ) ? false : $allow_clear;
$first_option = empty( $first_option ) ? '' : $first_option;
$pages = empty( $pages ) ? array() : $pages;
?>

<div class="sui-form-field">
	<label for="<?php echo esc_attr( $field_name ); ?>" class="sui-label">
		<?php echo esc_html( $field_label ); ?>
	</label>
	<div class="sui-row">
		<div class="sui-col-md-6">
			<select id="<?php echo esc_attr( $field_name ); ?>"
			        name="<?php echo esc_attr( "{$option_name}[$field_name]" ); ?>"
			        data-post-type="<?php echo esc_attr( $post_type ); ?>"
			        class="sui-select"
			        data-allow-clear="<?php echo intval( $allow_clear ); ?>"
			        <?php if ( $placeholder ): ?>data-placeholder="<?php echo esc_attr( $placeholder ); ?>"<?php endif; ?>
			>

				<?php if ( $placeholder || $first_option ): ?>
					<option value=""><?php echo esc_html( $first_option ); ?></option>
				<?php endif; ?>

				<?php foreach ( $pages as $page_id => $page_title ): ?>
					<option <?php selected( $selected_post_id, $page_id ); ?>
							value="<?php echo esc_attr( $page_id ); ?>">
						<?php echo esc_html( $page_title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<span class="sui-description">
		<?php echo esc_html( $field_description ); ?>
	</span>
</div>
