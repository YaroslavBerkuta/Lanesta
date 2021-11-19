<?php

class Smartcrawl_News_Sitemap extends Smartcrawl_Sitemap {
	public function add_rewrites() {
		/**
		 * @var $wp \WP
		 */
		global $wp;

		$wp->add_query_var( 'wds_news_sitemap' );
		$wp->add_query_var( 'wds_news_sitemap_type' );
		$wp->add_query_var( 'wds_news_sitemap_page' );
		$wp->add_query_var( 'wds_news_sitemap_gzip' );

		add_rewrite_rule( '^news-sitemap\.xml(\.gz)?$', 'index.php?wds_news_sitemap=1&wds_news_sitemap_type=index&wds_news_sitemap_gzip=$matches[1]', 'top' );
		add_rewrite_rule( '^news-([^/]+?)-sitemap([0-9]+)?\.xml(\.gz)?$', 'index.php?wds_news_sitemap=1&wds_news_sitemap_type=$matches[1]&wds_news_sitemap_page=$matches[2]&wds_news_sitemap_gzip=$matches[3]', 'top' );
	}

	public function is_enabled() {
		return parent::is_enabled()
		       && Smartcrawl_Sitemap_Utils::get_sitemap_option( 'enable-news-sitemap' );
	}

	public function can_handle_request() {
		return (boolean) get_query_var( 'wds_news_sitemap' );
	}

	public function do_fallback() {
		$this->do_404();
	}

	private function cache_type( $type ) {
		return "news-{$type}";
	}

	public function serve() {
		$sitemap_type = $this->get_sitemap_type_var();
		$sitemap_page = $this->get_sitemap_page_var();

		$sitemap_cache = Smartcrawl_Sitemap_Cache::get();
		$cached = $sitemap_cache->get_cached(
			$this->cache_type( $sitemap_type ),
			$sitemap_page
		);
		$gzip = $this->is_gzip_request();

		if ( ! empty( $cached ) ) {
			$this->output_xml( $cached, $gzip );
			return;
		}

		do_action( 'wds_before_news_sitemap_rebuild' );

		if ( $sitemap_type === self::SITEMAP_TYPE_INDEX ) {
			$xml = $this->build_index();
		} else {
			$xml = $this->build_partial_sitemap( $sitemap_type, $sitemap_page );
		}

		$sitemap_cache->set_cached(
			$this->cache_type( $sitemap_type ),
			$sitemap_page,
			$xml
		);
		$this->output_xml( $xml, $gzip );
	}

	private function build_partial_sitemap( $type, $page ) {
		$items = array();
		foreach ( $this->get_queries() as $query ) {
			if ( $query->can_handle_type( $type ) ) {
				$items = array_merge(
					$items,
					$query->get_items( $type, $page )
				);
				break;
			}
		}

		$items = apply_filters( 'wds_partial_news_sitemap_items', $items, $type, $page );

		if ( empty( $items ) ) {
			return false;
		}

		return $this->build_xml( $items );
	}

	private function post_process() {
		do_action( 'wds_news_sitemap_created' );
	}

	private function build_index() {
		$index_items = array();

		foreach ( $this->get_queries() as $query ) {
			$index_items = array_merge(
				$index_items,
				$query->get_index_items()
			);
		}

		$this->post_process();

		return $this->build_index_xml( $index_items );
	}

	private function get_sitemap_type_var() {
		return (string) get_query_var( 'wds_news_sitemap_type' );
	}

	private function get_sitemap_page_var() {
		return (int) get_query_var( 'wds_news_sitemap_page' );
	}

	private function is_gzip_request() {
		$query_var = get_query_var( 'wds_news_sitemap_gzip' );
		return ! empty( $query_var );
	}

	private function get_queries() {
		return array(
			new Smartcrawl_Sitemap_News_Query(),
		);
	}

	/**
	 * @param $items Smartcrawl_Sitemap_News_Item[]
	 *
	 * @return string
	 */
	public function build_xml( $items ) {
		return Smartcrawl_Simple_Renderer::load( 'sitemap/sitemap-news-xml', array(
			'news_items' => $items,
		) );
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
}
