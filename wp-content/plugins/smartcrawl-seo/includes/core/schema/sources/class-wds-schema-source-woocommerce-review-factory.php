<?php

class Smartcrawl_Schema_Source_Woocommerce_Review_Factory extends Smartcrawl_Schema_Source_Factory {
	private $comment;

	public function __construct( $post, $comment ) {
		parent::__construct( $post );
		$this->comment = $comment;
	}

	public function create( $source, $field, $type ) {
		if ( empty( $this->comment ) ) {
			return $this->create_default_source();
		}

		if ( $source === Smartcrawl_Schema_Source_Woocommerce_Review::ID ) {
			return new Smartcrawl_Schema_Source_Woocommerce_Review( $this->comment, $field );
		}

		return parent::create( $source, $field, $type );
	}
}
