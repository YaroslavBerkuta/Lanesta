<?php

class Smartcrawl_Controller_Assets extends Smartcrawl_Base_Controller {
	const SUI_JS = 'wds-shared-ui';
	const ADMIN_JS = 'wds-admin';
	const OPENGRAPH_JS = 'wds-admin-opengraph';
	const QTIP2_JS = 'wds-qtip2-script';
	const MACRO_REPLACEMENT = 'wds-macro-replacement';

	const POSTLIST_JS = 'wds-admin-postlist';
	const AUTOLINKS_PAGE_JS = 'wds-admin-autolinks';

	const ONPAGE_COMPONENTS = 'wds-onpage-components';
	const ONPAGE_JS = 'wds-admin-onpage';

	const SITEMAPS_PAGE_JS = 'wds-admin-sitemaps';

	const DASHBOARD_PAGE_JS = 'wds-admin-dashboard';

	const ONBOARDING_JS = 'wds-onboard';

	const EMAIL_RECIPIENTS_JS = 'wds-admin-email-recipients';
	const CHECKUP_PAGE_JS = 'wds-admin-checkup';

	const SOCIAL_PAGE_JS = 'wds-admin-social';

	const CONFIGS_JS = 'wds-configs';
	const THIRD_PARTY_IMPORT_JS = 'wds-third-party-import';
	const SETTINGS_PAGE_JS = 'wds-admin-settings';
	const NETWORK_SETTINGS_PAGE_JS = 'wds-admin-network-settings';

	const METABOX_COUNTER_JS = 'wds-metabox-counter';
	const METABOX_JS = 'wds-metabox';
	const METABOX_COMPONENTS_JS = 'wds-metabox-components';
	const METABOX_LINK_FORMAT_BUTTON = 'wds-link-format-button';
	const METABOX_LINK_REL_ATTRIBUTE_FIELD = 'wds-link-rel-attribute-field';

	const WP_POST_LIST_TABLE_JS = 'wds-admin-post-list-table';
	const WP_POST_LIST_TABLE_CSS = 'wds-admin-post-list-table-styling';

	const TERM_FORM_JS = 'wds-term-form';

	const QTIP2_CSS = 'wds-qtip2-style';
	const APP_CSS = 'wds-app';
	const WP_DASHBOARD_CSS = 'wds-wp-dashboard';

	const SCHEMA_JS = 'wds-admin-schema';
	const SCHEMA_TYPES_JS = 'wds-schema-types';
	const WELCOME_JS = 'wds-welcome-modal';
	const HEALTH_JS = 'wds-admin-health';
	const LIGHTHOUSE_JS = 'wds-admin-lighthouse';

	const BLACK_FRIDAY_2021 = 'wds-black-friday';

	/**
	 * Singleton instance
	 *
	 * @var self
	 */
	private static $_instance;

