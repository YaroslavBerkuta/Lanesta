<?php

class Smartcrawl_Network_Configs_Controller extends Smartcrawl_Base_Controller {
	private static $_instance;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function should_run() {
		return is_multisite();
	}

	protected function init() {
		add_action( 'wp_initialize_site', array( $this, 'apply_config' ), 99 );
		add_action( 'activate_blog', array( $this, 'apply_config' ) );
	}

	private function get_subsite_config_id() {
		return get_site_option( 'wds_subsite_config_id', '' );
	}

	/**
	 * Actually apply the config to the current site
	 *
	 * @param $blog
	 */
	public function apply_config( $blog ) {
		$config_id = $this->get_subsite_config_id();
		if ( empty( $config_id ) ) {
			return;
		}

		$config_collection = Smartcrawl_Config_Collection::get();
		$config = $config_collection->get_by_id( $config_id );
		if ( ! $config ) {
			return;
		}

		if ( is_numeric( $blog ) ) {
			$blog_id = (int) $blog;
		} elseif ( is_a( $blog, 'WP_Site' ) ) {
			$blog_id = $blog->blog_id;
		}
		if ( empty( $blog_id ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		Smartcrawl_Controller_Configs::get()
		                             ->apply_config( $config->get_configs() );
		restore_current_blog();
	}
}
