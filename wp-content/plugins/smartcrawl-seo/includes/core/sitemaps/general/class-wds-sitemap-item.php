<?php

class Smartcrawl_Sitemap_Item extends Smartcrawl_Sitemap_Index_Item {
	const FREQ_ALWAYS = 'always';
	const FREQ_HOURLY = 'hourly';
	const FREQ_DAILY = 'daily';
	const FREQ_WEEKLY = 'weekly';
	const FREQ_MONTHLY = 'monthly';
	const FREQ_YEARLY = 'yearly';
	const FREQ_NEVER = 'never';

	private $change_frequency = '';
	private $priority = 0.5;
	private $images = array();

	/**
	 * @return string
	 */
	public function get_change_frequency() {
		return apply_filters(
			'wds_sitemap_changefreq',
			$this->change_frequency,
			$this->get_location(),
			$this->get_priority(),
			$this->get_last_modified(),
			$this->get_images()
		);
	}

	/**
	 * @param string $change_frequency
	 *
	 * @return $this
	 */
	public function set_change_frequency( $change_frequency ) {
		$this->change_frequency = $change_frequency;
		return $this;
	}

	/**
	 * @return float
	 */
	public function get_priority() {
		return $this->priority;
	}

	/**
	 * @param float $priority
	 *
	 * @return $this
	 */
	public function set_priority( $priority ) {
		$this->priority = $priority;
		return $this;
	}

	/**
	 * @return array
	 */
	public function get_images() {
		return $this->images;
	}

	/**
	 * @param array $images
	 *
	 * @return $this
	 */
	public function set_images( $images ) {
		$this->images = $images;

		return $this;
	}

	protected function format_priority( $priority ) {
		return sprintf( '%.1f', $priority );
	}

	private function images_xml() {
		$images = array();
		foreach ( $this->get_images() as $image ) {
			$images[] = $this->image_xml( $image );
		}
		return join( "\n", $images );
	}

	private function image_xml( $image ) {
		$text = ! empty( $image['title'] )
			? $image['title']
			: (string) smartcrawl_get_array_value( $image, 'alt' );
		$src = (string) smartcrawl_get_array_value( $image, 'src' );

		$image_tag = '<image:image>';
		$image_tag .= '<image:loc>' . esc_url( $src ) . '</image:loc>';
		$image_tag .= '<image:title>' . esc_xml( $text ) . '</image:title>';
		$image_tag .= "</image:image>";

		return $image_tag;
	}

	public function to_xml() {
		$tags = array();

		$location = $this->get_location();
		if ( empty( $location ) ) {
			Smartcrawl_Logger::error( 'Sitemap item with empty location found' );
			return '';
		}

		$tags[] = sprintf( '<loc>%s</loc>', esc_url( $location ) );

		// Last modified date
		$tags[] = sprintf( '<lastmod>%s</lastmod>', $this->format_timestamp( $this->get_last_modified() ) );

		// Change frequency
		$change_frequency = $this->get_change_frequency();
		if ( ! empty( $change_frequency ) ) {
			$tags[] = sprintf( '<changefreq>%s</changefreq>', strtolower( $change_frequency ) );
		}

		// Priority
		$priority = $this->get_priority();
		if ( ! empty( $priority ) ) {
			$tags[] = sprintf( '<priority>%s</priority>', $this->format_priority( $priority ) );
		}

		// Images
		$images = $this->images_xml();
		if ( ! empty( $images ) ) {
			$tags[] = $images;
		}

		return sprintf( "<url>\n%s\n</url>", implode( "\n", $tags ) );
	}
}
