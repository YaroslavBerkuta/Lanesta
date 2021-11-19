<?php

class Smartcrawl_Controller_Sitemap_Front extends Smartcrawl_Base_Controller {
	const SITEMAP_TYPE_INDEX = 'index';
	const SITEMAP_REWRITE_RULES_FLUSHED = 'wds-sitemap-rewrite-rules-flushed';

	private static $_instance;
	/**
	 * @var Smartcrawl_Sitemap[]
	 */
	private $sitemaps;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		parent::__construct();

		$this->sitemaps = array(
			new Smartcrawl_News_Sitemap(),
			new Smartcrawl_General_Sitemap(),
		);
	}

	protected function init() {
		add_action( 'init', array( $this, 'add_rewrites' ) );
		add_action( 'wp', array( $this, 'serve_sitemaps' ), 999 );
		add_action( 'wp_sitemaps_enabled', array( $this, 'maybe_disable_native_sitemap' ) );

		return true;
	}

	public function maybe_disable_native_sitemap( $is_enabled ) {
		if ( Smartcrawl_Sitemap_Utils::override_native() ) {
			return false;
		}

		return $is_enabled;
	}

	public function add_rewrites() {
		foreach ( $this->sitemaps as $sitemap ) {
			$sitemap->add_rewrites();
		}

		$this->maybe_flush_rewrite_rules();
	}

	public function serve_sitemaps() {
		foreach ( $this->sitemaps as $sitemap ) {
			$this->serve_sitemap( $sitemap );
		}
	}

	/**
	 * @param $sitemap Smartcrawl_Sitemap
	 */
	private function serve_sitemap( $sitemap ) {
		if ( $sitemap->can_handle_request() ) {
			if ( $sitemap->is_enabled() ) {
				$sitemap->serve();
			} else {
				$sitemap->do_fallback();
			}
		}
	}

	protected function maybe_flush_rewrite_rules() {
		$flushed = get_option( self::SITEMAP_REWRITE_RULES_FLUSHED, false );
		if ( $flushed !== SMARTCRAWL_VERSION ) {
			flush_rewrite_rules();
			update_option( self::SITEMAP_REWRITE_RULES_FLUSHED, SMARTCRAWL_VERSION );
		}
	}
}
