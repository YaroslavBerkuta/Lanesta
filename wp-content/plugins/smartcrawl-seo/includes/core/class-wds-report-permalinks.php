<?php

class Smartcrawl_Report_Permalinks_Controller extends Smartcrawl_Base_Controller {
	const ACTION_QV = 'load-report';
	const ACTION_CHECK_REPORT = 'seo-checkup';
	const ACTION_AUDIT_REPORT = 'sitemap-crawler';

	/**
	 * Static instance
	 *
	 * @var self
	 */
	private static $_instance;

	/**
	 * Static instance getter
	 */
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
		add_action( 'wp', array( $this, 'intercept' ) );

		return true;
	}

	public function intercept() {
		if ( ! is_front_page() || ! isset( $_GET[ self::ACTION_QV ] ) ) {
			return;
		}

		$url = false;

		if ( $_GET[ self::ACTION_QV ] === self::ACTION_CHECK_REPORT ) {
			$url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH );
		} elseif ( $_GET[ self::ACTION_QV ] === self::ACTION_AUDIT_REPORT ) {
			$url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SITEMAP );
		}

		if ( $url ) {
			wp_safe_redirect( apply_filters( 'wds-report-admin-url', $url ) );
			exit;
		}
	}
}
