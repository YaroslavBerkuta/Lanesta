<?php

class Smartcrawl_Lighthouse_Http_Status_Code_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'http-status-code';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Page has successful HTTP status code', 'wds' ) );
		$this->set_failure_title( esc_html__( "Page has unsuccessful HTTP status code", 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
	}

	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
			<p>
				<?php printf(
					esc_html__( "Servers provide a three-digit %s for each resource request they receive. Status codes in the 400s and 500s %s with the requested resource. If a search engine encounters a status code error when it's crawling a web page, it may not index that page properly.", 'wds' ),
					sprintf(
						'<a target="%s" href="%s">%s</a>',
						"_blank",
						esc_url_raw( 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status' ),
						esc_html__( 'HTTP status code', 'wds' )
					),
					sprintf(
						'<a target="%s" href="%s">%s</a>',
						"_blank",
						esc_url_raw( 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#Client_error_responses' ),
						esc_html__( "indicate that there's an error", 'wds' )
					)
				); ?>
			</p>
		</div>
		<?php
	}

	private function format_success_description() {
		ob_start();
		$this->print_common_description();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-success',
				'message' => esc_html__( "Page has a successful HTTP status code.", 'wds' ),
			) ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	private function format_failure_description() {
		ob_start();
		$this->print_common_description();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-warning',
				'message' => esc_html__( "Page returns an unsuccessful HTTP status code.", 'wds' ),
			) ); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to fix an unsuccessful HTTP status code', 'wds' ); ?></strong>
			<p><?php esc_html_e( "First make sure you actually want search engines to crawl the page. Some pages, like your 404 page or any other page that shows an error, shouldn't be included in search results.", 'wds' ); ?></p>
			<p><?php esc_html_e( 'To fix an HTTP status code error, refer to the documentation for your server or hosting provider. The server should return a status code in the 200s for all valid URLs or a status code in the 300s for a resource that has moved to another URL.', 'wds' ); ?></p>
			<br/>
			<p>
				<?php echo smartcrawl_format_link(
					esc_html__( 'See %s page for more information.', 'wds' ),
					'https://web.dev/http-status-code/',
					esc_html__( 'Source code for Page has unsuccessful HTTP status code audit', 'wds' ),
					'_blank'
				); ?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}

	function get_id() {
		return self::ID;
	}
}