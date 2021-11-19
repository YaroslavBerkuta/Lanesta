<?php
$sitemap_enabled = empty( $sitemap_enabled ) ? false : $sitemap_enabled;
$custom_sitemap_url = empty( $custom_sitemap_url ) ? '' : $custom_sitemap_url;
$option_name = empty( $option_name ) ? '' : $option_name;
?>

<label for="custom_sitemap_url"
       class="sui-label"><?php esc_html_e( 'Sitemap URL', 'wds' ); ?></label>
<?php if ( $sitemap_enabled ): ?>
	<input id="custom_sitemap_url" type="hidden"
	       name="<?php echo esc_attr( $option_name ); ?>[custom_sitemap_url]"
	       value="<?php echo esc_attr( $custom_sitemap_url ); ?>"/>
	<label>
		<input type="text"
		       class="sui-form-control"
		       readonly
		       value="<?php echo esc_attr( smartcrawl_get_sitemap_url() ) ?>"/>
	</label>
	<?php $this->_render( 'notice', array(
		'class'   => 'wds-auto-sitemap-addition',
		'message' => esc_html__( "We've detected you're using SmartCrawl's built in sitemap and will output this for you automatically.", 'wds' ),
	) ); ?>
<?php else: ?>
	<input id="custom_sitemap_url" type="text"
	       class="sui-form-control"
	       name="<?php echo esc_attr( $option_name ); ?>[custom_sitemap_url]"
	       value="<?php echo esc_attr( $custom_sitemap_url ); ?>"/>

	<p class="sui-description">
		<?php printf(
			esc_html__( 'Copy and paste the URL to your sitemap. E.g %s or %s', 'wds' ),
			'<strong>/sitemap.xml</strong>',
			'<strong>https://example.com/sitemap.xml</strong>'
		) ?>
	</p>
<?php endif; ?>
