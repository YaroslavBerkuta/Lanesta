<?php
/**
 * Admin side handling
 *
 * @package wpmu-dev-seo
 */

/**
 * Admin handling root class
 */
class Smartcrawl_Admin extends Smartcrawl_Base_Controller {
	/**
	 * Static instance
	 *
	 * @var self
	 */
	private static $_instance;

	/**
	 * Admin page handlers
	 *
	 * @var array
	 */
	private $_handlers = array();

	/**
	 * Static instance getter
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initializing method
	 */
	protected function init() {
		// Set up dash.
		// TODO: dash setup probably needs its own controller
		if ( file_exists( SMARTCRAWL_PLUGIN_DIR . 'external/dash/wpmudev-dash-notification.php' ) ) {
			global $wpmudev_notices;
			if ( ! is_array( $wpmudev_notices ) ) {
				$wpmudev_notices = array();
			}
			$wpmudev_notices[] = array(
				'id'      => 167,
				'name'    => 'SmartCrawl',
				'screens' => array(
					'toplevel_page_wds_wizard-network',
					'toplevel_page_wds_wizard',
					'smartcrawl-pro_page_wds_health-network',
					'smartcrawl-pro_page_wds_health',
					'smartcrawl-pro_page_wds_onpage-network',
					'smartcrawl-pro_page_wds_onpage',
					'smartcrawl-pro_page_wds_schema-network',
					'smartcrawl-pro_page_wds_schema',
					'smartcrawl-pro_page_wds_social-network',
					'smartcrawl-pro_page_wds_social',
					'smartcrawl-pro_page_wds_sitemap-network',
					'smartcrawl-pro_page_wds_sitemap',
					'smartcrawl-pro_page_wds_autolinks-network',
					'smartcrawl-pro_page_wds_autolinks',
					'smartcrawl-pro_page_wds_settings-network',
					'smartcrawl-pro_page_wds_settings',
				),
			);
			require_once SMARTCRAWL_PLUGIN_DIR . 'external/dash/wpmudev-dash-notification.php';
		}

		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_filter( 'load-index.php', array( $this, 'enqueue_dashboard_resources' ), 20 );

		add_action( 'wp_ajax_wds_dismiss_message', array( $this, 'smartcrawl_dismiss_message' ) );

		if ( Smartcrawl_Settings::get_setting( 'extras-admin_bar' ) ) {
			add_action( 'admin_bar_menu', array( $this, 'add_toolbar_items' ), 99 );
		}

		// Sanity check first!
		if ( ! get_option( 'blog_public' ) ) {
			add_action( 'admin_notices', array( $this, 'blog_not_public_notice' ) );
		}

		$this->_handlers['dashboard'] = Smartcrawl_Settings_Dashboard::get_instance();
		$this->_handlers['health'] = Smartcrawl_Health_Settings::get_instance();
		$this->_handlers['onpage'] = Smartcrawl_Onpage_Settings::get_instance();
		$this->_handlers['schema'] = Smartcrawl_Schema_Settings::get_instance();
		$this->_handlers['social'] = Smartcrawl_Social_Settings::get_instance();
		$this->_handlers['sitemap'] = Smartcrawl_Sitemap_Settings::get_instance();
		$this->_handlers['autolinks'] = Smartcrawl_Autolinks_Settings::get_instance();
		$this->_handlers['settings'] = Smartcrawl_Settings_Settings::get_instance();
	}

	/**
	 * Brute-register all the settings.
	 *
	 * If we got this far, this is a sane thing to do.
	 * This overrides the `Smartcrawl_Core_Admin::register_setting()`.
	 *
	 * In response to "Unable to save options multiple times" bug.
	 */
	public function register_setting() {
		register_setting( 'wds_settings_options', 'wds_settings_options', array(
			$this->get_handler( 'settings' ),
			'validate',
		) );
		register_setting( 'wds_sitemap_options', 'wds_sitemap_options', array(
			$this->get_handler( 'sitemap' ),
			'validate',
		) );
		register_setting( 'wds_onpage_options', 'wds_onpage_options', array(
			$this->get_handler( 'onpage' ),
			'validate',
		) );
		register_setting( 'wds_social_options', 'wds_social_options', array(
			$this->get_handler( 'social' ),
			'validate',
		) );
		register_setting( 'wds_schema_options', 'wds_schema_options', array(
			$this->get_handler( 'schema' ),
			'validate',
		) );
		register_setting( 'wds_autolinks_options', 'wds_autolinks_options', array(
			$this->get_handler( 'autolinks' ),
			'validate',
		) );
		register_setting( 'wds_redirections_options', 'wds_redirections_options', array(
			$this->get_handler( 'redirections' ),
			'validate',
		) );
		register_setting( 'wds_health_options', 'wds_health_options', array(
			$this->get_handler( 'health' ),
			'validate',
		) );
	}

