<?php

class Smartcrawl_Settings_Settings extends Smartcrawl_Settings_Admin {


	private static $_instance;

	public static function get_instance() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Validate submitted options
	 *
	 * @param array $input Raw input
	 *
	 * @return array Validated input
	 */
	public function validate( $input ) {
		$result = self::get_specific_options( $this->option_name );

		$disable_schema_key = 'disable-schema';
		if ( isset( $input[ $disable_schema_key ] ) ) {
			$social_options = self::get_component_options( self::COMP_SOCIAL );
			$social_options[ $disable_schema_key ] = ! empty( $input[ $disable_schema_key ] );
			self::update_component_options( self::COMP_SOCIAL, $social_options );
			unset( $social_options[ $disable_schema_key ] );
		}

		// Accessibility
		$result['high-contrast'] = ! empty( $input['high-contrast'] );

		// Data settings
		$result['keep_settings_on_uninstall'] = ! empty( $input['keep_settings_on_uninstall'] );
		$result['keep_data_on_uninstall'] = ! empty( $input['keep_data_on_uninstall'] );

		if ( ! empty( $input['wds_settings-setup'] ) ) {
			$result['wds_settings-setup'] = true;
		}

		$booleans = array_keys( Smartcrawl_Settings::get_known_components() );
		foreach ( $booleans as $bool ) {
			$result[ $bool ] = ! empty( $input[ $bool ] );
		}

		// Robots.txt file
		if ( isset( $input['robots-txt'] ) ) {
			$result['robots-txt'] = ! empty( $input['robots-txt'] );
		}
		// Analysis/readability
		$result['analysis-seo'] = ! empty( $input['analysis-seo'] );
		$result['analysis-readability'] = ! empty( $input['analysis-readability'] );
		$result['analysis_strategy'] = ! empty( $input['analysis_strategy'] )
			? sanitize_text_field( $input['analysis_strategy'] )
			: Smartcrawl_Controller_Analysis_Content::STRATEGY_STRICT;
		$result['extras-admin_bar'] = ! empty( $input['extras-admin_bar'] );

		if ( ! empty( $input['redirections-code'] ) && is_numeric( $input['redirections-code'] ) ) {
			$code = (int) $input['redirections-code'];
			if ( in_array( $code, array( 301, 302 ), true ) ) {
				$result['redirections-code'] = $code;
			}
		}
		if ( ! empty( $input['metabox-lax_enforcement'] ) ) {
			$result['metabox-lax_enforcement'] = true;
		} else {
			$result['metabox-lax_enforcement'] = false;
		}
		if ( ! empty( $input['general-suppress-generator'] ) ) {
			$result['general-suppress-generator'] = true;
		} else {
			$result['general-suppress-generator'] = false;
		}
		if ( ! empty( $input['general-suppress-redundant_canonical'] ) ) {
			$result['general-suppress-redundant_canonical'] = true;
		} else {
			$result['general-suppress-redundant_canonical'] = false;
		}

		$strings = array(
			'access-id',
			'secret-key',
		);
		foreach ( $strings as $str ) {
			if ( isset( $input[ $str ] ) ) {
				$result[ $str ] = sanitize_text_field( $input[ $str ] );
			}
		}

		// Roles
		foreach ( $this->_get_permission_contexts() as $ctx ) {
			if ( empty( $input[ $ctx ] ) ) {
				continue;
			}
			$roles = array_keys( $this->_get_filtered_roles( "wds-{$ctx}" ) );
			$check_context = is_array( $input[ $ctx ] )
				? $input[ $ctx ]
				: array( $input[ $ctx ] );
			$result[ $ctx ] = array();
			foreach ( $check_context as $ctx_item ) {
				if ( in_array( $ctx_item, $roles, true ) ) {
					$result[ $ctx ][] = $ctx_item;
				}
			}
		}

		if ( isset( $input['verification-google-meta'] ) ) {
			$this->_validate_and_save_extra_options( $input );
		}

		return $result;
	}

	/**
	 * Get a list of permission contexts used for roles filtering
	 *
	 * @return array
	 */
	protected function _get_permission_contexts() {
		return array(
			'seo_metabox_permission_level',
			'seo_metabox_301_permission_level',
			'urlmetrics_metabox_permission_level',
		);
	}

