<?php
/**
 * Import and export settings admin handler
 *
 * @package wpmu-dev-seo
 */

/**
 * IO controller class
 */
class Smartcrawl_Controller_Third_Party_Import extends Smartcrawl_WorkUnit {

	/**
	 * Singleton instance holder
	 *
	 * @var Smartcrawl_Controller_Third_Party_Import
	 */
	private static $_instance;

	/**
	 * Controller state flag
	 *
	 * @var bool
	 */
	private $_is_running = false;

	/**
	 * Boot controller listeners
	 *
	 * Do it only once, if they're already up do nothing
	 *
	 * @return bool Status
	 */
	public static function serve() {
		$me = self::get();
		if ( $me->is_running() ) {
			return false;
		}

		return $me->_add_hooks();
	}

	/**
	 * Obtain instance without booting up
	 *
	 * @return Smartcrawl_Controller_Third_Party_Import instance
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Check if we already have the actions bound
	 *
	 * @return bool Status
	 */
	public function is_running() {
		return $this->_is_running;
	}

	/**
	 * Bind listening actions
	 *
	 * @return bool
	 */
	private function _add_hooks() {

		add_action( 'wp_ajax_import_yoast_data', array( $this, 'import_yoast_data' ) );
		add_action( 'wp_ajax_import_aioseop_data', array( $this, 'import_aioseop_data' ) );

		$this->_is_running = true;

		return ! ! $this->_is_running;
	}

	/**
	 * Stops controller listeners
	 *
	 * @return bool
	 */
	public static function stop() {
		$me = self::get();
		if ( ! $me->is_running() ) {
			return false;
		}

		return $me->_remove_hooks();
	}

	/**
	 * Unbinds listening actions
	 *
	 * @return bool
	 */
	private function _remove_hooks() {

		$this->_is_running = false;

		return ! $this->_is_running;
	}

	/**
	 * Filter prefix getter
	 *
	 * @return string
	 */
	public function get_filter_prefix() {
		return 'wds-controller-io';
	}

	public function import_yoast_data() {
		$options = $this->get_import_options_from_request();
		$this->do_import( new Smartcrawl_Yoast_Importer(), $options );
	}

	/**
	 * @param $importer Smartcrawl_Importer
	 * @param $plugin
	 */
	private function do_import( $importer, $options = array() ) {
		$result = array( 'success' => false );

		if ( ! $this->user_has_permission_to_import() ) {
			$result['message'] = __( "You don't have permission to perform this operation.", 'wds' );
			die( wp_json_encode( $result ) );
		}

		if ( ! $importer->data_exists() ) {
			$result['message'] = __( "We couldn't find any compatible data to import.", 'wds' );
			die( wp_json_encode( $result ) );
		}

		if ( is_multisite() ) {
			$importer->import_for_all_sites( $options );
			$in_progress = $importer->is_network_import_in_progress();
		} else {
			$importer->import( $options );
			$in_progress = $importer->is_import_in_progress();
		}
		$result['success'] = true;
		$result['in_progress'] = $in_progress;
		$result['status'] = $importer->get_status();
		$result['deactivation_url'] = $importer->get_deactivation_link();

		die( wp_json_encode( $result ) );
	}

	private function user_has_permission_to_import() {
		if ( ! is_network_admin() && ! is_admin() ) {
			return false;
		}
		if ( is_network_admin() && ! current_user_can( 'manage_network_options' ) ) {
			return false;
		}
		if ( is_admin() && ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return true;
	}

	public function import_aioseop_data() {
		$options = $this->get_import_options_from_request();
		$this->do_import( new Smartcrawl_AIOSEOP_Importer(), $options );
	}

	private function get_request_data() {
		if ( isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-io-nonce' ) ) {
			return stripslashes_deep( $_POST );
		} else {
			if ( ! empty( $_POST['io-action'] ) ) {
				$this->add_error( 'io-nonce-failure', __( 'Invalid parameters. Try refreshing the page and attempting again.', 'wds' ) );
			}
		}

		return array();
	}

	private function get_import_options_from_request() {
		$request_data = $this->get_request_data();
		$options = smartcrawl_get_array_value( $request_data, 'items_to_import' );
		$options['force-restart'] = (boolean) smartcrawl_get_array_value( $request_data, 'restart' );

		return empty( $options ) ? array() : $options;
	}
}

