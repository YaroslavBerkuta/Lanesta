<?php

/**
 * Class Smartcrawl_Compatibility
 *
 * Fixes third-party compatibility issues
 */
class Smartcrawl_Compatibility extends Smartcrawl_Base_Controller {
	/**
	 * Singleton instance
	 *
	 * @var Smartcrawl_Compatibility
	 */
	private static $_instance;

	/**
	 * Obtain instance without booting up
	 *
	 * @return Smartcrawl_Compatibility instance
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function init() {
		add_filter( 'wds-omitted-shortcodes', array( $this, 'avada_omitted_shortcodes' ) );
		add_filter( 'wds-omitted-shortcodes', array( $this, 'divi_omitted_shortcodes' ) );
		add_filter( 'wds-omitted-shortcodes', array( $this, 'wpbakery_omitted_shortcodes' ) );
		add_filter( 'wds-omitted-shortcodes', array( $this, 'swift_omitted_shortcodes' ) );
		add_filter( 'wds_before_sitemap_rebuild', array( $this, 'translate_url_wpml' ) );
		add_filter( 'wds_full_sitemap_items', array( $this, 'add_wpml_homepage_versions' ) );
		add_filter( 'wds_partial_sitemap_items', array( $this, 'add_wpml_homepage_versions_to_partial' ), 10, 3 );
		add_filter( 'bbp_register_topic_taxonomy', array( $this, 'allow_sitemap_access' ) );
		add_filter( 'bbp_register_forum_post_type', array( $this, 'allow_sitemap_access' ) );
		add_filter( 'bbp_register_topic_post_type', array( $this, 'allow_sitemap_access' ) );
		add_filter( 'bbp_register_reply_post_type', array( $this, 'allow_sitemap_access' ) );
		add_filter( 'wds-sitemaps-sitemap_url', array( $this, 'change_sitemap_url_for_domain_map' ) );
		// Disable defender login redirect because we are not entirely sure about its security implications
		//add_filter( 'wds-report-admin-url', array( $this, 'ensure_defender_login_redirect' ) );

		return true;
	}

	public function allow_sitemap_access( $args ) {
		$request = parse_url( rawurldecode( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH );
		$is_sitemap_request = strpos( $request, '/sitemap.xml' ) === strlen( $request ) - strlen( '/sitemap.xml' );
		$sc_sitemap_active = Smartcrawl_Settings::get_setting( 'sitemap' );
		if ( $sc_sitemap_active && $is_sitemap_request ) {
			$args['show_ui'] = true;
		}
		return $args;
	}

	public function avada_omitted_shortcodes( $omitted ) {
		return array_merge( $omitted, array(
			'fusion_code',
			'fusion_imageframe',
			'fusion_slide',
			'fusion_syntax_highlighter',
		) );
	}

	public function divi_omitted_shortcodes( $omitted ) {
		return array_merge( $omitted, array(
			'et_pb_code',
			'et_pb_fullwidth_code',
		) );
	}

	public function wpbakery_omitted_shortcodes( $omitted ) {
		return array_merge( $omitted, array(
			'vc_raw_js',
			'vc_raw_html',
		) );
	}

	public function swift_omitted_shortcodes( $omitted ) {
		return array_merge( $omitted, array(
			'spb_raw_js',
			'spb_raw_html',
		) );
	}

	public function add_wpml_homepage_versions_to_partial( $items, $type, $page_number ) {
		$is_first_post_sitemap = $type === 'post' && $page_number === 1;
		if ( ! $is_first_post_sitemap ) {
			return $items;
		}

		return $this->add_wpml_homepage_versions( $items );
	}

	public function add_wpml_homepage_versions( $items ) {
		global $sitepress;
		$sitepress_available = ! empty( $sitepress )
		                       && method_exists( $sitepress, 'get_active_languages' )
		                       && method_exists( $sitepress, 'get_default_language' )
		                       && method_exists( $sitepress, 'convert_url' );

		if ( ! $sitepress_available ) {
			return $items;
		}

		// Remove the original home url
		array_shift( $items );

		// Add all homepage versions
		$languages = $sitepress->get_active_languages( false, true );
		foreach ( $languages as $language_code => $language ) {
			if ( $sitepress->get_default_language() === $language_code ) {
				continue;
			}

			$item_url = $sitepress->convert_url( home_url(), $language_code );
			array_unshift(
				$items,
				$this->get_sitemap_homepage_item( $item_url )
			);
		}

		array_unshift(
			$items,
			$this->get_sitemap_homepage_item( home_url( '/' ) )
		);

		return $items;
	}

	private function get_sitemap_homepage_item( $url ) {
		$item = new Smartcrawl_Sitemap_Item();
		return $item->set_location( $url )
		            ->set_priority( 1 )
		            ->set_change_frequency( Smartcrawl_Sitemap_Item::FREQ_DAILY );
	}

	/**
	 * WPML tries to 'translate' urls but in our context it leads to every URL getting converted to the default language.
	 *
	 * If the post ID of an Urdu post is passed to get_permalink, we expect to get the Urdu url in return but the conversion changes it to default language URL.
	 */
	public function translate_url_wpml() {
		global $sitepress;
		if ( empty( $sitepress ) ) {
			return;
		}

		// Get rid of all permalink modifications when we are building the sitemap.
		$this->add_wpml_filters();
	}

