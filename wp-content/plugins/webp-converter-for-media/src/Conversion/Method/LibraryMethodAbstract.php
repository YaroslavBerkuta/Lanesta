<?php

namespace WebpConverter\Conversion\Method;

use WebpConverter\Conversion\Exception;
use WebpConverter\Conversion\SkipCrashed;
use WebpConverter\Conversion\SkipLarger;
use WebpConverter\Settings\Option\OutputFormatsOption;

/**
 * Abstract class for class that converts images using the PHP library.
 */
abstract class LibraryMethodAbstract extends MethodAbstract implements LibraryMethodInterface {

	/**
	 * @var SkipCrashed
	 */
	private $skip_crashed;

	public function __construct( SkipCrashed $skip_crashed ) {
		$this->skip_crashed = $skip_crashed;
	}

	/**
	 * {@inheritdoc}
	 */
	public function convert_paths( array $paths, array $plugin_settings ) {
		$output_formats = $plugin_settings[ OutputFormatsOption::OPTION_NAME ];
		foreach ( $output_formats as $output_format ) {
			foreach ( $paths as $path ) {
				try {
					$response = $this->convert_path( $path, $output_format, $plugin_settings );

					$this->size_before += $response['data']['size_before'];
					$this->size_after  += $response['data']['size_after'];
				} catch ( \Exception $e ) {
					$this->errors[] = $e->getMessage();
				}
			}
		}
	}

	/**
	 * Converts source path to output formats.
	 *
	 * @param string  $path            Server path of source image.
	 * @param string  $format          Extension of output format.
	 * @param mixed[] $plugin_settings .
	 *
	 * @return mixed[] Results data of conversion.
	 *
	 * @throws Exception\OutputPathException
	 * @throws Exception\SourcePathException
	 */
	private function convert_path( string $path, string $format, array $plugin_settings ): array {
		$this->set_server_config();

		try {
			$source_path = $this->get_image_source_path( $path );
			$output_path = $this->get_image_output_path( $source_path, $format );

			$this->skip_crashed->create_crashed_file( $output_path );

			$image = $this->create_image_by_path( $source_path, $plugin_settings );
			$this->convert_image_to_output( $image, $source_path, $output_path, $format, $plugin_settings );

			$this->skip_crashed->delete_crashed_file( $output_path );

			if ( file_exists( $output_path . '.' . SkipLarger::DELETED_FILE_EXTENSION ) ) {
				unlink( $output_path . '.' . SkipLarger::DELETED_FILE_EXTENSION );
			}
			do_action( 'webpc_convert_after', $output_path, $source_path );

			return [
				'success' => true,
				'message' => null,
				'data'    => $this->get_conversion_stats( $source_path, $output_path ),
			];
		} catch ( \Exception $e ) {
			$this->save_conversion_error( $e->getMessage(), $plugin_settings );
			throw $e;
		}
	}
}