	/**
	 * Get (optionally filtered) default roles
	 *
	 * @param string $context_filter Optional filter to pass the roles through first
	 *
	 * @return array List of roles
	 */
	protected function _get_filtered_roles( $context_filter = false ) {
		$default_roles = array(
			'manage_network'       => __( 'Super Admin' ),
			'list_users'           => sprintf( __( '%s (and up)', 'wds' ), __( 'Site Admin' ) ),
			'moderate_comments'    => sprintf( __( '%s (and up)', 'wds' ), __( 'Editor' ) ),
			'edit_published_posts' => sprintf( __( '%s (and up)', 'wds' ), __( 'Author' ) ),
			'edit_posts'           => sprintf( __( '%s (and up)', 'wds' ), __( 'Contributor' ) ),
		);
		if ( ! is_multisite() ) {
			unset( $default_roles['manage_network'] );
		}

		return ! empty( $context_filter )
			? (array) apply_filters( $context_filter, $default_roles )
			: $default_roles;
	}

	/**
	 * Processes extra options passed on from the main form
	 *
	 * This is a side-effect method - the extra options don't update
	 * the tab option key, but go to an extternal location
	 *
	 * @param array $input Raw form input
	 */
	private function _validate_and_save_extra_options( $input ) {
		// Sitemaps validation/save
		$sitemaps = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SITEMAP );
		$sitemaps_updated = false;
		if ( isset( $input['verification-pages'] ) ) {
			$pages = $input['verification-pages'];
			if ( in_array( $pages, array( '', 'home' ), true ) ) {
				$sitemaps['verification-pages'] = $pages;
			}
			$sitemaps_updated = true;
		}

		// Meta tags
		if ( isset( $input['verification-google-meta'] ) ) {
			$sitemaps['verification-google-meta'] = smartcrawl_is_valid_meta_tag( $input['verification-google-meta'] ) ? $input['verification-google-meta'] : '';
			$sitemaps_updated = true;
		}
		if ( isset( $input['verification-bing-meta'] ) ) {
			$sitemaps['verification-bing-meta'] = smartcrawl_is_valid_meta_tag( $input['verification-bing-meta'] ) ? $input['verification-bing-meta'] : '';
			$sitemaps_updated = true;
		}

		$custom_values_key = 'additional-metas';
		if ( ! empty( $input[ $custom_values_key ] ) && is_array( $input[ $custom_values_key ] ) ) {
			$custom_values = $input[ $custom_values_key ];
			$sanitized_custom_values = array();
			foreach ( $custom_values as $index => $custom_value ) {
				if ( trim( $custom_value ) ) {
					$sanitized = wp_kses( $custom_value, array(
						'meta' => array(
							'charset'    => array(),
							'content'    => array(),
							'http-equiv' => array(),
							'name'       => array(),
							'scheme'     => array(),
						),
					) );
					if ( preg_match( '/<meta\b/', trim( $sanitized ) ) ) {
						$sanitized_custom_values[] = $sanitized;
					}
				}
			}
			$sitemaps[ $custom_values_key ] = $sanitized_custom_values;
			$sitemaps_updated = true;
		}

