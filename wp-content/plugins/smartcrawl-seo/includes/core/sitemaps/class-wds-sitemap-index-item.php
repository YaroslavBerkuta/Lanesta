<?php

class Smartcrawl_Sitemap_Index_Item {
	private $location = '';
	private $last_modified = 0;

	/**
	 * @return string
	 */
	public function get_location() {
		return $this->location;
	}

	/**
	 * @param string $location
	 *
	 * @return $this
	 */
	public function set_location( $location ) {
		$this->location = $location;
		return $this;
	}

	/**
	 * @return int
	 */
	public function get_last_modified() {
		return $this->last_modified;
	}

	/**
	 * @param int $last_modified
	 *
	 * @return $this
	 */
	public function set_last_modified( $last_modified ) {
		$this->last_modified = $last_modified;
		return $this;
	}

	/**
	 * @param $timestamp int
	 *
	 * @return string
	 */
	protected function format_timestamp( $timestamp ) {
		return Smartcrawl_Sitemap_Utils::format_timestamp( $timestamp );
	}

	public function to_xml() {
		$tags = array();

		$location = $this->get_location();
		if ( empty( $location ) ) {
			Smartcrawl_Logger::error( 'Index item with empty location found' );
			return '';
		}

		$tags[] = sprintf( '<loc>%s</loc>', esc_url( $location ) );

		// Last modified date
		$timestamp = $this->get_last_modified();
		if ( $timestamp ) {
			$tags[] = sprintf( '<lastmod>%s</lastmod>', $this->format_timestamp( $timestamp ) );
		}

		return sprintf( '<sitemap>%s</sitemap>', implode( "", $tags ) );
	}
}