	/**
	 * Admin page handler getter
	 *
	 * @param string $hndl Handler to get.
	 *
	 * @return object Handler
	 */
	public function get_handler( $hndl ) {
		return isset( $this->_handlers[ $hndl ] )
			? $this->_handlers[ $hndl ]
			: $this;
	}

	/**
	 * Adds admin toolbar items
	 *
	 * @param object $admin_bar Admin toolbar object.
	 *
	 * @return bool
	 */
	public function add_toolbar_items( $admin_bar ) {
		if ( empty( $admin_bar ) || ! function_exists( 'is_admin_bar_showing' ) ) {
			return false;
		}
		if ( ! is_admin_bar_showing() ) {
			return false;
		}
		if ( ! apply_filters( 'wds-admin-ui-show_bar', true ) ) {
			return false;
		}
		// Do not show if only superadmin can view settings and the current user is not super admin.
		if (
			is_multisite()
			&& smartcrawl_subsite_manager_role() === 'superadmin'
			&& ! current_user_can( 'manage_network_options' )
		) {
			return false;
		}

		// On single site don't show for non-admins
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$optional_nodes = array();
		foreach ( $this->_handlers as $handler ) {
			if ( empty( $handler ) || empty( $handler->slug ) ) {
				continue;
			}

			if ( ! $this->is_admin_bar_node_allowed( $handler->slug ) ) {
				continue;
			}

			$optional_nodes[] = $this->create_admin_bar_node( $handler->slug, $handler->get_title() );
		}

		if ( ! empty( $optional_nodes ) ) {
			$admin_bar->add_node( $this->create_admin_bar_node( Smartcrawl_Settings::TAB_DASHBOARD, __( 'SmartCrawl', 'wds' ) ) );
			$admin_bar->add_node( $this->create_admin_bar_node( Smartcrawl_Settings::TAB_DASHBOARD . '_dashboard', __( 'Dashboard', 'wds' ), Smartcrawl_Settings::TAB_DASHBOARD ) );
			foreach ( $optional_nodes as $optional_node ) {
				$admin_bar->add_node( $optional_node );
			}
		}

		return true;
	}

	private function is_admin_bar_node_allowed( $slug ) {
		if ( is_multisite() ) {
			return Smartcrawl_Settings_Admin::is_tab_allowed( $slug );
		}

		return true;
	}

	private function create_admin_bar_node( $id, $title, $slug = '' ) {
		$node = array(
			'id'    => $id,
			'title' => $title,
			'href'  => sprintf(
				'%s?page=%s',
				admin_url( 'admin.php' ),
				empty( $slug ) ? $id : $slug
			),
		);

		if ( Smartcrawl_Settings::TAB_DASHBOARD !== $id ) {
			$node['parent'] = Smartcrawl_Settings::TAB_DASHBOARD;
		}

		return $node;
	}

	/**
	 * Validate user data for some/all of your input fields
	 *
	 * @param mixed $input Raw input.
	 *
	 * @return mixed
	 */
	public function validate( $input ) {
		return $input; // return validated input.
	}

	/**
	 * Shows blog not being public notice.
	 *
	 * TODO: probably not the right class for this method. We can probably make a separate controller for admin messages and the dismiss message.
	 */
	public function blog_not_public_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$message = sprintf(
			'%1$s <a href="%3$s">%2$s</a>',
			esc_html__( 'This site discourages search engines from indexing the pages, which will affect your SEO efforts.', 'wds' ),
			esc_html__( 'You can fix this here', 'wds' ),
			admin_url( '/options-reading.php' )
		);

		echo '<div class="notice-error notice is-dismissible"><p>' . wp_kses_post( $message ) . '</p></div>';
	}

	/**
	 * Process message dismissal request
	 */
	public function smartcrawl_dismiss_message() {
		$data = $this->get_request_data();
		$message = sanitize_key( smartcrawl_get_array_value( $data, 'message' ) );
		if ( null === $message ) {
			wp_send_json_error();

			return;
		}

		$dismissed_messages = get_user_meta( get_current_user_id(), 'wds_dismissed_messages', true );
		$dismissed_messages = '' === $dismissed_messages ? array() : $dismissed_messages;
		$dismissed_messages[ $message ] = true;
		update_user_meta( get_current_user_id(), 'wds_dismissed_messages', $dismissed_messages );
		wp_send_json_success();
	}

	/**
	 * TODO: we should remove widgets from the wordpress dashboard making dashboard resources unnecessary
	 */
	public function enqueue_dashboard_resources() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_css' ) );
	}

	function enqueue_dashboard_css() {
		wp_enqueue_style( Smartcrawl_Controller_Assets::WP_DASHBOARD_CSS );
	}

	private function get_request_data() {
		return isset( $_POST['_wds_nonce'] ) && wp_verify_nonce( $_POST['_wds_nonce'], 'wds-admin-nonce' ) ? stripslashes_deep( $_POST ) : array();
	}
}
