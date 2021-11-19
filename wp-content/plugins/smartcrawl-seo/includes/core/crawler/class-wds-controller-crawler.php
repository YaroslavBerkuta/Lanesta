<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Smartcrawl_Controller_Crawler extends Smartcrawl_Base_Controller {
	private static $_instance;
	private $seo_service;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		parent::__construct();

		$this->seo_service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SEO );
	}

	protected function init() {
		add_action( 'wp_ajax_wds_redirect_crawl_item', array( $this, 'redirect_crawl_item' ) );
		add_action( 'wp_ajax_wds_ignore_crawl_item', array( $this, 'ignore_crawl_item' ) );
		add_action( 'wp_ajax_wds_restore_crawl_item', array( $this, 'restore_crawl_item' ) );
		add_action( 'wp_ajax_wds_restore_all_crawl_items', array( $this, 'restore_all' ) );
		add_action( 'wp_ajax_wds_sitemap_add_extra', array( $this, 'add_sitemap_extra' ) );
		add_action( 'wp_ajax_wds_get_crawl_progress', array( $this, 'get_crawl_progress' ) );
	}

	public function get_crawl_progress() {
		if ( empty( $this->get_request_data() ) ) {
			wp_send_json_error();
		}

		$report = $this->seo_service->get_report();

		wp_send_json_success( array(
			'in_progress' => $report->is_in_progress(),
			'progress'    => $report->get_progress(),
		) );
	}

	public function redirect_crawl_item() {
		$data = $this->get_request_data();
		if ( empty( $data ) ) {
			wp_send_json_error();
		}

		$source = (string) smartcrawl_get_array_value( $data, 'source' );
		$destination = (string) smartcrawl_get_array_value( $data, 'destination' );
		if ( ! $source || ! $destination ) {
			wp_send_json_error();
		}

		if ( ! preg_match( '/^https?:\/\//', $source ) ) {
			$source = home_url( $source );
		}
		if ( ! preg_match( '/^https?:\/\//', $destination ) ) {
			$destination = home_url( $destination );
		}

		$model = new Smartcrawl_Model_Redirection();
		$model->set_redirection( $source, $destination );
		$model->set_redirection_type(
			$source,
			$model->get_default_redirection_status_type()
		);

		$this->send_success_response();
	}

	public function ignore_crawl_item() {
		$this->change_crawl_item_status( function ( $issue ) {
			$ignores = new Smartcrawl_Model_Ignores();
			$ignores->set_ignore( $issue );
		} );
	}

	public function restore_crawl_item() {
		$this->change_crawl_item_status( function ( $issue ) {
			$ignores = new Smartcrawl_Model_Ignores();
			$ignores->unset_ignore( $issue );
		} );
	}

	public function change_crawl_item_status( $operation ) {
		$data = $this->get_request_data();
		if ( empty( $data ) ) {
			wp_send_json_error();
		}

		$issue_id = smartcrawl_get_array_value( $data, 'issue_id' );
		if ( ! $issue_id ) {
			wp_send_json_error();
		}

		$issue_ids = is_array( $issue_id )
			? array_map( 'sanitize_text_field', $issue_id )
			: array( sanitize_text_field( $issue_id ) );

		foreach ( $issue_ids as $issue ) {
			call_user_func( $operation, $issue );
		}

		$this->sync_ignores_with_hub();

		$this->send_success_response();
	}

	private function restore_all() {
		$data = $this->get_request_data();
		if ( empty( $data ) ) {
			wp_send_json_error();
		}

		$ignores = new Smartcrawl_Model_Ignores();
		$ignores->clear();

		$this->sync_ignores_with_hub();

		$this->send_success_response();
	}

	public function add_sitemap_extra() {
		$data = $this->get_request_data();
		if ( empty( $data ) ) {
			wp_send_json_error();
		}

		$path = smartcrawl_get_array_value( $data, 'path' );
		if ( ! $path ) {
			wp_send_json_error();
		}

		$paths = is_array( $path )
			? array_map( 'sanitize_text_field', (array) $path )
			: array( sanitize_text_field( $path ) );
		if ( ! is_array( $paths ) ) {
			$paths = array();
		}

		$extras = Smartcrawl_Sitemap_Utils::get_extra_urls();
		foreach ( $paths as $current_path ) {
			if ( false !== array_search( $current_path, $extras, true ) ) {
				continue;
			}

			$current_path = esc_url( $current_path );
			if ( false !== array_search( $current_path, $extras, true ) ) {
				continue;
			}

			$extras[] = $current_path;
		}
		Smartcrawl_Sitemap_Utils::set_extra_urls( $extras );
		$this->send_success_response();
	}

	private function send_success_response() {
		$report = $this->seo_service->get_report();
		wp_send_json_success( array(
			'issues' => $report->get_all_issues_grouped_by_type(),
		) );
	}

	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-crawler-nonce' )
			? stripslashes_deep( $_POST )
			: array();
	}

	private function sync_ignores_with_hub() {
		$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SEO );
		if ( ! $service->sync_ignores() ) {
			Smartcrawl_Logger::debug( 'We encountered an error syncing ignores with Hub' );
		}
	}
}
