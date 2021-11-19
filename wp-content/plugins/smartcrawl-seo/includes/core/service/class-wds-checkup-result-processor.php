<?php

class Smartcrawl_Checkup_Result_Processor {
	/**
	 * Static instance
	 *
	 * @var $this
	 */
	private static $_instance;

	/**
	 * Static instance getter
	 */
	public static function get() {
		if ( empty( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function process( $results ) {
		if ( empty( $results['items'] ) ) {
			return $results;
		}

		foreach ( $results['items'] as $id => $item ) {
			if ( $this->is_ok( $item ) ) {
				continue;
			}

			$processor = $this->id_to_processor_name( $id );
			$method_name = "process_{$processor}";

			if ( method_exists( $this, $method_name ) ) {
				$results['items'][ $id ]['action_button'] = call_user_func_array( array(
					$this,
					$method_name,
				), array( $item ) );
			}
		}

		return $results;
	}

	private function id_to_processor_name( $id ) {
		return str_replace( '-', '_', $id );
	}

	private function is_ok( $item ) {
		return smartcrawl_get_array_value( $item, 'type' ) === 'ok';
	}

	private function process_anchors_uninformative() {
		return $this->edit_homepage_button();
	}

	private function process_anchors_redundant() {
		return $this->edit_homepage_button();
	}

	private function process_anchors_empty() {
		return $this->edit_homepage_button();
	}

	private function process_url_structure_hsh() {
		return $this->edit_wp_settings_button();
	}

	private function process_url_structure_cpt() {
		return $this->edit_wp_settings_button();
	}

	private function process_url_structure_dash() {
		return $this->edit_wp_settings_button();
	}

	private function process_url_structure_qv() {
		return $this->edit_wp_settings_button();
	}

	private function is_tab_allowed( $tab ) {
		return Smartcrawl_Settings_Admin::is_tab_allowed( $tab );
	}

	private function process_sitemaps( $item ) {
		if ( ! $this->is_tab_allowed( Smartcrawl_Settings::TAB_SITEMAP ) ) {
			return '';
		}

		return $this->button_markup(
			esc_html__( 'Enable Sitemap', 'wds' ),
			Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SITEMAP ),
			'sui-icon-plus'
		);
	}

	private function process_open_graph( $item ) {
		if ( ! $this->is_tab_allowed( Smartcrawl_Settings::TAB_SOCIAL ) ) {
			return '';
		}

		$social_url = $this->get_social_url();

		return $this->button_markup(
			esc_html__( 'Enable OpenGraph', 'wds' ),
			$social_url . '&tab=tab_open_graph',
			'sui-icon-plus'
		);
	}

	private function process_microdata( $item ) {
		if ( ! $this->is_tab_allowed( Smartcrawl_Settings::TAB_SCHEMA ) ) {
			return '';
		}

		$schema_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SCHEMA );

		return $this->button_markup(
			esc_html__( 'Add Schema Markup', 'wds' ),
			$schema_url,
			'sui-icon-plus'
		);
	}

	private function process_meta( $item ) {
		if ( ! $this->is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return '';
		}

		$is_too_long = $this->item_status_contains( $item, 'too long' );

		return $this->button_markup(
			$is_too_long ? esc_html__( 'Edit Description', 'wds' ) : esc_html__( 'Add Description', 'wds' ),
			Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ),
			$is_too_long ? 'sui-icon-pencil' : 'sui-icon-plus'
		);
	}

	private function process_title( $item ) {
		if ( ! $this->is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return '';
		}

		$is_too_long = $this->item_status_contains( $item, 'too long' );

		return $this->button_markup(
			$is_too_long ? esc_html__( 'Edit Title', 'wds' ) : esc_html__( 'Add Title', 'wds' ),
			Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ),
			$is_too_long ? 'sui-icon-pencil' : 'sui-icon-plus'
		);
	}

	private function process_imgalts( $item ) {
		return $this->edit_homepage_button();
	}

	private function process_headings( $item ) {
		return $this->edit_homepage_button();
	}

	private function process_favicons( $item ) {
		return $this->button_markup(
			esc_html__( 'Add Favicon', 'wds' ),
			admin_url( 'customize.php' ),
			'sui-icon-plus',
			'hide-if-no-customize'
		);
	}

	private function process_canonical( $item ) {
		return $this->edit_homepage_button(
			esc_html__( 'Add Canonical' ),
			'sui-icon-plus'
		);
	}

	private function process_robots( $item ) {
		if ( ! $this->is_tab_allowed( Smartcrawl_Settings::TAB_AUTOLINKS ) ) {
			return '';
		}

		$no_sitemap = $this->item_status_contains( $item, 'sitemap' );
		$url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_AUTOLINKS ) . '&tab=tab_robots_editor';
		return $this->button_markup(
			$no_sitemap ? esc_html__( 'Edit Robots.txt', 'wds' ) : esc_html__( 'Enable Robots.txt', 'wds' ),
			$url,
			$no_sitemap ? 'sui-icon-wrench-tool' : 'sui-icon-plus'
		);
	}

	private function edit_wp_settings_button( $text = '', $page = 'options-general.php' ) {
		if ( is_multisite() ) {
			return '';
		}

		return $this->button_markup(
			empty( $text ) ? esc_html__( 'Edit Settings', 'wds' ) : $text,
			admin_url( $page ),
			'sui-icon-wrench-tool'
		);
	}

	private function edit_homepage_button( $text = '', $icon = 'sui-icon-pencil' ) {
		$page_on_front = get_option( 'page_on_front' );
		$show_on_front = get_option( 'show_on_front' );

		$has_static_homepage = 'posts' !== $show_on_front && $page_on_front;
		if ( ! $has_static_homepage ) {
			return '';
		}

		return $this->button_markup(
			empty( $text ) ? esc_html__( 'Edit Homepage', 'wds' ) : $text,
			get_edit_post_link( get_option( 'page_on_front' ) ),
			$icon
		);
	}

	private function button_markup( $text, $url, $icon, $button_class = '' ) {
		ob_start();
		?>
		<a class="wds-action-button sui-button <?php echo esc_attr( $button_class ); ?>"
		   href="<?php echo esc_url( $url ); ?>">

			<span class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span>
			<?php echo esc_html( $text ); ?>
		</a>
		<?php
		return ob_get_clean();
	}

	/**
	 * @return string
	 */
	private function get_social_url() {
		return Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SOCIAL );
	}

	private function item_status_contains( $item, $needle ) {
		$item_body = smartcrawl_get_array_value( $item, 'body' );

		return stripos( $item_body, $needle ) !== false;
	}

	private function process_meta_robots( $item ) {
		if ( $this->item_status_contains( $item, 'WordPress Settings' ) ) {
			return $this->edit_wp_settings_button(
				esc_html__( 'Allow Robots', 'wds' ),
				'options-reading.php'
			);
		}

		$edit_homepage_button = $this->edit_homepage_button();
		if ( $edit_homepage_button ) {
			return $edit_homepage_button;
		}

		if ( ! $this->is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ) ) {
			return '';
		}

		return $this->button_markup(
			'Allow Robots',
			Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE ),
			'sui-icon-wrench-tool'
		);
	}
}
