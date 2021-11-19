<div class="sui-floating-notices">
	<?php
	if ( ! empty( $_GET['crawl-in-progress'] ) ) {
		$this->_render( 'floating-notice', array(
			'code'      => 'wds-crawl-started',
			'type'      => 'success',
			'message'   => esc_html__( 'Crawl started successfully', 'wds' ),
			'autoclose' => true,
		) );
	}
	if ( ! empty( $_GET['switched-to-native'] ) ) {
		$this->_render( 'floating-notice', array(
			'code'      => 'wds-switched-to-native',
			'type'      => 'success',
			'message'   => smartcrawl_format_link(
				esc_html__( 'You have successfully switched to the Wordpress core sitemap. You can find it at %s', 'wds' ),
				home_url( '/wp-sitemap.xml' ),
				'/wp-sitemap.xml',
				'_blank'
			),
			'autoclose' => true,
		) );
	}
	if ( ! empty( $_GET['switched-to-sc'] ) ) {
		$this->_render( 'floating-notice', array(
			'code'      => 'wds-switched-to-sc',
			'type'      => 'success',
			'message'   => smartcrawl_format_link(
				esc_html__( 'Well done! You have successfully switched to the SmartCrawl sitemap. You can find it at %s', 'wds' ),
				smartcrawl_get_sitemap_url(),
				'/sitemap.xml',
				'_blank'
			),
			'autoclose' => true,
		) );
	}
	?>
</div>
