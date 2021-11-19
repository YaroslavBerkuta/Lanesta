<?php

abstract class Smartcrawl_Sitemap_Query extends Smartcrawl_WorkUnit {
	const NO_LIMIT = PHP_INT_MAX;

	/**
	 * @param string $type
	 * @param int $page_number
	 *
	 * @return Smartcrawl_Sitemap_Item[] Array of sitemap items
	 */
	public abstract function get_items( $type = '', $page_number = 0 );

	public function can_handle_type( $type ) {
		$allowed = $this->get_supported_types();

		return in_array( $type, $allowed, true );
	}

	public abstract function get_supported_types();

	protected function get_limit( $page_number ) {
		if ( $page_number === 0 ) { // 0 means all items are requested
			return self::NO_LIMIT;
		}

		// Otherwise return the limit based on page number
		return Smartcrawl_Sitemap_Utils::get_items_per_sitemap();
	}

	protected function get_offset( $page_number ) {
		return $page_number > 1
			? ( $page_number - 1 ) * Smartcrawl_Sitemap_Utils::get_items_per_sitemap()
			: 0;
	}

	/**
	 * @param $haystack string
	 *
	 * @return array
	 */
	protected function find_images( $haystack ) {
		preg_match_all( '|(<img [^>]+?>)|', $haystack, $matches, PREG_SET_ORDER );
		if ( ! $matches ) {
			return array();
		}

		$images = array();
		foreach ( $matches as $tmp ) {
			$img = $tmp[0];

			$res = preg_match( '/src=("|\')([^"\']+)("|\')/', $img, $match );
			$src = $res ? $match[2] : '';
			if ( strpos( $src, 'http' ) !== 0 ) {
				$src = site_url( $src );
			}

			$res = preg_match( '/title=("|\')([^"\']+)("|\')/', $img, $match );
			$title = $res ? str_replace( '-', ' ', str_replace( '_', ' ', $match[2] ) ) : '';

			$res = preg_match( '/alt=("|\')([^"\']+)("|\')/', $img, $match );
			$alt = $res ? str_replace( '-', ' ', str_replace( '_', ' ', $match[2] ) ) : '';

			$images[] = array(
				'src'   => $src,
				'title' => $title,
				'alt'   => $alt,
			);
		}

		return $images;
	}

	/**
	 * @return Smartcrawl_Sitemap_Index_Item[]
	 */
	public function get_index_items() {
		$types = $this->get_supported_types();
		$index_items = array();
		foreach ( $types as $type ) {
			$index_items_for_type = $this->get_index_items_for_type( $type );

			$index_items = array_merge(
				$index_items,
				$index_items_for_type
			);
		}

		return $index_items;
	}

	protected function get_index_items_for_type( $type ) {
		$items = $this->get_items( $type );
		if ( empty( $items ) ) {
			return array();
		}
		$item_count = count( $items );

		return $this->make_index_items( $type, $items, $item_count );
	}

	protected function get_index_item_url( $type, $sitemap_num ) {
		return home_url( "/{$type}-sitemap{$sitemap_num}.xml" );
	}

	/**
	 * @param $type string
	 * @param $items Smartcrawl_Sitemap_Item[]
	 * @param $item_count
	 *
	 * @return array
	 */
	protected function make_index_items( $type, $items, $item_count ) {
		$per_sitemap = Smartcrawl_Sitemap_Utils::get_items_per_sitemap();
		if ( empty( $per_sitemap ) ) {
			return array();
		}

		$sitemap_count = (int) ceil( $item_count / $per_sitemap );
		$index_items = array();

		for ( $sitemap_num = 1; $sitemap_num <= $sitemap_count; $sitemap_num ++ ) {
			$location = $this->get_index_item_url( $type, $sitemap_num );
			$last_modified_item = $sitemap_num === $sitemap_count // If this is the last, potentially not full, sitemap
				? $item_count - 1                       // ... use the very last item index
				: ( $sitemap_num * $per_sitemap ) - 1;  // ... otherwise use the last item in this sitemap

			if ( ! isset( $items[ $last_modified_item ] ) ) {
				Smartcrawl_Logger::error( "Could not find expected sitemap item at index [{$last_modified_item}]" );
				continue;
			}

			$index_item = new Smartcrawl_Sitemap_Index_Item();
			$index_item->set_location( $location );

			$last_modified = $this->get_item_last_modified( $items[ $last_modified_item ] );
			if ( $last_modified ) {
				$index_item->set_last_modified( $last_modified );
			}

			$index_items[] = $index_item;
		}

		return $index_items;
	}

	/**
	 * @param $item Smartcrawl_Sitemap_Item
	 *
	 * @return int
	 */
	protected function get_item_last_modified( $item ) {
		return $item->get_last_modified();
	}
}
