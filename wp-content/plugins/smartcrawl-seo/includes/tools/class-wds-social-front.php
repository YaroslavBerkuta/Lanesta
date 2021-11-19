<?php

class Smartcrawl_Social_Front extends Smartcrawl_Base_Controller {

	private static $_instance;

	public function should_run() {
		return Smartcrawl_Settings::get_setting( 'social' )
		       && Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SOCIAL );
	}

	protected function init() {
		Smartcrawl_OpenGraph_Printer::run();
		Smartcrawl_Twitter_Printer::run();
		Smartcrawl_Pinterest_Printer::run();
	}

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}
