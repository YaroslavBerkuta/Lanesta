<?php

namespace WebpConverter\Conversion\Endpoint;

use WebpConverter\PluginData;

/**
 * Abstract class for class that supports image conversion method.
 */
abstract class EndpointAbstract implements EndpointInterface {

	/**
	 * @var PluginData
	 */
	protected $plugin_data;

	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_args(): array {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_route_url(): string {
		return get_rest_url(
			null,
			sprintf(
				'%1$s/%2$s?_wpnonce=%3$s',
				EndpointIntegration::ROUTE_NAMESPACE,
				$this->get_route_name(),
				wp_create_nonce( 'wp_rest' )
			)
		);
	}
}
