<?php

class Smartcrawl_Lighthouse_Robots_Txt_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'robots-txt';

	public function prepare() {
		$this->set_success_title( esc_html__( 'robots.txt is valid', 'wds' ) );
		$this->set_failure_title( esc_html__( 'robots.txt is not valid', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
			<p><?php esc_html_e( "The robots.txt file tells search engines which of your site's pages they can crawl. An invalid robots.txt configuration can cause two types of problems:", 'wds' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'It can keep search engines from crawling public pages, causing your content to show up less often in search results.', 'wds' ); ?></li>
				<li><?php esc_html_e( 'It can cause search engines to crawl pages you may not want shown in search results.', 'wds' ); ?></li>
			</ul>
		</div>
		<?php
	}

	public function format_success_description() {
		ob_start();
		$this->print_common_description();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-success',
				'message' => esc_html__( "We've detected a robots.txt file, nice work.", 'wds' ),
			) ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	public function format_failure_description() {
		ob_start();
		$this->print_common_description();
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Status', 'wds' ); ?></strong>
			<?php Smartcrawl_Simple_Renderer::render( 'notice', array(
				'class'   => 'sui-notice-warning',
				'message' => esc_html__( 'The robots.txt file is not valid.', 'wds' ),
			) ); ?>

			<p>
				<?php esc_html_e( 'If your robots.txt file is malformed, crawlers may not be able to understand how you want your website to be crawled or indexed.', 'wds' ); ?>
			</p>

			<?php $this->print_details_table(); ?>
		</div>

		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'How to fix problems with robots.txt', 'wds' ); ?></strong>
			<p>
				<?php printf(
					esc_html__( 'SmartCrawl can automatically add a robots.txt file for you, and link to your sitemap. Jump to %s and fix the issues in your robots.txt file.', 'wds' ),
					'<strong>' . esc_html__( 'Advanced Tools / Robots.txt Editor', 'wds' ) . '</strong>'
				); ?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}

	function get_id() {
		return self::ID;
	}

	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Table( array(
			esc_html__( 'Line Number', 'wds' ),
			esc_html__( 'Content', 'wds' ),
			esc_html__( 'Error', 'wds' ),
		), $this->get_report() );

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$table->add_row( array(
				smartcrawl_get_array_value( $item, 'index' ),
				smartcrawl_get_array_value( $item, 'line' ),
				smartcrawl_get_array_value( $item, 'message' ),
			) );
		}

		return $table;
	}

	public function get_action_button() {
		if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_AUTOLINKS ) ) {
			return '';
		}

		$url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_AUTOLINKS ) . '&tab=tab_robots_editor';
		return $this->button_markup(
			esc_html__( 'Edit Robots.txt', 'wds' ), $url, 'sui-icon-wrench-tool'
		);
	}

	private function format_copy_description() {
		$parts = array_merge( array(
			esc_html__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			esc_html__( 'Audit Type: Indexing audits', 'wds' ),
			"",
			esc_html__( 'Failing Audit: robots.txt is not valid', 'wds' ),
			"",
			esc_html__( 'Status: The robots.txt file is not valid.', 'wds' ),
			esc_html__( 'If your robots.txt file is malformed, crawlers may not be able to understand how you want your website to be crawled or indexed.', 'wds' ),
			"",
		), $this->get_flattened_details(), array(
			"",
			esc_html__( 'Overview:', 'wds' ),
			esc_html__( "The robots.txt file tells search engines which of your site's pages they can crawl. An invalid robots.txt configuration can cause two types of problems:", 'wds' ),
			"",
			esc_html__( '- It can keep search engines from crawling public pages, causing your content to show up less often in search results.', 'wds' ),
			esc_html__( '- It can cause search engines to crawl pages you may not want shown in search results.', 'wds' ),
			"",
			esc_html__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		) );

		return implode( "\n", $parts );
	}
}
