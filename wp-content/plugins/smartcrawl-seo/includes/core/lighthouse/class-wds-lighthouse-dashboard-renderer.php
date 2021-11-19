<?php

class Smartcrawl_Lighthouse_Dashboard_Renderer extends Smartcrawl_Renderable {
	private static $_instance;

	public static function get_instance() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function render( $view, $args = array() ) {
		$instance = self::get_instance();
		$instance->_render( $view, $args );
	}

	public static function load( $view, $args = array() ) {
		$instance = self::get_instance();

		return $instance->_load( $view, $args );
	}

	protected function _get_view_defaults() {
		/**
		 * @var $lighthouse Smartcrawl_Lighthouse_Service
		 */
		$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );
		$device = Smartcrawl_Lighthouse_Options::dashboard_widget_device();
		if ( ! in_array( $device, array( 'desktop', 'mobile' ), true ) ) {
			$device = 'desktop';
		}

		return array(
			'lighthouse_start_time' => $lighthouse->get_start_time(),
			'lighthouse_report'     => $lighthouse->get_last_report( $device ),
		);
	}
}
