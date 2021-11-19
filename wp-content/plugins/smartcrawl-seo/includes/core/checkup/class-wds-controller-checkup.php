<?php

/**
 * Class Smartcrawl_Controller_Checkup
 * 
 * TODO: allow checkups on subsites
 */
class Smartcrawl_Controller_Checkup extends Smartcrawl_Base_Controller {
	private static $_instance;
	/**
	 * @var Smartcrawl_Model_Ignores
	 */
	private $model;

	public function __construct() {
		$this->model = new Smartcrawl_Model_Ignores( Smartcrawl_Model_Ignores::IGNORES_CHECKUP_STORAGE );
	}

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Child controllers can use this method to initialize.
	 *
	 * @return mixed
	 */
	protected function init() {
		add_action( 'wp_ajax_wds-checkup-ignore', array( $this, 'json_ignore_checkup_issues' ) );
		add_action( 'wp_ajax_wds-checkup-unignore', array( $this, 'json_unignore_checkup_issues' ) );
		add_action( 'wp_ajax_wds-start-checkup', array( $this, 'start_checkup' ) );

		return true;
	}

	public function start_checkup() {
		$request_data = $this->get_request_data();
		if ( empty( $request_data ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_CHECKUP );
		$service->start();
		wp_send_json_success();
	}

	public function json_ignore_checkup_issues() {
		$result = array( 'status' => 0 );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json( $result );
			return;
		}

		$data = $this->get_request_data();
		if ( empty( $data['issue_id'] ) ) {
			wp_send_json( $result );
			return;
		}

		$issue_id = sanitize_text_field( $data['issue_id'] );
		$ignores = $this->get_ignores_model();
		$ignores->set_ignore( $issue_id );

		$this->maybe_sync_ignores();
		$this->send_success_response();
	}

	public function json_unignore_checkup_issues() {
		$result = array( 'status' => 0 );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json( $result );
			return;
		}

		$data = $this->get_request_data();
		if ( empty( $data['issue_id'] ) ) {
			wp_send_json( $result );
			return;
		}

		$issue_id = sanitize_text_field( $data['issue_id'] );
		$ignores = $this->get_ignores_model();
		$ignores->unset_ignore( $issue_id );

		$this->maybe_sync_ignores();
		$this->send_success_response();
	}

	private function send_success_response() {
		wp_send_json( array(
			'status'        => 1,
			'top_markup'    => Smartcrawl_Checkup_Renderer::load( 'checkup/checkup-top' ),
			'report_markup' => Smartcrawl_Checkup_Renderer::load( 'checkup/checkup-checkup' ),
			'nav_markup'    => Smartcrawl_Checkup_Renderer::load( 'checkup/checkup-side-nav', array(
				'active_tab' => 'tab_checkup',
			) ),
		) );
	}

	private function get_request_data() {
		return isset( $_POST['_wds_checkup_nonce'] )
		       && wp_verify_nonce( $_POST['_wds_checkup_nonce'], 'wds-checkup-nonce' )
			? stripslashes_deep( $_POST )
			: array();
	}

	/**
	 * @return Smartcrawl_Model_Ignores
	 */
	private function get_ignores_model() {
		return $this->model;
	}

	/**
	 * @return Smartcrawl_Checkup_Service
	 */
	private function get_checkup_service() {
		return Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_CHECKUP );
	}

	private function maybe_sync_ignores() {
		$service = $this->get_checkup_service();
		if ( ! $service->is_member() ) {
			// Ignores are member only
			return;
		}

		if ( ! $service->sync_ignores() ) {
			Smartcrawl_Logger::debug( 'We encountered an error syncing ignores with Hub' );
		}
	}
}
