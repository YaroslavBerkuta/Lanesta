<?php
$inverted = empty( $inverted ) ? false : $inverted;
$field_name = empty( $field_name ) ? '' : $field_name;
$field_id = empty( $field_id ) ? $field_name : $field_id;
$checked = empty( $checked ) ? '' : $checked;
$item_label = empty( $item_label ) ? '' : $item_label;
$item_value = empty( $item_value ) ? '1' : $item_value;
$item_description = empty( $item_description ) ? '' : $item_description;
$html_description = empty( $html_description ) ? '' : $html_description;
$attributes = empty( $attributes ) ? array() : $attributes;
$sub_settings_template = empty( $sub_settings_template ) ? '' : $sub_settings_template;
$sub_settings_template_args = empty( $sub_settings_template_args ) ? array() : $sub_settings_template_args;
$sub_settings_border = isset( $sub_settings_border ) ? $sub_settings_border : true;

$attr_string = '';
foreach ( $attributes as $attribute => $attribute_value ) {
	$attr_string .= sprintf( '%s="%s" ', esc_attr( $attribute ), esc_attr( $attribute_value ) );
}
$checkbox_checked = ( $inverted && ! $checked ) || ( ! $inverted && $checked );
?>

<div class="sui-form-field <?php echo $inverted ? esc_attr( 'wds-inverted-toggle' ) : ''; ?>">
	<label for="<?php echo esc_attr( $field_id ); ?>"
	       class="wds-toggle sui-toggle">

		<input type="checkbox"
		       id="<?php echo esc_attr( $field_id ); ?>"
		       value="<?php echo esc_attr( $item_value ); ?>"
		       name="<?php echo esc_attr( $field_name ); ?>"
		       aria-labelledby="label-<?php echo esc_attr( $field_id ); ?>"
			<?php if ( $item_description ) : ?>
				aria-describedby="description-<?php echo esc_attr( $field_id ); ?>"
			<?php endif; ?>
			<?php if ( $checkbox_checked ) : ?>
				checked="checked"
			<?php endif; ?>
			<?php if ( $sub_settings_template ): ?>
				aria-controls="sub-settings-<?php echo esc_attr( $field_id ); ?>"
			<?php endif; ?>
			<?php echo $attr_string; // phpcs:ignore -- Built escaped. ?>
		/>

		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<span id="label-<?php echo esc_attr( $field_id ); ?>" class="sui-toggle-label">
			<?php echo esc_html( $item_label ); ?>
		</span>

		<div id="description-<?php echo esc_attr( $field_id ); ?>" class="sui-description">
			<?php if ( $item_description ) {
				echo esc_html( $item_description );
			} ?>

			<?php if ( $html_description ) {
				echo wp_kses_post( $html_description );
			} ?>
		</div>
	</label>

	<?php if ( $sub_settings_template ): ?>
		<div id="sub-settings-<?php echo esc_attr( $field_id ); ?>"
		     class="sui-toggle-content <?php echo $sub_settings_border ? 'sui-border-frame' : ''; ?>"
		     aria-label="<?php printf( esc_html__( "Sub-settings of '%s'", 'wds' ), $item_label ); ?>"
		     style="<?php echo $checkbox_checked ? '' : 'display:none;'; ?>">

			<?php $this->_render( $sub_settings_template, $sub_settings_template_args ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $inverted ): ?>
		<input type="hidden"
		       class="wds-inverted-toggle-value"
		       data-value="<?php echo esc_attr( $item_value ); ?>"
		       value="<?php echo $checked ? esc_attr( $item_value ) : ''; ?>"
		       name="<?php echo esc_attr( $field_name ); ?>"
		       aria-hidden="true"
		/>
	<?php endif; ?>
</div>
