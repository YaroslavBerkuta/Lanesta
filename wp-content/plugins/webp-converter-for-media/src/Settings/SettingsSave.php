<?php

namespace WebpConverter\Settings;

use WebpConverter\Conversion\Cron\Event;
use WebpConverter\Conversion\Directory\DirectoryFactory;
use WebpConverter\Helper\OptionsAccess;
use WebpConverter\Loader\LoaderAbstract;
use WebpConverter\PluginData;
use WebpConverter\Settings\Option\SupportedDirectoriesOption;

/**
 * Supports saving plugin settings on plugin settings page.
 */
class SettingsSave {

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	const SETTINGS_OPTION   = 'webpc_settings';
	const SUBMIT_VALUE      = 'webpc_save';
	const NONCE_PARAM_KEY   = '_wpnonce';
	const NONCE_PARAM_VALUE = 'webpc-save';

	/**
	 * Saves plugin settings after submitting form on plugin settings page.
	 *
	 * @return void
	 */
	public function save_settings() {
		if ( ! isset( $_POST[ self::SUBMIT_VALUE ] )
			|| ! isset( $_REQUEST[ self::NONCE_PARAM_KEY ] )
			|| ! wp_verify_nonce( $_REQUEST[ self::NONCE_PARAM_KEY ], self::NONCE_PARAM_VALUE ) ) { // phpcs:ignore
			return;
		}

		OptionsAccess::update_option( self::SETTINGS_OPTION, ( new PluginOptions() )->get_values( false, $_POST ) );
		$this->plugin_data->invalidate_plugin_settings();
		$this->init_actions_after_save();
	}

	/**
	 * Runs actions needed after saving plugin settings.
	 *
	 * @return void
	 */
	private function init_actions_after_save() {
		do_action( LoaderAbstract::ACTION_NAME, true );
		wp_clear_scheduled_hook( Event::CRON_ACTION );

		$settings = $this->plugin_data->get_plugin_settings();
		( new DirectoryFactory() )->remove_unused_output_directories( $settings[ SupportedDirectoriesOption::OPTION_NAME ] );
	}
}
