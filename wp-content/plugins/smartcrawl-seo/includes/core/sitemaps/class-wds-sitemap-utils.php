<?php
/**
 * SmartCrawl pages optimization classes
 *
 * Smartcrawl_Sitemap_Utils::generate_sitemap()
 * inspired by WordPress SEO by Joost de Valk (http://yoast.com/wordpress/seo/).
 *
 * @package wpmu-dev-seo
 * @since 1.3
 */

/**
 * Sitemap handling class
 *
 * phpcs:ignoreFile -- Since the class has a lot of file operations
 */
class Smartcrawl_Sitemap_Utils {

	const DEFAULT_ITEMS_PER_SITEMAP = 2000;
	const EXTRAS_STORAGE = 'wds-sitemap-extras';
	const IGNORE_URLS_STORAGE = 'wds-sitemap-ignore_urls';
	const IGNORE_IDS_STORAGE = 'wds-sitemap-ignore_post_ids';
	const ENGINE_NOTIFICATION_OPTION_ID = 'wds_engine_notification';
	const SITEMAP_META_OPTION_ID = 'wds_sitemap_dashboard';

	/**
	 * Extra URLs storage setter
	 *
	 * @param array $extras New extra URLs.
	 *
	 * @return bool
	 */
	public static function set_extra_urls( $extras = array() ) {
		if ( ! is_array( $extras ) ) {
			return false;
		}

		return update_option( self::EXTRAS_STORAGE, array_filter( array_unique( $extras ) ) );
	}

	/**
	 * Ignore URLs storage setter
	 *
	 * @param array $extras New ignore URLs.
	 *
	 * @return bool
	 */
	public static function set_ignore_urls( $extras = array() ) {
		if ( ! is_array( $extras ) ) {
			return false;
		}

		return update_option( self::IGNORE_URLS_STORAGE, array_filter( array_unique( $extras ) ) );
	}

	/**
	 * Ignore post IDs storage setter
	 *
	 * @param array $extras New ignore post IDs.
	 *
	 * @return bool
	 */
	public static function set_ignore_ids( $extras = array() ) {
		if ( ! is_array( $extras ) ) {
			return false;
		}

		return update_option( self::IGNORE_IDS_STORAGE, array_filter( array_unique( $extras ) ) );
	}

	/**
	 * Ignore URLs storage getter
	 *
	 * @return array Ignore sitemap URLs.
	 */
	public static function get_ignore_urls() {
		$extras = get_option( self::IGNORE_URLS_STORAGE );
		$ignores = empty( $extras ) || ! is_array( $extras )
			? array()
			: array_filter( array_unique( $extras ) );

		return apply_filters( 'wds-sitemaps-ignore_urls', $ignores );
	}

	/**
	 * Ignore post IDs storage getter
	 *
	 * @return array Ignore sitemap post IDs
	 */
	public static function get_ignore_ids() {
		$extras = get_option( self::IGNORE_IDS_STORAGE );

		return empty( $extras ) || ! is_array( $extras )
			? array()
			: array_filter( array_unique( array_map( 'intval', $extras ) ) );
	}

	/**
	 * Extra URLs storage getter
	 *
	 * @return array Extra sitemap URLs
	 */
	public static function get_extra_urls() {
		$extras = get_option( self::EXTRAS_STORAGE );

		return empty( $extras ) || ! is_array( $extras )
			? array()
			: array_filter( array_unique( $extras ) );
	}

	/**
	 * Notifies search engines of the latest sitemap update
	 *
	 * @param bool $forced Whether to forcefully do engine notification.
	 *
	 * @return bool
	 */
	public static function notify_engines( $forced = false ) {
		if ( smartcrawl_is_switch_active( 'SMARTCRAWL_SITEMAP_SKIP_SE_NOTIFICATION' ) ) {
			Smartcrawl_Logger::debug( 'Skipping SE update notification.' );

			return false;
		}

		$result = array();
		$now = time();
		$smartcrawl_options = Smartcrawl_Settings::get_options();

		if ( $forced || ! empty( $smartcrawl_options['ping-google'] ) ) {
			do_action( 'wds_before_search_engine_update', 'google' );
			wp_remote_get(
				'http://www.google.com/webmasters/tools/ping?sitemap=' . esc_url( smartcrawl_get_sitemap_url() ),
				array( 'blocking' => false )
			);
			$result['google'] = array( 'time' => $now );
			do_action( 'wds_after_search_engine_update', 'google', true, array() );
		}

		if ( $forced || ! empty( $smartcrawl_options['ping-bing'] ) ) {
			do_action( 'wds_before_search_engine_update', 'bing' );
			wp_remote_get(
				'http://www.bing.com/webmaster/ping.aspx?sitemap=' . esc_url( smartcrawl_get_sitemap_url() ),
				array( 'blocking' => false )
			);
			$result['bing'] = array( 'time' => $now );
			do_action( 'wds_after_search_engine_update', 'bing', true, array() );
		}

		update_option( self::ENGINE_NOTIFICATION_OPTION_ID, $result );

		return true;
	}

