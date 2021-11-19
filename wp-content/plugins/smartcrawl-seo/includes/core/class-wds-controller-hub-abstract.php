<?php

abstract class Smartcrawl_Controller_Hub_Abstract {

	public function json_receive_audit_data( $params = array(), $action = '' ) {

	}

	public function sync_ignores_list( $params = array(), $action = '' ) {
		return false;
	}

	public function json_sync_ignores_list( $params = array(), $action = '' ) {
	}

	public function purge_ignores_list( $params = array(), $action = '' ) {
		return false;
	}

	public function json_purge_ignores_list( $params = array(), $action = '' ) {
	}

	public function sync_extras_list( $params = array(), $action = '' ) {
		return false;
	}

	public function json_sync_extras_list( $params = array(), $action = '' ) {
	}

	public function purge_extras_list( $params = array(), $action = '' ) {
		return false;
	}

	public function json_purge_extras_list( $params = array(), $action = '' ) {
	}

	public function json_seo_summary() {
		$options = Smartcrawl_Settings::get_options();

		// Twitter cards
		$twitter_enabled = (bool) smartcrawl_get_array_value( $options, 'twitter-card-enable' );
		$card_type = smartcrawl_get_array_value( $options, 'twitter-card-type' );
		$twitter = $twitter_enabled
			? array( 'card_type' => $card_type )
			: array();

		// Pinterest
		$pinterest_status = smartcrawl_get_array_value( $options, 'pinterest-verification-status' );
		$pinterest_value = smartcrawl_get_array_value( $options, 'pinterest-verify' );
		$pinterest = empty( $pinterest_value )
			? array()
			: array(
				'status' => 'fail' === $pinterest_status ? 'unverified' : 'verified',
			);

		// URL redirects
		$redirection = new Smartcrawl_Model_Redirection();
		$redirects_count = count( $redirection->get_all_redirections() );

		// Moz
		$moz_access_id = smartcrawl_get_array_value( $options, 'access-id' );
		$moz_secret_key = smartcrawl_get_array_value( $options, 'secret-key' );
		$moz_active = $moz_access_id && $moz_secret_key;
		$moz = array(
			'active' => $moz_active,
			'data'   => $moz_active
				? get_option( Smartcrawl_Controller_Moz_Cron::OPTION_ID, array() )
				: (object) array(),
		);

		// Robots file
		$robots_controller = Smartcrawl_Controller_Robots::get();

		// The whole advanced page can be disabled in the network settings
		$autolinks_settings = Smartcrawl_Autolinks_Settings::get_instance();
		$advanced_active = Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_AUTOLINKS );
		$autolinks_active = $this->is_active( 'autolinks' );
		$autolinks = $autolinks_active
			? array(
				'insert'  => $autolinks_settings->get_insert_options(),
				'link_to' => $autolinks_settings->get_linkto_options(),
			)
			: array();
		$advanced = $advanced_active
			? array(
				'autolinks'          => (object) $autolinks,
				'url_redirects'      => $redirects_count,
				'moz'                => $moz,
				'robots_txt_active'  => $robots_controller->robots_active(),
				'autolinking_active' => Smartcrawl_Settings::get_setting( 'autolinks' ),
			)
			: array();

		// Lighthouse reporting schedule
		$lighthouse_reporting_enabled = Smartcrawl_Lighthouse_Options::is_cron_enabled();
		$lighthouse_reporting = $lighthouse_reporting_enabled
			? array(
				'frequency'  => Smartcrawl_Lighthouse_Options::reporting_frequency(),
				'day'        => Smartcrawl_Lighthouse_Options::reporting_dow(),
				'time'       => Smartcrawl_Lighthouse_Options::reporting_tod(),
				'recipients' => count( Smartcrawl_Lighthouse_Options::email_recipients() ),
			)
			: array();
		$lighthouse = array(
			'enabled'   => Smartcrawl_Health_Settings::is_test_mode_lighthouse(),
			'reporting' => (object) $lighthouse_reporting,
		);

