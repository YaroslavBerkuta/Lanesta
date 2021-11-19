<?php

namespace WebpConverter\Error\Detector;

use WebpConverter\Error\Notice\SettingsIncorrectNotice;
use WebpConverter\PluginData;
use WebpConverter\Settings\Option\ConversionMethodOption;
use WebpConverter\Settings\Option\ImagesQualityOption;
use WebpConverter\Settings\Option\OutputFormatsOption;
use WebpConverter\Settings\Option\SupportedDirectoriesOption;
use WebpConverter\Settings\Option\SupportedExtensionsOption;

/**
 * Checks for configuration errors about incorrectly saved plugin settings.
 */
class SettingsIncorrectDetector implements ErrorDetector {

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_error() {
		$settings = $this->plugin_data->get_plugin_settings();

		if ( ( ! isset( $settings[ SupportedExtensionsOption::OPTION_NAME ] )
				|| ! $settings[ SupportedExtensionsOption::OPTION_NAME ] )
			|| ( ! isset( $settings[ SupportedDirectoriesOption::OPTION_NAME ] )
				|| ! $settings[ SupportedDirectoriesOption::OPTION_NAME ] )
			|| ( ! isset( $settings[ ConversionMethodOption::OPTION_NAME ] )
				|| ! $settings[ ConversionMethodOption::OPTION_NAME ] )
			|| ( ! isset( $settings[ OutputFormatsOption::OPTION_NAME ] )
				|| ! $settings[ OutputFormatsOption::OPTION_NAME ] )
			|| ( ! isset( $settings[ ImagesQualityOption::OPTION_NAME ] )
				|| ! $settings[ ImagesQualityOption::OPTION_NAME ] ) ) {
			return new SettingsIncorrectNotice();
		}

		return null;
	}
}
