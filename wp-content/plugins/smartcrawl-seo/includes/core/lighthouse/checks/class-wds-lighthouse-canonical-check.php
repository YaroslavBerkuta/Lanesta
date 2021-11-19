<?php

class Smartcrawl_Lighthouse_Canonical_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'canonical';

	public function prepare() {
		$this->set_success_title( esc_html__( 'Document has a valid rel=canonical', 'wds' ) );
		$this->set_failure_title( esc_html__( 'Document does not have a valid rel=canonical', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
		<p><?php esc_html_e( 'When multiple pages have similar content, search engines consider them duplicate versions of the same page. For example, desktop and mobile versions of a product page are often considered duplicates.', 'wds' ); ?></p>
		<p><?php esc_html_e( 'Search engines select one of the pages as the canonical, or primary, version and crawl that one more. Valid canonical links let you tell search engines which version of a page to crawl and display to users in search results.', 'wds' ); ?></p>
		<?php
	}

	private function format_success_description() {
		ob_start();
		?>
		<div class="wds-lh-section">
			<?php $this->print_common_description(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-success',
				'message' => esc_html__( "We found a valid canonical meta tag.", 'wds' ),
			) ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	private function format_failure_description() {
		ob_start();
		?>
		<div class="wds-lh-section">
			<?php $this->print_common_description(); ?>

			<p><?php esc_html_e( 'Using canonical links has many advantages:', 'wds' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'It helps search engines consolidate multiple URLs into a single, preferred URL. For example, if other sites put query parameters on the ends of links to your page, search engines consolidate those URLs to your preferred version.', 'wds' ); ?></li>
				<li><?php esc_html_e( 'It simplifies tracking methods. Tracking one URL is easier than tracking many.', 'wds' ); ?></li>
				<li><?php esc_html_e( 'It improves the page ranking of syndicated content by consolidating the syndicated links to your original content back to your preferred URL.', 'wds' ); ?></li>
			</ul>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-warning',
				'message' => esc_html__( "We couldn’t detect a valid canonical meta tag.", 'wds' ),
			) ); ?>

			<p><?php esc_html_e( 'It’s highly recommended to always set a single canonical URL for every webpage to ensure search engines never get confused and always have the original source of truth content.', 'wds' ); ?></p>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to add canonical links to your pages', 'wds' ); ?></strong>
			<p><?php esc_html_e( 'For your homepage, set the canonical URL using the Titles & Meta settings area. For individual pages we automatically generate a canonical URL based off your base site URL, but you can override that on a per post basis using the Post Editor SEO widget.', 'wds' ); ?></p>
			<p>
				<?php echo smartcrawl_format_link(
					esc_html__( 'To help ensure your SEO efforts are up to snuff, see our blog post, %s, for an easy setup guide to get canonicals right.', 'wds' ),
					'https://wpmudev.com/blog/wordpress-canonicalization-guide/',
					esc_html__( 'WordPress Canonicalization Made Simple With SmartCrawl', 'wds' ),
					'_blank'
				); ?>
			</p>
		</div>

		<div class="wds-lh-toggle-container">
			<a class="wds-lh-toggle" href="#">
				<?php esc_html_e( 'Read More - Guidelines' ); ?>
			</a>

			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'General guidelines', 'wds' ); ?></strong>
				<ul>
					<li><?php esc_html_e( 'Make sure that the canonical URL is valid.', 'wds' ); ?></li>
					<li><?php echo smartcrawl_format_link(
							esc_html__( 'Use secure %s canonical URLs rather than HTTP whenever possible.', 'wds' ),
							'https://developers.google.com/search/docs/advanced/security/https',
							'HTTPS',
							'_blank'
						); ?>
					</li>
					<li>
						<?php echo smartcrawl_format_link(
							esc_html__( 'If you use %s to serve different versions of a page depending on a user\'s language or country, make sure that the canonical URL points to the proper page for that respective language or country.', 'wds' ),
							'https://developers.google.com/search/docs/advanced/crawling/localized-versions?hl=en#expandable-1',
							esc_html__( 'hreflang links', 'wds' ),
							'_blank'
						); ?>
					</li>
					<li><?php esc_html_e( "Don't point the canonical URL to a different domain. Yahoo and Bing don't allow this.", 'wds' ); ?></li>
					<li><?php esc_html_e( "Don't point lower-level pages to the site's root page unless their content is the same.", 'wds' ); ?></li>
				</ul>

				<p><?php echo smartcrawl_format_link(
						"See %s page.",
						'https://developers.google.com/search/docs/advanced/crawling/consolidate-duplicate-urls',
						esc_html__( "Google's Consolidate duplicate URLs", 'wds' ),
						'_blank'
					); ?>
				</p>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	function get_id() {
		return self::ID;
	}

	public function get_action_button() {
		return $this->edit_homepage_button();
	}

	private function format_copy_description() {
		$parts = array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Content audits', 'wds' ),
			"",
			__( 'Failing Audit: Document does not have a valid rel=canonical', 'wds' ),
			"",
			__( 'Status: We couldn’t detect a valid canonical meta tag.', 'wds' ),
			__( 'It’s highly recommended to always set a single canonical URL for every webpage to ensure search engines never get confused and always have the original source of truth content.', 'wds' ),
			"",
			__( 'Overview:', 'wds' ),
			__( 'When multiple pages have similar content, search engines consider them duplicate versions of the same page. For example, desktop and mobile versions of a product page are often considered duplicates.', 'wds' ),
			__( 'Search engines select one of the pages as the canonical, or primary, version and crawl that one more. Valid canonical links let you tell search engines which version of a page to crawl and display to users in search results.', 'wds' ),
			"",
			__( 'Using canonical links has many advantages:', 'wds' ),
			__( '- It helps search engines consolidate multiple URLs into a single, preferred URL. For example, if other sites put query parameters on the ends of links to your page, search engines consolidate those URLs to your preferred version.', 'wds' ),
			__( '- It simplifies tracking methods. Tracking one URL is easier than tracking many.', 'wds' ),
			__( '- It improves the page ranking of syndicated content by consolidating the syndicated links to your original content back to your preferred URL.', 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		);

		return implode( "\n", $parts );
	}
}
