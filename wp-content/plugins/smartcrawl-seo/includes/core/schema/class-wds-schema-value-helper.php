<?php

class Smartcrawl_Schema_Value_Helper extends Smartcrawl_Type_Traverser {
	const TYPE_ORGANIZATION = "Organization";
	const TYPE_WEBSITE = "WebSite";
	const TYPE_SEARCH_ACTION = "SearchAction";
	const TYPE_WEB_PAGE = "WebPage";
	const TYPE_IMAGE = 'ImageObject';
	const TYPE_PERSON = 'Person';
	const TYPE_CONTACT_POINT = 'ContactPoint';
	const TYPE_ABOUT_PAGE = 'AboutPage';
	const TYPE_CONTACT_PAGE = 'ContactPage';
	const TYPE_COMMENT = 'Comment';
	const TYPE_VIDEO_OBJECT = 'VideoObject';
	const TYPE_AUDIO_OBJECT = 'AudioObject';

	const ORGANIZATION_AIRLINE = 'Airline';
	const ORGANIZATION_CONSORTIUM = 'Consortium';
	const ORGANIZATION_CORPORATION = 'Corporation';
	const ORGANIZATION_EDUCATIONAL = 'EducationalOrganization';
	const ORGANIZATION_FUNDING_SCHEME = 'FundingScheme';
	const ORGANIZATION_GOVERNMENT = 'GovernmentOrganization';
	const ORGANIZATION_LIBRARY_SYSTEM = 'LibrarySystem';
	const ORGANIZATION_MEDICAL = 'MedicalOrganization';
	const ORGANIZATION_NGO = 'NGO';
	const ORGANIZATION_NEWS_MEDIA = 'NewsMediaOrganization';
	const ORGANIZATION_PERFORMING_GROUP = 'PerformingGroup';
	const ORGANIZATION_PROJECT = 'Project';
	const ORGANIZATION_SPORTS = 'SportsOrganization';
	const ORGANIZATION_WORKERS_UNION = 'WorkersUnion';
	const TYPE_ITEM_LIST = "ItemList";
	const TYPE_ARTICLE = 'Article';
	const TYPE_NEWS_ARTICLE = "NewsArticle";

	private $schema;
	private $social_options;
	private $schema_option;
	/**
	 * @var Smartcrawl_Model_User
	 */
	private $owner;
	/**
	 * @var array
	 */
	private $type_registry = array();

	/**
	 * Singleton instance
	 *
	 * @var self
	 */
	private static $_instance;
	/**
	 * @var Smartcrawl_Schema_Utils
	 */
	private $utils;

