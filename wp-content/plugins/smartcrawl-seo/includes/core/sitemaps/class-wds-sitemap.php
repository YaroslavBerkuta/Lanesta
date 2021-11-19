<?php

abstract class Smartcrawl_Sitemap {
	const SITEMAP_TYPE_INDEX = 'index';

	abstract public function add_rewrites();

	abstract public function can_handle_request();

	abstract public function do_fallback();

	abstract public function serve();

	public function is_enabled() {
		return Smartcrawl_Settings::get_setting( 'sitemap' )
		       && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SITEMAP );
	}

	protected function output_xml( $xml, $gzip ) {
		if ( ! headers_sent() ) {
			status_header( 200 );
			// Prevent the search engines from indexing the XML Sitemap.
			header( 'X-Robots-Tag: noindex, follow', true );
			header( 'Content-Type: text/xml; charset=UTF-8' );

			if (
				$this->is_gzip_supported() &&
				function_exists( 'gzencode' ) &&
				$gzip
			) {
				header( 'Content-Encoding: gzip' );
				$xml = gzencode( $xml );
			}
			die( $xml );
		}
	}

	private function is_gzip_supported() {
		$accepted = (string) smartcrawl_get_array_value( $_SERVER, 'HTTP_ACCEPT_ENCODING' );
		return stripos( $accepted, 'gzip' ) !== false;
	}

	protected function do_404() {
		global $wp_query;

		$wp_query->set_404();
		status_header( 404 );
	}
}
