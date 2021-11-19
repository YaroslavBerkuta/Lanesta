<?php

class Smartcrawl_Sitemap_Posts_Query extends Smartcrawl_Sitemap_Query {
	public function get_items( $type = '', $page_number = 0 ) {
		$items = array();
		$posts = $this->fetch_full_data( $type, $page_number );
		foreach ( $posts as $post ) {
			$item = new Smartcrawl_Sitemap_Item();

			$item->set_location( $this->get_post_url( $post ) )
			     ->set_priority( $this->get_post_priority( $post ) )
			     ->set_change_frequency( Smartcrawl_Sitemap_Item::FREQ_WEEKLY )
			     ->set_last_modified( $this->get_post_modified_time( $post ) )
			     ->set_images( $this->get_post_images( $post ) );

			$items[] = $item;
		}

		return $items;
	}

	private function get_post_images( $post ) {
		if ( ! Smartcrawl_Sitemap_Utils::sitemap_images_enabled() ) {
			return array();
		}

		$thumbnail_id = get_post_thumbnail_id( $post->ID );
		$thumbnail_image = wp_get_attachment_image( $thumbnail_id, 'full' );

		$html = $thumbnail_image;
		if ( ! empty( $post->post_content ) ) {
			$html .= "\n" . $post->post_content;
		}

		return $this->find_images( $html );
	}

	private function get_post_url( $post ) {
		return ! empty( $post->canonical )
			? $post->canonical
			: get_permalink( $post->ID );
	}

	private function get_post_priority( $post ) {
		$default = $post->post_parent ? 0.6 : 0.8;
		$priority = ! empty( $post->sitemap_priority )
			? $post->sitemap_priority
			: $default;

		return apply_filters( 'wds-post-priority', $priority, $post );
	}

	private function get_post_modified_time( $post ) {
		return ! empty( $post->post_modified )
			? strtotime( $post->post_modified )
			: time();
	}

	public function get_filter_prefix() {
		return 'wds-sitemap-posts';
	}

	/**
	 * Returns post IDs whose canonical URLs are ignored. (Assumes that canonical URLs are absolute)
	 *
	 * @param $types
	 *
	 * @return array
	 */
	private function get_ignored_canonical_url_ids( $types ) {
		$ignore_urls = Smartcrawl_Sitemap_Utils::get_ignore_urls();
		if ( empty( $ignore_urls ) ) {
			return array();
		}
		/**
		 * On the sitemap settings page we are forcing ignore URLs to be relative.
		 * Let's convert them to absolute first.
		 */
		$absolute_urls = array_map( array( $this, 'absolute_url' ), $ignore_urls );
		$possible_canonicals = array_merge(
			array_map( 'untrailingslashit', $absolute_urls ),
			array_map( 'trailingslashit', $absolute_urls )
		);

		return get_posts( array(
			'fields'     => 'ids',
			'post_type'  => $types,
			'meta_query' => array(
				array(
					'key'     => '_wds_canonical',
					'value'   => join( ',', $possible_canonicals ),
					'compare' => 'IN',
				),
			),
		) );
	}

	private function absolute_url( $url ) {
		$url = trim( $url );

		$host = parse_url( home_url(), PHP_URL_HOST );
		if ( strpos( $url, $host ) === false ) {
			$url = home_url( $url );
		}

		return $url;
	}

	private function get_ignored_url_ids() {
		$ignore_urls = Smartcrawl_Sitemap_Utils::get_ignore_urls();
		$post_ids = array();

		foreach ( $ignore_urls as $ignore_url ) {
			$post_id = url_to_postid( $ignore_url );

			if ( $post_id ) {
				$post_ids[] = $post_id;
			}
		}

		return $post_ids;
	}

	private function make_fetcher( $offset, $limit, $post_types ) {
		$post_types = is_array( $post_types )
			? $post_types
			: array( $post_types );

		$fetcher = new Smartcrawl_Sitemap_Post_Fetcher();
		return $fetcher->set_offset( $offset )
		               ->set_limit( $limit )
		               ->set_post_types( $post_types )
		               ->set_ignore_ids( $this->get_ignore_ids( $post_types ) );
	}

	/**
	 * @param $post WP_Post
	 *
	 * @return bool
	 */
	public function is_post_included( $post ) {
		if ( ! is_a( $post, 'WP_Post' ) ) {
			return false;
		}

		if ( ! in_array( $post->post_type, $this->get_supported_types(), true ) ) {
			return false;
		}

		$ignored_ids = $this->get_ignore_ids( $post->post_type );
		if ( in_array( $post->ID, $ignored_ids ) ) {
			return false;
		}

		$fetcher = $this->make_fetcher( 0, 1, $post->post_type )
		                ->set_include_ids( array( $post->ID ) );

		return ! empty( $fetcher->fetch_essential() );
	}

	private function fetch_essential_data( $type, $page_number ) {
		$fetcher = $this->make_fetcher(
			$this->get_offset( $page_number ),
			$this->get_limit( $page_number ),
			empty( $type ) ? $this->get_supported_types() : $type
		);

		return $fetcher->fetch_essential();
	}

	private function fetch_full_data( $type, $page_number ) {
		$extra_columns = Smartcrawl_Sitemap_Utils::sitemap_images_enabled()
			? array( 'post_content' )
			: array();

		$fetcher = $this->make_fetcher(
			$this->get_offset( $page_number ),
			$this->get_limit( $page_number ),
			empty( $type ) ? $this->get_supported_types() : $type
		)->set_extra_columns( $extra_columns );

		return $fetcher->fetch();
	}

	public function get_ignore_ids( $post_types ) {
		return array_unique( array_merge(
			Smartcrawl_Sitemap_Utils::get_ignore_ids(),
			$this->get_ignored_url_ids(),
			$this->get_ignored_canonical_url_ids( $post_types ),
			$this->get_front_page_id()
		) );
	}

	public function get_supported_types() {
		$options = Smartcrawl_Settings::get_options();
		$types = array();
		$raw = get_post_types( array(
			'public'  => true,
			'show_ui' => true,
		) );
		foreach ( $raw as $type ) {
			if ( ! empty( $options[ 'post_types-' . $type . '-not_in_sitemap' ] ) ) {
				continue;
			}
			$types[] = $type;
		}
		return $types;
	}

	/**
	 * @return int
	 */
	public function get_item_count() {
		$posts = $this->make_fetcher(
			0,
			Smartcrawl_Sitemap_Query::NO_LIMIT,
			$this->get_supported_types()
		)->fetch_essential();

		return count( $posts );
	}

	protected function get_index_items_for_type( $type ) {
		global $wpdb;
		$items = $this->fetch_essential_data( $type, 0 );
		if ( empty( $items ) ) {
			return array();
		}
		$item_count = $wpdb->num_rows;

		return $this->make_index_items( $type, $items, $item_count );
	}

	/**
	 * @param WP_Post $item
	 *
	 * @return mixed
	 */
	protected function get_item_last_modified( $item ) {
		return $this->get_post_modified_time( $item );
	}

	/**
	 * @return array
	 */
	private function get_front_page_id() {
		return 'page' === get_option( 'show_on_front' )
			? array( (int) get_option( 'page_on_front' ) )
			: array();
	}
}
