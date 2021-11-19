<?php

namespace WebpConverter\Conversion\Exception;

/**
 * Handles "server_configuration" exception when converting images.
 */
class ServerConfigurationException extends ExceptionAbstract {

	const ERROR_MESSAGE = 'Server configuration: "%s" function is not available.';
	const ERROR_CODE    = 'server_configuration';

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
