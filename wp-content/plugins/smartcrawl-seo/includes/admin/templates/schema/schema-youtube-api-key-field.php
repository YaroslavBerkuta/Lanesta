<?php
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$schema_yt_api_key = empty( $schema_yt_api_key ) ? '' : $schema_yt_api_key;
?>
<div class="sui-form-field">
	<p class="sui-description">
		<?php echo smartcrawl_format_link(
			esc_html__( 'To learn more about how to connect to Youtube, see our %s.', 'wds' ),
			'https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=schema-enabling-the-youtube-api#enabling-the-youtube-api',
			'Documentation',
			'_blank'
		); ?>
	</p>
	<label for="schema_yt_api_key" class="sui-label"><?php esc_html_e( 'Access Code', 'wds' ); ?></label>
	<input type="text" id="schema_yt_api_key" class="sui-form-control"
	       name="<?php echo esc_attr( $option_name ); ?>[schema_yt_api_key]"
	       value="<?php echo esc_attr( $schema_yt_api_key ); ?>"
	       placeholder="<?php esc_attr_e( 'API Key', 'wds' ); ?>"/>
</div>

<button class="sui-button sui-button-blue" id="wds-authorize-api-key">
	<?php esc_html_e( 'Authorize', 'wds' ); ?>
</button>
