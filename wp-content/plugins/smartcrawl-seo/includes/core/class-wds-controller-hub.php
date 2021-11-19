<?php

class Smartcrawl_Controller_Hub extends Smartcrawl_Controller_Hub_Abstract {


	private static $_instance;

	private $_is_running = false;

	private function __construct() {
	}

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

		$me->_add_hooks();

		return true;
	}

	/**
	 * Obtain instance without booting up
	 *
	 * @return Smartcrawl_Controller_Hub instance
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
	 */
	private function _add_hooks() {
		add_filter( 'wdp_register_hub_action', array( $this, 'register_hub_actions' ) );

		$this->_is_running = true;
	}

	public function register_hub_actions( $actions ) {
		if ( ! is_array( $actions ) ) {
			return $actions;
		}

		$actions['wds-seo-summary'] = array( $this, 'json_seo_summary' );
		$actions['wds-run-checkup'] = array( $this, 'json_run_checkup' );
		$actions['wds-run-crawl'] = array( $this, 'json_run_crawl' );

		return $actions;
	}
}
