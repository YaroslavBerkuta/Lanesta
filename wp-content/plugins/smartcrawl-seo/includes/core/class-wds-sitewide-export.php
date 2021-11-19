<?php

class Smartcrawl_Sitewide_Export extends Smartcrawl_Export {
	public static function load() {
		$me = new self();

		$me->load_all();

		// The source of the sitewide export is always the network admin even if the load method is called on a subsite
		// so set network URL
		$me->_model->set_url( network_home_url() );

		return $me->_model;
	}

	public function load_options() {
		$options = array();

		$components = Smartcrawl_Settings::get_all_components();
		foreach ( $components as $component ) {
			$option_name = $this->get_option_name( $component );
			$options[ $option_name ] = get_site_option( $option_name );
		}

		$options['wds_settings_options'] = get_site_option( 'wds_settings_options' );

		$this->_model->set( Smartcrawl_Model_IO::OPTIONS, $options );

		return $this->_model;
	}

	public function load_redirects() {
		$redirects = get_site_option( Smartcrawl_Model_Redirection::OPTIONS_KEY );
		if ( ! is_array( $redirects ) ) {
			$redirects = array();
		}
		$this->_model->set( Smartcrawl_Model_IO::REDIRECTS, $redirects );

		return $this->_model;
	}

	public function load_redirect_types() {
		$types = get_site_option( Smartcrawl_Model_Redirection::OPTIONS_KEY_TYPES );
		if ( ! is_array( $types ) ) {
			$types = array();
		}
		$this->_model->set( Smartcrawl_Model_IO::REDIRECT_TYPES, $types );

		return $this->_model;
	}
}
