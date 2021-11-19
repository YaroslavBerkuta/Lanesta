<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
?>

<div class="sui-form-field">
	<label for="redirect-attachments-images_only" class="sui-checkbox">
		<input
				type="checkbox"
				id="redirect-attachments-images_only"
				aria-labelledby="label-redirect-attachments-images_only"
				name="<?php echo esc_html( $option_name ); ?>[redirect-attachments-images_only]"
			<?php checked( ! empty( $_view['options']['redirect-attachments-images_only'] ) ); ?>
		/>
		<span aria-hidden="true"></span>
		<span id="label-redirect-attachments-images_only">
			<?php esc_html_e( 'Redirect image attachments only', 'wds' ); ?>
		</span>
	</label>

	<p class="sui-description" style="margin-left: 25px;">
		<?php esc_html_e( 'Select this option if you only want to redirect attachments that are an image.', 'wds' ); ?>
	</p>
</div>
