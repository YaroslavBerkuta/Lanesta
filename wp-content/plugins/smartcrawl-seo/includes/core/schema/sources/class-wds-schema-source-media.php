<?php

class Smartcrawl_Schema_Source_Media extends Smartcrawl_Schema_Property_Source {
	const OBJECT = 'image';
	const URL = 'image_url';

	private $media_id;
	private $field;

	public function __construct( $media_id, $field ) {
		parent::__construct();

		$this->media_id = $media_id;
		$this->field = $field;
	}

	public function get_value() {
		if ( $this->field === self::URL ) {
			$image_source = $this->utils->get_attachment_image_source( $this->media_id );

			return $image_source
				? $image_source[0]
				: '';
		} else {
			return $this->utils->get_media_item_image_schema(
				$this->media_id,
				home_url( "/#image-{$this->media_id}" )
			);
		}
	}
}
