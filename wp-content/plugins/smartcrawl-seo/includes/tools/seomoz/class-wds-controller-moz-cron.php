<?php

class Smartcrawl_Controller_Moz_Cron extends Smartcrawl_Base_Controller {
	const EVENT_HOOK = 'wds_daily_moz_data_hook';
	const OPTION_ID = 'wds-moz-data';
	private static $_instance;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function init() {
		add_action( 'admin_init', array( $this, 'schedule_moz_data_event' ) );
		add_action( self::EVENT_HOOK, array( $this, 'save_moz_data' ) );
	}

	public function schedule_moz_data_event() {
		if ( ! wp_next_scheduled( self::EVENT_HOOK ) ) {
			wp_schedule_event( time(), 'daily', self::EVENT_HOOK );
		}
	}

	public function save_moz_data() {
		$access_id = Smartcrawl_Settings::get_setting( 'access-id' );
		$secret_key = Smartcrawl_Settings::get_setting( 'secret-key' );

		if ( empty( $access_id ) || empty( $secret_key ) ) {
			return;
		}

		$target_url = preg_replace( '!http(s)?:\/\/!', '', home_url() );
		$api = new Smartcrawl_Moz_API( $access_id, $secret_key );
		$urlmetrics = $api->urlmetrics( $target_url );

		$data = get_option( self::OPTION_ID, array() );
		$data = empty( $data ) || ! is_array( $data )
			? array()
			: $data;
		$data[ time() ] = $urlmetrics;
		update_option( self::OPTION_ID, $data );
	}
}
