<?php

namespace WebpConverter\Error\Detector;

use WebpConverter\Conversion\Format\WebpFormat;
use WebpConverter\Conversion\Method\GdMethod;
use WebpConverter\Conversion\Method\ImagickMethod;
use WebpConverter\Error\Notice\LibsWithoutWebpSupportNotice;

/**
 * Checks for configuration errors about image conversion methods that do not support WebP output format.
 */
class LibsWithoutWebpSupportDetector implements ErrorDetector {

	/**
	 * {@inheritdoc}
	 */
	public function get_error() {
		if ( GdMethod::is_method_active( WebpFormat::FORMAT_EXTENSION )
			|| ImagickMethod::is_method_active( WebpFormat::FORMAT_EXTENSION ) ) {
			return null;
		}

		return new LibsWithoutWebpSupportNotice();
	}
}
