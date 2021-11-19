<?php

class Smartcrawl_Schema_Loop_Woocommerce_Reviews extends Smartcrawl_Schema_Loop {
	const ID = 'woocommerce-reviews';
	private $post;

	public function __construct( $post ) {
		$this->post = $post;
	}

	public function get_property_value( $property ) {
		if ( empty( $this->post ) ) {
			return array();
		}

		$schema = array();
		foreach ( $this->get_comments() as $comment ) {
			$factory = new Smartcrawl_Schema_Source_Woocommerce_Review_Factory( $this->post, $comment );
			$property_value_helper = new Smartcrawl_Schema_Property_Values( $factory, $this->post );
			$schema[] = $property_value_helper->get_property_value( $property );
		}

		return $schema;
	}

	private function get_comments() {
		return get_comments(
			array(
				'number'     => 10,
				'post_id'    => $this->post->ID,
				'status'     => 'approve',
				'post_type'  => 'product',
				'parent'     => 0,
				'meta_query' => array(
					array(
						'key'     => 'rating',
						'type'    => 'NUMERIC',
						'compare' => '>',
						'value'   => 0,
					),
				),
			)
		);
	}
}
