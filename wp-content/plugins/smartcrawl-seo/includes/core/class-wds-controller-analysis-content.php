<?php

class Smartcrawl_Controller_Analysis_Content extends Smartcrawl_Base_Controller {

	private static $_instance;

	const STRATEGY_STRICT = 'strict';
	const STRATEGY_MODERATE = 'moderate';
	const STRATEGY_LOOSE = 'loose';
	const STRATEGY_MANUAL = 'manual';

	private $strategy;

	protected function __construct() {
		parent::__construct();

		$this->set_analysis_strategy( $this->get_analysis_strategy_option() );
	}

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function is_analysis_running() {
		return ! empty( $_GET['wds-frontend-check'] )
		       && ! is_admin()
		       && is_user_logged_in();
	}

	protected function init() {
		$priority = $this->priority();

		// In strict mode, wrap the post content in a div
		add_filter( 'the_content', array( $this, 'wrap_post_content' ), $priority );

		// Try to remove as much stuff from the page as possible by intercepting common template tags
		add_action( 'init', array( $this, 'hook_template_tag_interceptors' ), - $priority );

		// Based on the current analysis strategy, return a content value
		add_filter( 'wds-analysis-content', array( $this, 'filter_analysis_content' ), - $priority );
	}

	public function get_analysis_strategy() {
		return $this->strategy;
	}

	public function set_analysis_strategy( $strategy ) {
		$this->strategy = $strategy;
	}

	public function filter_analysis_content( $content ) {
		$strategy = $this->get_analysis_strategy();
		switch ( $strategy ) {
			// When strategy is moderate, return the page minus header, footer, sidebar etc
			case self::STRATEGY_MODERATE:
				return $this->remove_redundant_tags( $content );

			// When strategy is manual, return the explicitly marked content
			case self::STRATEGY_MANUAL:
				return $this->get_fragments_by_class(
					$content,
					'.smartcrawl-checkup-included'
				);

			// When strategy is strict, only return whatever is within the_content()
			case self::STRATEGY_STRICT:
				return $this->get_fragments_by_class(
					$content,
					'.wds-frontend-content-check'
				);

			// When strategy is loose, return the whole page content
			case self::STRATEGY_LOOSE:
			default:
				return $content;
		}
	}

	public function hook_template_tag_interceptors() {
		if ( ! $this->is_analysis_running() ) {
			return;
		}

		// We never want to see the admin bar in analysis
		add_filter( 'show_admin_bar', '__return_false' );

		if ( $this->get_analysis_strategy() === self::STRATEGY_MODERATE ) {

			$priority = $this->priority();
			add_action( 'register_sidebar', array( $this, 'remove_sidebar' ), $priority );
			add_filter( 'comments_template', array( $this, 'remove_comments_area' ), $priority );
			add_filter( 'previous_post_link', '__return_empty_string', $priority );
			add_filter( 'next_post_link', '__return_empty_string', $priority );
			add_filter( 'get_avatar', '__return_empty_string', $priority );
			add_filter( 'get_search_form', '__return_empty_string', $priority );
		}
	}

	/**
	 * Removes each sidebar from the page.
	 *
	 * @param $sidebar array
	 */
	function remove_sidebar( $sidebar ) {
		unregister_sidebar( $sidebar['id'] );
	}

	/**
	 * Removes the comment area by returning an empty file as the new comments template.
	 * @return string An empty string to be used as the comment template.
	 */
	function remove_comments_area() {
		return SMARTCRAWL_PLUGIN_DIR . 'core/resources/empty-comments-template.php';
	}

	/**
	 * Wraps post content in a container so we can identify it later
	 *
	 * @param $content string The original post content.
	 *
	 * @return string Content wrapped in a container
	 */
	public function wrap_post_content( $content ) {
		if ( $this->is_analysis_running() && $this->get_analysis_strategy() === self::STRATEGY_STRICT ) {
			return '<div class="wds-frontend-content-check">' . trim( $content ) . '</div>';
		}

		return $content;
	}

	/**
	 * @return int
	 */
	private function priority() {
		return 99999;
	}

	private function get_analysis_strategy_option() {
		$options = Smartcrawl_Settings::get_options();

		return empty( $options['analysis_strategy'] )
			? self::STRATEGY_STRICT
			: $options['analysis_strategy'];
	}

	private function remove_redundant_tags( $content ) {
		$redundant_tags = array( 'header', 'nav', 'footer', 'aside', 'script', 'style' );

		return Smartcrawl_Html::remove_tags( $content, $redundant_tags );
	}

	private function get_fragments_by_class( $content, $class ) {
		$bits = Smartcrawl_Html::find( $class, $content );
		return (string) trim( join( "\n", $bits ) );
	}
}
