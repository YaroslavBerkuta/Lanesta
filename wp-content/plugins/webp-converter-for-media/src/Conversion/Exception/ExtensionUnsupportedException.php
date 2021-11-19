<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "unsupported_extension" exception when converting images.
 */
class ExtensionUnsupportedException extends ExceptionAbstract {

	const ERROR_MESSAGE = 'Unsupported extension "%s" for file "%s".';
	const ERROR_CODE    = 'unsupported_extension';

	/**
	 * {@inheritdoc}
	 */
	public function get_error_message( array $values ): string {
		return sprintf( self::ERROR_MESSAGE, $values[0], $values[1] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_error_status(): string {
		return self::ERROR_CODE;
	}
}