	private function add_wpml_filters() {
		$callback = array( $this, 'translate_post_url_wpml' );

		add_filter( 'post_link', $callback, 10, 2 );
		add_filter( 'page_link', $callback, 10, 2 );
		add_filter( 'post_type_link', $callback, 10, 2 );
	}

	private function remove_wpml_filters() {
		$callback = array( $this, 'translate_post_url_wpml' );

		remove_filter( 'post_link', $callback, 10 );
		remove_filter( 'page_link', $callback, 10 );
		remove_filter( 'post_type_link', $callback, 10 );
	}

	/**
	 * @param $link
	 * @param $post WP_Post
	 *
	 * @return string
	 */
	public function translate_post_url_wpml( $link, $post_or_id ) {
		global $sitepress;

		$post = get_post( $post_or_id );
		$language = wpml_get_language_information( null, $post->ID );
		$language_code = smartcrawl_get_array_value( $language, 'language_code' );
		if ( $sitepress->get_current_language() === $language_code ) {
			return $link;
		}

		$this->remove_wpml_filters(); // To avoid infinite recursion
		$language_url = apply_filters( 'wpml_permalink', get_permalink( $post->ID ), $language_code, true );
		$this->add_wpml_filters();

		return $language_url;
	}

	private function is_preview_request() {
		return is_admin()
		       && smartcrawl_is_switch_active( 'DOING_AJAX' )
		       && isset( $_POST['_wds_nonce'] )
		       && (
			       wp_verify_nonce( $_POST['_wds_nonce'], 'wds-metabox-nonce' )
			       || wp_verify_nonce( $_POST['_wds_nonce'], 'wds-onpage-nonce' )
		       );
	}

	public function ensure_defender_login_redirect( $url ) {
		if (
			is_user_logged_in()
			|| ! method_exists( '\WP_Defender\Module\Advanced_Tools\Component\Mask_Api', 'maybeAppendTicketToUrl' )
		) {
			return $url;
		}

		return \WP_Defender\Module\Advanced_Tools\Component\Mask_Api::maybeAppendTicketToUrl( $url );
	}

	public function change_sitemap_url_for_domain_map( $sitemap_url ) {
		if (
			is_multisite()
			&& class_exists( 'domain_map' )
			&& smartcrawl_is_switch_active( 'SMARTCRAWL_SITEMAP_DM_SIMPLE_DISCOVERY_FALLBACK' )
		) {
			$sitemap_url = ( is_network_admin() ? '../../' : ( is_admin() ? '../' : '/' ) ) . 'sitemap.xml'; // Simplest possible logic.
		}

		return $sitemap_url;
	}
}
