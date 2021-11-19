<?php

class Smartcrawl_Settings_Dashboard extends Smartcrawl_Settings_Admin {

	const CRAWL_TIMEOUT_CODE = 'crawl_timeout';
	const BOX_SOCIAL = 'wds-social-dashboard-box';
	const BOX_ADVANCED_TOOLS = 'wds-advanced-tools-dashboard-box';
	const BOX_ONPAGE = 'wds-title-and-meta-dashboard-box';
	const BOX_CONTENT_ANALYSIS = 'wds-content-analysis-box';
	const BOX_SITEMAP = 'wds-sitemap-box';
	const BOX_SEO_CHECKUP = 'wds-seo-checkup';
	const BOX_LIGHTHOUSE = 'wds-lighthouse';
	const BOX_TOP_STATS = 'wds-dashboard-stats';
	const BOX_REPORTS = 'wds-reports-box';
	const BOX_UPGRADE = 'wds-upgrade';
	const BOX_SCHEMA = 'wds-schema-box';
	private static $_instance;
	protected $_seo_service;
	protected $_uptime_service;

	public static function get_instance() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function validate( $input ) {
		return $input;
	}

	public function init() {
		$this->slug = Smartcrawl_Settings::TAB_DASHBOARD;
		$this->page_title = __( 'SmartCrawl Wizard: Dashboard', 'wds' );

		add_action( 'wp_ajax_wds-activate-component', array( $this, 'json_activate_component' ) );
		add_action( 'wp_ajax_wds-reload-box', array( $this, 'json_reload_component' ) );

		parent::init();
	}

	public function json_activate_component() {
		$result = array( 'success' => false );
		$data = $this->get_request_data();

		$option_id = sanitize_key( smartcrawl_get_array_value( $data, 'option' ) );
		$flag = sanitize_key( smartcrawl_get_array_value( $data, 'flag' ) );
		$value = (bool) smartcrawl_get_array_value( $data, 'value' );

		if ( is_null( $option_id ) || is_null( $flag ) ) {
			wp_send_json( $result );

			return;
		}

		$options = self::get_specific_options( $option_id );
		$options[ $flag ] = $value;
		self::update_specific_options( $option_id, $options );

		$result['success'] = true;
		wp_send_json( $result );
	}

	public function json_reload_component() {
		$result = array( 'success' => false );
		$data = $this->get_request_data();

		$box_id = smartcrawl_get_array_value( $data, 'box_id' );

		if ( is_null( $box_id ) ) {
			wp_send_json( $result );

			return;
		}

		if ( ! is_array( $box_id ) ) {
			$box_id = array( $box_id );
		}
		$box_id = array_map( 'sanitize_key', $box_id );

		$box_id = array_unique( $box_id );

		foreach ( $box_id as $id ) {
			$result[ $id ] = $this->load_box_markup( $id );
		}

		$result['success'] = true;
		wp_send_json( $result );
	}

	private function load_box_markup( $box_id ) {
		switch ( $box_id ) {
			case self::BOX_SOCIAL:
				return $this->_load( 'dashboard/dashboard-widget-social' );

			case self::BOX_ADVANCED_TOOLS:
				return $this->_load( 'dashboard/dashboard-widget-advanced-tools' );

			case self::BOX_ONPAGE:
				return $this->_load( 'dashboard/dashboard-widget-onpage' );

			case self::BOX_CONTENT_ANALYSIS:
				return $this->_load( 'dashboard/dashboard-widget-content-analysis' );

			case self::BOX_SITEMAP:
				return $this->_load( 'dashboard/dashboard-widget-sitemap' );

			case self::BOX_SEO_CHECKUP:
				return Smartcrawl_Checkup_Renderer::load( 'dashboard/dashboard-widget-seo-checkup' );

			case self::BOX_LIGHTHOUSE:
				return Smartcrawl_Lighthouse_Dashboard_Renderer::load( 'dashboard/dashboard-widget-lighthouse' );

			case self::BOX_TOP_STATS:
				if ( Smartcrawl_Health_Settings::is_test_mode_checkup() ) {
					return Smartcrawl_Checkup_Renderer::load( 'dashboard/dashboard-top' );
				} else {
					return Smartcrawl_Lighthouse_Dashboard_Renderer::load( 'dashboard/dashboard-top-lighthouse' );
				}

			case self::BOX_SCHEMA:
				return $this->_load( 'dashboard/dashboard-widget-schema' );
		};

		return null;
	}

