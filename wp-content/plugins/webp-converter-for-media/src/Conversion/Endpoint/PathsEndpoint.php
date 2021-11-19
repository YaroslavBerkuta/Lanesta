<?php

namespace WebpConverter\Conversion\Endpoint;

use WebpConverter\Settings\Option\SupportedDirectoriesOption;

/**
 * Supports endpoint to get list of image paths to be converted.
 */
class PathsEndpoint extends EndpointAbstract {

	const PATHS_PER_REQUEST = 10;

	/**
	 * {@inheritdoc}
	 */
	public function get_route_name(): string {
		return 'paths';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_args(): array {
		return [
			'regenerate_force' => [
				'description'       => 'Option to force all images to be converted again (set `1` to enable)',
				'required'          => false,
				'default'           => false,
				'sanitize_callback' => function ( $value ) {
					return ( $value === '1' );
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_response( \WP_REST_Request $request ) {
		$params         = $request->get_params();
		$skip_converted = ( $params['regenerate_force'] !== true );

		$data = $this->get_paths( $skip_converted, self::PATHS_PER_REQUEST );
		return new \WP_REST_Response(
			$data,
			200
		);
	}

	/**
	 * Returns list of server paths of source images to be converted.
	 *
	 * @param bool $skip_converted Skip converted images?
	 * @param int  $chunk_size     Number of files per one conversion request.
	 *
	 * @return array[] Server paths of source images.
	 */
	public function get_paths( bool $skip_converted = false, int $chunk_size = 0 ): array {
		$settings = $this->plugin_data->get_plugin_settings();
		$dirs     = array_filter(
			array_map(
				function ( $dir_name ) {
					return apply_filters( 'webpc_dir_path', '', $dir_name );
				},
				$settings[ SupportedDirectoriesOption::OPTION_NAME ]
			)
		);

		$list = [];
		foreach ( $dirs as $dir_path ) {
			$paths = apply_filters( 'webpc_dir_files', [], $dir_path, $skip_converted );
			$list  = array_merge( $list, $paths );
		}

		if ( $chunk_size === 0 ) {
			return $list;
		}
		return array_chunk( $list, $chunk_size );
	}
}