	public static function update_meta_data( $item_count ) {
		update_option( self::SITEMAP_META_OPTION_ID, array(
			'items' => $item_count,
			'time'  => time(),
		) );
	}

	/**
	 * Gets sitemap stat options
	 *
	 * @return array
	 */
	public static function get_meta_data() {
		$opts = get_option( self::SITEMAP_META_OPTION_ID );

		return is_array( $opts ) ? $opts : array();
	}

	public static function sitemap_enabled() {
		return Smartcrawl_Settings::get_setting( 'sitemap' )
		       && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SITEMAP );
	}

	public static function sitemap_images_enabled() {
		return (boolean) self::get_sitemap_option( 'sitemap-images' )
		       && ! smartcrawl_is_switch_active( 'SMARTCRAWL_SITEMAP_SKIP_IMAGES' );
	}

	public static function stylesheet_enabled() {
		return (boolean) self::get_sitemap_option( 'sitemap-stylesheet' );
	}

	public static function native_sitemap_available() {
		return function_exists( 'wp_sitemaps_get_server' )
		       && is_a( wp_sitemaps_get_server(), 'WP_Sitemaps' )
		       && wp_sitemaps_get_server()->sitemaps_enabled();
	}

	public static function override_native() {
		return (boolean) self::get_sitemap_option( 'override-native' );
	}

	public static function get_items_per_sitemap() {
		$items_per_sitemap = self::get_sitemap_option( 'items-per-sitemap' );

		return is_numeric( $items_per_sitemap )
			? intval( $items_per_sitemap )
			: Smartcrawl_Sitemap_Utils::DEFAULT_ITEMS_PER_SITEMAP;
	}

	public static function get_max_items_per_sitemap() {
		return Smartcrawl_Sitemap_Utils::DEFAULT_ITEMS_PER_SITEMAP;
	}

	public static function get_sitemap_option( $key ) {
		$options = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SITEMAP );
		return smartcrawl_get_array_value( $options, $key );
	}

	public static function set_sitemap_option( $key, $value ) {
		$options = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SITEMAP );
		$options[ $key ] = $value;

		return Smartcrawl_Settings::update_component_options( Smartcrawl_Settings::COMP_SITEMAP, $options );
	}

	public static function auto_regeneration_enabled() {
		return ! self::get_sitemap_option( 'sitemap-disable-automatic-regeneration' );
	}

	public static function is_url_ignored( $url ) {
		$ignore_urls = self::get_ignore_urls();
		if ( ! empty( $ignore_urls ) ) {
			if ( preg_match( smartcrawl_get_relative_urls_regex( $ignore_urls ), $url ) || in_array( $url, $ignore_urls, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $post WP_Post
	 *
	 * @return bool
	 */
	public static function is_post_included( $post ) {
		$query = new Smartcrawl_Sitemap_Posts_Query();
		return $query->is_post_included( $post );
	}

	/**
	 * @param $post_type
	 *
	 * @return bool
	 */
	public static function is_post_type_included( $post_type ) {
		$query = new Smartcrawl_Sitemap_Posts_Query();
		return in_array( $post_type, $query->get_supported_types(), true );
	}

	public static function is_taxonomy_included( $taxonomy ) {
		$query = new Smartcrawl_Sitemap_Terms_Query();
		return in_array( $taxonomy, $query->get_supported_types(), true );
	}

	/**
	 * @param $term WP_Term
	 *
	 * @return bool
	 */
	public static function is_term_included( $term ) {
		$query = new Smartcrawl_Sitemap_Terms_Query();
		return $query->is_term_included( $term );
	}

	public static function prime_cache( $blocking ) {
		wp_remote_get( smartcrawl_get_sitemap_url(), array( 'blocking' => $blocking ) );
	}

	public static function crawler_available() {
		return is_main_site()
		       && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SITEMAP );
	}

	public static function format_timestamp( $timestamp ) {
		$timestamp = intval( $timestamp ) > 0
			? $timestamp
			: time();
		$offset = date( 'O', $timestamp );

		return date( 'Y-m-d\TH:i:s', $timestamp ) . substr( $offset, 0, 3 ) . ':' . substr( $offset, - 2 );
	}
}
