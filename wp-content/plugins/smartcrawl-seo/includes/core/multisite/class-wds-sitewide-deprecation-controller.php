<?php

class Smartcrawl_Sitewide_Deprecation_Controller extends Smartcrawl_Base_Controller {
	const SITEWIDE_DEPRECATION_TIMESTAMP = 'wds_sitewide_deprecation_timestamp';
	private static $_instance;

	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function should_run() {
		return is_multisite()
		       && $this->is_network_sitewide();
	}

	protected function init() {
		add_action( 'wds_plugin_update', array( $this, 'deprecate_sitewide' ) );
		add_action( 'wds_plugin_update', array( $this, 'maybe_copy_old_sitewide_settings' ) );
		add_action( 'network_admin_notices', array( $this, 'print_deprecation_notice' ) );
	}

	public function print_deprecation_notice() {
		$key = 'sitewide-deprecation-notice';
		$network_admin_url = network_admin_url( 'admin.php?page=wds_network_settings' );
		$dismissed_messages = get_user_meta( get_current_user_id(), 'wds_dismissed_messages', true );
		$is_message_dismissed = smartcrawl_get_array_value( $dismissed_messages, $key ) === true;
		if ( $is_message_dismissed ) {
			return;
		}
		?>
		<div class="notice-info notice is-dismissible wds-native-dismissible-notice"
		     data-message-key="<?php echo esc_attr( $key ); ?>">
			<p>
				<?php esc_html_e( "SmartCrawl's Sitewide mode has been removed. Your Sitewide settings will be applied automatically to all sites in your network. Only Super Admins are permitted to access SmartCrawl sub-site settings.", 'wds' ); ?>
				<br/><?php esc_html_e( "Please review your 'Network Settings' page for more configuration options.", 'wds' ); ?>
			</p>
			<a href="<?php echo esc_attr( $network_admin_url ); ?>"
			   class="button button-primary">
				<?php esc_html_e( 'Network Settings', 'wds' ); ?>
			</a>
			<a href="#" class="wds-native-dismiss"><?php esc_html_e( 'Dismiss', 'wds' ); ?></a>
			<p></p>
		</div>
		<?php
	}

	public function deprecate_sitewide() {
		$timestamp = $this->get_sitewide_deprecation_timestamp();
		if ( $timestamp ) {
			// Already deprecated
			return;
		}

		// We need to know the exact moment when the plugin was updated to version 2.13 or later so we can apply sitewide settings to older sites
		update_site_option( self::SITEWIDE_DEPRECATION_TIMESTAMP, time() );
		// Simulate sitewide mode by only giving superadmins access to the sub-site settings pages
		update_site_option( 'wds_subsite_manager_role', 'superadmin' );
		// Activate all modules on sub-sites
		smartcrawl_activate_all_blog_tabs();
	}

	private function get_sitewide_deprecation_timestamp() {
		$timestamp = get_site_option( self::SITEWIDE_DEPRECATION_TIMESTAMP, 0 );
		return empty( $timestamp )
			? 0
			: (int) $timestamp;
	}

	public function maybe_copy_old_sitewide_settings() {
		$last_version = Smartcrawl_Loader::get_last_version();
		// We only need to copy values if the user is upgrading from a version that had sitewide mode
		// if the user is upgrading from a newer version then we already did the copy operation once
		$copy_needed = version_compare( $last_version, '2.12.0', '<=' );

		if ( $copy_needed && $this->is_old_site() ) {
			// Get from sitewide
			$sitewide = Smartcrawl_Sitewide_Export::load();
			// And save to local
			Smartcrawl_Import::load( $sitewide->get_json() )->save();

			// Create a config for new sites if one doesn't exist already
			$subsite_config_id = get_site_option( 'wds_subsite_config_id' );
			if ( is_main_site() && ! $subsite_config_id ) {
				$config_id = $this->create_config();
				update_site_option( 'wds_subsite_config_id', $config_id );
			}
		}
	}

	private function create_config() {
		$config_name = sprintf(
			esc_html__( 'Sitewide Settings @ %s', 'wds' ),
			get_site_option( 'site_name' )
		);
		$config = Smartcrawl_Config_Model::create_from_plugin_snapshot( $config_name );
		$collection = Smartcrawl_Config_Collection::get();
		$collection->add( $config );
		$collection->save();

		return $config->get_id();
	}

	/**
	 * An *old* site is one that existed before sitewide deprecation
	 */
	private function is_old_site() {
		$blog_details = get_blog_details( get_current_blog_id() );
		$blog_registration_timestamp = strtotime( $blog_details->registered );
		$sitewide_deprecation_timestamp = $this->get_sitewide_deprecation_timestamp();
		if ( ! $blog_registration_timestamp || ! $sitewide_deprecation_timestamp ) {
			return false;
		}

		return $blog_registration_timestamp < $sitewide_deprecation_timestamp;
	}

	private function is_network_sitewide() {
		return get_site_option( 'wds_sitewide_mode', true );
	}
}
