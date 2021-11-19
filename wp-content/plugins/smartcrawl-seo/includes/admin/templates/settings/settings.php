<?php
$active_tab = empty( $active_tab ) ? '' : $active_tab;
$import_available = is_main_site();
$show_data_settings = is_main_site();
$configs_available = is_main_site();

$this->_render( 'before-page-container' );
?>
<div id="container" class="<?php smartcrawl_wrap_class( 'wds-page-settings' ); ?>">

	<?php $this->_render( 'page-header', array(
		'title'                 => esc_html__( 'SmartCrawl Settings', 'wds' ),
		'documentation_chapter' => 'settings',
		'utm_campaign'          => 'smartcrawl_settings_docs',
	) ); ?>

	<?php $this->_render( 'floating-notices', array(
		'keys' => array(
			'wds-data-reset',
			'wds-config-notice',
		),
	) ); ?>
	<?php $this->_render( 'settings/settings-import-notice' ); ?>

	<div class="wds-vertical-tabs-container sui-row-with-sidenav">
		<?php $this->_render( 'settings/settings-sidenav', array(
			'active_tab'         => $active_tab,
			'show_data_settings' => $show_data_settings,
			'configs_available'  => $configs_available,
			'import_available'   => $import_available,
		) ); ?>

		<form action='<?php echo esc_attr( $_view['action_url'] ); ?>' method='post' class="wds-form">
			<?php $this->settings_fields( $_view['option_name'] ); ?>

			<input type="hidden"
			       name='<?php echo esc_attr( $_view['option_name'] ); ?>[<?php echo esc_attr( $_view['slug'] ); ?>-setup]'
			       value="1"/>

			<?php
			$this->_render( 'vertical-tab', array(
				'tab_id'       => 'tab_general_settings',
				'tab_name'     => __( 'General Settings', 'wds' ),
				'is_active'    => 'tab_general_settings' === $active_tab,
				'tab_sections' => array(
					array(
						'section_template' => 'settings/settings-section-general',
						'section_args'     => array(
							'verification_pages'  => $verification_pages,
							'sitemap_option_name' => $sitemap_option_name,
							'plugin_modules'      => $plugin_modules,
						),
					),
				),
			) );

			if ( $configs_available ) {
				$this->_render( 'settings/settings-section-configs', array(
					'is_active' => 'tab_configs' === $active_tab,
				) );
			}

			$this->_render( 'vertical-tab', array(
				'tab_id'        => 'tab_user_roles',
				'tab_name'      => __( 'User Roles', 'wds' ),
				'is_active'     => 'tab_user_roles' === $active_tab,
				'before_output' => $this->_load( '_forms/settings' ),
				'after_output'  => '</form>',
				'tab_sections'  => array(
					array(
						'section_template' => 'settings/settings-section-user-roles',
						'section_args'     => array(
							'seo_metabox_permission_level'        => $seo_metabox_permission_level,
							'seo_metabox_301_permission_level'    => $seo_metabox_301_permission_level,
							'urlmetrics_metabox_permission_level' => $urlmetrics_metabox_permission_level,
						),
					),
				),
			) );
			?>

			<?php if ( $show_data_settings ): ?>
				<?php $this->_render( 'vertical-tab', array(
					'tab_id'       => 'tab_data',
					'tab_name'     => esc_html__( 'Data & Settings', 'wds' ),
					'is_active'    => 'tab_data' === $active_tab,
					'tab_sections' => array(
						array(
							'section_description' => esc_html__( 'Control what to do with your settings and data. Settings are each module’s configuration options, Data includes the stored information like logs, checkup results and other bits of information stored over time.', 'wds' ),
							'section_template'    => 'settings/settings-section-data',
							'section_args'        => array(),
						),
					),
				) ); ?>
			<?php endif; ?>

			<?php
			$this->_render( 'vertical-tab', array(
				'tab_id'       => 'tab_accessibility',
				'tab_name'     => __( 'Accessibility', 'wds' ),
				'is_active'    => 'tab_accessibility' === $active_tab,
				'tab_sections' => array(
					array(
						'section_description' => esc_html__( 'Enable support for any accessibility enhancements available in the plugin interface.', 'wds' ),
						'section_template'    => 'settings/settings-section-accessibility',
						'section_args'        => array(),
					),
				),
			) );
			?>
		</form>

		<?php if ( $import_available ): ?>
			<form method='post' enctype="multipart/form-data" class="wds-form">
				<?php $this->settings_fields( $_view['option_name'] ); ?>

				<input type="hidden"
				       name='<?php echo esc_attr( $_view['option_name'] ); ?>[<?php echo esc_attr( $_view['slug'] ); ?>-setup]'
				       value="1"/>
				<?php
				$this->_render( 'vertical-tab', array(
					'tab_id'       => 'tab_import_export',
					'tab_name'     => __( 'Import', 'wds' ),
					'is_active'    => 'tab_import_export' === $active_tab,
					'button_text'  => false,
					'tab_sections' => array(
						array(
							'section_template' => 'settings/settings-section-import-export',
						),
					),
				) );
				?>
			</form>
		<?php endif; ?>
	</div>

	<?php $this->_render( 'footer' ); ?>
</div>
