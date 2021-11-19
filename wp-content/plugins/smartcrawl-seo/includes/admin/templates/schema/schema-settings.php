<?php
$action_url = empty( $_view['action_url'] ) ? '' : $_view['action_url'];
$option_name = empty( $_view['option_name'] ) ? '' : $_view['option_name'];
$options = empty( $options ) ? array() : $options;
$social_options = empty( $social_options ) ? array() : $social_options;
$active_tab = empty( $active_tab ) ? '' : $active_tab;
$post_types = empty( $post_types ) ? array() : $post_types;
$taxonomies = empty( $taxonomies ) ? array() : $taxonomies;
$pages = empty( $pages ) ? array() : $pages;
$schema_disabled = empty( $social_options['disable-schema'] ) ? false : true;

$this->_render( 'before-page-container' );
?>
<div id="container" class="<?php smartcrawl_wrap_class( 'wds-page-schema' ); ?>">
	<?php $this->_render( 'page-header', array(
		'title'                 => esc_html__( 'Schema', 'wds' ),
		'documentation_chapter' => 'schema',
		'utm_campaign'          => 'smartcrawl_schema_docs',
	) ); ?>

	<?php $this->_render( 'floating-notices', array(
		'keys' => array(
			'wds-youtube-api-key-valid',
			'wds-schema-types-notice',
			'wds-schema-types-local-business-notice',
			'wds-schema-types-invalid-notice',
		),
	) ); ?>

	<?php if ( $schema_disabled ) {
		$this->_render( 'schema/schema-disabled' );
	} else { ?>
		<form action='<?php echo esc_attr( $action_url ); ?>' method='post' class="wds-form">
			<?php $this->settings_fields( $option_name ); ?>

			<div class="wds-vertical-tabs-container sui-row-with-sidenav">
				<?php $this->_render( 'schema/schema-sidenav', array(
					'active_tab' => $active_tab,
				) ); ?>

				<?php
				$this->_render( 'vertical-tab', array(
					'tab_id'       => 'tab_general',
					'tab_name'     => esc_html__( 'General', 'wds' ),
					'is_active'    => 'tab_general' === $active_tab,
					'tab_sections' => array(
						array(
							'section_description' => esc_html__( 'Let search engines know whether you’re an organization or a person, then add all your social profiles so search engines know which social profiles to attribute your web content to.', 'wds' ),
							'section_template'    => 'schema/schema-section-general',
							'section_args'        => array(
								'options'        => $options,
								'social_options' => $social_options,
								'pages'          => $pages,
							),
						),
					),
				) );

				$this->_render( 'vertical-tab', array(
					'tab_id'       => 'tab_advanced',
					'tab_name'     => esc_html__( 'Advanced', 'wds' ),
					'is_active'    => 'tab_advanced' === $active_tab,
					'tab_sections' => array(
						array(
							'section_description' => esc_html__( 'Choose additional schema markup you want to enable for this website.', 'wds' ),
							'section_template'    => 'schema/schema-section-advanced',
							'section_args'        => array(
								'options'        => $options,
								'social_options' => $social_options,
								'pages'          => $pages,
								'post_types'     => $post_types,
								'taxonomies'     => $taxonomies,
							),
						),
					),
				) );

				$this->_render( 'vertical-tab', array(
					'tab_id'       => 'tab_types',
					'tab_name'     => esc_html__( 'Types Builder', 'wds' ),
					'is_active'    => 'tab_types' === $active_tab,
					'tab_sections' => array(
						array(
							'section_description' => esc_html__( 'Choose what post types and taxonomies you want to associate with each available schema type. By default we output general markup on key WordPress pages, but you can enhance and tailor this to your specific needs below.', 'wds' ),
							'section_template'    => 'schema/schema-section-types',
							'section_args'        => array(),
						),
					),
					'button_text'  => false,
				) );

				$this->_render( 'vertical-tab', array(
					'tab_id'       => 'tab_settings',
					'tab_name'     => esc_html__( 'Settings', 'wds' ),
					'is_active'    => 'tab_settings' === $active_tab,
					'tab_sections' => array(
						array(
							'section_description' => esc_html__( '', 'wds' ),
							'section_template'    => 'schema/schema-section-settings',
							'section_args'        => array(
								'options'        => $options,
								'social_options' => $social_options,
							),
						),
					),
				) );
				?>
			</div>
		</form>
	<?php } ?>

	<?php $this->_render( 'footer' ); ?>
</div>
