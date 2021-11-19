<?php

/**
 * Outputs JSON+LD schema.org data to the page
 */
class Smartcrawl_Schema_Printer extends Smartcrawl_WorkUnit {
	/**
	 * Singleton instance holder
	 */
	private static $_instance;

	private $_is_running = false;
	private $_is_done = false;

	public function __construct() {
	}

	/**
	 * Boot the hooking part
	 */
	public static function run() {
		self::get()->_add_hooks();
	}

	/**
	 * Singleton instance getter
	 *
	 * @return object Smartcrawl_Schema_Printer instance
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * First-line dispatching of schema tags injection
	 */
	public function dispatch_schema_injection() {
		if ( ! ! $this->_is_done ) {
			return false;
		}

		if ( $this->is_schema_disabled() ) {
			$this->_is_done = true;

			return false; // Disabled
		}

		$helper = Smartcrawl_Schema_Value_Helper::get();
		$helper->clear();
		$helper->traverse();
		$data = $helper->get_schema();

		if ( empty( $data ) ) {
			return false;
		}

		if ( empty( $data ) ) {
			return false;
		}

		$this->_is_done = true;

		echo '<script type="application/ld+json">' . wp_json_encode( $data ) . "</script>\n";
	}

	public function get_filter_prefix() {
		return 'wds-schema';
	}

	public function admin_bar_menu_items( $admin_bar ) {
		$schema_options = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SCHEMA );
		if (
			is_admin()
			|| ! current_user_can( 'manage_options' )
			|| $this->is_schema_disabled()
			|| empty( $schema_options['schema_enable_test_button'] )
		) {
			return $admin_bar;
		}

		// Do not show if only superadmin can view settings and the current user is not super admin.
		if (
			is_multisite()
			&& smartcrawl_subsite_manager_role() === 'superadmin'
			&& ! current_user_can( 'manage_network_options' )
		) {
			return $admin_bar;
		}

		$url = 'http' . ( isset( $_SERVER['HTTPS'] ) ? 's' : '' ) . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$admin_bar->add_menu( array(
			'id'    => 'smartcrawl-test-item',
			'title' => __( 'Test Schema', 'wds' ),
			'href'  => sprintf( 'https://search.google.com/test/rich-results?url=%s&user_agent=2', urlencode( $url ) ),
			'meta'  => array(
				'title'  => __( 'Test Schema', 'wds' ),
				'target' => __( '_blank' ),
			),
		) );

		return $admin_bar;
	}

	private function _add_hooks() {
		// Do not double-bind
		if ( $this->apply_filters( 'is_running', $this->_is_running ) ) {
			return true;
		}

		add_action( 'wp_head', array( $this, 'dispatch_schema_injection' ), 50 );
		add_action( 'wds_head-after_output', array( $this, 'dispatch_schema_injection' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu_items' ), 99 );
		add_filter( 'woocommerce_structured_data_product', array( $this, 'remove_woocommerce_product_schema' ), 10, 2 );

		$this->_is_running = true;
	}

	public function remove_woocommerce_product_schema( $markup, $product ) {
		$helper = Smartcrawl_Schema_Value_Helper::get();
		$schema = $helper->get_schema();
		$graph = smartcrawl_get_array_value( $schema, '@graph' );
		if ( $graph ) {
			foreach ( $graph as $graph_item ) {
				if ( smartcrawl_get_array_value( $graph_item, '@type' ) === 'Product' ) {
					return array();
				}
			}
		}

		return $markup;
	}

	/**
	 * @return mixed
	 */
	private function is_schema_disabled() {
		$social = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );
		return ! empty( $social['disable-schema'] )
		       || ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SCHEMA );
	}
}
