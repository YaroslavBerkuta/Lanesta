<?php

class Smartcrawl_Lighthouse_Is_Crawlable_Check extends Smartcrawl_Lighthouse_Check {
	const ID = 'is-crawlable';
	private $is_blog_public;
	private $is_home_noindex;

	public function __construct( $report ) {
		$this->is_blog_public = $this->is_blog_public();
		$this->is_home_noindex = $this->is_home_noindex();

		parent::__construct( $report );
	}

	public function prepare() {
		$this->set_success_title( esc_html__( "Page isn't blocked from indexing", 'wds' ) );
		$this->set_failure_title( esc_html__( 'Page is blocked from indexing', 'wds' ) );
		$this->set_success_description( $this->format_success_description() );
		$this->set_failure_description( $this->format_failure_description() );
		$this->set_copy_description( $this->format_copy_description() );
	}

	private function print_common_description() {
		?>
		<div class="wds-lh-section">
			<strong><?php esc_html_e( 'Overview', 'wds' ); ?></strong>
			<p><?php esc_html_e( "Search engines can only show pages in their search results if those pages don't explicitly block indexing by search engine crawlers. Some HTTP headers and meta tags tell crawlers that a page shouldn't be indexed.", 'wds' ); ?></p>
			<p><?php esc_html_e( "Only block indexing for content that you don't want to appear in search results.", 'wds' ); ?></p>
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
				'message' => esc_html__( "Page is crawlable", 'wds' ),
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
				'message' => $this->get_warning_message(),
			) ); ?>

			<?php $this->print_details_table(); ?>
		</div>

		<?php if ( ! $this->is_blog_public || $this->is_home_noindex ): ?>
			<div class="wds-lh-section">
				<strong><?php esc_html_e( 'How to ensure search engines can crawl your page', 'wds' ); ?></strong>

				<?php if ( ! $this->is_blog_public ):
					$this->print_search_engine_visibility_fix();
				elseif ( $this->is_home_noindex ):
					$this->print_sc_title_and_meta_fix();
				endif; ?>
			</div>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	private function get_warning_message() {
		$default = esc_html__( "Page is not crawlable", 'wds' );
		if ( ! $this->is_blog_public ) {
			return sprintf(
				esc_html__( 'Your WordPress Settings are currently to %s this site.', 'wds' ),
				'<strong>' . esc_html__( 'Discourage search engines from indexing', 'wds' ) . '</strong>'
			);
		} elseif ( $this->is_home_noindex ) {
			return sprintf(
				esc_html__( 'Your SmartCrawl Settings are currently set to %s.', 'wds' ),
				'<strong>' . esc_html__( 'No Index', 'wds' ) . '</strong>'
			);
		} else {
			return $default;
		}
	}

	private function print_sc_title_and_meta_fix() {
		?>
		<p>
			<?php printf(
				esc_html__( 'Go to %s and enable the indexing option for your Homepage. Indexing enables you to configure how you want your website to appear in search results.', 'wds' ),
				'<strong>' . esc_html__( 'SmartCrawl > Titles & Meta', 'wds' ) . '</strong>'
			); ?>
		</p>
		<?php
	}

	private function print_search_engine_visibility_fix() {
		?>
		<p><?php esc_html_e( 'Preventing search engine bots from indexing your site is generally not recommended. However, if this is intentional (you’re still in development) you can ignore this recommendation.', 'wds' ); ?></p>
		<p>
			<?php printf(
				esc_html__( 'In the %s area, the %s has a checkbox labelled Search Engine Visibility. Make sure the checkbox is not selected and click Save Changes. If this warning is still displaying after running another checkup, it’s likely the <meta> tag has been hardcoded to your theme files, or is being output from another plugin. Contact your web developer to take a look and fix up the issue.', 'wds' ),
				'<strong>' . esc_html__( 'WordPress Settings', 'wds' ) . '</strong>',
				'<strong>' . esc_html__( 'Reading tab', 'wds' ) . '</strong>'
			); ?>
		</p>
		<?php
	}

	private function is_home_noindex() {
		$resolver = Smartcrawl_Endpoint_Resolver::resolve();
		$robots = new Smartcrawl_Robots_Value_Helper();
		$posts_on_front = 'posts' === get_option( 'show_on_front' ) ||
		                  0 === (int) get_option( 'page_on_front' );
		if ( $posts_on_front ) {
			$query = new WP_Query();
			$query->is_home = true;
			$resolver->simulate( Smartcrawl_Endpoint_Resolver::L_BLOG_HOME, null, $query );
			$robots->handle_blog_home();
		} else {
			$page_on_front = (int) get_option( 'page_on_front' );
			$resolver->simulate_post( get_post( $page_on_front ) );
			$robots->handle_singular( $page_on_front );
		}
		$resolver->stop_simulation();
		$home_robots = $robots->get_value();
		return strpos( $home_robots, 'noindex' ) !== false;
	}

	function get_id() {
		return self::ID;
	}

	public function parse_details( $raw_details ) {
		$table = new Smartcrawl_Lighthouse_Table( array(
			esc_html__( 'Blocking Directive Source', 'wds' ),
		), $this->get_report() );

		$items = smartcrawl_get_array_value( $raw_details, 'items' );
		foreach ( $items as $item ) {
			$source_details = smartcrawl_get_array_value( $item, 'source' );
			$source_type = smartcrawl_get_array_value( $source_details, 'type' );
			if ( is_string( $source_details ) ) {
				$table->add_row( array( $source_details ) );
			} elseif ( $source_type === 'node' ) {
				$snippet = smartcrawl_get_array_value( $source_details, 'snippet' );
				if ( $snippet ) {
					$table->add_row( array( $snippet ) );
				}
			} elseif ( $source_type === 'source-location' ) {
				$robots_url = smartcrawl_get_array_value( $source_details, 'url' );
				if ( $robots_url ) {
					$table->add_row( array( $robots_url ) );
				}
			}
		}

		return $table;
	}

	/**
	 * @return bool|mixed|void
	 */
	private function is_blog_public() {
		return get_option( 'blog_public' );
	}

	public function get_action_button() {
		if ( ! $this->is_blog_public ) {
			return $this->get_reading_options_button();
		} else if ( $this->is_home_noindex ) {
			return $this->get_homepage_onpage_button();
		} else {
			return '';
		}
	}

	private function get_homepage_onpage_button() {
		if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return '';
		}

		return $this->button_markup(
			esc_html__( 'Edit Settings', 'wds' ), Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ), 'sui-icon-wrench-tool'
		);
	}

	private function get_reading_options_button() {
		if ( is_multisite() ) {
			return '';
		}

		return $this->button_markup(
			empty( $text ) ? esc_html__( 'Edit Settings', 'wds' ) : $text,
			admin_url( 'options-reading.php' ),
			'sui-icon-wrench-tool'
		);
	}

	private function format_copy_description() {
		$parts = array_merge( array(
			__( 'Tested Device: ', 'wds' ) . $this->get_device_label(),
			__( 'Audit Type: Indexing audits', 'wds' ),
			"",
			__( 'Failing Audit: Page is blocked from indexing', 'wds' ),
			"",
			__( 'Status: Page is not crawlable', 'wds' ),
			"",
		), $this->get_flattened_details(), array(
			"",
			__( 'Overview:', 'wds' ),
			__( "Search engines can only show pages in their search results if those pages don't explicitly block indexing by search engine crawlers. Some HTTP headers and meta tags tell crawlers that a page shouldn't be indexed.", 'wds' ),
			__( "Only block indexing for content that you don't want to appear in search results.", 'wds' ),
			"",
			__( 'For more information please check the SEO Audits section in SmartCrawl plugin.', 'wds' ),
		) );
		return implode( "\n", $parts );
	}
}
