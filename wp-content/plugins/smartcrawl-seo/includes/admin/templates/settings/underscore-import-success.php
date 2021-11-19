<p><?php esc_html_e( 'All imported successfully, nice work!', 'wds' ); ?></p>
<?php $this->_render( 'notice', array(
	'class'   => 'sui-notice-success',
	'message' => sprintf( esc_html__( 'Your %s settings have been imported successfully and are now active.', 'wds' ), '{{- plugin_name }}' ),
) ); ?>

{{ if(plugin == "yoast" || deactivation_url) { }}
<?php $this->_render( 'notice', array(
	'class'   => 'sui-notice-warning',
	'message' => '{{ if(deactivation_url) { }}' .
	             sprintf( esc_html__( 'We highly recommend you deactivate %s to avoid potential conflicts. ', 'wds' ), '{{- plugin_name }}' ) .
	             '{{ } }}' .

	             '{{ if(plugin == "yoast") { }}' .
	             smartcrawl_format_link(
		             esc_html__( 'Please recheck your %s to make sure your website is correctly indexed.', 'wds' ),
		             admin_url( 'admin.php?page=wds_onpage' ),
		             esc_html__( 'index settings', 'wds' ),
		             '_blank'
	             ) .
	             '{{ } }}',
) ); ?>
{{ } }}

<div class="wds-import-footer">
	<div class="cf">
		<button type="button" class="sui-button sui-button-ghost wds-import-skip">
			<?php esc_html_e( 'Close', 'wds' ); ?>
		</button>

		{{ if(deactivation_url) { }}
		<a class="sui-button wds-import-main-action" href="{{= deactivation_url }}">
			<span class="sui-icon-power-on-off" aria-hidden="true"></span>

			<?php esc_html_e( 'Deactivate', 'wds' ); ?> {{- plugin_name }}
		</a>
		{{ } }}
	</div>
</div>
