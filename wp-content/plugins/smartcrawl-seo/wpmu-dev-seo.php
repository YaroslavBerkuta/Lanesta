<?php
/**
 * Plugin Name: SmartCrawl
 * Plugin URI: https://wpmudev.com/project/smartcrawl-wordpress-seo/
 * Description: Every SEO option that a site requires, in one easy bundle.
 * Version: 2.15.3
 * Network: true
 * Text Domain: wds
 * Author: WPMU DEV
 * Author URI: https://wpmudev.com
 */

/*
* Copyright 2010-2011 Incsub (http://incsub.com/)
* Author - Ulrich Sossou (Incsub)
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.

* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.

* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

if ( ! class_exists( 'Smartcrawl_Loader' ) ) {
	class Smartcrawl_Loader {
		const LAST_VERSION_OPTION_ID = 'wds_last_version';
		const VERSION_OPTION_ID = 'wds_version';

		/**
		 * Construct the Plugin object
		 */
		public function __construct() {
			$this->plugin_init();
		}

		/**
		 * Init Plugin
		 */
		public function plugin_init() {
			require_once plugin_dir_path( __FILE__ ) . 'constants.php';

			// Init plugin.
			require_once SMARTCRAWL_PLUGIN_DIR . 'init.php';

			add_action( 'plugins_loaded', array( $this, 'set_version_options' ) );
		}

		/**
		 * Activate the plugin
		 *
		 * @return void
		 */
		public static function activate() {
			require_once plugin_dir_path( __FILE__ ) . 'constants.php';

			// Init plugin
			require_once SMARTCRAWL_PLUGIN_DIR . 'init.php';

			Smartcrawl_Settings_Dashboard::get_instance()->defaults();

			Smartcrawl_Health_Settings::get_instance()->defaults();

			Smartcrawl_Onpage_Settings::get_instance()->defaults();

			Smartcrawl_Schema_Settings::get_instance()->defaults();

			Smartcrawl_Social_Settings::get_instance()->defaults();

			Smartcrawl_Sitemap_Settings::get_instance()->defaults();

			Smartcrawl_Autolinks_Settings::get_instance()->defaults();

			Smartcrawl_Settings_Settings::get_instance()->defaults();

			$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );
			if ( $lighthouse->is_member() ) {
				$lighthouse->set_lighthouse_status();
			}

			self::save_free_installation_timestamp();
		}

		private static function save_free_installation_timestamp() {
			$service = self::get_service();
			if ( $service->is_member() ) {
				return;
			}

			$free_install_date = get_site_option( 'wds-free-install-date' );
			if ( empty( $free_install_date ) ) {
				update_site_option( 'wds-free-install-date', current_time( 'timestamp' ) );
			}
		}

		public function set_version_options() {
			$version = get_option( self::VERSION_OPTION_ID, false );
			if ( ! $version || version_compare( $version, SMARTCRAWL_VERSION, '!=' ) ) {
				update_option( self::LAST_VERSION_OPTION_ID, $version );
				update_option( self::VERSION_OPTION_ID, SMARTCRAWL_VERSION );

				do_action( 'wds_plugin_update', SMARTCRAWL_VERSION, $version );
			}
		}

		/**
		 * @return Smartcrawl_Checkup_Service
		 */
		private static function get_service() {
			$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_CHECKUP );

			return $service;
		}

		/**
		 * Deactivate the plugin
		 *
		 * @return void
		 */
		public static function deactivate() {
		}

		public static function get_last_version() {
			return get_option( self::LAST_VERSION_OPTION_ID, false );
		}

		/**
		 * Gets the version number string
		 *
		 * @return string Version number info
		 */
		public static function get_version() {
			static $version;
			if ( empty( $version ) ) {
				$version = defined( 'SMARTCRAWL_VERSION' ) && SMARTCRAWL_VERSION ? SMARTCRAWL_VERSION : null;
			}

			return $version;
		}
	}
}

require_once 'autoloader.php';

if ( ! defined( 'SMARTCRAWL_PLUGIN_BASENAME' ) ) {
	define( 'SMARTCRAWL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

// Plugin Activation and Deactivation hooks
register_activation_hook( __FILE__, array( 'Smartcrawl_Loader', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Smartcrawl_Loader', 'deactivate' ) );

if ( defined( 'SMARTCRAWL_CONDITIONAL_EXECUTION' ) && SMARTCRAWL_CONDITIONAL_EXECUTION ) {
	add_action(
		'plugins_loaded',
		array( 'Smartcrawl_Loader', 'plugin_init' )
	);
} else {
	$smartcrawl_loader = new Smartcrawl_Loader();
}
