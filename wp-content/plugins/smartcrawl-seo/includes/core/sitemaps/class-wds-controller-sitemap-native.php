<?php

class Smartcrawl_Controller_Sitemap_Native extends Smartcrawl_Base_Controller {
	private static $_instance;
	/**
	 * @var Smartcrawl_Sitemap_Posts_Query
	 */
	private $posts_query;
	/**
	 * @var Smartcrawl_Sitemap_Terms_Query
	 */
	private $terms_query;
	/**
	 * @var Smartcrawl_Sitemap_BP_Profile_Query
	 */
	private $bp_profile_query;
	/**
	 * @var Smartcrawl_Sitemap_BP_Groups_Query
	 */
	private $bp_groups_query;
	/**
	 * @var Smartcrawl_Sitemap_Extras_Query
	 */
	private $extras_query;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		parent::__construct();

		$this->posts_query = new Smartcrawl_Sitemap_Posts_Query();
		$this->terms_query = new Smartcrawl_Sitemap_Terms_Query();
		$this->bp_profile_query = new Smartcrawl_Sitemap_BP_Profile_Query();
		$this->bp_groups_query = new Smartcrawl_Sitemap_BP_Groups_Query();
		$this->extras_query = new Smartcrawl_Sitemap_Extras_Query();
	}

	public function should_run() {
		return ! Smartcrawl_Sitemap_Utils::override_native();
	}

	protected function init() {
		add_action( 'init', array( $this, 'hook' ), 15 ); // Give native sitemaps a chance to initialize properly
	}

	public function hook() {
		if ( ! Smartcrawl_Sitemap_Utils::native_sitemap_available() ) {
			return;
		}

		add_filter( 'wp_sitemaps_post_types', array( $this, 'filter_post_types' ) );
		add_filter( 'wp_sitemaps_posts_query_args', array( $this, 'exclude_post_ids' ), 10, 2 );
		add_filter( 'wp_sitemaps_posts_entry', array( $this, 'replace_post_url_with_canonical' ), 10, 2 );

		add_filter( 'wp_sitemaps_taxonomies', array( $this, 'filter_taxonomies' ) );
		add_filter( 'wp_sitemaps_taxonomies_query_args', array( $this, 'exclude_term_ids' ), 10, 2 );
		add_filter( 'wp_sitemaps_taxonomies_entry', array( $this, 'replace_term_url_with_canonical' ), 10, 3 );

		add_filter( 'wp_sitemaps_max_urls', array( 'Smartcrawl_Sitemap_Utils', 'get_items_per_sitemap' ) );

		$this->register_providers();
	}

	public function filter_post_types( $post_types ) {
		return array_filter(
			$post_types,
			array( 'Smartcrawl_Sitemap_Utils', 'is_post_type_included' ),
			ARRAY_FILTER_USE_KEY
		);
	}

	public function exclude_post_ids( $query_args, $post_type ) {
		$query_args['post__not_in'] = array_merge(
			$this->posts_query->get_ignore_ids( $post_type ),
			$this->get_redirected_and_noindex_post_ids( $post_type )
		);

		return $query_args;
	}

	private function get_redirected_and_noindex_post_ids( $types ) {
		return get_posts( array(
			'fields'     => 'ids',
			'post_type'  => $types,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => '_wds_redirect',
					'value'   => '',
					'compare' => '!=',
				),
				array(
					'key'     => '_wds_meta-robots-noindex',
					'value'   => 1,
					'compare' => '=',
				),
			),
		) );
	}

	public function replace_post_url_with_canonical( $sitemap_entry, $post ) {
		$canonical = smartcrawl_get_value( 'canonical', $post->ID );
		if ( $canonical ) {
			$sitemap_entry['loc'] = $canonical;
		}

		return $sitemap_entry;
	}

	public function filter_taxonomies( $taxonomies ) {
		return array_filter(
			$taxonomies,
			array( 'Smartcrawl_Sitemap_Utils', 'is_taxonomy_included' ),
			ARRAY_FILTER_USE_KEY
		);
	}

	public function exclude_term_ids( $args, $taxonomy ) {
		$ignored_ids = $this->terms_query->get_ignored_ids( $taxonomy );

		if ( $ignored_ids ) {
			$args['exclude'] = implode( ',', $ignored_ids );
		}

		return $args;
	}

	/**
	 * @param $sitemap_entry
	 * @param $term WP_Term|int
	 * @param $taxonomy string
	 *
	 * @return array
	 */
	public function replace_term_url_with_canonical( $sitemap_entry, $term, $taxonomy ) {
		if ( is_numeric( $term ) ) {
			$term = get_term( $term, $taxonomy );
		}

		$canonical = smartcrawl_get_term_meta( $term, $taxonomy, 'wds_canonical' );
		if ( $canonical ) {
			$sitemap_entry['loc'] = $canonical;
		}

		return $sitemap_entry;
	}

	public function register_providers() {
		if ( $this->bp_profile_query->can_handle_type( 'bp_profile' ) ) {
			wp_register_sitemap_provider(
				'bp-profile',
				new Smartcrawl_Sitemaps_Provider( 'bp-profile', $this->bp_profile_query )
			);
		}
		if ( $this->bp_groups_query->can_handle_type( 'bp_groups' ) ) {
			wp_register_sitemap_provider(
				'bp-groups',
				new Smartcrawl_Sitemaps_Provider( 'bp-groups', $this->bp_groups_query )
			);
		}
		wp_register_sitemap_provider(
			'extras',
			new Smartcrawl_Sitemaps_Provider( 'extras', $this->extras_query )
		);
	}
}
