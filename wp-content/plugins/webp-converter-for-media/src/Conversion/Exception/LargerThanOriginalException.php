<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "larger_than_original" exception when converting images.
 */
class LargerThanOriginalException extends ExceptionAbstract {

	const ERROR_MESSAGE = 'Image "%s" converted to WebP is larger than original and has been deleted.';
	const ERROR_CODE    = 'larger_than_original';

	/**
	 * {@inheritdoc}
	 */
	public function get_error_message( array $values ): string {
		return sprintf( self::ERROR_MESSAGE, $values[0] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_error_status(): string {
		return self::ERROR_CODE;
	}
}
