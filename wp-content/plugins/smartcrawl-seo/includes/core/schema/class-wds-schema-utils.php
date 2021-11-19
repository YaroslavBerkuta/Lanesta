<?php

class Smartcrawl_Schema_Utils {
	private static $_instance;
	/**
	 * @var array
	 */
	private $social_options;

	/**
	 * @var array
	 */
	private $schema_options;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function url_to_id( $url, $id ) {
		/**
		 * @var $wp_rewrite WP_Rewrite
		 */
		global $wp_rewrite;
		if ( $wp_rewrite->using_permalinks() ) {
			$url = trailingslashit( $url );
		}

		return $url . $id;
	}

	public function get_schema_option( $key ) {
		$value = smartcrawl_get_array_value( $this->get_schema_options(), $key );
		if ( is_string( $value ) ) {
			return sanitize_text_field( trim( $value ) );
		}

		return $value;
	}

	private function get_schema_options() {
		if ( empty( $this->schema_options ) ) {
			$schema = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SCHEMA );
			$this->schema_options = is_array( $schema ) ? $schema : array();
		}

		return $this->schema_options;
	}

	private function get_social_options() {
		if ( empty( $this->social_options ) ) {
			$social = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );
			$this->social_options = is_array( $social ) ? $social : array();
		}

		return $this->social_options;
	}

	public function get_social_option( $key ) {
		$value = smartcrawl_get_array_value( $this->get_social_options(), $key );
		if ( is_string( $value ) ) {
			return sanitize_text_field( trim( $value ) );
		}

		return $value;
	}

	public function get_media_item_image_schema( $media_item_id, $schema_id ) {
		if ( ! $media_item_id ) {
			return array();
		}

		$media_item = $this->get_attachment_image_source( $media_item_id );
		if ( ! $media_item ) {
			return array();
		}

		return $this->get_image_schema(
			$schema_id,
			$media_item[0],
			$media_item[1],
			$media_item[2],
			wp_get_attachment_caption( $media_item_id )
		);
	}

	public function get_attachment_image_source( $media_item_id ) {
		$media_item = wp_get_attachment_image_src( $media_item_id, 'full' );
		if ( ! $media_item || count( $media_item ) < 3 ) {
			return false;
		}
		return $media_item;
	}

	public function get_image_schema( $id, $url, $width = '', $height = '', $caption = '' ) {
		$image_schema = array(
			'@type' => 'ImageObject',
			'@id'   => $id,
			'url'   => $url,
		);

		if ( $height ) {
			$image_schema['height'] = $height;
		}

		if ( $width ) {
			$image_schema['width'] = $width;
		}

		if ( $caption ) {
			$image_schema['caption'] = $caption;
		}

		return $image_schema;
	}

	public function apply_filters( $filter, ...$args ) {
		return apply_filters( "wds-schema-{$filter}", ...$args );
	}

	public function reset_options() {
		$this->schema_options = array();
		$this->social_options = array();
	}
}
