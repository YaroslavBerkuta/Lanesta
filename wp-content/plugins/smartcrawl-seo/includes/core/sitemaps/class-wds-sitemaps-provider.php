<?php

class Smartcrawl_Sitemaps_Provider extends WP_Sitemaps_Provider {
	/**
	 * @var Smartcrawl_Sitemap_Query
	 */
	private $query;

	public function __construct( $name, $query ) {
		$this->name = $name;
		$this->object_type = $name;

		$this->query = $query;
	}

	public function get_url_list( $page_num, $object_subtype = '' ) {
		$sitemap_items = $this->query->get_items( $object_subtype, $page_num );

		return array_map( array( $this, 'convert_to_array' ), $sitemap_items );
	}

	public function get_max_num_pages( $object_subtype = '' ) {
		$index_items = $this->query->get_index_items();

		return count( $index_items );
	}

	/**
	 * @param $sitemap_item Smartcrawl_Sitemap_Item
	 *
	 * @return array
	 */
	private function convert_to_array( $sitemap_item ) {
		return array(
			'loc' => $sitemap_item->get_location(),
		);
	}
}