		// Checkup reporting schedule
		$checkup_service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_CHECKUP );
		if ( $checkup_service->in_progress() ) {
			// Call status once so that the last updated timestamp gets updated
			$checkup_service->status();
		}
		$checkup_reporting_enabled = Smartcrawl_Checkup_Options::is_cron_enabled();
		$checkup_reporting = $checkup_reporting_enabled
			? array(
				'frequency'  => Smartcrawl_Checkup_Options::reporting_frequency(),
				'day'        => Smartcrawl_Checkup_Options::reporting_dow(),
				'time'       => Smartcrawl_Checkup_Options::reporting_tod(),
				'recipients' => count( Smartcrawl_Checkup_Options::email_recipients() ),
			)
			: array();
		$checkup = array(
			'enabled'            => Smartcrawl_Health_Settings::is_test_mode_checkup(),
			'in_progress'        => $checkup_service->in_progress(),
			'last_run_timestamp' => $checkup_service->get_last_checked_timestamp(),
			'reporting'          => (object) $checkup_reporting,
		);

		// Crawler reporting schedule
		$seo_service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SEO );
		$seo_report = $seo_service->get_report();
		$crawler_reporting_enabled = smartcrawl_get_array_value( $options, 'crawler-cron-enable' );
		$crawler_reporting = $crawler_reporting_enabled
			? array(
				'frequency'  => smartcrawl_get_array_value( $options, 'crawler-frequency' ),
				'day'        => smartcrawl_get_array_value( $options, 'crawler-dow' ),
				'time'       => smartcrawl_get_array_value( $options, 'crawler-tod' ),
				'recipients' => count( Smartcrawl_Sitemap_Settings::get_email_recipients() ),
			)
			: array();
		$sitemap_stats = Smartcrawl_Sitemap_Utils::get_meta_data();
		$sitemap_se_stats = get_option( Smartcrawl_Sitemap_Utils::ENGINE_NOTIFICATION_OPTION_ID );
		$sitemap = $this->is_active( 'sitemap' )
			? array(
				'url'                      => smartcrawl_get_sitemap_url(),
				'last_update'              => smartcrawl_get_array_value( $sitemap_stats, 'time' ),
				'last_google_notification' => smartcrawl_get_array_value( $sitemap_se_stats, array(
					'google',
					'time',
				) ),
				'last_bing_notification'   => smartcrawl_get_array_value( $sitemap_se_stats, array(
					'bing',
					'time',
				) ),
				'crawler'                  => array(
					'in_progress'        => $seo_report->is_in_progress(),
					'last_run_timestamp' => $seo_service->get_last_run_timestamp(),
					'reporting'          => (object) $crawler_reporting,
				),
			)
			: array();

		// Third-party import
		$import_plugins = array();
		$yoast_importer = new Smartcrawl_Yoast_Importer();
		if ( $yoast_importer->data_exists() ) {
			$import_plugins[] = 'yoast';
		}
		$aioseo = new Smartcrawl_AIOSEOP_Importer();
		if ( $aioseo->data_exists() ) {
			$import_plugins[] = 'aioseo';
		}

		$onpage_active = $this->is_active( 'onpage' );
		$onpage = $onpage_active
			? array(
				'meta'              => $this->get_titles_and_meta(),
				'static_homepage'   => get_option( 'show_on_front' ) === 'page',
				'public_post_types' => count( get_post_types( array( 'public' => true ) ) ),
			)
			: array();

		$social_active = $this->is_active( 'social' );
		$social = $social_active
			? array(
				'opengraph_active' => (bool) smartcrawl_get_array_value( $options, 'og-enable' ),
				'twitter'          => (object) $twitter,
				'pinterest'        => (object) $pinterest,
			)
			: array();

		$analysis = array();
		$analysis_model = new Smartcrawl_Model_Analysis();
		$seo_analysis_enabled = Smartcrawl_Settings::get_setting( 'analysis-seo' );
		if ( $seo_analysis_enabled ) {
			$analysis['seo'] = $analysis_model->get_overall_seo_analysis();
		}
		$readability_analysis_enabled = Smartcrawl_Settings::get_setting( 'analysis-readability' );
		if ( $readability_analysis_enabled ) {
			$analysis['readability'] = $analysis_model->get_overall_readability_analysis();
		}

		// Schema
		$schema_helper = new Smartcrawl_Schema_Value_Helper();
		$is_schema_type_person = $schema_helper->is_schema_type_person();
		$schema_active = ! smartcrawl_get_array_value( $options, 'disable-schema' )
		                 && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SCHEMA );
		$schema = $schema_active ? array(
			'org_type' => $is_schema_type_person
				? Smartcrawl_Schema_Value_Helper::TYPE_PERSON
				: Smartcrawl_Schema_Value_Helper::TYPE_ORGANIZATION,
			'org_name' => $is_schema_type_person
				? $schema_helper->get_personal_brand_name()
				: $schema_helper->get_organization_name(),
			'types'    => $this->get_schema_types(),
		) : array();

		wp_send_json_success( array(
			'onpage'     => (object) $onpage,
			'schema'     => (object) $schema,
			'social'     => (object) $social,
			'advanced'   => (object) $advanced,
			'lighthouse' => (object) $lighthouse,
			'checkup'    => (object) $checkup,
			'sitemap'    => (object) $sitemap,
			'analysis'   => (object) $analysis,
			'import'     => array( 'plugins' => $import_plugins ),
		) );
	}

	public function json_run_checkup() {
		$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_CHECKUP );
		$started = $service->start();

		if ( $started ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	public function json_run_crawl() {
		$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SEO );
		$started = $service->start();

		if ( is_wp_error( $started ) ) {
			wp_send_json_error( array(
				'message' => $started->get_error_message(),
			) );
		} elseif ( ! $started ) {
			wp_send_json_error();
		} else {
			wp_send_json_success();
		}
	}

	private function get_titles_and_meta() {
		$meta = array();
		$posts_on_front = 'posts' === get_option( 'show_on_front' ) ||
		                  0 === (int) get_option( 'page_on_front' );
		$resolver = Smartcrawl_Endpoint_Resolver::resolve();
		$meta_helper = Smartcrawl_Meta_Value_Helper::get();
		$robots = new Smartcrawl_Robots_Value_Helper();
		if ( $posts_on_front ) {
			$query = new WP_Query();
			$query->is_home = true;
			$resolver->simulate( Smartcrawl_Endpoint_Resolver::L_BLOG_HOME, null, $query );
			$meta_helper->handle_blog_home();
			$robots->handle_blog_home();
		} else {
			$page_on_front = (int) get_option( 'page_on_front' );
			$resolver->simulate_post( get_post( $page_on_front ) );
			$meta_helper->handle_singular( $page_on_front );
			$robots->handle_singular( $page_on_front );
		}
		$resolver->stop_simulation();
		$home_robots = $robots->get_value();
		$meta['home'] = array(
			'label'       => esc_html__( 'Homepage', 'wds' ),
			'title'       => $meta_helper->get_title(),
			'description' => $meta_helper->get_description(),
			'noindex'     => strpos( $home_robots, 'noindex' ) !== false,
			'nofollow'    => strpos( $home_robots, 'nofollow' ) !== false,
			'url'         => home_url(),
		);

		foreach (
			get_post_types( array(
				'public'  => true,
				'show_ui' => true,
			), 'objects' ) as $post_type
		) {
			$random_post = $this->get_random_post( $post_type->name );
			if ( ! $random_post ) {
				continue;
			}
			$resolver->simulate_post( $random_post );
			$meta_helper = Smartcrawl_Meta_Value_Helper::get();
			$robots = new Smartcrawl_Robots_Value_Helper();
			$robots->traverse();
			$post_type_robots = $robots->get_value();
			$meta[ $post_type->name ] = array(
				'label'       => $post_type->label,
				'title'       => $meta_helper->get_title(),
				'description' => $meta_helper->get_description(),
				'noindex'     => strpos( $post_type_robots, 'noindex' ) !== false,
				'nofollow'    => strpos( $post_type_robots, 'nofollow' ) !== false,
				'url'         => get_permalink( $random_post ),
			);
			$resolver->stop_simulation();
		}

		return $meta;
	}

	/**
	 * @param $post_type
	 *
	 * @return WP_Post|null
	 */
	private function get_random_post( $post_type ) {
		$posts = get_posts( array(
			'post_status'    => array( 'publish', 'inherit' ),
			'orderby'        => 'rand',
			'posts_per_page' => 1,
			'post_type'      => $post_type,
		) );

		return smartcrawl_get_array_value( $posts, 0 );
	}

	private function get_schema_types() {
		$schema_types = Smartcrawl_Controller_Schema_Types::get()->get_schema_types();

		return array_unique( array_column( $schema_types, 'type' ) );
	}

	private function is_active( $module ) {
		return Smartcrawl_Settings::get_setting( $module )
		       && Smartcrawl_Settings_Admin::is_tab_allowed( 'wds_' . $module );
	}
}
