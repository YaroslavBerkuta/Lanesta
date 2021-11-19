<?php

class Smartcrawl_Schema_Property_Values {
	/**
	 * @var Smartcrawl_Schema_Source_Factory
	 */
	private $property_source_factory;

	/**
	 * @var WP_Post context post
	 */
	private $post;

	public function __construct( $property_source_factory, $post ) {
		$this->property_source_factory = $property_source_factory;
		$this->post = $post;
	}

	public function get_property_values( $properties ) {
		$values = array();

		foreach ( $properties as $property_key => $property ) {
			$value = $this->get_property_value( $property );
			if ( ! empty( $value ) ) {
				$values[ $property_key ] = $value;
			}
		}

		return $values;
	}

	private function array_keys_numeric( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}

		return count( array_filter( array_keys( $array ), 'is_numeric' ) ) === count( $array );
	}

	public function get_property_value( $property ) {
		if ( $this->has_alt_versions( $property ) ) {
			return $this->get_property_value( $this->get_active_property_version( $property ) );
		} else if ( $this->has_loop( $property ) ) {
			$loop_id = $this->get_loop_id( $property );
			$loop_helper = Smartcrawl_Schema_Loop::create( $loop_id, $this->post );
			if ( $loop_helper ) {
				return $loop_helper->get_property_value( array_merge(
					$property, array( 'loop' => false ) // Disable loop to avoid infinite recursion
				) );
			}
		} else if ( $this->is_nested_property( $property ) ) {
			$nested_property_values = $this->get_property_values( $this->get_nested_properties( $property ) );
			if ( $this->array_keys_numeric( $nested_property_values ) ) {
				$nested_property_values = array_values( $nested_property_values );
			}
			if ( $nested_property_values && $this->has_required_for_block( $property, $nested_property_values ) ) {
				$property_type_value = $this->get_property_type( $property );
				$property_type = $property_type_value && ! $this->is_simple_type( $property_type_value )
					? array( '@type' => $property_type_value )
					: array();

				return $property_type + $nested_property_values;
			}
		} else {
			$property_value = $this->get_single_property_value( $property );
			if ( $property_value ) {
				return $property_value;
			}
		}

		return '';
	}

	private function has_required_for_block( $property, $values ) {
		if ( ! $this->is_nested_property( $property ) ) {
			return true;
		}

		$nested = $this->get_nested_properties( $property );
		$required_for_block = array_filter( $nested, function ( $nested_property ) {
			return ! empty( $nested_property['requiredInBlock'] );
		} );
		if ( empty( $required_for_block ) || ! is_array( $required_for_block ) ) {
			return true;
		}

		foreach ( array_keys( $required_for_block ) as $required_item ) {
			if ( empty( $values[ $required_item ] ) ) {
				return false;
			}
		}

		return true;
	}

	private function has_loop( $property ) {
		return ! empty( $this->get_loop_id( $property ) );
	}

	private function get_loop_id( $property ) {
		return smartcrawl_get_array_value( $property, 'loop' );
	}

	private function has_alt_versions( $property ) {
		return ! empty( $this->get_active_property_version( $property ) );
	}

	private function get_active_property_version( $property ) {
		$active_version = smartcrawl_get_array_value( $property, 'activeVersion' );
		if ( empty( $active_version ) ) {
			return array();
		}

		return empty( $property['properties'][ $active_version ] )
			? array()
			: $property['properties'][ $active_version ];
	}

	private function is_nested_property( $property ) {
		return (boolean) $this->get_nested_properties( $property );
	}

	private function get_nested_properties( $property ) {
		return smartcrawl_get_array_value( $property, 'properties' );
	}

	private function get_property_type( $property ) {
		return smartcrawl_get_array_value( $property, 'type' );
	}

	private function is_simple_type( $type ) {
		return in_array( $type, array(
			'DateTime',
			'Email',
			'ImageObject',
			'ImageURL',
			'Phone',
			'Text',
			'TextFull',
			'URL',
		) );
	}

	private function get_single_property_value( $property ) {
		$source = smartcrawl_get_array_value( $property, 'source' );
		if ( ! $source ) {
			return '';
		}

		$value = smartcrawl_get_array_value( $property, 'value' );
		$type = $this->get_property_type( $property );
		$property = $this->property_source_factory->create( $source, $value, $type );
		return $property->get_value();
	}
}
