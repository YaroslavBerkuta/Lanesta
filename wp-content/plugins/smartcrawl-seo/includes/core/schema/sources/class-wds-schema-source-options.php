<?php

class Smartcrawl_Schema_Source_Options extends Smartcrawl_Schema_Property_Source {
	const ID = 'options';

	private $option;
	private $type;

	public function __construct( $option, $type ) {
		parent::__construct();

		$this->option = $option;
		$this->type = $type;
	}

	public function get_value() {
		if ( $this->type !== 'Array' && is_array( $this->option ) ) {
			return join( ',', $this->option );
		}

		return $this->option;
	}
}
