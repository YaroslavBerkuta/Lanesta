<?php

class Smartcrawl_Controller_Sitemap extends Smartcrawl_Base_Controller {

	private static $_instance;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function should_run() {
		return Smartcrawl_Settings::get_setting( 'sitemap' )
		       && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SITEMAP );
	}

	protected function init() {
		add_action( 'wp_ajax_wds_update_sitemap', array( $this, 'json_update_sitemap' ) );
		add_action( 'wp_ajax_wds_update_engines', array( $this, 'json_update_engines' ) );

		add_action( 'wp_ajax_wds-manually-update-engines', array( $this, 'json_manually_update_engines' ) );
		add_action( 'wp_ajax_wds-manually-update-sitemap', array( $this, 'json_manually_update_sitemap' ) );
		add_action( 'wp_ajax_wds-deactivate-sitemap-module', array( $this, 'json_deactivate_sitemap_module' ) );
		add_action( 'wp_ajax_wds-override-native', array( $this, 'json_override_native' ) );

		add_action( 'admin_init', array( $this, 'prime_cache_on_sitemap_settings_page_load' ) );

		add_action( 'update_option_wds_sitemap_options', array( $this, 'invalidate_sitemap_cache' ) );

		add_action( 'wds_plugin_update', array( $this, 'invalidate_sitemap_cache_on_plugin_update' ) );

		if ( Smartcrawl_Sitemap_Utils::auto_regeneration_enabled() ) {
			add_action( 'save_post', array( $this, 'handle_post_save' ) );
			add_action( 'delete_post', array( $this, 'handle_post_delete' ) );
			add_action( 'wp_update_term_data', array( $this, 'handle_term_slug_update' ), 10, 3 );
			add_action( 'pre_delete_term', array( $this, 'handle_term_deletion' ), 10, 2 );
		}
	}

	/**
	 * TODO: remove after enough time has passed after 2.14.2
	 */
	public function invalidate_sitemap_cache_on_plugin_update() {
		if ( SMARTCRAWL_VERSION === '2.14.2' ) {
			// We added escaping in 2.14.2 so it's necessary to clear the cache after update 
			$this->invalidate_sitemap_cache();
		}
	}

	public function prime_cache_on_sitemap_settings_page_load() {
		global $plugin_page;

		$is_sitemap_page = isset( $plugin_page ) && Smartcrawl_Settings::TAB_SITEMAP === $plugin_page;
		if ( ! $is_sitemap_page ) {
			return;
		}

		if ( Smartcrawl_Sitemap_Cache::get()->is_index_cached() ) {
			return;
		}

		Smartcrawl_Sitemap_Utils::prime_cache( false );
	}

	public function json_manually_update_engines() {
		Smartcrawl_Sitemap_Utils::notify_engines( true );
	}

	public function json_manually_update_sitemap() {
		$this->invalidate_sitemap_cache();
	}

	public function json_deactivate_sitemap_module() {
		$data = $this->get_request_data();
		if ( empty( $data ) ) {
			wp_send_json_error();
			return;
		}

		Smartcrawl_Settings::deactivate_component( 'sitemap' );
		wp_send_json_success();
	}

	public function json_override_native() {
		$data = $this->get_request_data();
		$override = smartcrawl_get_array_value( $data, 'override' );

		if ( is_null( $override ) ) {
			wp_send_json_error();
			return;
		}

		Smartcrawl_Sitemap_Utils::set_sitemap_option( 'override-native', (boolean) $override );
		wp_send_json_success();
	}

	/**
	 * Invalidates sitemap cache.
	 *
	 * This is so the next sitemap request re-generates the caches.
	 * Serves as performance improvement for post-based action listeners.
	 *
	 * On setups with large posts table, fully regenerating sitemap can take a
	 * while. So instead, we just invalidate the cache and potentially ping the
	 * search engines to notify them about the change.
	 *
	 * @param $post_id
	 */
	public function handle_post_save( $post_id ) {
		$post = get_post( $post_id );
		if (
			! Smartcrawl_Sitemap_Utils::is_post_type_included( $post->post_type )
			|| wp_is_post_autosave( $post )
			|| wp_is_post_revision( $post )
		) {
			return;
		}

		$this->invalidate_sitemap_cache();
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			// The above if condition is necessary because save_post is called twice by gutenberg: https://github.com/WordPress/gutenberg/issues/12903
			// We don't want the search engines to be notified of sitemap changes twice, so as a workaround we are going to invalidate sitemap cache both times
			// but only prime the cache for gutenberg (and other rests requests)
			Smartcrawl_Sitemap_Utils::prime_cache( false );
		}
	}

	public function handle_post_delete( $post_id ) {
		if ( ! Smartcrawl_Sitemap_Utils::is_post_included( get_post( $post_id ) ) ) {
			return;
		}

		$this->invalidate_sitemap_cache();
		Smartcrawl_Sitemap_Utils::prime_cache( false );
	}

	public function handle_term_slug_update( $data, $term_id, $taxonomy ) {
		$term = get_term( $term_id, $taxonomy );
		$new_slug = smartcrawl_get_array_value( $data, 'slug' );
		$taxonomy_included = Smartcrawl_Sitemap_Utils::is_taxonomy_included( $taxonomy );

		if ( $taxonomy_included && ! empty( $term->count ) && $new_slug !== $term->slug ) {
			$this->invalidate_sitemap_cache();
			Smartcrawl_Sitemap_Utils::prime_cache( false );
		}

		return $data;
	}

	public function handle_term_deletion( $term_id, $taxonomy ) {
		$term = get_term( $term_id, $taxonomy );
		if ( is_wp_error( $term ) ) {
			return;
		}

		if ( ! Smartcrawl_Sitemap_Utils::is_term_included( $term ) ) {
			return;
		}

		$this->invalidate_sitemap_cache();
		Smartcrawl_Sitemap_Utils::prime_cache( false );
	}

	public function json_update_sitemap() {
		$this->invalidate_sitemap_cache();
		Smartcrawl_Sitemap_Utils::prime_cache( true );
		die( 1 );
	}

	public function json_update_engines() {
		Smartcrawl_Sitemap_Utils::notify_engines( 1 );
		die( 1 );
	}

	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-nonce' ) ? stripslashes_deep( $_POST ) : array();
	}

	public function invalidate_sitemap_cache() {
		Smartcrawl_Sitemap_Cache::get()->invalidate();
	}
}
