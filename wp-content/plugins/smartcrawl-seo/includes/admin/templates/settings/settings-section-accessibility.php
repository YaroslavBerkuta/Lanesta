<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$high_contrast_option = "{$option_name}[high-contrast]";
$enabled = ! empty( $_view['options']['high-contrast'] );
?>
<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">

			<?php esc_html_e( 'High Contrast Mode', 'wds' ); ?>
		</label>
		<span class="sui-description">
			<?php esc_html_e( 'Increase the visibility and high-contrast of elements and components of this plugin’s interface to meet WCAG AAA requirements.', 'wds' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<?php
		$this->_render( 'toggle-item', array(
			'field_name' => $high_contrast_option,
			'field_id'   => $high_contrast_option,
			'checked'    => $enabled,
			'item_label' => esc_html__( 'Enable high contrast mode', 'wds' ),
		) );
		?>
	</div>
</div>
