<?php

class Smartcrawl_Schema_Source_Woocommerce_Review extends Smartcrawl_Schema_Property_Source {
	const ID = 'woocommerce_review';

	private $comment;
	private $field;

	public function __construct( $comment, $field ) {
		parent::__construct();

		$this->comment = $comment;
		$this->field = $field;
	}

	public function get_value() {
		if ( empty( $this->comment ) ) {
			return '';
		}

		switch ( $this->field ) {
			case 'comment_date':
				return get_comment_date( 'c', $this->comment );

			case 'comment_author_name':
				return get_comment_author( $this->comment );

			case 'rating_value':
				return get_comment_meta( $this->comment->comment_ID, 'rating', true );

			case 'comment_text':
				return get_comment_text( $this->comment );

			default:
				return '';
		}
	}
}
