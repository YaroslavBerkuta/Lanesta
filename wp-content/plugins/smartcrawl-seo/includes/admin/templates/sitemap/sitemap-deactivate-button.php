<?php
$label_description = empty( $label_description ) ? '' : $label_description;
$button_description = empty( $button_description ) ? '' : $button_description;
?>
<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<label class="sui-settings-label">
			<?php esc_html_e( 'Deactivate', 'wds' ); ?>
		</label>
		<?php if ( $label_description ): ?>
			<p class="sui-description">
				<?php echo esc_html( $label_description ); ?>
			</p>
		<?php endif; ?>
	</div>
	<div class="sui-box-settings-col-2">
		<button type="button"
		        id="wds-deactivate-sitemap-module"
		        class="sui-button sui-button-ghost">
			<span class="sui-loading-text">
				<span class="sui-icon-power-on-off" aria-hidden="true"></span>
				<?php esc_html_e( 'Deactivate', 'wds' ); ?>
			</span>

			<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
		</button>

		<?php if ( $button_description ): ?>
			<p class="sui-description">
				<?php echo esc_html( $button_description ); ?>
			</p>
		<?php endif; ?>
	</div>
</div>
