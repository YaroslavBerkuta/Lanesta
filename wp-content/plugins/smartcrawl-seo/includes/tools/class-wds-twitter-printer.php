<?php

/**
 * Outputs Twitter cards data to the page
 */
class Smartcrawl_Twitter_Printer extends Smartcrawl_WorkUnit {

	const CARD_SUMMARY = 'summary';
	const CARD_IMAGE = 'summary_large_image';

	/**
	 * Singleton instance holder
	 */
	private static $_instance;

	private $_is_running = false;
	private $_is_done = false;

	/**
	 * Holds resolver instance
	 *
	 * @var Smartcrawl_Twitter_Value_Helper instance
	 */
	private $_helper;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return Smartcrawl_Twitter_Value_Helper
	 */
	private function helper() {
		if ( empty( $this->_helper ) ) {
			$this->_helper = new Smartcrawl_Twitter_Value_Helper();
		}

		return $this->_helper;
	}

	/**
	 * Boot the hooking part
	 */
	public static function run() {
		self::get()->_add_hooks();
	}

	private function _add_hooks() {
		// Do not double-bind
		if ( $this->apply_filters( 'is_running', $this->_is_running ) ) {
			return true;
		}

		add_action( 'wp_head', array( $this, 'dispatch_tags_injection' ), 50 );
		add_action( 'wds_head-after_output', array( $this, 'dispatch_tags_injection' ) );

		$this->_is_running = true;
		return true;
	}

	/**
	 * Singleton instance getter
	 *
	 * @return Smartcrawl_Twitter_Printer instance
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function dispatch_tags_injection() {
		if ( ! ! $this->_is_done ) {
			return false;
		}
		$this->_is_done = true;

		if ( ! $this->is_globally_enabled() || ! $this->helper()->is_enabled() ) {
			return false;
		}

		$images = $this->helper()->get_images();
		$card = $this->get_card_content( $images );
		$this->print_html_tag( 'card', $card );

		$site = $this->get_site_content();
		if ( ! empty( $site ) ) {
			$this->print_html_tag( 'site', $site );
		}

		$title = $this->helper()->get_title();
		if ( ! empty( $title ) ) {
			$this->print_html_tag( 'title', $title );
		}

		$desc = $this->helper()->get_description();
		if ( ! empty( $desc ) ) {
			$this->print_html_tag( 'description', $desc );
		}

		if ( ! empty( $images ) && is_array( $images ) ) {
			$twitter_image_url = array_keys( $images )[0];
			if ( $twitter_image_url ) {
				$this->print_html_tag( 'image', $twitter_image_url );
			}
		}

		return true;
	}

	private function is_globally_enabled() {
		$settings = Smartcrawl_Settings::get_options();
		return ! empty( $settings['twitter-card-enable'] );
	}

	/**
	 * Card type to render
	 *
	 * @param array $images
	 *
	 * @return string Card type
	 */
	public function get_card_content( $images = array() ) {
		$options = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );
		$card = is_array( $options ) && ! empty( $options['twitter-card-type'] )
			? $options['twitter-card-type']
			: self::CARD_IMAGE;

		if ( self::CARD_IMAGE === $card ) {
			// Force summary card if we can't show image
			if ( empty( $images ) ) {
				$card = self::CARD_SUMMARY;
			}
		}

		return $card;
	}

	/**
	 * Gets image URL to use for this card
	 *
	 * @return string Image URL
	 */
	public function get_image_content() {
		_deprecated_function( __METHOD__, '2.7', 'Smartcrawl_Twitter_Value_Helper->get_images' );

		$this->helper()->traverse();
		$images = $this->helper()->get_images();
		if ( ! empty( $images ) && is_array( $images ) ) {
			return array_keys( $images )[0];
		}

		return '';
	}

	/**
	 * Gets HTML element ready for rendering
	 *
	 * @param string $type Element type to prepare
	 * @param string $content Element content
	 *
	 * @return string Element
	 */
	public function get_html_tag( $type, $content ) {
		$content = apply_filters( 'wds_custom_twitter_meta', $content, $type );

		return '<meta name="twitter:' . esc_attr( $type ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
	}

	/**
	 * Sitewide twitter handle
	 *
	 * @return string Handle
	 */
	public function get_site_content() {
		$options = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );

		return is_array( $options ) && ! empty( $options['twitter_username'] )
			? $options['twitter_username']
			: '';
	}

	/**
	 * Current resolved title
	 *
	 * @return string Title
	 */
	public function get_title_content() {
		_deprecated_function( __METHOD__, '2.7', 'Smartcrawl_Twitter_Value_Helper->get_title' );

		$this->helper()->traverse();
		return $this->helper()->get_title();
	}

	/**
	 * Current resolved description
	 *
	 * @return string Description
	 */
	public function get_description_content() {
		_deprecated_function( __METHOD__, '2.7', 'Smartcrawl_Twitter_Value_Helper->get_description' );

		$this->helper()->traverse();
		return $this->helper()->get_description();
	}

	public function get_filter_prefix() {
		return 'wds-twitter';
	}

	private function get_allowed_tags() {
		$allowed_tags = array(
			'meta' => array(
				'name'    => array(),
				'content' => array(),
			),
		);

		return $allowed_tags;
	}

	private function print_html_tag( $type, $content ) {
		echo wp_kses( $this->get_html_tag( $type, $content ), $this->get_allowed_tags() );
	}
}
