<?php

class Smartcrawl_Sitemap_Post_Fetcher {
	private $offset = 0;

	private $limit = 10;

	private $post_types = array( 'post' );

	private $extra_columns = array();

	private $ignore_ids = array();

	private $include_ids = array();

	private $date_query = array();

	private $order_by = 'post_modified';

	public function fetch() {
		global $wpdb;

		$columns = array( 'ID', 'post_title', 'post_parent', 'post_type', 'post_modified', 'post_date' );
		$columns = array_merge(
			$columns,
			$this->get_extra_columns()
		);

		$posts_query = $this->prepare_posts_query( $columns );
		if ( ! $posts_query ) {
			return array();
		}

		$query = "SELECT posts.*, canonical.meta_value AS canonical, sitemap_priority.meta_value AS sitemap_priority FROM ({$posts_query}) AS posts " .
		         "LEFT OUTER JOIN {$wpdb->postmeta} AS canonical ON ID = canonical.post_id AND canonical.meta_key = '_wds_canonical' " .
		         "LEFT OUTER JOIN {$wpdb->postmeta} AS sitemap_priority ON ID = sitemap_priority.post_id AND sitemap_priority.meta_key = '_wds_sitemap-priority'";

		$posts = $wpdb->get_results( $query );

		$posts = $posts ? $posts : array();

		return $posts;
	}

	public function fetch_essential() {
		global $wpdb;
		$posts_query = $this->prepare_posts_query( array( 'ID', 'post_modified', 'post_date' ) );
		if ( ! $posts_query ) {
			return array();
		}

		$posts = $wpdb->get_results( $posts_query );

		return $posts ? $posts : array();
	}

	protected function prepare_posts_query( $columns ) {
		global $wpdb;

		$offset = $this->get_offset();
		$limit = $this->get_limit();

		$included_types = $this->post_types;
		if ( empty( $included_types ) ) {
			return false;
		}

		$included_types_placeholders = $this->get_db_placeholders( $included_types );
		$included_types_string = $wpdb->prepare( $included_types_placeholders, $included_types );
		$types_where = "AND post_type IN ({$included_types_string})";

		$ignore_ids_where = '';
		$ignore_ids = $this->get_ignore_ids();
		if ( $ignore_ids ) {
			$ignore_ids_placeholders = $this->get_db_placeholders( $ignore_ids, '%d' );
			$ignore_ids_string = $wpdb->prepare( $ignore_ids_placeholders, $ignore_ids );
			$ignore_ids_where = "AND ID NOT IN ({$ignore_ids_string})";
		}

		$include_ids_where = '';
		$include_ids = $this->get_include_ids();
		if ( $include_ids ) {
			$include_ids_placeholders = $this->get_db_placeholders( $include_ids, '%d' );
			$include_ids_string = $wpdb->prepare( $include_ids_placeholders, $include_ids );
			$include_ids_where = "AND ID IN ({$include_ids_string})";
		}

		$date_where = $this->get_date_where();

		$column_string = join( ', ', $columns );

		$order_by = $this->get_order_by();

		$query = "SELECT {$column_string} FROM {$wpdb->posts} " .
		         "WHERE post_status = 'publish' " .
		         "AND post_password = '' " .
		         "{$include_ids_where} " .
		         "{$types_where} " .
		         "{$date_where} " .
		         "{$ignore_ids_where} " .
		         "AND ID NOT IN (SELECT post_id FROM {$wpdb->postmeta} WHERE (meta_key = '_wds_meta-robots-noindex' AND meta_value = 1) OR (meta_key = '_wds_redirect' AND meta_value != '')) " .

		         "ORDER BY {$order_by} ASC LIMIT {$limit} OFFSET {$offset}";

		return $query;
	}

	private function get_db_placeholders( $items, $single_placeholder = '%s' ) {
		return join( ',', array_fill( 0, count( $items ), $single_placeholder ) );
	}

	/**
	 * @return int
	 */
	public function get_offset() {
		return $this->offset;
	}

	/**
	 * @param int $offset
	 *
	 * @return Smartcrawl_Sitemap_Post_Fetcher
	 */
	public function set_offset( $offset ) {
		$this->offset = $offset;

		return $this;
	}

	/**
	 * @return int
	 */
	public function get_limit() {
		return $this->limit;
	}

	/**
	 * @param int $limit
	 *
	 * @return Smartcrawl_Sitemap_Post_Fetcher
	 */
	public function set_limit( $limit ) {
		$this->limit = $limit;

		return $this;
	}

	/**
	 * @return string[]
	 */
	public function get_post_types() {
		return $this->post_types;
	}

	/**
	 * @param string[] $post_types
	 *
	 * @return Smartcrawl_Sitemap_Post_Fetcher
	 */
	public function set_post_types( $post_types ) {
		$this->post_types = $post_types;

		return $this;
	}

	/**
	 * @return string[]
	 */
	public function get_extra_columns() {
		return $this->extra_columns;
	}

	/**
	 * @param string[] $extra_columns
	 *
	 * @return Smartcrawl_Sitemap_Post_Fetcher
	 */
	public function set_extra_columns( $extra_columns ) {
		$this->extra_columns = $extra_columns;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_ignore_ids() {
		return $this->ignore_ids;
	}

	/**
	 * @param array $ignore_ids
	 *
	 * @return Smartcrawl_Sitemap_Post_Fetcher
	 */
	public function set_ignore_ids( $ignore_ids ) {
		$this->ignore_ids = $ignore_ids;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_include_ids() {
		return $this->include_ids;
	}

	/**
	 * @param array $include_ids
	 *
	 * @return Smartcrawl_Sitemap_Post_Fetcher
	 */
	public function set_include_ids( $include_ids ) {
		$this->include_ids = $include_ids;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_date_query() {
		return $this->date_query;
	}

	/**
	 * @param array $date_query
	 *
	 * @return Smartcrawl_Sitemap_Post_Fetcher
	 */
	public function set_date_query( $date_query ) {
		$this->date_query = $date_query;

		return $this;
	}

	private function get_date_where() {
		$date_query_args = $this->get_date_query();
		if ( $date_query_args && is_array( $date_query_args ) ) {
			$date_query = new WP_Date_Query( $date_query_args );
			return $date_query->get_sql();
		}

		return '';
	}

	public function get_order_by() {
		return $this->order_by;
	}

	public function set_order_by( $order_by ) {
		$this->order_by = $order_by;

		return $this;
	}
}