		if ( $sitemaps_updated ) {
			Smartcrawl_Settings::update_component_options( Smartcrawl_Settings::COMP_SITEMAP, $sitemaps );
		}
	}

	public function init() {
		$this->option_name = 'wds_settings_options';
		$this->name = 'settings';
		$this->slug = Smartcrawl_Settings::TAB_SETTINGS;
		$this->action_url = admin_url( 'options.php' );
		$this->page_title = __( 'SmartCrawl Wizard: Settings', 'wds' );

		add_action( 'admin_init', array( $this, 'activate_component' ) );
		add_action( 'admin_init', array( $this, 'save_moz_api_credentials' ) );
		add_action( 'admin_footer', array( $this, 'add_native_dismissible_notice_javascript' ) );
		add_action( 'network_admin_notices', array( $this, 'wp_org_rating_request' ) );
		add_action( 'admin_notices', array( $this, 'wp_org_rating_request' ) );
		add_action( 'network_admin_notices', array( $this, 'import_notice' ) );

		if ( $this->display_single_site_import_notice() ) {
			add_action( 'admin_notices', array( $this, 'import_notice' ) );
		}

		parent::init();
	}

	public function get_title() {
		return __( 'Settings', 'wds' );
	}

	private function display_single_site_import_notice() {
		// Always display on non-multisite
		return ! is_multisite()
		       // or when the current site is the main network site
		       || is_main_site();
	}

	/**
	 * Updates the options to activate a component.
	 */
	public function activate_component() {
		$data = $this->get_request_data();
		if ( isset( $data['wds-activate-component'] ) ) {
			$component = sanitize_key( $data['wds-activate-component'] );
			$options = self::get_specific_options( $this->option_name );
			$options[ $component ] = 1;

			self::update_specific_options( $this->option_name, $options );

			do_action( "wds-component-activated-$component" );

			wp_redirect( esc_url_raw( add_query_arg( array() ) ) );
		}
	}

	public function save_moz_api_credentials() {
		$data = $this->get_request_data();
		if ( isset( $data['wds-moz-access-id'] ) || isset( $data['wds-moz-secret-key'] ) ) {
			$options = self::get_specific_options( $this->option_name );
			$options['access-id'] = sanitize_text_field( $data['wds-moz-access-id'] );
			$options['secret-key'] = sanitize_text_field( $data['wds-moz-secret-key'] );

			self::update_specific_options( $this->option_name, $options );

			wp_redirect( esc_url_raw( add_query_arg( array() ) ) );
		}
	}

	/**
	 * Add admin settings page
	 */
	public function options_page() {
		parent::options_page();

		$arguments['default_roles'] = $this->_get_filtered_roles();

		$arguments['plugin_modules'] = $this->get_plugin_modules();

		foreach ( $this->_get_permission_contexts() as $ctx ) {
			$arguments[ $ctx ] = $this->_get_filtered_roles( "wds-{$ctx}" );
		}

		$sitemap_settings = Smartcrawl_Sitemap_Settings::get_instance();
		$arguments['sitemap_option_name'] = $sitemap_settings->option_name;

		$arguments['verification_pages'] = array(
			''     => __( 'All pages', 'wds' ),
			'home' => __( 'Home page', 'wds' ),
		);

		$arguments['active_tab'] = $this->_get_active_tab( 'tab_general_settings' );

		wp_enqueue_script( Smartcrawl_Controller_Assets::SETTINGS_PAGE_JS );
		$this->_render_page( 'settings/settings', $arguments );
	}

	private function get_plugin_modules() {
		$disable_schema = (bool) smartcrawl_get_array_value(
			self::get_component_options( Smartcrawl_Settings::COMP_SOCIAL ),
			'disable-schema'
		);

		// All available modules:
		$all_plugin_modules = array(
			'onpage'    => $this->plugin_module_args( __( 'Title & Meta', 'wds' ), 'onpage' ),
			'schema'    => $this->plugin_module_args(
				__( 'Schema', 'wds' ), 'disable-schema', true, $disable_schema
			),
			'social'    => $this->plugin_module_args( __( 'Social', 'wds' ), 'social' ),
			'sitemap'   => $this->plugin_module_args( __( 'Sitemaps', 'wds' ), 'sitemap' ),
			'autolinks' => $this->plugin_module_args( __( 'Advanced Tools', 'wds' ), 'autolinks' ),
		);

		if ( ! is_multisite() || is_network_admin() ) {
			return $all_plugin_modules;
		}

		// The modules that are to be shown in the sub-site settings:
		$sub_site_modules = array();
		$active_blog_tabs = self::get_blog_tabs();
		foreach ( $all_plugin_modules as $plugin_module => $label ) {
			if (
				array_key_exists( 'wds_' . $plugin_module, $active_blog_tabs )
				// Only display the checkup option on the main site or network admin (handled above)
				&& ( 'checkup' !== $plugin_module || is_main_site() )
			) {
				$sub_site_modules[ $plugin_module ] = $label;
			}
		}

		return $sub_site_modules;
	}

	private function plugin_module_args( $label, $field_name, $inverted = false, $checked = null ) {
		if ( is_null( $checked ) ) {
			$checked = smartcrawl_get_array_value( self::get_options(), $field_name );
		}

		return array(
			'field_name' => "{$this->option_name}[$field_name]",
			'item_label' => $label,
			'inverted'   => $inverted,
			'checked'    => $checked,
		);
	}

	/**
	 * Get allowed blog tabs
	 *
	 * @return array
	 */
	public static function get_blog_tabs() {
		$blog_tabs = get_site_option( 'wds_blog_tabs' );

		return is_array( $blog_tabs )
			? $blog_tabs
			: array();
	}

	/**
	 * Default settings
	 */
	public function defaults() {
		$this->options = self::get_specific_options( $this->option_name );

		if ( empty( $this->options ) ) {
			if ( empty( $this->options['autolinks'] ) ) {
				$this->options['autolinks'] = 0;
			}

			if ( empty( $this->options['seomoz'] ) ) {
				$this->options['seomoz'] = 0;
			}

			if ( empty( $this->options['sitemap'] ) ) {
				$this->options['sitemap'] = 1;
			}

			if ( empty( $this->options['onpage'] ) ) {
				$this->options['onpage'] = 1;
			}

			if ( empty( $this->options['social'] ) ) {
				$this->options['social'] = 1;
			}
		}

		if ( empty( $this->options['seo_metabox_permission_level'] ) ) {
			$this->options['seo_metabox_permission_level'] = ( is_multisite() ? 'manage_network' : 'list_users' );
		}

		if ( empty( $this->options['urlmetrics_metabox_permission_level'] ) ) {
			$this->options['urlmetrics_metabox_permission_level'] = ( is_multisite() ? 'manage_network' : 'list_users' );
		}

		if ( empty( $this->options['seo_metabox_301_permission_level'] ) ) {
			$this->options['seo_metabox_301_permission_level'] = ( is_multisite() ? 'manage_network' : 'list_users' );
		}

		if ( empty( $this->options['access-id'] ) ) {
			$this->options['access-id'] = '';
		}

		if ( empty( $this->options['secret-key'] ) ) {
			$this->options['secret-key'] = '';
		}

		if ( ! isset( $this->options['analysis-seo'] ) ) {
			$this->options['analysis-seo'] = true;
		}
		if ( ! isset( $this->options['analysis-readability'] ) ) {
			$this->options['analysis-readability'] = true;
		}
		if ( ! isset( $this->options['extras-admin_bar'] ) ) {
			$this->options['extras-admin_bar'] = true;
		}

		apply_filters( 'wds_defaults', $this->options );

		self::update_specific_options( $this->option_name, $this->options );

		$blog_tabs = get_site_option( 'wds_blog_tabs', false );
		if ( $blog_tabs === false ) {
			smartcrawl_activate_all_blog_tabs();
		}
	}

	public function import_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$this->show_import_notice(
			new Smartcrawl_Yoast_Importer(),
			'yoast-seo',
			esc_html__( 'Yoast SEO', 'wds' )
		);

		$this->show_import_notice(
			new Smartcrawl_AIOSEOP_Importer(),
			'all-in-one-seo',
			esc_html__( 'All In One SEO', 'wds' )
		);
	}

	/**
	 * @param $importer Smartcrawl_Importer
	 * @param $plugin_key
	 * @param $plugin_name string
	 * @param $plugin
	 */
	private function show_import_notice( $importer, $plugin_key, $plugin_name ) {
		if ( ! $importer->data_exists() ) {
			return;
		}

		$auto_import_url = sprintf(
			'<a href="%s">%s</a>',
			Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SETTINGS ) . '&tab=tab_import_export',
			esc_html__( 'auto-import', 'wds' )
		);
		$message = sprintf(
			esc_html__( "We've detected you have %1\$s settings. Do you want to %2\$s your configuration into SmartCrawl?" ),
			$plugin_name,
			$auto_import_url
		);
		$message_key = sprintf( '%s-import', $plugin_key );
		$dismissed_messages = get_user_meta( get_current_user_id(), 'wds_dismissed_messages', true );
		$is_message_dismissed = smartcrawl_get_array_value( $dismissed_messages, $message_key ) === true;

		if ( $is_message_dismissed ) {
			return;
		}

		?>
		<div class="notice-warning notice is-dismissible wds-native-dismissible-notice"
		     data-message-key="<?php echo esc_attr( $message_key ); ?>">
			<p><?php echo wp_kses_post( $message ); ?></p>
		</div>
		<?php
	}

	public function add_native_dismissible_notice_javascript() {
		$this->_render( 'native-dismissible-notice-javascript' );
	}

	public function wp_org_rating_request() {
		$service = $this->get_service();
		if ( $service->is_member() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( is_multisite() && ! is_network_admin() ) {
			return;
		}

		$days = 7;
		$now = current_time( 'timestamp' );
		$free_install_date = get_site_option( 'wds-free-install-date' );
		if ( ( $now - (int) $free_install_date ) < ( $days * 24 * 60 * 60 ) ) {
			return;
		}

		$key = 'wp-org-rating-request';
		$dismissed_messages = get_user_meta( get_current_user_id(), 'wds_dismissed_messages', true );
		$is_message_dismissed = smartcrawl_get_array_value( $dismissed_messages, $key ) === true;
		if ( $is_message_dismissed ) {
			return;
		}

		?>
		<div class="notice-info notice is-dismissible wds-native-dismissible-notice"
		     data-message-key="<?php echo esc_attr( $key ); ?>">
			<p><?php esc_html_e( "Excellent! You've been using SmartCrawl for over a week. Hope you are enjoying it so far. We have spent countless hours developing this free plugin for you, and we would really appreciate it if you could drop us a rating on wp.org to help us spread the word and boost our motivation.", 'wds' ); ?></p>
			<a target="_blank" href="https://wordpress.org/plugins/smartcrawl-seo#reviews"
			   class="button button-primary">
				<?php esc_html_e( 'Rate SmartCrawl', 'wds' ); ?>
			</a>
			<a href="#" class="wds-native-dismiss">No Thanks</a>
			<p></p>
		</div>
		<?php
	}

	/**
	 * @return Smartcrawl_Checkup_Service
	 */
	private static function get_service() {
		$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_CHECKUP );

		return $service;
	}

	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-settings-nonce' ) ? $_POST : array();
	}
}

