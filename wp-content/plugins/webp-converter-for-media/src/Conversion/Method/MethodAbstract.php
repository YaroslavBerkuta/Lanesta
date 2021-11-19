<?php

namespace WebpConverter\Conversion\Method;

use WebpConverter\Conversion\Exception;
use WebpConverter\Conversion\OutputPath;
use WebpConverter\Settings\Option\ExtraFeaturesOption;

/**
 * Abstract class for class that converts images.
 */
abstract class MethodAbstract implements MethodInterface {

	/**
	 * Messages of errors that occurred during conversion.
	 *
	 * @var string[]
	 */
	protected $errors = [];

	/**
	 * Sum of size of source images before conversion.
	 *
	 * @var int
	 */
	protected $size_before = 0;

	/**
	 * Sum of size of output images after conversion.
	 *
	 * @var int
	 */
	protected $size_after = 0;

	/**
	 * {@inheritdoc}
	 */
	public function get_errors(): array {
		return $this->errors;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_size_before(): int {
		return $this->size_before;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_size_after(): int {
		return $this->size_after;
	}

	/**
	 * @return void
	 */
	protected function set_server_config() {
		ini_set( 'memory_limit', '1G' ); // phpcs:ignore
		if ( strpos( ini_get( 'disable_functions' ) ?: '', 'set_time_limit' ) === false ) {
			set_time_limit( 120 );
		}
	}

	/**
	 * Checks server path of source image.
	 *
	 * @param string $source_path Server path of source image.
	 *
	 * @return string Server path of source image.
	 *
	 * @throws Exception\SourcePathException
	 */
	protected function get_image_source_path( string $source_path ): string {
		$path = urldecode( $source_path );
		if ( ! is_readable( $path ) ) {
			throw new Exception\SourcePathException( $path );
		}

		return $path;
	}

	/**
	 * Returns server path for output image.
	 *
	 * @param string $source_path Server path of source image.
	 * @param string $format      Extension of output format.
	 *
	 * @return string Server path of output image.
	 *
	 * @throws Exception\OutputPathException
	 */
	protected function get_image_output_path( string $source_path, string $format ): string {
		if ( ! $output_path = OutputPath::get_path( $source_path, true, $format ) ) {
			throw new Exception\OutputPathException( $source_path );
		}

		return $output_path;
	}

	/**
	 * Returns results data of conversion.
	 *
	 * @param string $source_path Server path of source image.
	 * @param string $output_path Server path of output image.
	 *
	 * @return int[] Results data of conversion.
	 */
	protected function get_conversion_stats( string $source_path, string $output_path ): array {
		$size_before = filesize( $source_path );
		$size_after  = ( file_exists( $output_path ) ) ? filesize( $output_path ) : $size_before;

		return [
			'size_before' => $size_before ?: 0,
			'size_after'  => $size_after ?: 0,
		];
	}

	/**
	 * @param string  $error_message   .
	 * @param mixed[] $plugin_settings .
	 *
	 * @return void
	 */
	protected function save_conversion_error( string $error_message, array $plugin_settings ) {
		$features = $plugin_settings[ ExtraFeaturesOption::OPTION_NAME ];
		if ( in_array( ExtraFeaturesOption::OPTION_VALUE_DEBUG_ENABLED, $features ) ) {
			error_log( sprintf( 'WebP Converter for Media: %s', $error_message ) ); // phpcs:ignore
		}
	}
}
