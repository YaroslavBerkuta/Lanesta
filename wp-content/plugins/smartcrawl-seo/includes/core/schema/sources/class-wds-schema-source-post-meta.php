<?php

class Smartcrawl_Schema_Source_Post_Meta extends Smartcrawl_Schema_Property_Source {
	const ID = 'post_meta';

	private $meta_key;
	private $post;

	public function __construct( $post, $meta_key ) {
		parent::__construct();

		$this->meta_key = $meta_key;
		$this->post = $post;
	}

	public function get_value() {
		$meta_value = get_post_meta( $this->post->ID, $this->meta_key, true );
		if ( $meta_value && is_scalar( $meta_value ) ) {
			return $meta_value;
		}

		return '';
	}
}
