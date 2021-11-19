<?php

class Smartcrawl_Seo_Service extends Smartcrawl_Service {

	const ERR_BASE_API_ISSUE = 40;
	const ERR_BASE_CRAWL_RUN = 51;
	const ERR_BASE_COOLDOWN = 52;
	const ERR_BASE_CRAWL_ERR = 53;
	const ERR_BASE_GENERIC = 59;

	public function get_known_verbs() {
		return array();
	}

	public function is_cacheable_verb( $verb ) {
		return false;
	}

	public function get_service_base_url() {
		return false;
	}

	public function get_request_url( $verb ) {
		return false;
	}

	public function get_request_arguments( $verb ) {
		return array();
	}

	/**
	 * Local ignores list sync handler
	 *
	 * @return bool Status
	 */
	public function sync_ignores() {
		return false;
	}

	/**
	 * Checks whether a call is currently being processed
	 *
	 * @return bool
	 */
	public function in_progress() {
		$flag = $this->get_progress_flag();

		$expected_timeout = intval( $flag ) + ( HOUR_IN_SECONDS / 4 );
		if ( ! empty( $flag ) && is_numeric( $flag ) && time() > $expected_timeout ) {
			// Over timeout threshold, clear flag forcefully
			$this->stop();
		}

		return ! ! $flag;
	}

	/**
	 * Gets progress flag state
	 *
	 * @return bool
	 */
	public function get_progress_flag() {
		return get_option( $this->get_filter( 'seo-progress' ), false );
	}

	/**
	 * Stops expecting response
	 *
	 * @return bool
	 */
	public function stop() {
		$this->set_progress_flag( false );

		return true;
	}

	/**
	 * Sets progress flag state
	 *
	 * param bool $flag Whether the service check is in progress
	 *
	 * @return bool
	 */
	public function set_progress_flag( $flag ) {
		if ( ! empty( $flag ) ) {
			$flag = time();
		}

		return ! ! update_option( $this->get_filter( 'seo-progress' ), $flag );
	}

	/**
	 * Public wrapper for start service method call
	 *
	 * @return mixed Service response hash on success, (bool) on failure
	 */
	public function start() {
		$this->stop();

		return false;
	}

	/**
	 * Public wrapper for status service method call
	 *
	 * @return mixed Service response hash on success, (bool)false on failure
	 */
	public function status() {
		return false;
	}

	/**
	 * Public wrapper for result service method call
	 *
	 * @return mixed Service response hash on success, (bool)false on failure
	 */
	public function result() {
		return false;
	}

	/**
	 * Sets result to new value
	 *
	 * Sets both cache and permanent result
	 *
	 * @return bool
	 */
	public function set_result( $result ) {
		return ! ! update_option( $this->get_filter( 'seo-service-result' ), $result );
	}

	/**
	 * Returns last service run time
	 *
	 * Returns either time embedded in results, or the timestamp
	 * from the results service, whichever is greater.
	 *
	 * @return int UNIX timestamp
	 */
	public function get_last_run_timestamp() {
		$recorded = (int) get_option( $this->get_filter( 'seo-service-last_runtime' ), 0 );

		$raw = $this->get_result();
		$embedded = ! empty( $raw['end'] ) ? (int) $raw['end'] : 0;
		if ( empty( $embedded ) && ! empty( $raw['issues']['previous']['timestamp'] ) ) {
			$embedded = (int) $raw['issues']['previous']['timestamp'];
		}

		return max( $recorded, $embedded );
	}

	/**
	 * Public result getter
	 *
	 * @return mixed result
	 */
	public function get_result() {
		$result = get_option( $this->get_filter( 'seo-service-result' ), false );

		return $result;
	}

	/**
	 * Sets service last run time
	 *
	 * Attempts to use embedded result, and falls back
	 * to current timestamp
	 *
	 * @return bool
	 */
	public function set_last_run_timestamp() {
		$raw = $this->get_result();
		$timestamp = ! empty( $raw['end'] ) ? (int) $raw['end'] : 0;
		if ( empty( $timestamp ) && ! empty( $raw['issues']['previous']['timestamp'] ) ) {
			$timestamp = (int) $raw['issues']['previous']['timestamp'];
		}

		if ( empty( $timestamp ) ) {
			$timestamp = time();
		}

		return ! ! update_option( $this->get_filter( 'seo-service-last_runtime' ), $timestamp );
	}

	public function handle_error_response( $response, $verb ) {
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );
		if ( empty( $body ) || empty( $data ) ) {
			$this->_set_error( __( 'Unspecified error', 'wds' ) );

			return true;
		}

		$msg = '';
		if ( ! empty( $data['message'] ) ) {
			$msg = $data['message'];
		}

		if ( ! empty( $data['data']['manage_link'] ) ) {
			$url = esc_url( $data['data']['manage_link'] );
			$msg .= ' <a href="' . $url . '">' . __( 'Manage', 'wds' ) . '</a>';
		}

		if ( ! empty( $msg ) ) {
			$this->_set_error( $msg );
		}

		return true;
	}

	private function _clear_result() {
		return ! ! delete_option( $this->get_filter( 'seo-service-result' ) );
	}

	public function get_report() {
		return new Smartcrawl_SeoReport();
	}

}
