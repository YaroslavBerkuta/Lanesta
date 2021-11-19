<?php

class Smartcrawl_Controller_Data extends Smartcrawl_Base_Controller {
	private $site_service;
	private static $_instance;
	const PROGRESS_OPTION_ID = 'wds-multisite-data-reset-progress';

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function __construct() {
		$this->site_service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SITE );
	}

	public function user_has_permission() {
		if ( is_multisite() ) {
			return current_user_can( 'manage_network_options' );
		}

		return current_user_can( 'manage_options' );
	}

	protected function init() {
		add_action( 'wp_ajax_wds_data_reset', array( $this, 'json_reset' ) );
		add_action( 'wp_ajax_wds_multisite_data_reset', array( $this, 'json_reset_multisite' ) );
	}

	public function reset_multisite() {
		$runner = new Smartcrawl_Subsite_Process_Runner(
			self::PROGRESS_OPTION_ID,
			array( $this, 'reset' )
		);
		$total_sites = $runner->get_total_site_count();
		$next_site_id = $runner->get_next_site_to_process();
		$processed_sites = $runner->run();
		$finished = $total_sites === $processed_sites;

		return array(
			'total_sites'      => $total_sites,
			'completed_sites'  => $processed_sites,
			'progress_message' => $this->get_progress_message( $next_site_id, $finished ),
		);
	}

	/**
	 * @param $next_site_id int
	 * @param $finished boolean
	 *
	 * @return string
	 */
	private function get_progress_message( $next_site_id, $finished ) {
		if ( $finished ) {
			// Finished processing, we don't have a next site
			return esc_html__( 'Finishing up', 'wds' );
		}

		if ( empty( $next_site_id ) ) {
			return '';
		}

		$next_site = get_site( $next_site_id );
		return empty( $next_site->blogname )
			? ''
			: sprintf( esc_html__( 'Resetting %s', 'wds' ), "<strong>{$next_site->blogname}</strong>" );
	}

	public function json_reset_multisite() {
		if ( ! $this->user_has_permission() ) {
			return;
		}
		check_admin_referer( 'wds-multisite-data-reset-nonce', '_wds_nonce' );

		wp_send_json_success( $this->reset_multisite() );
	}

	public function json_reset() {
		if ( ! $this->user_has_permission() ) {
			return;
		}
		check_admin_referer( 'wds-data-reset-nonce', '_wds_nonce' );

		$this->reset();

		wp_send_json_success();
	}

	/**
	 * Resets data and settings based on user's data retention options
	 */
	public function uninstall() {
		$options = Smartcrawl_Settings::get_options();
		$keep_settings = (boolean) smartcrawl_get_array_value( $options, 'keep_settings_on_uninstall' );
		$keep_data = (boolean) smartcrawl_get_array_value( $options, 'keep_data_on_uninstall' );

		if ( ! $keep_settings ) {
			$this->reset_settings();
		}

		if ( ! $keep_data ) {
			$this->reset_data();
		}

		wp_cache_flush();
	}

	/**
	 * Reset all settings and data.
	 */
	public function reset() {
		$this->reset_settings();
		$this->reset_data();

		wp_cache_flush();

		return true;
	}

	/**
	 * Settings include options, post meta and taxonomy meta
	 */
	public function reset_settings() {
		$this->remove_options();
		if ( is_multisite() && is_main_site() ) {
			$this->remove_site_options();
		}

		$this->remove_post_meta();
		$this->remove_user_meta();
	}

	/**
	 * Data includes checkup/crawl results and all files stored by the plugin
	 */
	public function reset_data() {
		$this->remove_service_results();
		if ( is_multisite() && is_main_site() ) {
			$this->remove_site_service_results();
		}

		$this->remove_files();
	}

	private function remove_site_options() {
		global $wpdb;
		$service_model_key = $this->get_service_model_key();

		return $wpdb->query(
			$wpdb->prepare( "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s AND meta_key NOT LIKE %s", 'wds%', $service_model_key )
		);
	}

	private function remove_options() {
		global $wpdb;
		$service_model_key = $this->get_service_model_key();

		return $wpdb->query(
			$wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s AND option_name NOT LIKE %s", 'wds%', $service_model_key )
		);
	}

	private function remove_post_meta() {
		global $wpdb;
		return $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_wds%'" );
	}

	private function remove_user_meta() {
		global $wpdb;
		return $wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'wds_%'" );
	}

	private function get_service_model_key() {
		return $this->site_service->get_filter( '%' );
	}

	private function remove_site_service_results() {
		global $wpdb;
		$key = $this->get_service_model_key();

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s", "$key" ) );
	}

	private function remove_service_results() {
		global $wpdb;
		$key = $this->get_service_model_key();

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", "$key" ) );
	}

	private function remove_files() {
		$file_system = $this->fs_direct();
		$file_system->rmdir( smartcrawl_uploads_dir(), true );
	}

	private function fs_direct() {
		if ( ! class_exists( 'WP_Filesystem_Direct', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		}
		return new WP_Filesystem_Direct( null );
	}
}
