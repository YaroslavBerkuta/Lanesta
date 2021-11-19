<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$options = empty( $options ) ? array() : $options;
$social_options = empty( $social_options ) ? array() : $social_options;

$schema_enable_test_button = (bool) smartcrawl_get_array_value( $options, 'schema_enable_test_button' );
?>
<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Test Schema Button', 'wds' ); ?>
		</label>

		<p class="sui-description">
			<?php esc_html_e( 'This feature adds a Test Schema button to the WordPress admin bar.', 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<?php $this->_render( 'toggle-item', array(
			'field_name' => sprintf( '%s[%s]', $option_name, 'schema_enable_test_button' ),
			'field_id'   => 'schema_enable_test_button',
			'checked'    => $schema_enable_test_button,
			'item_label' => esc_html__( 'Show Test Schema button in admin bar', 'wds' ),
		) ); ?>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Deactivate', 'wds' ); ?>
		</label>

		<p class="sui-description">
			<?php esc_html_e( 'If you no longer wish to add schema, you can deactivate it.', 'wds' ); ?>
		</p>
	</div>

	<div class="sui-box-settings-col-2">
		<button type="button"
		        id="wds-deactivate-schema-component"
		        class="sui-button sui-button-ghost">
			<span class="sui-icon-power-on-off" aria-hidden="true"></span>

			<?php esc_html_e( 'Deactivate', 'wds' ); ?>
		</button>
	</div>
</div>
