<?php

class Smartcrawl_General_Sitemap extends Smartcrawl_Sitemap {
	public function add_rewrites() {
		/**
		 * @var $wp \WP
		 */
		global $wp;

		$wp->add_query_var( 'wds_sitemap' );
		$wp->add_query_var( 'wds_sitemap_type' );
		$wp->add_query_var( 'wds_sitemap_page' );
		$wp->add_query_var( 'wds_sitemap_gzip' );

		add_rewrite_rule( '^sitemap\.xml(\.gz)?$', 'index.php?wds_sitemap=1&wds_sitemap_type=index&wds_sitemap_gzip=$matches[1]', 'top' );
		add_rewrite_rule( '^([^/]+?)-sitemap([0-9]+)?\.xml(\.gz)?$', 'index.php?wds_sitemap=1&wds_sitemap_type=$matches[1]&wds_sitemap_page=$matches[2]&wds_sitemap_gzip=$matches[3]', 'top' );
	}

	public function can_handle_request() {
		return (boolean) get_query_var( 'wds_sitemap' );
	}

	public function do_fallback() {
		$this->maybe_redirect_to_native( $this->native_sitemap_available() );
	}

	public function serve() {
		$native_available = $this->native_sitemap_available();
		$override_native = Smartcrawl_Sitemap_Utils::override_native();
		if ( ! $override_native && $native_available ) {
			$this->redirect_to_native();
			return;
		}

		$sitemap_type = $this->get_sitemap_type_var();
		$sitemap_page = $this->get_sitemap_page_var();

		$sitemap_cache = Smartcrawl_Sitemap_Cache::get();
		$cached = $sitemap_cache->get_cached( $sitemap_type, $sitemap_page );
		$gzip = $this->is_gzip_request();

		if ( ! empty( $cached ) ) {
			$this->output_xml( $cached, $gzip );
			return;
		}

		do_action( 'wds_before_sitemap_rebuild' );

		if ( $sitemap_type === self::SITEMAP_TYPE_INDEX ) {
			$xml = $this->build_index();
		} else {
			$xml = $this->build_partial_sitemap( $sitemap_type, $sitemap_page );
		}

		if ( ! $xml ) {
			$this->maybe_redirect_to_native( $native_available );
			return;
		}

		$sitemap_cache->set_cached( $sitemap_type, $sitemap_page, $xml );
		$this->output_xml( $xml, $gzip );
	}

	private function maybe_redirect_to_native( $native_available ) {
		if ( $native_available ) {
			$this->redirect_to_native();
		} else {
			$this->do_404();
		}
	}

	private function redirect_to_native() {
		/**
		 * @var $wp_sitemaps WP_Sitemaps
		 */
		global $wp_sitemaps;

		wp_safe_redirect( $wp_sitemaps->index->get_index_url() );
		die();
	}

	private function build_partial_sitemap( $type, $page ) {
		$items = array();
		if ( $type === 'post' && $page === 1 ) {
			$items[] = $this->make_home_page_item();
		}

		foreach ( $this->get_queries() as $query ) {
			if ( $query->can_handle_type( $type ) ) {
				$items = array_merge(
					$items,
					$query->get_items( $type, $page )
				);
				break;
			}
		}

		$items = apply_filters( 'wds_partial_sitemap_items', $items, $type, $page );

		if ( empty( $items ) ) {
			return false;
		}

		return $this->build_xml( $items );
	}

	private function build_index() {
		$index_items = array();

		foreach ( $this->get_queries() as $query ) {
			$index_items = array_merge(
				$index_items,
				$query->get_index_items()
			);
		}

		if ( empty( $index_items ) ) {
			return false;
		}

		$this->post_process( $index_items );

		return $this->build_index_xml( $index_items );
	}

	private function get_sitemap_type_var() {
		return (string) get_query_var( 'wds_sitemap_type' );
	}

	private function get_sitemap_page_var() {
		return (int) get_query_var( 'wds_sitemap_page' );
	}

	private function is_gzip_request() {
		$query_var = get_query_var( 'wds_sitemap_gzip' );
		return ! empty( $query_var );
	}

	/**
	 * @return Smartcrawl_Sitemap_Query[]
	 */
	private function get_queries() {
		return array(
			new Smartcrawl_Sitemap_Posts_Query(),
			new Smartcrawl_Sitemap_Terms_Query(),
			new Smartcrawl_Sitemap_BP_Groups_Query(),
			new Smartcrawl_Sitemap_BP_Profile_Query(),
			new Smartcrawl_Sitemap_Extras_Query(),
		);
	}

	private function post_process( $items ) {
		do_action( 'wds_sitemap_created' );
		Smartcrawl_Sitemap_Utils::notify_engines();
		Smartcrawl_Sitemap_Utils::update_meta_data( count( $items ) );
	}

	private function make_home_page_item() {
		$item = new Smartcrawl_Sitemap_Item();
		$item->set_location( home_url( '/' ) )
		     ->set_priority( 1 )
		     ->set_change_frequency( Smartcrawl_Sitemap_Item::FREQ_DAILY );

		return $item;
	}

	/**
	 * @param $index_items Smartcrawl_Sitemap_Index_Item[]
	 *
	 * @return string
	 */
	private function build_index_xml( $index_items ) {
		return Smartcrawl_Simple_Renderer::load( 'sitemap/sitemap-index-xml', array(
			'index_items' => $index_items,
		) );
	}

	/**
	 * @param $items Smartcrawl_Sitemap_Item[]
	 *
	 * @return string
	 */
	private function build_xml( $items ) {
		return Smartcrawl_Simple_Renderer::load( 'sitemap/sitemap-general-xml', array(
			'items' => $items,
		) );
	}

	/**
	 * @return bool
	 */
	private function native_sitemap_available() {
		return Smartcrawl_Sitemap_Utils::native_sitemap_available();
	}
}
