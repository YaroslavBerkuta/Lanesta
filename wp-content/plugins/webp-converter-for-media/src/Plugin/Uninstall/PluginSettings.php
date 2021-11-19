<?php

namespace WebpConverter\Plugin\Uninstall;

use WebpConverter\Error\ErrorDetectorAggregator;
use WebpConverter\Helper\OptionsAccess;
use WebpConverter\Notice\ThanksNotice;
use WebpConverter\Notice\WelcomeNotice;
use WebpConverter\Plugin\Update;
use WebpConverter\Settings\SettingsSave;

/**
 * Removes options saved by plugin.
 */
class PluginSettings {

	/**
	 * Removes options from wp_options table.
	 *
	 * @return void
	 */
	public static function remove_plugin_settings() {
		OptionsAccess::delete_option( ThanksNotice::NOTICE_OLD_OPTION );
		OptionsAccess::delete_option( ThanksNotice::NOTICE_OPTION );
		OptionsAccess::delete_option( WelcomeNotice::NOTICE_OPTION );
		OptionsAccess::delete_option( ErrorDetectorAggregator::ERRORS_CACHE_OPTION );
		OptionsAccess::delete_option( SettingsSave::SETTINGS_OPTION );
		OptionsAccess::delete_option( Update::VERSION_OPTION );
	}
}
