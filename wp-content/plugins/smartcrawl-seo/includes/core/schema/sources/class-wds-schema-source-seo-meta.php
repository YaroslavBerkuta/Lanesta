<?php

class Smartcrawl_Schema_Source_SEO_Meta extends Smartcrawl_Schema_Property_Source {
	const ID = 'seo_meta';

	const TITLE = 'seo_title';
	const DESCRIPTION = 'seo_description';

	private $field;

	public function __construct( $field ) {
		parent::__construct();

		$this->field = $field;
	}

	public function get_value() {
		$helper = Smartcrawl_Meta_Value_Helper::get();

		if ( $this->field === self::TITLE ) {
			return $helper->get_title();
		} else {
			return $helper->get_description();
		}
	}
}
