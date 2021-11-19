<?php

/**
 * Class Smartcrawl_Controller_Lighthouse
 *
 * TODO: add more checks to make lighthouse as close to SEO checkups as possible so we can retire checkup
 */
class Smartcrawl_Controller_Lighthouse extends Smartcrawl_Base_Controller {
	const ERROR_RESULT_NOT_FOUND = 30;
	private static $_instance;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function init() {
		add_action( 'wp_ajax_wds-lighthouse-run', array( $this, 'run_lighthouse' ) );
		add_action( 'wp_ajax_wds-lighthouse-start-test', array( $this, 'start_lighthouse_test' ) );
	}

	public function start_lighthouse_test() {
		$request_data = $this->get_request_data();
		if ( empty( $request_data ) ) {
			wp_send_json_error();
		}
		/**
		 * @var Smartcrawl_Lighthouse_Service $lighthouse
		 */
		$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );
		$lighthouse->clear_last_report();
		$lighthouse->stop();
		$lighthouse->start();

		wp_send_json_success();
	}

	public function run_lighthouse() {
		$request_data = $this->get_request_data();
		if ( empty( $request_data ) ) {
			wp_send_json_error();
		}

		/**
		 * @var Smartcrawl_Lighthouse_Service $lighthouse
		 */
		$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );
		$start_time = $lighthouse->get_start_time();
		if ( ! $start_time ) {
			$lighthouse->start();
			wp_send_json_success( array( 'finished' => false ) );
		}

		$now = current_time( 'timestamp' );
		if ( $now < $start_time + 15 ) {
			// Not enough time has passed, buy more time
			wp_send_json_success( array( 'finished' => false ) );
		}

		if ( $now >= $start_time + 90 ) {
			// Too much time has passed, something might be wrong, force user to start over
			$lighthouse->stop();
			$lighthouse->clear_last_report();
			$lighthouse->set_error(
				'timeout',
				esc_html__( 'We were not able to get results for your site', 'wds' )
			);
			wp_send_json_success( array( 'finished' => true ) );
		}

		$lighthouse->refresh_report();
		$last_report = $lighthouse->get_last_report();
		if (
			$last_report->get_error_code() === self::ERROR_RESULT_NOT_FOUND
			|| ! $last_report->is_fresh()
		) {
			// Let's wait a little longer for the results to become available
			wp_send_json_success( array( 'finished' => false ) );
		}

		$lighthouse->stop();
		wp_send_json_success( array( 'finished' => true ) );
	}

	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-lighthouse-nonce' )
			? $_POST
			: array();
	}
}
