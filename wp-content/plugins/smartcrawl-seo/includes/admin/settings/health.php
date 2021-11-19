<?php

class Smartcrawl_Health_Settings extends Smartcrawl_Settings_Admin {
	/**
	 * Singleton instance
	 *
	 * @var self
	 */
	private static $_instance;

	/**
	 * Singleton instance getter
	 *
	 * @return self instance
	 */
	public static function get_instance() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function is_test_mode_checkup() {
		return ! Smartcrawl_Health_Settings::is_test_mode_lighthouse();
	}

	public static function is_test_mode_lighthouse() {
		$health_options = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_HEALTH );
		return smartcrawl_get_array_value( $health_options, 'health-test-mode' ) === 'lighthouse';
	}

	/**
	 * TODO: The following makes updating just the health options impossible, change it
	 */
	public function validate( $input ) {
		if ( self::is_test_mode_lighthouse() ) {
			Smartcrawl_Lighthouse_Options::save_form_data( $input );
		} else {
			Smartcrawl_Checkup_Options::save_form_data( $input );
		}

		$result = array();
		$result['health-test-mode'] = empty( $input['health-test-mode'] )
			? 'lighthouse'
			: $input['health-test-mode'];

		return $result;
	}

	public function init() {
		$this->option_name = 'wds_health_options';
		$this->name = Smartcrawl_Settings::COMP_HEALTH;
		$this->slug = Smartcrawl_Settings::TAB_HEALTH;
		$this->action_url = admin_url( 'options.php' );
		$this->page_title = __( 'SmartCrawl Wizard: SEO Health', 'wds' );

		add_action( 'wp_ajax_wds-save-health-settings', array( $this, 'save_health_settings' ) );

		parent::init();
	}

	public function save_health_settings() {
		$data = $this->get_request_data();
		if ( empty( $data ) ) {
			wp_send_json_error();
		}

		Smartcrawl_Settings::update_specific_options( $this->option_name, $_GET['wds_health_options'] );

		$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );
		if ( $lighthouse->is_member() ) {
			$lighthouse->set_lighthouse_status();
		}

		wp_send_json_success();
	}

	public function get_title() {
		return __( 'SEO Health', 'wds' );
	}

	public function options_page() {
		if ( self::is_test_mode_lighthouse() ) {
			$this->lighthouse_options_page();
		} else {
			$this->checkup_options_page();
		}
	}

	private function checkup_options_page() {
		wp_enqueue_script( Smartcrawl_Controller_Assets::CHECKUP_PAGE_JS );

		$this->_render( 'checkup/checkup-settings' );
	}

	private function lighthouse_options_page() {
		wp_enqueue_script( Smartcrawl_Controller_Assets::LIGHTHOUSE_JS );

		$this->_render( 'lighthouse/lighthouse-settings' );
	}

	public function defaults() {
		Smartcrawl_Lighthouse_Options::save_defaults();
		Smartcrawl_Checkup_Options::save_defaults();

		$options = Smartcrawl_Settings::get_component_options( $this->name );
		$options = is_array( $options ) ? $options : array();

		foreach ( $this->get_default_options() as $opt => $default ) {
			if ( ! isset( $options[ $opt ] ) ) {
				$options[ $opt ] = $default;
			}
		}

		Smartcrawl_Settings::update_component_options( $this->name, $options );
	}

	private function get_default_options() {
		return array(
			'health-test-mode' => 'seo-checkup',
		);
	}

	protected function _get_view_defaults() {
		if ( self::is_test_mode_lighthouse() ) {
			$active_tab = 'tab_lighthouse';
			$mode_defaults = Smartcrawl_Lighthouse_Renderer::get_instance()->get_view_defaults();
		} else {
			$active_tab = 'tab_checkup';
			$mode_defaults = Smartcrawl_Checkup_Renderer::get_instance()->get_view_defaults();
		}

		return array_merge(
			array(
				'active_tab' => $this->_get_active_tab( $active_tab ),
			),
			$mode_defaults,
			parent::_get_view_defaults()
		);
	}

	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-health-nonce' )
			? $_POST
			: array();
	}
}
