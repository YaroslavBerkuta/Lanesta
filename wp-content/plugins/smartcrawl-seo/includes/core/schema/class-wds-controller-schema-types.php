<?php

class Smartcrawl_Controller_Schema_Types extends Smartcrawl_Base_Controller {
	const SCHEMA_TYPES_OPTION_ID = 'wds-schema-types';
	const SCHEMA_TYPE_OPTION_PREFIX = 'wds-schema-type-';
	/**
	 * Static instance
	 *
	 * @var self
	 */
	private static $_instance;

	/**
	 * Static instance getter
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function init() {
		add_action( 'init', array( $this, 'save_settings' ) );
	}

	public function save_settings() {
		$types_json = smartcrawl_get_array_value( $_POST, self::SCHEMA_TYPES_OPTION_ID );
		if ( ! $types_json ) {
			return;
		}

		$types = json_decode( stripslashes_deep( $types_json ), true );
		$this->flush_old_schema_types();
		$this->save_schema_types( $types );
	}

	public function get_schema_types() {
		$types = array();
		$type_keys = get_option( self::SCHEMA_TYPES_OPTION_ID, array() );
		foreach ( $type_keys as $type_key ) {
			$type_option = get_option( $this->type_key_to_option_id( $type_key ) );

			if ( $type_option ) {
				$types[ $type_key ] = $type_option;
			}
		}

		return $types;
	}

	private function flush_old_schema_types() {
		$types = get_option( self::SCHEMA_TYPES_OPTION_ID, array() );
		foreach ( $types as $type_key ) {
			delete_option( $this->type_key_to_option_id( $type_key ) );
		}
	}

	private function type_key_to_option_id( $type_key ) {
		return self::SCHEMA_TYPE_OPTION_PREFIX . $type_key;
	}

	private function save_schema_types( $types ) {
		update_option( self::SCHEMA_TYPES_OPTION_ID, array_keys( $types ), false );
		foreach ( $types as $type_key => $schema ) {
			update_option( $this->type_key_to_option_id( $type_key ), $schema, false );
		}
	}
}
