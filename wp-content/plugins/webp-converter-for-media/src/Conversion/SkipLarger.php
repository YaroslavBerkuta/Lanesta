<?php

namespace WebpConverter\Conversion;

use WebpConverter\Conversion\Exception;
use WebpConverter\HookableInterface;
use WebpConverter\PluginData;
use WebpConverter\Settings\Option\ExtraFeaturesOption;

/**
 * Deletes output after conversion if it is larger than original.
 */
class SkipLarger implements HookableInterface {

	const DELETED_FILE_EXTENSION = 'deleted';

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
	public function init_hooks() {
		add_action( 'webpc_convert_after', [ $this, 'remove_image_if_is_larger' ], 10, 2 );
	}

	/**
	 * Removes converted output image if it is larger than original image.
	 *
	 * @param string $webp_path     Server path of output image.
	 * @param string $original_path Server path of source image.
	 *
	 * @return void
	 * @throws Exception\LargerThanOriginalException
	 * @internal
	 */
	public function remove_image_if_is_larger( string $webp_path, string $original_path ) {
		if ( ( ! $settings = $this->plugin_data->get_plugin_settings() )
			|| ! in_array( ExtraFeaturesOption::OPTION_VALUE_ONLY_SMALLER, $settings[ ExtraFeaturesOption::OPTION_NAME ] )
			|| ( ! file_exists( $webp_path ) || ! file_exists( $original_path ) )
			|| ( filesize( $webp_path ) < filesize( $original_path ) ) ) {
			return;
		}

		$file = fopen( $webp_path . '.' . self::DELETED_FILE_EXTENSION, 'w' );
		if ( $file !== false ) {
			fclose( $file );
			unlink( $webp_path );
		}

		throw new Exception\LargerThanOriginalException( $original_path );
	}
}
