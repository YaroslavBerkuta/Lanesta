<?php

class Smartcrawl_Configs_Service extends Smartcrawl_Service {
	const VERB_GET_PACKAGE_CONFIGS = 'get-package-configs';
	const VERB_CREATE_CONFIG = 'create-config';
	const VERB_UPDATE_CONFIG = 'update-config';
	const VERB_DELETE_CONFIG = 'delete-config';
	const REST_BASE = 'package-configs';

	/**
	 * @var Smartcrawl_Config_Model
	 */
	private $config;

	public function get_service_base_url() {
		$base_url = 'https://wpmudev.com/';
		if ( defined( 'WPMUDEV_CUSTOM_API_SERVER' ) && WPMUDEV_CUSTOM_API_SERVER ) {
			$base_url = trailingslashit( WPMUDEV_CUSTOM_API_SERVER );
		}

		$api = apply_filters(
			$this->get_filter( 'api-endpoint' ),
			'api'
		);

		$namespace = apply_filters(
			$this->get_filter( 'api-namespace' ),
			'hub/v1'
		);

		return trailingslashit( $base_url ) . trailingslashit( $api ) . trailingslashit( $namespace );
	}

	public function get_known_verbs() {
		return array(
			self::VERB_GET_PACKAGE_CONFIGS,
			self::VERB_CREATE_CONFIG,
			self::VERB_UPDATE_CONFIG,
			self::VERB_DELETE_CONFIG,
		);
	}

	public function is_cacheable_verb( $verb ) {
		return false;
	}

	public function get_request_url( $verb ) {
		$query = array(
			'package_id' => SMARTCRAWL_PACKAGE_ID,
		);
		$base_url = trailingslashit( $this->get_service_base_url() ) . self::REST_BASE;
		if (
			( self::VERB_DELETE_CONFIG === $verb || self::VERB_UPDATE_CONFIG === $verb )
			&& $this->config
		) {
			$base_url = trailingslashit( $base_url ) . $this->config->get_hub_id();
		}

		return esc_url_raw( add_query_arg( $query, $base_url ) );
	}

	public function get_request_arguments( $verb ) {
		switch ( $verb ) {
			case self::VERB_CREATE_CONFIG:
				$args = $this->get_create_config_args();
				break;

			case self::VERB_UPDATE_CONFIG:
				$args = $this->get_update_config_args();
				break;

			case self::VERB_DELETE_CONFIG:
				$args = array( 'method' => 'DELETE' );
				break;

			default:
				$args = array( 'method' => 'GET' );
		}

		$args['timeout'] = $this->get_timeout();
		$args['sslverify'] = false;

		$key = (string) $this->get_dashboard_api_key();
		if ( $key ) {
			$args['headers']['Authorization'] = "Basic {$key}";
		}

		$args = apply_filters(
			$this->get_filter( 'configs-args' ),
			$args,
			$verb
		);

		return $args;
	}

	public function get_configs() {
		return $this->request( self::VERB_GET_PACKAGE_CONFIGS );
	}

	/**
	 * @param $config Smartcrawl_Config_Model
	 *
	 * @return mixed
	 */
	public function publish_config( $config ) {
		$this->config = $config;
		$response = $this->request( self::VERB_CREATE_CONFIG );
		$this->config = null;
		return $response;
	}

	/**
	 * @param $config Smartcrawl_Config_Model
	 *
	 * @return mixed
	 */
	public function update_config( $config ) {
		$this->config = $config;
		$response = $this->request( self::VERB_UPDATE_CONFIG );
		$this->config = null;
		return $response;
	}

	/**
	 * @param $config Smartcrawl_Config_Model
	 *
	 * @return mixed
	 */
	public function delete_config( $config ) {
		$this->config = $config;
		$response = $this->request( self::VERB_DELETE_CONFIG );
		$this->config = null;
		return $response;
	}

	public function handle_error_response( $response, $verb ) {
		// TODO: Implement handle_error_response() method.
	}

	/**
	 * @return array
	 */
	private function get_create_config_args() {
		return array(
			'method' => 'POST',
			'body'   => array(
				'name'        => $this->config->get_name(),
				'description' => $this->config->get_description(),
				'package'     => array(
					'name' => 'SmartCrawl Pro',
					'id'   => SMARTCRAWL_PACKAGE_ID,
				),
				'config'      => json_encode( array(
					'configs' => $this->config->get_configs(),
					'strings' => $this->config->get_strings(),
				), true ),
			),
		);
	}

	/**
	 * @return array
	 */
	private function get_update_config_args() {
		return array(
			'method' => 'POST',
			'body'   => array(
				'name'        => $this->config->get_name(),
				'description' => $this->config->get_description(),
				'package'     => array(
					'name' => 'SmartCrawl Pro',
					'id'   => SMARTCRAWL_PACKAGE_ID,
				),
			),
		);
	}
}
