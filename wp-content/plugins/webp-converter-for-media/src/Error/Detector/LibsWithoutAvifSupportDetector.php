<?php

namespace WebpConverter\Error\Detector;

use WebpConverter\Conversion\Format\AvifFormat;
use WebpConverter\Conversion\Method\GdMethod;
use WebpConverter\Conversion\Method\ImagickMethod;
use WebpConverter\Error\Notice\LibsWithoutAvifSupportNotice;
use WebpConverter\PluginData;
use WebpConverter\Settings\Option\OutputFormatsOption;

/**
 * Checks for configuration errors about image conversion methods that do not support AVIF output format.
 */
class LibsWithoutAvifSupportDetector implements ErrorDetector {

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
		$output_formats = $this->plugin_data->get_plugin_settings()[ OutputFormatsOption::OPTION_NAME ];

		if ( ! in_array( AvifFormat::FORMAT_EXTENSION, $output_formats )
			|| GdMethod::is_method_active( AvifFormat::FORMAT_EXTENSION )
			|| ImagickMethod::is_method_active( AvifFormat::FORMAT_EXTENSION ) ) {
			return null;
		}

		return new LibsWithoutAvifSupportNotice();
	}
}
