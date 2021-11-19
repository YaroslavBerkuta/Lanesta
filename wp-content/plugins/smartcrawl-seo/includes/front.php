<?php
/**
 * Initializes plugin front-end behavior
 *
 * @package wpmu-dev-seo
 */

/**
 * Frontend init class
 * 
 * TODO: get rid of this class
 */
class Smartcrawl_Front extends Smartcrawl_Base_Controller {
	/**
	 * Static instance
	 *
	 * @var self
	 */
	private static $_instance;

	/**
	 * Static instance getter
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initializing method
	 */
	protected function init() {
		if ( defined( 'SMARTCRAWL_EXPERIMENTAL_FEATURES_ON' ) && SMARTCRAWL_EXPERIMENTAL_FEATURES_ON ) {
			if ( file_exists( SMARTCRAWL_PLUGIN_DIR . 'tools/video-sitemaps.php' ) ) {
				require_once SMARTCRAWL_PLUGIN_DIR . 'tools/video-sitemaps.php';
			}
		}
	}
}