	/**
	 * @return self instance
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->owner = Smartcrawl_Model_User::owner();
		$this->utils = Smartcrawl_Schema_Utils::get();
	}

	public function get_schema_types() {
		return $this->type_registry;
	}

	public function clear() {
		$this->type_registry = array();
	}

	public function get_schema() {
		$graph = array();
		foreach ( $this->type_registry as $type_collection ) {
			foreach ( $type_collection as $type_schema ) {
				$graph[] = $type_schema;
			}
		}

		if ( empty( $graph ) ) {
			return null;
		}

		return array(
			"@context" => "https://schema.org",
			"@graph"   => $graph,
		);
	}

	public function handle_bp_groups() {
		// TODO: Implement handle_bp_groups() method.
	}

	public function handle_bp_profile() {
		// TODO: Implement handle_bp_profile() method.
	}

	public function handle_woo_shop() {
		// TODO: Implement handle_woo_shop() method.
	}

	public function handle_blog_home() {
		$this->add_header_footer_schema( get_site_url() );

		$this->add_publisher_schema();
		$this->add_website_schema();

		$custom_schema_types = $this->get_custom_schema_types();
		if ( ! empty( $custom_schema_types ) ) {
			$this->add_custom_schema_types( $custom_schema_types );
		} else {
			$this->add_blog_home_webpage_schema();
		}
	}

	public function handle_static_home() {
		$page_for_posts = get_option( 'page_for_posts' );
		$wp_query = $this->get_query_context();

		$this->set_archive_schema( "CollectionPage", get_permalink( $page_for_posts ), $wp_query->posts );
		$this->add_custom_schema_types( $this->get_custom_schema_types() );
	}

	public function handle_search() {
		$enabled = (bool) $this->get_schema_option( 'schema_enable_search' );
		if ( ! $enabled ) {
			return;
		}

		$wp_query = $this->get_query_context();
		$search_term = $wp_query->get( 's' );

		$this->set_archive_schema( "SearchResultsPage", get_search_link( $search_term ), $wp_query->posts );
		$this->add_custom_schema_types( $this->get_custom_schema_types() );
	}

	public function handle_404() {
		// Not needed
	}

	public function handle_date_archive() {
		$enabled = (bool) $this->get_schema_option( 'schema_enable_date_archives' );
		if ( ! $enabled ) {
			return;
		}

		$wp_query = $this->get_query_context();

		$requested_year = $wp_query->get( 'year' );
		$requested_month = $wp_query->get( 'monthnum' );
		$date_callback = ! empty( $requested_year ) && empty( $requested_month )
			? 'get_year_link'
			: 'get_month_link';

		$date_archive_url = $date_callback( $requested_year, $requested_month );
		$this->set_archive_schema( "CollectionPage", $date_archive_url, $wp_query->posts );
		$this->add_custom_schema_types( $this->get_custom_schema_types() );
	}

	public function handle_pt_archive() {
		/**
		 * @var $post_type WP_Post_Type
		 */
		$post_type = $this->get_queried_object();
		if ( is_a( $post_type, 'WP_Post_Type' ) ) {
			$wp_query = $this->get_query_context();
			$pt_archive_url = get_post_type_archive_link( $post_type->name );

			$enabled = (bool) $this->get_schema_option( 'schema_enable_post_type_archives' );
			$disabled = (bool) $this->get_schema_option( array(
				'schema_disabled_post_type_archives',
				$post_type->name,
			) );

			if ( $enabled && ! $disabled ) {
				$this->set_archive_schema( "CollectionPage", $pt_archive_url, $wp_query->posts );
			}
			$this->add_custom_schema_types( $this->get_custom_schema_types() );
		}
	}

	public function handle_tax_archive() {
		$wp_query = $this->get_query_context();
		/**
		 * @var $term \WP_Term
		 */
		$term = $wp_query->get_queried_object();
		$term_url = get_term_link( $term, $term->taxonomy );

		$enabled = (bool) $this->get_schema_option( 'schema_enable_taxonomy_archives' );
		$disabled = (bool) $this->get_schema_option( array(
			'schema_disabled_taxonomy_archives',
			$term->taxonomy,
		) );
		if ( $enabled && ! $disabled ) {
			$this->set_archive_schema( "CollectionPage", $term_url, $wp_query->posts );
		}
		$this->add_custom_schema_types( $this->get_custom_schema_types() );
	}

	public function handle_author_archive() {
		$wp_query = $this->get_query_context();
		$author = $this->get_queried_object();
		$author_url = get_author_posts_url( $author->ID );

		$enabled = (bool) $this->get_schema_option( 'schema_enable_author_archives' );
		if ( $enabled ) {
			$this->set_archive_schema( "ProfilePage", $author_url, $wp_query->posts );
		}
		$this->add_custom_schema_types( $this->get_custom_schema_types() );
	}

	public function handle_archive() {
		// Specific archives already handled
	}

	/**
	 * @param $author Smartcrawl_Model_User
	 *
	 * @return bool
	 */
	private function author_schema_required( $author ) {
		if (
			! $this->is_schema_type_organization() &&
			$this->is_publisher_output_page() &&
			$this->owner->get_id() === $author->get_id()
		) {
			// Owner data has already been printed, separate author data not needed
			return false;
		}

		return true;
	}

	private function get_webpage_type() {
		if ( $this->is_about_page() ) {
			return self::TYPE_ABOUT_PAGE;
		}

		if ( $this->is_contact_page() ) {
			return self::TYPE_CONTACT_PAGE;
		}

		return self::TYPE_WEB_PAGE;
	}

	public function handle_singular() {
		$post = $this->get_context();
		$post_permalink = get_permalink( $post );

		$this->add_header_footer_schema( $post_permalink );

		$this->add_publisher_schema();
		$this->add_website_schema();
		$custom_schema_types = $this->get_custom_schema_types( $post );

		if ( ! empty( $custom_schema_types ) ) {
			$this->add_custom_schema_types( $custom_schema_types, $post_permalink );
		} else if ( $this->is_contact_page() || $this->is_about_page() ) {
			$this->add_webpage_schema( $post, $post_permalink );
		} else {
			$this->add_minimal_webpage_schema( $post_permalink );
			$this->add_article_schema( $post, $post_permalink );
		}

		$this->add_media_schema( $post );
	}

	private function get_custom_schema_types( $post = null ) {
		$custom_types = array();
		$schema_types = Smartcrawl_Controller_Schema_Types::get()->get_schema_types();
		foreach ( $schema_types as $schema_type ) {
			$type = Smartcrawl_Schema_Type::create( $schema_type, $post );

			if ( $type->is_active() && $type->conditions_met() ) {
				$custom_types[ $type->get_type() ][] = $type->get_schema();
			}
		}
		return $custom_types;
	}

	private function add_custom_schema_types( $schema_types, $url = '' ) {
		foreach ( $schema_types as $type_key => $type_collection ) {
			if ( $type_key === self::TYPE_ARTICLE ) {
				// Article schemas will be handled separately
				continue;
			}

			foreach ( $type_collection as $schema ) {
				$this->register_type( $type_key, $schema );
			}
		}

		$webpage_schema_added = false;
		$article_schemas = smartcrawl_get_array_value( $schema_types, self::TYPE_ARTICLE );
		if ( ! empty( $article_schemas ) && is_array( $article_schemas ) ) {
			foreach ( $article_schemas as $article_schema ) {
				if ( ! $webpage_schema_added && $url ) {
					$this->add_minimal_webpage_schema( $url );
					$article_schema['mainEntityOfPage'] = $this->get_webpage_id( $url );
					$webpage_schema_added = true;
				}

				$this->register_type( self::TYPE_ARTICLE, $article_schema );
			}
		}
	}

	private function filter_post_data_image( $schema_image ) {
		return $this->apply_filters( 'post-data-image', $schema_image );
	}

	/**
	 * @param $post WP_Post
	 * @param $permalink string
	 *
	 * TODO: pageStart/pageEnd, wordCount
	 */
	private function add_article_schema( $post, $permalink ) {
		$post = $this->apply_filters( 'post', $post );
		$schema_type = $this->show_news_article_schema( $post )
			? self::TYPE_NEWS_ARTICLE
			: self::TYPE_ARTICLE;

		$schema = array(
			          "@type"            => $schema_type,
			          "mainEntityOfPage" => array(
				          "@id" => $this->get_webpage_id( $permalink ),
			          ),
		          ) + $this->post_to_schema( $post, $permalink );

		$this->register_type(
			$schema_type,
			$this->apply_filters( 'post-data', $schema )
		);
	}

	private function post_to_schema( $post, $permalink, $include_comments = true ) {
		$post = $this->apply_filters( 'post', $post );
		$author = Smartcrawl_Model_User::get( $post->post_author );
		$headline = Smartcrawl_Meta_Value_Helper::get()->get_title();

		$author_schema = $this->author_schema_required( $author )
			? $this->get_author_schema( $author )
			: array( "@id" => $this->get_publishing_person_id() );

		$schema = array(
			"author"        => $author_schema,
			"publisher"     => array(
				"@id" => $this->get_publisher_id(),
			),
			"dateModified"  => get_the_modified_date( 'Y-m-d\TH:i:s', $post ),
			"datePublished" => get_the_date( 'Y-m-d\TH:i:s', $post ),
			"headline"      => $this->apply_filters( 'post-data-headline', $headline, $post ),
			"description"   => Smartcrawl_Meta_Value_Helper::get()->get_description(),
			"name"          => $this->apply_filters( 'post-data-name', get_the_title( $post ), $post ),
		);

		$enable_comments = (bool) $this->get_schema_option( 'schema_enable_comments' );
		if ( $include_comments && $enable_comments ) {
			$schema["commentCount"] = get_comments_number( $post->ID );
			$schema["comment"] = $this->get_comments_schema( $post->ID, $permalink );
		}

		$schema = $this->add_article_image( $post, $permalink, $schema );

		return $schema;
	}

	private function add_minimal_webpage_schema( $url ) {
		$schema = array(
			"@type"     => self::TYPE_WEB_PAGE,
			"@id"       => $this->get_webpage_id( $url ),
			"isPartOf"  => array(
				"@id" => $this->get_website_id(),
			),
			"publisher" => array(
				"@id" => $this->get_publisher_id(),
			),
			"url"       => $url,
		);

		$menu_schema = $this->get_menu_schema( $url );
		if ( $menu_schema ) {
			$schema["hasPart"] = $menu_schema;
		}

		$this->register_type( self::TYPE_WEB_PAGE, $schema );
	}

	private function add_blog_home_webpage_schema() {
		$meta_value_helper = Smartcrawl_Meta_Value_Helper::get();
		$schema = array(
			"@type"      => $this->get_webpage_type(),
			"@id"        => $this->get_webpage_id( get_site_url() ),
			"url"        => get_site_url(),
			"name"       => $meta_value_helper->get_title(),
			"inLanguage" => get_bloginfo( 'language' ),
			"isPartOf"   => array(
				"@id" => $this->get_website_id(),
			),
			"publisher"  => array(
				"@id" => $this->get_publisher_id(),
			),
		);

		$description = $meta_value_helper->get_description();
		if ( $description ) {
			$schema["description"] = $this->apply_filters( 'site-data-description', $description );
		}

		$last_post_date = get_lastpostdate( 'blog' );
		if ( $last_post_date ) {
			$schema["dateModified"] = $last_post_date;
		}

		$menu_schema = $this->get_menu_schema( get_site_url() );
		if ( $menu_schema ) {
			$schema["hasPart"] = $menu_schema;
		}

		$this->register_type( $this->get_webpage_type(), $schema );
	}

	private function add_webpage_schema( $post, $permalink ) {
		$post = $this->apply_filters( 'post', $post );
		$schema = array(
			          "@type"    => $this->get_webpage_type(),
			          "@id"      => $this->get_webpage_id( $permalink ),
			          "isPartOf" => $this->get_website_id(),
		          )
		          + $this->post_to_schema( $post, $permalink );

		$schema["url"] = $permalink;

		$menu_schema = $this->get_menu_schema( get_site_url() );
		if ( $menu_schema ) {
			$schema["hasPart"] = $menu_schema;
		}

		$this->register_type(
			$this->get_webpage_type(),
			$this->apply_filters( 'post-data', $schema )
		);
	}

	private function get_webpage_id( $url ) {
		return $this->url_to_id( $url, '#schema-webpage' );
	}

	private function get_website_id() {
		return $this->url_to_id( get_site_url(), "#schema-website" );
	}

	private function add_website_schema() {
		$website_name = $this->get_social_option( 'sitename' );
		$website_name = ! empty( $website_name )
			? $website_name
			: get_bloginfo( 'name' );
		$website_url = get_site_url();

		$schema = array(
			"@type"    => self::TYPE_WEBSITE,
			"@id"      => $this->get_website_id(),
			"url"      => $website_url,
			"name"     => $this->apply_filters( 'site-data-name', $website_name ),
			"encoding" => get_bloginfo( 'charset' ),
		);

		if ( (bool) $this->get_schema_option( 'sitelinks_search_box' ) ) {
			$search_url = str_replace(
				'search_term_string',
				'{search_term_string}',
				get_search_link( 'search_term_string' )
			);
			$schema["potentialAction"] = array(
				"@type"       => self::TYPE_SEARCH_ACTION,
				"target"      => $search_url,
				"query-input" => "required name=search_term_string",
			);
		}

		$image = $this->utils->get_media_item_image_schema(
			(int) $this->get_schema_option( 'schema_website_logo' ),
			$this->url_to_id( $website_url, '#schema-site-logo' )
		);
		if ( $image ) {
			$schema["image"] = $image;
		}

		$this->register_type(
			self::TYPE_WEBSITE,
			$this->apply_filters( 'site-data', $schema )
		);
	}

	private function add_publisher_schema() {
		$is_output_page = $this->is_publisher_output_page();

		if ( $this->is_schema_type_person() ) {
			// Publisher information on every page
			$this->add_personal_brand_schema();

			// Full personal details only on output page
			if ( $is_output_page ) {
				$this->add_publishing_person_schema();
			}
		} else {
			$this->add_publishing_organization_schema( $is_output_page );
		}
	}

	private function filter_owner_data( $data ) {
		return $this->apply_filters( 'owner-data', $data );
	}

	private function get_publisher_id() {
		return $this->is_schema_type_person()
			? $this->get_personal_brand_id()
			: $this->get_publishing_organization_id();
	}

	private function get_personal_brand_id() {
		return $this->url_to_id( $this->get_publisher_url(), '#schema-personal-brand' );
	}

	private function get_publishing_person_id() {
		return $this->url_to_id( $this->get_publisher_url(), '#schema-publishing-person' );
	}

	private function get_publishing_organization_id() {
		return $this->url_to_id( $this->get_publisher_url(), "#schema-publishing-organization" );
	}

	private function add_publishing_organization_schema( $full ) {
		// Summary
		$organization_type = $full
			? $this->get_organization_type_option() // Only use the specific org type If we're showing the full output
			: self::TYPE_ORGANIZATION;  // Otherwise use Organization 

		$schema = array(
			"@type" => $organization_type,
			"@id"   => $this->get_publishing_organization_id(),
			"url"   => $this->get_publisher_url(),
		);

		// Name
		$schema["name"] = $this->get_organization_name();

		// Logo
		$org_logo = $this->get_organization_logo();
		if ( $org_logo ) {
			$schema["logo"] = $org_logo;

			if ( $full ) {
				$schema["image"] = $org_logo;
			}
		}

		if ( ! $full ) {
			$this->register_type(
				$organization_type,
				$this->filter_owner_data( $schema )
			);
			return;
		}

		// Description
		$schema["description"] = $this->get_organization_description();

		// Contact point
		$contact_point = $this->get_contact_point(
			$this->get_schema_option( 'organization_phone_number' ),
			(int) $this->get_schema_option( 'organization_contact_page' ),
			$this->get_schema_option( 'organization_contact_type' )
		);
		if ( $contact_point ) {
			$schema['contactPoint'] = $contact_point;
		}

		// Social URLs
		$social_urls = $this->get_social_urls();
		if ( $social_urls ) {
			$schema["sameAs"] = $social_urls;
		}

		$this->register_type(
			$organization_type,
			$this->filter_owner_data( $schema )
		);
	}

	/**
	 * @return bool|WP_Post
	 */
	private function get_publisher_url() {
		$output_page = $this->get_special_page( 'schema_output_page' );

		return $output_page
			? get_permalink( $output_page )
			: get_site_url();
	}

	/**
	 * @param $user Smartcrawl_Model_User
	 *
	 * @return string
	 */
	private function get_author_id( $user ) {
		$url = get_author_posts_url( $user->get_id() );

		return $this->url_to_id( $url, '#schema-author' );
	}

	private function get_special_page( $key ) {
		$page_id = (int) $this->get_schema_option( $key );
		if ( ! $page_id ) {
			return false;
		}

		$output_page = get_post( $page_id );
		if ( ! $output_page || is_wp_error( $output_page ) ) {
			return false;
		}

		return $output_page;
	}

	private function is_about_page() {
		return $this->is_special_page( 'schema_about_page' );
	}

	private function is_contact_page() {
		return $this->is_special_page( 'schema_contact_page' );
	}

	private function is_publisher_output_page() {
		return $this->is_special_page(
			'schema_output_page',
			$this->get_query_context()->is_front_page()
		);
	}

	private function is_special_page( $key, $default = false ) {
		$output_page = $this->get_special_page( $key );
		if ( ! $output_page ) {
			return $default;
		}

		$queried_object = $this->get_queried_object();

		return $queried_object && is_a( $queried_object, 'WP_Post' ) &&
		       $queried_object->ID === $output_page->ID;
	}

	private function add_personal_brand_schema() {
		// Summary
		$schema = array(
			"@type" => self::TYPE_ORGANIZATION,
			"@id"   => $this->get_personal_brand_id(),
			"url"   => $this->get_publisher_url(),
		);

		// Name
		$schema["name"] = $this->get_personal_brand_name();

		// Logo
		$site_url = get_site_url();
		$logo = $this->utils->get_media_item_image_schema(
			(int) $this->get_schema_option( 'person_brand_logo' ),
			$this->url_to_id( $site_url, '#schema-personal-brand-logo' )
		);
		if ( $logo ) {
			$schema["logo"] = $logo;
		}

		$this->register_type( self::TYPE_ORGANIZATION, $schema );
	}

	private function add_publishing_person_schema() {
		// Summary
		$schema = array(
			"@type" => self::TYPE_PERSON,
			"@id"   => $this->get_publishing_person_id(),
			"url"   => $this->get_publisher_url(),
		);

		// Name
		$name = $this->first_non_empty_string(
			$this->get_social_option( 'override_name' ),
			$this->get_user_full_name( $this->owner )
		);
		$schema["name"] = $name;

		// Description
		$description = $this->get_textarea_schema_option( 'person_bio' );
		$description = ! empty( $description ) ? $description : $this->owner->get_description();
		if ( $description ) {
			$schema["description"] = $description;
		}

		// Job
		$job_title = $this->get_schema_option( 'person_job_title' );
		if ( $job_title ) {
			$schema["jobTitle"] = $job_title;
		}

		// Image
		$site_url = get_site_url();
		$image = $this->utils->get_media_item_image_schema(
			(int) $this->get_schema_option( 'person_portrait' ),
			$this->url_to_id( $site_url, '#schema-publisher-portrait' )
		);
		if ( ! $image && $this->is_author_gravatar_enabled() ) {
			$schema["image"] = $this->utils->get_image_schema(
				$this->url_to_id( $site_url, "#schema-publisher-gravatar" ),
				$this->owner->get_avatar_url( 100 ),
				100,
				100,
				$name
			);
		}
		if ( $image ) {
			$schema["image"] = $image;
		}

		// Contact
		$contact_point = $this->get_contact_point(
			$this->get_schema_option( 'person_phone_number' ),
			(int) $this->get_schema_option( 'person_contact_page' )
		);
		if ( $contact_point ) {
			$schema['contactPoint'] = $contact_point;
		}

		// Social URLs
		$social_urls = $this->get_social_urls();
		if ( $social_urls ) {
			$schema["sameAs"] = $social_urls;
		}

		$this->register_type(
			self::TYPE_PERSON,
			$this->filter_owner_data( $schema )
		);
	}

	private function get_contact_point( $phone, $contact_page_id, $contact_type = '' ) {
		$schema = array();
		if ( $phone ) {
			$schema['telephone'] = $phone;
		}

		if ( $contact_page_id ) {
			$contact_page_url = get_permalink( $contact_page_id );
			if ( $contact_page_url ) {
				$schema['url'] = $contact_page_url;
			}
		}

		if ( $schema ) {
			$other_values = array( '@type' => self::TYPE_CONTACT_POINT );
			if ( $contact_type ) {
				$other_values['contactType'] = $contact_type;
			}
			$schema = $other_values + $schema;
		}

		return $schema;
	}

	/**
	 * @param $user Smartcrawl_Model_User
	 *
	 * @return array
	 */
	private function get_author_schema( $user ) {
		$url = $this->get_user_url( $user );
		$name = $this->get_user_full_name( $user );

		$schema = array(
			"@type" => self::TYPE_PERSON,
			"@id"   => $this->get_author_id( $user ),
			"name"  => $name,
		);

		if ( (bool) $this->get_schema_option( 'schema_enable_author_url' ) ) {
			$schema["url"] = $url;
		}

		$description = $user->get_description();
		if ( $description ) {
			$schema["description"] = $description;
		}

		if ( $this->is_author_gravatar_enabled() ) {
			$schema["image"] = $this->utils->get_image_schema(
				$this->url_to_id( $url, "#schema-author-gravatar" ),
				$user->get_avatar_url( 100 ),
				100,
				100,
				$name
			);
		}

		$urls = $this->get_user_urls( $user );
		if ( $urls ) {
			$schema["sameAs"] = $urls;
		}

		return $this->apply_filters( 'user-data', $schema, $user );
	}

	private function is_author_gravatar_enabled() {
		return (bool) $this->get_schema_option( 'schema_enable_author_gravatar' );
	}

	private function get_organization_type_option() {
		$org_type = $this->get_schema_option( 'organization_type' );

		// Since version 2.10 LocalBusiness is not supported as organization_type.
		// Instead, the users are encouraged to use the LocalBusiness type in the schema builder.
		if ( $org_type === 'LocalBusiness' ) {
			$org_type = '';
		}

		return $org_type
			? $org_type
			: self::TYPE_ORGANIZATION;
	}

	private function get_organization_logo() {
		$url = $this->get_social_option( 'organization_logo' );
		if ( empty( $url ) ) {
			return array();
		}

		$schema = $this->utils->get_image_schema(
			$this->url_to_id( get_site_url(), '#schema-organization-logo' ),
			esc_url( $url ),
			60,
			60
		);
		return $this->apply_filters( 'site-logo', $schema );
	}

	private function get_schema_options() {
		if ( empty( $this->schema_option ) ) {
			$schema = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SCHEMA );
			$this->schema_option = is_array( $schema ) ? $schema : array();
		}

		return $this->schema_option;
	}

	private function get_schema_option( $key ) {
		$value = smartcrawl_get_array_value( $this->get_schema_options(), $key );
		if ( is_string( $value ) ) {
			return sanitize_text_field( trim( $value ) );
		}

		return $value;
	}

	private function get_textarea_schema_option( $key ) {
		$value = smartcrawl_get_array_value( $this->get_schema_options(), $key );
		if ( is_string( $value ) ) {
			return sanitize_textarea_field( trim( $value ) );
		}

		return $value;
	}

	private function get_social_options() {
		if ( empty( $this->social_options ) ) {
			$social = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );
			$this->social_options = is_array( $social ) ? $social : array();
		}

		return $this->social_options;
	}

	private function get_social_option( $key ) {
		$value = smartcrawl_get_array_value( $this->get_social_options(), $key );
		if ( is_string( $value ) ) {
			return sanitize_text_field( trim( $value ) );
		}

		return $value;
	}

	private function get_social_urls() {
		$urls = array();
		$social = $this->get_social_options();
		foreach ( $social as $key => $value ) {
			if ( preg_match( '/_url$/', $key ) && ! empty( trim( $value ) ) ) {
				$urls[] = $this->get_social_option( $key );
			}
		}

		$twitter_username = $this->get_social_option( 'twitter_username' );
		if ( $twitter_username ) {
			$urls[] = "https://twitter.com/{$twitter_username}";
		}

		return $urls;
	}

	public function is_schema_type_person() {
		return $this->get_schema_type() === self::TYPE_PERSON;
	}

	public function is_schema_type_organization() {
		return $this->get_schema_type() === self::TYPE_ORGANIZATION;
	}

	private function get_schema_type() {
		return self::TYPE_PERSON === $this->get_social_option( 'schema_type' )
			? self::TYPE_PERSON
			: self::TYPE_ORGANIZATION;
	}

	private function url_to_id( $url, $id ) {
		/**
		 * @var $wp_rewrite WP_Rewrite
		 */
		global $wp_rewrite;
		if ( $wp_rewrite->using_permalinks() ) {
			$url = trailingslashit( $url );
		}

		return $url . $id;
	}

	/**
	 * @param $url
	 * @param $posts
	 */
	private function set_archive_schema( $type, $url, $posts ) {
		$this->add_header_footer_schema( $url );
		$this->add_publisher_schema();
		$this->add_website_schema();

		$list_type = $this->get_archive_main_entity_type();
		$is_type_item_list = $list_type === self::TYPE_ITEM_LIST;
		$list_item_type = $is_type_item_list ? "ListItem" : "BlogPosting";
		$list_type_key = $is_type_item_list ? "itemListElement" : "blogPosts";

		$main = array(
			"@type"     => $type,
			"@id"       => $this->get_webpage_id( $url ),
			"isPartOf"  => array(
				"@id" => $this->get_website_id(),
			),
			"publisher" => array(
				"@id" => $this->get_publisher_id(),
			),
			"url"       => $url,
		);

		$item_list = array();
		$position = 1;
		$posts = empty( $posts ) || ! is_array( $posts ) ? array() : $posts;

		foreach ( $posts as $post ) {
			$post_url = get_permalink( $post );

			$item_list[] = $is_type_item_list
				? array(
					"@type"    => $list_item_type,
					"position" => (string) $position,
					"url"      => $post_url,
				)
				: $this->post_to_schema( $post, $post_url, false );
			$position ++;
		}
		if ( $item_list ) {
			$main["mainEntity"] = array(
				"@type"        => $list_type,
				$list_type_key => $item_list,
			);
		}

		$this->register_type( $type, $main );
	}

	private function get_archive_main_entity_type() {
		$list_type = $this->get_schema_option( 'schema_archive_main_entity_type' );
		return $list_type
			? $list_type
			: self::TYPE_ITEM_LIST;
	}

	private function get_menu_schema( $web_page_url ) {
		$main_menu_slug = $this->get_schema_option( 'schema_main_navigation_menu' );
		if ( empty( $main_menu_slug ) ) {
			return array();
		}

		$menu_items = wp_get_nav_menu_items( $main_menu_slug );
		$schema = array();

		if ( is_array( $menu_items ) ) {
			foreach ( $menu_items as $menu_item ) {
				/**
				 * @var $menu_item WP_Post
				 */
				$schema[] = array(
					"@type" => "SiteNavigationElement",
					"@id"   => $this->url_to_id( $web_page_url, '#schema-nav-element-' . $menu_item->ID ),
					"name"  => $menu_item->post_title,
					"url"   => $menu_item->url,
				);
			}
		}

		return $schema;
	}

	private function get_comments_schema( $post_id, $post_url ) {
		/**
		 * @var $comments WP_Comment[]
		 */
		$comments = get_comments( array(
			'post_id'      => $post_id,
			'status'       => 'approve',
			'hierarchical' => 'threaded',
		) );

		return $this->comments_to_schema( $comments, $post_url );
	}

	/**
	 * @param $comments WP_Comment[]
	 * @param $post_url
	 *
	 * @return array
	 */
	private function comments_to_schema( $comments, $post_url ) {
		$schema = array();
		foreach ( $comments as $comment ) {
			$author_id = '#comment-author-' . md5( $comment->comment_author_email );
			$comment_schema = array(
				"@type"       => self::TYPE_COMMENT,
				"@id"         => $this->url_to_id( $post_url, '#schema-comment-' . $comment->comment_ID ),
				"text"        => $comment->comment_content,
				"dateCreated" => $comment->comment_date,
				"url"         => get_comment_link( $comment ),
				"author"      => array(
					"@type" => self::TYPE_PERSON,
					"@id"   => $this->url_to_id( $post_url, $author_id ),
					"name"  => $comment->comment_author,
				),
			);

			$children = $comment->get_children();
			if ( ! empty( $children ) ) {
				$comment_schema["comment"] = $this->comments_to_schema( $children, $post_url );
			}

			if ( ! empty( $comment->comment_author_url ) ) {
				$comment_schema["author"]["url"] = $comment->comment_author_url;
			}

			$schema[] = $comment_schema;
		}

		return $schema;
	}

	public function add_header_footer_schema( $url ) {
		$enable_header_footer = (bool) $this->get_schema_option( 'schema_wp_header_footer' );
		if ( ! $enable_header_footer ) {
			return;
		}

		$helper = Smartcrawl_Meta_Value_Helper::get();
		$headline = $helper->get_title();
		$description = $helper->get_description();

		$this->register_type( 'WPHeader', array(
			"@type"       => "WPHeader",
			"url"         => $url,
			"headline"    => $headline,
			"description" => $description,
		) );

		$this->register_type( 'WPFooter', array(
			"@type"         => "WPFooter",
			"url"           => $url,
			"headline"      => $headline,
			"description"   => $description,
			"copyrightYear" => date( 'Y' ),
		) );
	}

	private function add_media_schema( $post ) {
		$this->add_oembed_schema( $post );
		$this->add_attachment_schema( $post );
	}

	private function add_oembed_schema( $post ) {
		$schema_data_controller = Smartcrawl_Controller_Media_Schema_Data::get();
		$schema_data_controller->maybe_refresh_wp_embeds_cache( $post );

		$cache = $schema_data_controller->get_cache( $post->ID );
		if ( empty( $cache ) ) {
			return;
		}

		$enable_audio = (bool) $this->get_schema_option( 'schema_enable_audio' );
		$audio_data = smartcrawl_get_array_value( $cache, 'audio' );
		if ( $enable_audio && ! empty( $audio_data ) && is_array( $audio_data ) ) {
			foreach ( $audio_data as $audio_datum ) {
				$this->register_type(
					self::TYPE_AUDIO_OBJECT,
					$this->get_audio_schema( $audio_datum )
				);
			}
		}

		$enable_youtube = (bool) $this->get_schema_option( 'schema_enable_yt_api' );
		$youtube_data = smartcrawl_get_array_value( $cache, 'youtube' );
		$youtube_data = empty( $youtube_data ) ? array() : $youtube_data;

		$enable_video = (bool) $this->get_schema_option( 'schema_enable_video' );
		$video_data = smartcrawl_get_array_value( $cache, 'video' );
		if ( $enable_video && ! empty( $video_data ) && is_array( $video_data ) ) {
			foreach ( $video_data as $video_id => $video_datum ) {
				if ( $enable_youtube && array_key_exists( $video_id, $youtube_data ) ) {
					$this->register_type(
						self::TYPE_VIDEO_OBJECT,
						$this->get_youtube_schema( $youtube_data[ $video_id ], $video_datum )
					);
				} else {
					$this->register_type(
						self::TYPE_VIDEO_OBJECT,
						$this->get_video_schema( $video_datum )
					);
				}
			}
		}
	}

	private function get_audio_schema( $data ) {
		return $this->media_data_to_schema( array(
			'title'         => "name",
			'url'           => "url",
			'description'   => "description",
			'thumbnail_url' => "thumbnailUrl",
		), $data, self::TYPE_AUDIO_OBJECT );
	}

	private function get_video_schema( $data ) {
		$schema = $this->media_data_to_schema( array(
			'title'            => "name",
			'url'              => "url",
			'description'      => "description",
			'upload_date'      => "uploadDate",
			'thumbnail_url'    => array( "thumbnail", "url" ),
			'thumbnail_width'  => array( "thumbnail", "width" ),
			'thumbnail_height' => array( "thumbnail", "height" ),
		), $data, self::TYPE_VIDEO_OBJECT );

		$duration = $this->get_duration( $data );
		if ( $duration ) {
			$schema['duration'] = $duration;
		}

		$schema = $this->add_embed_url_property( $schema, $data );

		return $schema;
	}

	private function get_youtube_schema( $data, $embed_data ) {
		$schema = $this->media_data_to_schema( array(
			'title'                => "name",
			'url'                  => "url",
			'description'          => "description",
			'publishedAt'          => "uploadDate",
			'duration'             => "duration",
			'defaultAudioLanguage' => "inLanguage",
			'definition'           => "videoQuality",
		), $data, self::TYPE_VIDEO_OBJECT );

		$schema = $this->add_youtube_thumbnail_data( $data, $schema );

		$tags = smartcrawl_get_array_value( $data, 'tags' );
		if ( $tags && is_array( $tags ) ) {
			$schema["keywords"] = join( ',', $tags );
		}

		$schema = $this->add_embed_url_property( $schema, $embed_data );

		return $schema;
	}

	private function add_embed_url_property( $schema, $embed_data ) {
		if ( isset( $embed_data['html'] ) ) {
			$src_attribute = Smartcrawl_Html::find_attributes( 'iframe', 'src', $embed_data['html'] );
			if ( ! empty( $src_attribute ) ) {
				$schema['embedUrl'] = array_shift( $src_attribute );
			}
		}

		return $schema;
	}

	private function seconds_to_duration( $seconds ) {
		$mins = (int) gmdate( "i", $seconds );
		$secs = (int) gmdate( "s", $seconds );

		return "PT{$mins}M{$secs}S";
	}

	private function get_duration( $data ) {
		$seconds = smartcrawl_get_array_value( $data, 'duration' );
		if ( ! $seconds ) {
			return '';
		}

		return $this->seconds_to_duration( $seconds );
	}

	private function media_data_to_schema( $mapping, $data, $type ) {
		$schema = array(
			"@type" => $type,
		);
		foreach ( $mapping as $source_key => $target_key ) {
			$source_value = smartcrawl_get_array_value( $data, $source_key );
			if ( $source_value ) {
				smartcrawl_put_array_value( $source_value, $schema, $target_key );
			}
		}
		if ( ! empty( $schema['author'] ) ) {
			$schema['author'] = array( "@type" => self::TYPE_PERSON ) + $schema['author'];
		}
		if ( ! empty( $schema['publisher'] ) ) {
			$schema['publisher'] = array( "@type" => self::TYPE_ORGANIZATION ) + $schema['publisher'];
		}
		if ( ! empty( $schema['thumbnail'] ) ) {
			$schema['thumbnail'] = array( "@type" => self::TYPE_IMAGE ) + $schema['thumbnail'];
			$schema['thumbnailUrl'] = $schema['thumbnail']['url'];
		}

		return $schema;
	}

	private function add_youtube_thumbnail_data( $data, array $schema ) {
		$thumbnails = smartcrawl_get_array_value( $data, 'thumbnails' );
		$thumbnail_url_default = '';
		foreach ( $thumbnails as $thumbnail_size => $thumbnail ) {
			$thumbnail_url = smartcrawl_get_array_value( $thumbnail, 'url' );
			$schema["thumbnail"][] = array(
				"@type"  => self::TYPE_IMAGE,
				"url"    => $thumbnail_url,
				"width"  => smartcrawl_get_array_value( $thumbnail, 'width' ),
				"height" => smartcrawl_get_array_value( $thumbnail, 'height' ),
			);

			if ( $thumbnail_size === 'default' ) {
				$thumbnail_url_default = $thumbnail_url;
			}
		}
		if ( $thumbnail_url_default ) {
			$schema['thumbnailUrl'] = $thumbnail_url_default;
		}
		return $schema;
	}

	/**
	 * @param $user Smartcrawl_Model_User
	 */
	private function get_user_full_name( $user ) {
		return $this->apply_filters( 'user-full_name', $user->get_full_name(), $user );
	}

	/**
	 * @param $user Smartcrawl_Model_User
	 */
	private function get_user_url( $user ) {
		return $this->apply_filters( 'user-url', $user->get_user_url(), $user );
	}

	/**
	 * @param $user Smartcrawl_Model_User
	 */
	public function get_user_urls( $user ) {
		return $this->apply_filters( 'user-urls', $user->get_user_urls(), $user );
	}

	private function apply_filters( $filter, ...$args ) {
		return apply_filters( "wds-schema-{$filter}", ...$args );
	}

	/**
	 * @param $post
	 * @param $permalink
	 * @param array $schema
	 *
	 * @return array
	 */
	private function add_article_image( $post, $permalink, array $schema ) {
		if ( has_post_thumbnail( $post ) ) {
			$image_id = get_post_thumbnail_id( $post );
		} else {
			$image_id = (int) $this->get_schema_option( 'schema_default_image' );
		}

		if ( $image_id ) {
			$schema["image"] = $this->filter_post_data_image( $this->utils->get_media_item_image_schema(
				$image_id,
				$this->url_to_id( $permalink, '#schema-article-image' )
			) );
			$schema["thumbnailUrl"] = (string) $this->apply_filters(
				'post-data-thumbnailUrl',
				smartcrawl_get_array_value( $schema, array( 'image', 'url' ) )
			);
		}
		return $schema;
	}

	function first_non_empty_string( ...$args ) {
		foreach ( $args as $arg ) {
			if ( ! empty( $arg ) ) {
				return $arg;
			}
		}

		return '';
	}

	private function add_attachment_schema( $post ) {
		$src_attributes = Smartcrawl_Html::find_attributes( 'video, audio', 'src', $post->post_content );
		foreach ( $src_attributes as $html_element => $src_url ) {
			$attachment_id = attachment_url_to_postid( $src_url );
			if ( ! $attachment_id ) {
				continue;
			}

			$attachment = get_post( $attachment_id );

			if ( $this->is_mime_type_video( $attachment ) ) {
				$this->register_type(
					self::TYPE_VIDEO_OBJECT,
					$this->get_video_attachment_schema( $attachment, $html_element )
				);
			}

			if ( $this->is_mime_type_audio( $attachment ) ) {
				$this->register_type(
					self::TYPE_AUDIO_OBJECT,
					$this->get_audio_attachment_schema( $attachment )
				);
			}
		}
	}

	private function get_video_attachment_schema( $attachment, $video_element_html ) {
		$attachment_url = wp_get_attachment_url( $attachment->ID );
		$attachment_schema = $this->get_attachment_schema( self::TYPE_VIDEO_OBJECT, $attachment, $attachment_url );

		// Video poster image
		$poster_image_url = $this->get_video_poster_attribute( $video_element_html );
		if ( $poster_image_url ) {
			$attachment_schema['thumbnailUrl'] = $poster_image_url;
		}

		return $attachment_schema;
	}

	private function get_video_poster_attribute( $video_element_html ) {
		$poster_values = Smartcrawl_Html::find_attributes( 'video', 'poster', $video_element_html );
		if ( count( $poster_values ) > 0 ) {
			return array_shift( $poster_values );
		}

		return '';
	}

	private function get_audio_attachment_schema( $attachment ) {
		return $this->get_attachment_schema(
			self::TYPE_AUDIO_OBJECT,
			$attachment,
			wp_get_attachment_url( $attachment->ID )
		);
	}

	/**
	 * @param $attachment WP_Post
	 *
	 * @return bool
	 */
	private function is_mime_type_video( $attachment ) {
		return strpos( $attachment->post_mime_type, 'video/' ) !== false;
	}

	/**
	 * @param $attachment WP_Post
	 *
	 * @return bool
	 */
	private function is_mime_type_audio( $attachment ) {
		return strpos( $attachment->post_mime_type, 'audio/' ) !== false;
	}

	/**
	 * @param $attachment WP_Post
	 * @param $attachment_url
	 *
	 * @return array
	 */
	private function get_attachment_schema( $type, $attachment, $attachment_url ) {
		$description = $attachment->post_excerpt
			? $attachment->post_excerpt
			: $attachment->post_content;

		return array(
			'@type'       => $type,
			'name'        => $attachment->post_title,
			'description' => wp_strip_all_tags( $description ),
			'uploadDate'  => $attachment->post_date,
			'contentUrl'  => $attachment_url,
		);
	}

	public function get_personal_brand_name() {
		return $this->first_non_empty_string(
			$this->get_schema_option( 'person_brand_name' ),
			$this->get_social_option( 'override_name' ),
			$this->get_user_full_name( $this->owner )
		);
	}

	public function get_organization_name() {
		$organization_name = $this->get_social_option( 'organization_name' );
		return $organization_name ? $organization_name : get_bloginfo( 'name' );
	}

	public function get_organization_description() {
		$description = $this->get_textarea_schema_option( 'organization_description' );
		return $description ? $description : get_bloginfo( 'description' );
	}

	private function register_type( $type, $schema ) {
		$this->type_registry[ $type ][] = $schema;
	}

	/**
	 * @param $post WP_Post
	 *
	 * @return bool
	 */
	private function show_news_article_schema( $post ) {
		if ( ! Smartcrawl_Sitemap_Utils::get_sitemap_option( 'enable-news-sitemap' ) ) {
			return false;
		}

		$news_query = new Smartcrawl_Sitemap_News_Query();
		$supported_types = $news_query->get_supported_types();
		if ( ! in_array( $post->post_type, $supported_types, true ) ) {
			return false;
		}

		$ignore_ids = $news_query->get_ignore_ids( array( $post->post_type ) );
		if ( in_array( $post->ID, $ignore_ids ) ) {
			return false;
		}

		return true;
	}
}