	/**
	 * Add admin settings page
	 */
	public function options_page() {
		wp_enqueue_script( Smartcrawl_Controller_Assets::DASHBOARD_PAGE_JS );

		$uptime = $this->_get_uptime_service();

		$this->_render_page( 'dashboard/dashboard', array() );
	}

	protected function _get_uptime_service() {
		if ( ! empty( $this->_uptime_service ) ) {
			return $this->_uptime_service;
		}

		$this->_uptime_service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_UPTIME );

		return $this->_uptime_service;
	}

	/**
	 * Add sub page to the Settings Menu
	 */
	public function add_page() {
		if ( ! $this->_is_current_tab_allowed() ) {
			return false;
		}

		$this->smartcrawl_page_hook = add_menu_page(
			$this->page_title,
			$this->get_title(),
			$this->capability,
			$this->slug,
			array( &$this, 'options_page' ),
			$this->get_icon()
		);

		$this->smartcrawl_page_hook = add_submenu_page(
			$this->slug,
			$this->page_title,
			$this->get_sub_title(),
			$this->capability,
			$this->slug,
			array( &$this, 'options_page' )
		);

		add_action( "admin_print_styles-{$this->smartcrawl_page_hook}", array( &$this, 'admin_styles' ) );
	}

	public function get_title() {
		return smartcrawl_is_build_type_full()
			? __( 'SmartCrawl Pro', 'wds' )
			: __( 'SmartCrawl', 'wds' );
	}

	public function get_sub_title() {
		return __( 'Dashboard', 'wds' );
	}

	/**
	 * Always allow dashboard tab if there's more than one tab allowed
	 *
	 * Overrides Smartcrawl_Settings::_is_current_tab_allowed
	 *
	 * @return bool
	 */
	protected function _is_current_tab_allowed() {
		if ( parent::_is_current_tab_allowed() ) {
			return true;
		}
		// Else we always add dashboard if there are other pages
		$all_tabs = Smartcrawl_Settings_Settings::get_blog_tabs();

		return ! empty( $all_tabs );
	}

	/**
	 * Default settings
	 */
	public function defaults() {
		$this->options = Smartcrawl_Settings::get_options();
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		$svg = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><svg width="18px" height="18px" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Artboard" fill-rule="nonzero" fill="#FFFFFF"><path d="M0.558,12.1008055 L17.445,12.1008055 C16.1402452,15.6456454 12.7704642,18 9.0015,18 C5.23253577,18 1.86275481,15.6456454 0.558,12.1008055 Z M17.442,5.89919449 L0.555,5.89919449 C1.85975481,2.35435463 5.22953577,4.81675263e-16 8.9985,7.11236625e-16 C12.7674642,9.40797988e-16 16.1372452,2.35435463 17.442,5.89919449 Z M0.042,8 L17.958,8 C17.985,8.32740214 18,8.66192171 18,9 C18,9.33807829 17.985,9.66903915 17.958,10 L0.042,10 C0.018,9.66903915 0,9.33807829 0,9 C0,8.66192171 0.018,8.32740214 0.042,8 Z" id="smartcrawl"></path></g></g></svg>';
		$icon = 'data:image/svg+xml;base64,' . base64_encode( $svg );
		return $icon; // phpcs:ignore -- base64_encode is harmless here
	}

	protected function _get_seo_service() {
		if ( ! empty( $this->_seo_service ) ) {
			return $this->_seo_service;
		}

		$this->_seo_service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_SEO );

		return $this->_seo_service;
	}

	/**
	 * TODO: replace with check_ajax_referer
	 */
	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-nonce' ) ? stripslashes_deep( $_POST ) : array();
	}
}
