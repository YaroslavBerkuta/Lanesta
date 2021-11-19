<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

abstract class Smartcrawl_Admin_Page extends Smartcrawl_Base_Controller {
	protected function init() {
		add_action( 'admin_body_class', array( $this, 'add_body_class' ), 20 );
	}

	public function add_body_class( $classes ) {
		$sui_class = smartcrawl_sui_class();
		$screen = get_current_screen();

		if (
			$screen->id
			&& strpos( $screen->id, $this->get_menu_slug() ) !== false
			&& strpos( $classes, $sui_class ) === false
		) {
			$classes .= " {$sui_class} ";
		}

		return $classes;
	}

	abstract public function get_menu_slug();
}
