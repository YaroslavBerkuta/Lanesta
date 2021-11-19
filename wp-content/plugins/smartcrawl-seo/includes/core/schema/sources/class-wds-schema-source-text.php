<?php

class Smartcrawl_Schema_Source_Text extends Smartcrawl_Schema_Property_Source {
	const ID = 'custom_text';

	private $text;

	public function __construct( $text ) {
		parent::__construct();
		$this->text = $text;
	}

	public function get_value() {
		return $this->text;
	}
}
