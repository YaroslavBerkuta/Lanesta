<?php

class Smartcrawl_Schema_Type {
	/**
	 * @var Smartcrawl_Schema_Utils
	 */
	protected $utils;
	/**
	 * @var WP_Post
	 */
	protected $post;
	/**
	 * @var array
	 */
	protected $type;

	/**
	 * Smartcrawl_Schema_Type constructor.
	 *
	 * @param $type array
	 * @param $post WP_Post
	 */
	private function __construct( $type, $post ) {
		$this->utils = Smartcrawl_Schema_Utils::get();
		$this->type = $type;
		$this->post = $post;
	}

	public function conditions_met() {
		$conditions = smartcrawl_get_array_value( $this->type, 'conditions' );
		if ( is_null( $conditions ) ) {
			return false;
		}

		$conditions_helper = new Smartcrawl_Schema_Type_Conditions( $conditions, $this->post );
		return $conditions_helper->met();
	}

	public function get_type() {
		return smartcrawl_get_array_value( $this->type, 'type' );
	}

	public function get_schema() {
		$type = $this->get_type();
		$properties = smartcrawl_get_array_value( $this->type, 'properties' );

		if ( is_null( $type ) || is_null( $properties ) ) {
			return array();
		}

		$factory = new Smartcrawl_Schema_Source_Factory( $this->post );
		$property_value_helper = new Smartcrawl_Schema_Property_Values( $factory, $this->post );
		$property_values = $property_value_helper->get_property_values( $properties );

		if ( empty( $property_values ) ) {
			return array();
		}

		return array( '@type' => $type ) + $property_values;
	}

	public function is_active() {
		return ! smartcrawl_get_array_value( $this->type, 'disabled' );
	}

	public static function create( $data, $post ) {
		$type = smartcrawl_get_array_value( $data, 'type' );

		switch ( $type ) {
			case Smartcrawl_Schema_Type_Woo_Product::TYPE:
			case Smartcrawl_Schema_Type_Woo_Product::TYPE_SIMPLE:
			case Smartcrawl_Schema_Type_Woo_Product::TYPE_VARIABLE:
				return new Smartcrawl_Schema_Type_Woo_Product( $data, $post );

			default:
				return new self( $data, $post );
		}
	}
}
