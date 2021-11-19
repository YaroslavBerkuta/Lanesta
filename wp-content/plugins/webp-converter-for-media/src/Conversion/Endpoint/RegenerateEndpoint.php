<?php

namespace WebpConverter\Conversion\Endpoint;

use WebpConverter\Conversion\Method\MethodIntegrator;

/**
 * Supports endpoint for converting list of paths to images.
 */
class RegenerateEndpoint extends EndpointAbstract {

	/**
	 * {@inheritdoc}
	 */
	public function get_route_name(): string {
		return 'regenerate';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_args(): array {
		return [
			'paths' => [
				'description'       => 'Array of file paths (server paths)',
				'required'          => true,
				'default'           => [],
				'validate_callback' => function ( $value ) {
					return ( is_array( $value ) && $value );
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_response( \WP_REST_Request $request ) {
		$params = $request->get_params();
		$data   = $this->convert_images( $params['paths'] );

		if ( $data !== false ) {
			return new \WP_REST_Response(
				$data,
				200
			);
		} else {
			return new \WP_Error(
				'webpc_rest_api_error',
				'',
				[
					'status' => 405,
				]
			);
		}
	}

	/**
	 * Initializes image conversion to output formats.
	 *
	 * @param string[] $paths Server paths of source images.
	 *
	 * @return array[]|false Status of conversion.
	 */
	public function convert_images( array $paths ) {
		$response = ( new MethodIntegrator( $this->plugin_data ) )->init_conversion( $paths );
		if ( $response === null ) {
			return false;
		}
		return $response;
	}
}
