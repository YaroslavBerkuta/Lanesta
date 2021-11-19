<?php

namespace WebpConverter\Error\Detector;

use WebpConverter\Conversion\Method\GdMethod;
use WebpConverter\Conversion\Method\ImagickMethod;
use WebpConverter\Error\Notice\LibsNotInstalledNotice;

/**
 * Checks for configuration errors about non-installed methods for converting images.
 */
class LibsNotInstalledDetector implements ErrorDetector {

	/**
	 * {@inheritdoc}
	 */
	public function get_error() {
		if ( GdMethod::is_method_installed() || ImagickMethod::is_method_installed() ) {
			return null;
		}

		return new LibsNotInstalledNotice();
	}
}
