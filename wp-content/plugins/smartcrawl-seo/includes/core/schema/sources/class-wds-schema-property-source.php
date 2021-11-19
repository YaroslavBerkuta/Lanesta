<?php

abstract class Smartcrawl_Schema_Property_Source {
	/**
	 * @var Smartcrawl_Schema_Utils
	 */
	protected $utils;

	public function __construct() {
		$this->utils = Smartcrawl_Schema_Utils::get();
	}

	public abstract function get_value();
}
