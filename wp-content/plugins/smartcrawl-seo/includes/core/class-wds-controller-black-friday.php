<?php

class Smartcrawl_Controller_Black_Friday extends Smartcrawl_Base_Controller {
	const DISMISSED_KEY = 'black-friday-2021';
	const DISMISSED_OPTION_KEY = 'wds-black-friday-2021-dismissed';
	private static $_instance;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function init() {
		add_action( 'wp_ajax_wds-dismiss-black-friday-notice', array( $this, 'dismiss_black_friday_notice' ) );
	}

	private function is_dismissed() {
		if ( get_site_option( self::DISMISSED_OPTION_KEY, false ) ) {
			return true;
		}

		$dismissed_messages = get_user_meta( get_current_user_id(), 'wds_dismissed_messages', true );
		return smartcrawl_get_array_value( $dismissed_messages, self::DISMISSED_KEY ) === true;
	}

	public function show_notice() {
		$is_admin = current_user_can( 'manage_options' );
		$whitelabel_off = ! Smartcrawl_White_Label::get()->is_hide_wpmudev_branding();
		$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SITE );
		$is_free_or_wpmudev_user = ! $service->is_member() || $this->is_wpmu_dev_user();

		return $is_admin
		       && $whitelabel_off
		       && $is_free_or_wpmudev_user
		       && is_main_site()
		       && ! $this->is_dismissed();
	}

	private function is_wpmu_dev_user() {
		if ( class_exists( 'WPMUDEV_Dashboard' ) ) {
			if ( method_exists( 'WPMUDEV_Dashboard_Site', 'allowed_user' ) ) {
				$user_id = get_current_user_id();
				return WPMUDEV_Dashboard::$site->allowed_user( $user_id );
			}
		}

		return false;
	}

	public function dismiss_black_friday_notice() {
		update_site_option( self::DISMISSED_OPTION_KEY, true );
		wp_send_json_success();
	}
}