	/**
	 * Obtain instance without booting up
	 *
	 * @return self instance
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Bind listening actions
	 *
	 * @return bool
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ), - 10 );

		return true;
	}

	private function is_page( $page ) {
		global $pagenow;

		return $pagenow === 'admin.php'
		       && smartcrawl_get_array_value( $_GET, 'page' ) === $page;
	}

	public function register_assets() {
		$this->register_general_scripts();
		$this->register_advanced_tools_scripts();
		$this->register_onpage_page_scripts();
		$this->register_sitemap_page_scripts();
		$this->register_dashboard_page_scripts();
		$this->register_checkup_page_scripts();
		$this->register_social_page_scripts();
		$this->register_settings_page_scripts();
		$this->register_metabox_scripts();
		$this->register_post_list_scripts();
		$this->register_term_form_scripts();
		$this->register_network_settings_page_scripts();
		$this->register_schema_settings_page_scripts();
		$this->register_health_settings_page_scripts();

		$this->register_general_styles();
		$this->register_wp_dashboard_styles();
		$this->register_post_list_styles();
	}

	private function get_version() {
		$value = Smartcrawl_Loader::get_version();
		if ( defined( 'SMARTCRAWL_BUILD' ) ) {
			$value = sprintf( '%s-%s', $value, SMARTCRAWL_BUILD );
		}
		return $value;
	}

	private function register_js( $handle, $src, $deps = array() ) {
		wp_register_script( $handle, $this->base_url( $src ), $deps, $this->get_version(), true );
	}

	private function register_css( $handle, $src, $deps = array() ) {
		wp_register_style( $handle, $this->base_url( $src ), $deps, $this->get_version(), 'all' );
	}

	private function base_url( $url ) {
		return trailingslashit( SMARTCRAWL_PLUGIN_URL ) . "assets/$url";
	}

	private function register_general_scripts() {
		// Shared UI
		$this->register_js( self::SUI_JS, 'shared-ui/js/shared-ui.js', array(
			'jquery',
			'clipboard',
		) );

		// Common JS functions and utils
		$this->register_js( self::ADMIN_JS, 'js/wds-admin.js', array(
			self::SUI_JS,
			'jquery',
		) );

		wp_localize_script( self::ADMIN_JS, '_wds_admin', array(
			'strings' => array(
				'initializing' => esc_html__( 'Initializing ...', 'wds' ),
				'running'      => esc_html__( 'Running SEO checks ...', 'wds' ),
				'finalizing'   => esc_html__( 'Running final checks and finishing up ...', 'wds' ),
				'characters'   => esc_html__( 'characters', 'wds' ),
			),
			'nonce'   => wp_create_nonce( 'wds-admin-nonce' ),
		) );

		$this->register_js( self::OPENGRAPH_JS, 'js/wds-admin-opengraph.js', array(
			'underscore',
			'jquery',
			self::ADMIN_JS,
		) );

		wp_localize_script( self::OPENGRAPH_JS, '_wds_opengraph', array(
			'templates' => array(
				'item' => Smartcrawl_Simple_Renderer::load( 'social-image-item', array(
					'id'         => '{{= id }}',
					'url'        => '{{= url }}',
					'field_name' => '{{= name }}',
				) ),
			),
		) );

		$this->register_js( self::QTIP2_JS, 'js/external/jquery.qtip.min.js', array(
			'jquery',
		) );

		$macro_replacement_deps = $this->dynamic_dependencies( self::MACRO_REPLACEMENT, array(
			'jquery',
			'underscore',
			'wp-api',
			'wp-api-fetch',
			'wp-date',
		) );
		$this->register_js( self::MACRO_REPLACEMENT, 'js/build/wds-macro-replacement.js', $macro_replacement_deps );

		wp_localize_script( self::MACRO_REPLACEMENT, '_wds_replacement', array(
			'date_format'         => get_option( 'date_format' ),
			'time_format'         => get_option( 'time_format' ),
			'metadesc_max_length' => smartcrawl_metadesc_max_length(),
			'taxonomies'          => $this->get_taxonomies(),
			'replacements'        => $this->get_replacements(),
			'omitted_shortcodes'  => apply_filters( 'wds-omitted-shortcodes', array() ),
		) );

		$configs_deps = $this->dynamic_dependencies( self::CONFIGS_JS, array(
			'jquery',
			self::ADMIN_JS,
		) );
		$this->register_js( self::CONFIGS_JS, 'js/build/wds-configs.js', $configs_deps );

		$this->set_script_translations( self::CONFIGS_JS );

		$service = new Smartcrawl_Configs_Service();
		wp_localize_script( self::CONFIGS_JS, '_wds_config', array(
			'is_member'    => $service->is_member(),
			'nonce'        => wp_create_nonce( 'wds-configs-nonce' ),
			'configs'      => Smartcrawl_Config_Collection::get()->get_deflated_configs(),
			'timezone'     => $this->get_timezone(),
			'manage_url'   => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings_Admin::TAB_SETTINGS ) . '&tab=tab_configs',
			'default_icon' => sprintf( '%s/assets/images/default-config.svg', SMARTCRAWL_PLUGIN_URL ),
		) );

		$this->register_js(
			self::BLACK_FRIDAY_2021,
			'js/build/wds-black-friday.js',
			$this->dynamic_dependencies( self::BLACK_FRIDAY_2021 )
		);
		wp_localize_script( self::BLACK_FRIDAY_2021, '_wds_black_friday', array(
			'build_type' => SMARTCRAWL_BUILD_TYPE,
		) );
	}

	private function register_advanced_tools_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_AUTOLINKS ) ) {
			return;
		}

		$this->register_js( self::POSTLIST_JS, 'js/wds-admin-postlist.js', array(
			'underscore',
			'jquery',
			self::ADMIN_JS,
		) );

		wp_localize_script( self::POSTLIST_JS, '_wds_postlist', array(
			'templates'  => array(
				'exclude'      => Smartcrawl_Simple_Renderer::load( 'advanced-tools/underscore-postlist-exclusion' ),
				'exclude-item' => Smartcrawl_Simple_Renderer::load( 'advanced-tools/underscore-postlist-exclusion-item' ),
				'selector'     => Smartcrawl_Simple_Renderer::load( 'advanced-tools/underscore-postlist-selector' ),
			),
			'post_types' => Smartcrawl_Autolinks_Settings::get_post_types(),
			'strings'    => array(),
			'nonce'      => wp_create_nonce( 'wds-autolinks-nonce' ),
		) );

		$autolinks_deps = $this->dynamic_dependencies( self::AUTOLINKS_PAGE_JS, array(
			'underscore',
			'jquery',
			self::ADMIN_JS,
			self::POSTLIST_JS,
			self::BLACK_FRIDAY_2021,
		) );
		$this->register_js( self::AUTOLINKS_PAGE_JS, 'js/build/wds-admin-autolinks.js', $autolinks_deps );

		$this->set_script_translations( self::AUTOLINKS_PAGE_JS );

		$redirection_model = new Smartcrawl_Model_Redirection();
		wp_localize_script( self::AUTOLINKS_PAGE_JS, '_wds_autolinks', array(
			'custom_keywords'       => (string) smartcrawl_get_array_value( Smartcrawl_Settings::get_options(), 'customkey' ),
			'redirects'             => $redirection_model->get_all_redirections(),
			'redirect_types'        => $redirection_model->get_all_redirection_types(),
			'default_redirect_type' => $redirection_model->get_default_redirection_status_type(),
		) );
	}

	private function register_onpage_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return;
		}

		$onpage_deps = $this->dynamic_dependencies( self::ONPAGE_COMPONENTS, array(
			'jquery',
			'underscore',
		) );
		$this->register_js( self::ONPAGE_COMPONENTS, 'js/build/wds-onpage-components.js', $onpage_deps );
		wp_localize_script( self::ONPAGE_COMPONENTS, '_wds_onpage_components', array(
			'random_posts' => Smartcrawl_Onpage_Settings::get_random_post_data(),
			'random_terms' => Smartcrawl_Onpage_Settings::get_random_terms(),
		) );

		$this->register_js( self::ONPAGE_JS, 'js/wds-admin-onpage.js', array(
			'jquery',
			self::ADMIN_JS,
			self::OPENGRAPH_JS,
			self::ONPAGE_COMPONENTS,
			self::MACRO_REPLACEMENT,
			self::BLACK_FRIDAY_2021,
		) );
		wp_localize_script( self::ONPAGE_JS, '_wds_onpage', array(
			'templates'         => array(
				'notice'  => Smartcrawl_Simple_Renderer::load( 'notice', array( 'message' => '{{- message }}' ) ),
				'preview' => Smartcrawl_Simple_Renderer::load( 'onpage/underscore-onpage-preview' ),
			),
			'home_url'          => home_url( '/' ),
			'nonce'             => wp_create_nonce( 'wds-onpage-nonce' ),
			'title_min'         => smartcrawl_title_min_length(),
			'title_max'         => smartcrawl_title_max_length(),
			'metadesc_min'      => smartcrawl_metadesc_min_length(),
			'metadesc_max'      => smartcrawl_metadesc_max_length(),
			'random_archives'   => Smartcrawl_Onpage_Settings::get_random_archives(),
			'random_buddypress' => Smartcrawl_Onpage_Settings::get_random_buddypress(),
		) );
	}

	private function register_sitemap_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_SITEMAP ) ) {
			return;
		}

		$this->register_email_recipients_js();

		$sitemap_deps = $this->dynamic_dependencies( self::SITEMAPS_PAGE_JS, array(
			'jquery',
			self::ADMIN_JS,
			self::EMAIL_RECIPIENTS_JS,
			self::BLACK_FRIDAY_2021,
		) );
		$this->register_js( self::SITEMAPS_PAGE_JS, 'js/build/wds-admin-sitemaps.js', $sitemap_deps );

		$this->set_script_translations( self::SITEMAPS_PAGE_JS );

		wp_localize_script( self::SITEMAPS_PAGE_JS, '_wds_sitemaps', array(
			'nonce'   => wp_create_nonce( 'wds-nonce' ),
			'strings' => array(
				'manually_updated'          => esc_html__( 'Your sitemap has been updated.', 'wds' ),
				'manually_notified_engines' => esc_html__( 'Search Engines are being notified with changes.', 'wds' ),
			),
		) );

		$seo_service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SEO );
		$report = $seo_service->get_report();

		wp_localize_script( self::SITEMAPS_PAGE_JS, '_wds_crawler', array(
			'nonce'              => wp_create_nonce( 'wds-crawler-nonce' ),
			'issues'             => $report->get_all_issues_grouped_by_type(),
			'advanced_tools_url' => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_AUTOLINKS ) . '&tab=tab_url_redirection',
		) );

		$social_options = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );
		$schema_disabled = empty( $social_options['disable-schema'] ) ? false : true;

		wp_localize_script( self::SITEMAPS_PAGE_JS, '_wds_news', array_merge(
			array(
				'home_url'       => home_url( '/' ),
				'schema_enabled' => ! $schema_disabled,
			),
			$this->get_news_sitemap_data()
		) );
	}

	private function get_news_sitemap_data() {
		$settings = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SITEMAP );
		$data = new Smartcrawl_News_Sitemap_Data();
		return $data->settings_to_data( $settings );
	}

	private function register_dashboard_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_DASHBOARD ) ) {
			return;
		}

		$this->register_js( self::ONBOARDING_JS, 'js/wds-admin-onboard.js', array(
			self::ADMIN_JS,
		) );

		wp_localize_script( self::ONBOARDING_JS, '_wds_onboard', array(
			'templates' => array(
				'progress' => Smartcrawl_Simple_Renderer::load( 'dashboard/onboard-progress' ),
			),
			'strings'   => array(
				'All done' => esc_html__( 'All done, please hold on...', 'wds' ),
			),
			'nonce'     => wp_create_nonce( 'wds-onboard-nonce' ),
		) );

		$this->register_js( self::WELCOME_JS, 'js/wds-welcome-modal.js', array(
			self::ADMIN_JS,
		) );

		$nonce = wp_create_nonce( 'wds-nonce' );
		wp_localize_script( self::WELCOME_JS, '_wds_welcome', array(
			'nonce' => $nonce,
		) );

		$this->register_js( self::DASHBOARD_PAGE_JS, 'js/wds-admin-dashboard.js', array(
			'jquery',
			'underscore',
			self::ADMIN_JS,
			self::ONBOARDING_JS,
			self::WELCOME_JS,
			self::BLACK_FRIDAY_2021,
		) );

		wp_localize_script( self::DASHBOARD_PAGE_JS, '_wds_dashboard', array(
			'nonce'                    => $nonce,
			'health_page_url'          => Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH ),
			'lighthouse_widget_device' => Smartcrawl_Lighthouse_Options::dashboard_widget_device(),
			'lighthouse_nonce'         => wp_create_nonce( 'wds-lighthouse-nonce' ),
			'checkup_nonce'            => wp_create_nonce( 'wds-checkup-nonce' ),
		) );
	}

	private function register_checkup_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_HEALTH ) ) {
			return;
		}

		$this->register_email_recipients_js();

		$this->register_js( self::CHECKUP_PAGE_JS, 'js/wds-admin-checkup.js', array(
			'jquery',
			self::ADMIN_JS,
			self::EMAIL_RECIPIENTS_JS,
			self::HEALTH_JS,
			self::BLACK_FRIDAY_2021,
		) );

		wp_localize_script( self::CHECKUP_PAGE_JS, '_wds_checkup', array(
			'nonce'        => wp_create_nonce( 'wds-checkup-nonce' ),
			'broken_image' => SMARTCRAWL_PLUGIN_URL . 'assets/images/broken-image.png',
			'strings'      => array(
				'check-not-started' => esc_html__( 'Checkup could not be started.', 'wds' ),
				'ignored'           => esc_html__( 'check has been ignored.', 'wds' ),
				'restored'          => esc_html__( 'check has been restored.', 'wds' ),
			),
		) );
	}

	private function register_social_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_SOCIAL ) ) {
			return;
		}

		$this->register_js( self::SOCIAL_PAGE_JS, 'js/wds-admin-social.js', array(
			'jquery',
			self::ADMIN_JS,
			self::BLACK_FRIDAY_2021,
		) );
	}

	private function get_timezone() {
		$timezone = get_option( 'timezone_string' );
		if ( ! empty( $timezone ) ) {
			return $timezone;
		}

		$offset = (int) get_option( 'gmt_offset', 0 );
		return "UTC+{$offset}";
	}

	private function register_settings_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_SETTINGS ) ) {
			return;
		}

		$this->register_js( self::THIRD_PARTY_IMPORT_JS, 'js/wds-third-party-import.js', array(
			'jquery',
			'underscore',
			self::ADMIN_JS,
		) );

		wp_localize_script( self::THIRD_PARTY_IMPORT_JS, '_wds_import', array(
			'templates' => array(
				'import-options'        => Smartcrawl_Simple_Renderer::load( 'settings/underscore-import-options' ),
				'import-error'          => Smartcrawl_Simple_Renderer::load( 'settings/underscore-import-error' ),
				'import-progress'       => Smartcrawl_Simple_Renderer::load( 'settings/underscore-import-progress' ),
				'import-progress-reset' => Smartcrawl_Simple_Renderer::load( 'settings/underscore-progress-reset' ),
				'import-success'        => Smartcrawl_Simple_Renderer::load( 'settings/underscore-import-success' ),
			),
			'strings'   => array(
				'Yoast'          => esc_html__( 'Yoast', 'wds' ),
				'All In One SEO' => esc_html__( 'All In One SEO', 'wds' ),
			),
			'nonce'     => wp_create_nonce( 'wds-io-nonce' ),
		) );

		$setting_deps = $this->dynamic_dependencies( self::SETTINGS_PAGE_JS, array(
			'jquery',
			self::ADMIN_JS,
			self::THIRD_PARTY_IMPORT_JS,
			self::BLACK_FRIDAY_2021,
		) );
		$this->register_js( self::SETTINGS_PAGE_JS, 'js/build/wds-admin-settings.js', $setting_deps );

		$this->set_script_translations( self::SETTINGS_PAGE_JS );

		wp_localize_script( self::SETTINGS_PAGE_JS, '_wds_reset', array(
			'nonce'           => wp_create_nonce( 'wds-data-reset-nonce' ),
			'multisite_nonce' => wp_create_nonce( 'wds-multisite-data-reset-nonce' ),
		) );
	}

	private function register_metabox_scripts() {
		if ( $this->is_block_editor_active() ) {
			$link_format_button_deps = $this->dynamic_dependencies( self::METABOX_LINK_FORMAT_BUTTON, array(
				'wp-block-editor',
				'wp-i18n',
				'wp-element',
				'wp-components',
				'wp-rich-text',
				'wp-html-entities',
				'wp-element',
				'wp-components',
				'wp-url',
			) );

			$this->register_js( self::METABOX_LINK_FORMAT_BUTTON, 'js/build/wds-link-format-button.js', $link_format_button_deps );
		} else {
			$this->register_js( self::METABOX_LINK_REL_ATTRIBUTE_FIELD, 'js/wds-link-rel-attribute-field.js', array(
				'jquery',
				self::ADMIN_JS,
			) );
			wp_localize_script( self::METABOX_LINK_REL_ATTRIBUTE_FIELD, '_wds_link_rel_attr', array(
				'templates' => array(
					'field' => Smartcrawl_Simple_Renderer::load( 'metabox/underscore-link-rel-attribute' ),
				),
			) );
		}

		$options = Smartcrawl_Settings::get_options();
		if ( ! $this->is_block_editor_active() ) {
			$this->register_js( self::METABOX_COUNTER_JS, 'js/wds-metabox-counter.js', array() );
			wp_localize_script( self::METABOX_COUNTER_JS, 'l10nWdsCounters', array(
				'title_length'      => esc_html__( '{TOTAL_LEFT} characters left', 'wds' ),
				'title_longer'      => esc_html__( 'Over {MAX_COUNT} characters ({CURRENT_COUNT})', 'wds' ),
				'main_title_longer' => esc_html__( 'Over {MAX_COUNT} characters ({CURRENT_COUNT}) - make sure your SEO title is shorter', 'wds' ),

				'title_min'          => smartcrawl_title_min_length(),
				'title_max'          => smartcrawl_title_max_length(),
				'metadesc_min'       => smartcrawl_metadesc_min_length(),
				'metadesc_max'       => smartcrawl_metadesc_max_length(),
				'main_title_warning' => ! ( defined( 'SMARTCRAWL_MAIN_TITLE_LENGTH_WARNING_HIDE' ) && SMARTCRAWL_MAIN_TITLE_LENGTH_WARNING_HIDE ),
			) );
		}

		$title = '';
		$description = '';
		$post_type = $this->get_post_type();
		$post_id = $this->get_post_id_query_var();
		if ( $this->is_post_edit_screen() ) {
			$title = (string) smartcrawl_get_array_value( $options, 'title-' . $post_type );
			$description = (string) smartcrawl_get_array_value( $options, 'metadesc-' . $post_type );
		}
		$metabox_deps = $this->dynamic_dependencies( self::METABOX_COMPONENTS_JS, array(
			'jquery',
			'underscore',
			'wp-api',
			'wp-api-fetch',
			'wp-date',
			self::ADMIN_JS,
		) );
		$this->register_js( self::METABOX_COMPONENTS_JS, 'js/build/wds-metabox-components.js', $metabox_deps );
		wp_localize_script( self::METABOX_COMPONENTS_JS, '_wds_metabox', array(
			'nonce'               => wp_create_nonce( 'wds-metabox-nonce' ),
			'meta_title'          => $title,
			'meta_desc'           => $description,
			'home_url'            => home_url( '/' ),
			'post_url'            => $post_id ? get_post_permalink( $post_id ) : '',
			'title_min_length'    => smartcrawl_title_min_length(),
			'title_max_length'    => smartcrawl_title_max_length(),
			'metadesc_min_length' => smartcrawl_metadesc_min_length(),
			'metadesc_max_length' => smartcrawl_metadesc_max_length(),
			'post_type'           => $this->get_post_type(),
			'taxonomies'          => $this->get_taxonomies(),
			'gutenberg_active'    => $this->is_block_editor_active(),
			'onpage_active'       => Smartcrawl_Settings::get_setting( 'onpage' ) && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ),
			'analysis_active'     => Smartcrawl_Settings::get_setting( 'analysis-readability' ) || Smartcrawl_Settings::get_setting( 'analysis-seo' ),
			'enforce_limits'      => ( isset( $options['metabox-lax_enforcement'] ) ? ! ! $options['metabox-lax_enforcement'] : false ),
			'templates'           => array(
				'preview' => Smartcrawl_Simple_Renderer::load( 'metabox/underscore-google-preview' ),
			),
		) );

		$metabox_dependencies = array(
			'underscore',
			self::OPENGRAPH_JS,
			self::METABOX_COMPONENTS_JS,
			self::MACRO_REPLACEMENT,
		);
		if ( $this->is_block_editor_active() ) {
			if ( smartcrawl_is_switch_active( 'SMARTCRAWL_SHOW_GUTENBERG_LINK_FORMAT_BUTTON' ) ) {
				$metabox_dependencies[] = self::METABOX_LINK_FORMAT_BUTTON;
			}
		} else {
			$metabox_dependencies[] = self::METABOX_LINK_REL_ATTRIBUTE_FIELD;
			$metabox_dependencies[] = self::METABOX_COUNTER_JS;
			$metabox_dependencies[] = 'autosave';
		}
		$this->register_js( self::METABOX_JS, 'js/wds-metabox.js', $metabox_dependencies );
		wp_localize_script( self::METABOX_JS, 'l10nWdsMetabox', array(
			'content_analysis_working' => esc_html__( 'Analyzing content, please wait a few moments', 'wds' ),
		) );
	}

	private function get_replacements() {
		$general_macros = Smartcrawl_Onpage_Settings::get_general_macros();
		$map = array();
		foreach ( $general_macros as $macro => $macro_desc ) {
			$map[ str_replace( '%', '', $macro ) ] = Smartcrawl_Replacement_Helper::replace( $macro );
		}
		return $map;
	}

	private function is_block_editor_active() {
		$screen = get_current_screen();
		if ( $screen && method_exists( $screen, 'is_block_editor' ) ) {
			return $screen->is_block_editor();
		}

		if ( function_exists( 'is_gutenberg_page' ) ) {
			return is_gutenberg_page();
		}

		return false;
	}

	private function register_post_list_scripts() {
		$this->register_js( self::WP_POST_LIST_TABLE_JS, 'js/wds-admin-post-list-table.js', array(
				'jquery',
				'underscore',
				self::ADMIN_JS,
				self::QTIP2_JS,
			)
		);

		wp_localize_script( self::WP_POST_LIST_TABLE_JS, '_wds_post_list', array(
			'strings'             => array(
				'loading' => __( 'Loading, please hold on...', 'wds' ),
			),
			'nonce'               => wp_create_nonce( 'wds-metabox-nonce' ),
			'analyse_posts_delay' => (int) apply_filters( 'wds-list-table-delay', 500 ),
		) );
	}

	private function register_term_form_scripts() {
		$this->register_js( self::TERM_FORM_JS, 'js/wds-term-form.js', array(
				'jquery',
				self::ADMIN_JS,
				self::OPENGRAPH_JS,
			)
		);
		wp_localize_script( self::TERM_FORM_JS, '_wds_term_form', array(
			'nonce' => wp_create_nonce( 'wds-metabox-nonce' ),
		) );
	}

	private function register_general_styles() {
		$this->register_css( self::QTIP2_CSS, 'css/external/jquery.qtip.min.css' );

		$this->register_css( self::APP_CSS, 'css/app.css' );
	}

	private function register_wp_dashboard_styles() {
		$this->register_css( self::WP_DASHBOARD_CSS, 'css/wp-dashboard.css', array() );
	}

	private function register_post_list_styles() {
		$this->register_css(
			self::WP_POST_LIST_TABLE_CSS, 'css/wp-post-list-table.css', array( self::QTIP2_CSS )
		);
	}

	private function register_network_settings_page_scripts() {
		$this->register_js( self::NETWORK_SETTINGS_PAGE_JS, 'js/wds-admin-network-settings.js', array(
			'jquery',
			self::ADMIN_JS,
			self::BLACK_FRIDAY_2021,
		) );
	}

	private function is_post_edit_screen() {
		global $pagenow;

		return $pagenow === 'post-new.php' || $pagenow === 'post.php';
	}

	private function get_post_id_query_var() {
		return smartcrawl_get_array_value( $_GET, 'post' );
	}

	private function get_post_type() {
		$post = get_post();

		return ( $post instanceof WP_Post ) ? $post->post_type : 'post';
	}

	private function get_taxonomies() {
		$post_type = $this->get_post_type();

		return get_object_taxonomies( $post_type );
	}

	private function register_schema_settings_page_scripts() {
		if ( ! $this->is_page( Smartcrawl_Settings::TAB_SCHEMA ) ) {
			return;
		}

		$this->register_js( self::SCHEMA_JS, 'js/wds-admin-schema.js', array(
			'jquery',
			self::ADMIN_JS,
			self::BLACK_FRIDAY_2021,
		) );

		wp_localize_script( self::SCHEMA_JS, '_wds_schema', array(
			'nonce'               => wp_create_nonce( 'wds-schema-nonce' ),
			'youtube_key_valid'   => esc_html__( 'Key valid!', 'wds' ),
			'youtube_key_invalid' => esc_html__( 'Key invalid', 'wds' ),
		) );

		$post_types = array_map( function ( $post_type ) {
			return get_post_type_object( $post_type )->labels->singular_name;
		}, smartcrawl_frontend_post_types() );
		$post_formats = $this->get_post_formats();
		$page_templates = wp_get_theme()->get_page_templates();
		$user_roles = array_map( function ( $role ) {
			return smartcrawl_get_array_value( $role, 'name' );
		}, wp_roles()->roles );
		$taxonomies = array_map( function ( $taxonomy ) {
			return $taxonomy->label;
		}, smartcrawl_frontend_taxonomies() );

		$schema_types_deps = $this->dynamic_dependencies( self::SCHEMA_TYPES_JS, array(
			'jquery-ui-datepicker',
		) );
		$this->register_js( self::SCHEMA_TYPES_JS, 'js/build/wds-schema-types.js', $schema_types_deps );

		$this->set_script_translations( self::SCHEMA_TYPES_JS );

		wp_localize_script( self::SCHEMA_TYPES_JS, '_wds_schema_types', array(
			'plugin_version'       => SMARTCRAWL_VERSION,
			'post_types'           => $post_types,
			'post_formats'         => $post_formats,
			'page_templates'       => $page_templates,
			'taxonomies'           => $taxonomies,
			'post_type_taxonomies' => $this->get_post_type_taxonomies(),
			'user_roles'           => $user_roles,
			'ajax_url'             => admin_url( 'admin-ajax.php' ),
			'types'                => Smartcrawl_Controller_Schema_Types::get()->get_schema_types(),
			'woocommerce'          => class_exists( 'woocommerce' ),
			'settings_updated'     => smartcrawl_get_array_value( $_GET, 'settings-updated' ),
		) );
	}

	private function get_post_type_taxonomies() {
		$post_types = smartcrawl_frontend_post_types();
		$available_taxonomies = smartcrawl_frontend_taxonomies();
		$post_type_taxonomies = array();

		foreach ( $post_types as $post_type ) {
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$taxonomies = empty( $taxonomies ) ? array() : $taxonomies;
			$taxonomies = array_filter( $taxonomies, function ( $taxonomy ) use ( $available_taxonomies ) {
				return array_key_exists( $taxonomy->name, $available_taxonomies );
			} );
			$post_type_label = get_post_type_object( $post_type )->labels->singular_name;
			if ( empty( $taxonomies ) ) {
				$post_type_taxonomies[ $post_type ] = $post_type_label;
				continue;
			}

			$post_type_taxonomies[ $post_type ] = array(
				'label'   => $post_type_label,
				'options' => array_merge(
					array(
						$post_type => $post_type_label,
					),
					array_map( function ( $taxonomy ) {
						return isset( $taxonomy->labels->singular_name )
							? $taxonomy->labels->singular_name
							: $taxonomy->label;
					}, $taxonomies )
				),
			);
		}
		return $post_type_taxonomies;
	}

	private function get_post_formats() {
		$post_formats = smartcrawl_get_array_value( get_theme_support( 'post-formats' ), 0 );
		$post_formats = empty( $post_formats ) ? array() : $post_formats;

		return array_combine( $post_formats, $post_formats );
	}

	private function register_email_recipients_js() {
		$this->register_js( self::EMAIL_RECIPIENTS_JS, 'js/wds-admin-email-recipients.js', array(
			'jquery',
			'underscore',
			self::ADMIN_JS,
		) );

		wp_localize_script( self::EMAIL_RECIPIENTS_JS, '_wds_email_recipients', array(
			'templates' => array(
				'recipient' => Smartcrawl_Simple_Renderer::load( 'underscore-email-recipient' ),
			),
			'strings'   => array(
				'recipient_added' => esc_html__( ' has been added as a recipient. Please save your changes to set this live.', 'wds' ),
			),
		) );
	}

	private function register_health_settings_page_scripts() {
		$this->register_email_recipients_js();

		$this->register_js( self::HEALTH_JS, 'js/wds-admin-health.js', array(
			self::ADMIN_JS,
			self::BLACK_FRIDAY_2021,
		) );

		wp_localize_script( self::HEALTH_JS, '_wds_health', array(
			'nonce' => wp_create_nonce( 'wds-health-nonce' ),
		) );

		$this->register_js( self::LIGHTHOUSE_JS, 'js/wds-admin-lighthouse.js', array(
			self::ADMIN_JS,
			self::EMAIL_RECIPIENTS_JS,
			self::HEALTH_JS,
		) );

		$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );
		$report = $lighthouse->get_last_report();

		wp_localize_script( self::LIGHTHOUSE_JS, '_wds_lighthouse', array(
			'start_time' => $lighthouse->get_start_time(),
			'nonce'      => wp_create_nonce( 'wds-lighthouse-nonce' ),
			'strings'    => array(
				'analyzing'                 => esc_html__( 'Analyzing data and preparing report...', 'wds' ),
				'running'                   => esc_html__( 'Running SEO test...', 'wds' ),
				'refreshing'                => esc_html__( 'Refreshing data. Please wait...', 'wds' ),
				'audit_copied'              => esc_html__( 'The audit has been copied successfully.', 'wds' ),
				'audit_copy_failed'         => esc_html__( 'Audit could not be copied to clipboard.', 'wds' ),
				'cooldown_message'          => esc_html__( 'SmartCrawl is just catching her breath - you can run another test in %s minutes.', 'wds' ),
				'cooldown_message_singular' => esc_html__( 'SmartCrawl is just catching her breath - you can run another test in %s minute.', 'wds' ),
			),
		) );
	}

	function set_script_translations( $handle ) {
		if ( ! function_exists( 'wp_set_script_translations' ) ) {
			return;
		}

		wp_set_script_translations( $handle, 'wds' );
	}

	private function dynamic_dependencies( $file_name, $extra_deps = array() ) {
		$assets_path = SMARTCRAWL_PLUGIN_DIR . 'assets/js/build/assets.php';
		if ( ! file_exists( $assets_path ) ) {
			return $extra_deps;
		}

		$assets = require( $assets_path );
		$key = "{$file_name}.js";
		$asset_data = smartcrawl_get_array_value( $assets, $key );
		if ( ! $asset_data ) {
			return $extra_deps;
		}

		$dependencies = smartcrawl_get_array_value( $asset_data, 'dependencies' );
		$dependencies = empty( $dependencies ) ? array() : $dependencies;

		return array_merge(
			$extra_deps,
			$dependencies
		);
	}
}
