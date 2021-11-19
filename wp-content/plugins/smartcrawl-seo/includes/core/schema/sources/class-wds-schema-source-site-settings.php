<?php

class Smartcrawl_Schema_Source_Site_Settings extends Smartcrawl_Schema_Property_Source {
	const ID = 'site_settings';

	const NAME = 'site_name';
	const DESCRIPTION = 'site_description';
	const URL = 'site_url';
	const ADMIN_EMAIL = 'site_admin_email';

	private $setting;

	public function __construct( $setting ) {
		parent::__construct();

		$this->setting = $setting;
	}

	public function get_value() {
		$setting = str_replace( 'site_', '', $this->setting );
		return get_bloginfo( $setting );
	}
}
