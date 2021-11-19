<?php
$crawl_url = Smartcrawl_Sitemap_Settings::crawl_url();
$is_member = empty( $_view['is_member'] ) ? false : true;
$active_tab = empty( $active_tab ) ? '' : $active_tab;
$crawl_report = empty( $_view['crawl_report'] ) ? null : $_view['crawl_report'];
$smartcrawl_buddypress = empty( $smartcrawl_buddypress ) ? array() : $smartcrawl_buddypress;
$sitemaps_enabled = Smartcrawl_Settings::get_setting( 'sitemap' );
$sitemap_crawler_available = Smartcrawl_Sitemap_Utils::crawler_available();
$automatically_switched = empty( $automatically_switched ) ? false : $automatically_switched;
$total_post_count = empty( $total_post_count ) ? 0 : $total_post_count;
$email_recipients = Smartcrawl_Sitemap_Settings::get_email_recipients();
$override_native = empty( $override_native ) ? false : $override_native;
$ping_google = ! empty( $_view['options']['ping-google'] );
$ping_bing = ! empty( $_view['options']['ping-bing'] );
?>

<?php $this->_render( 'before-page-container' ); ?>
<div id="container" class="<?php smartcrawl_wrap_class( 'wds-sitemap-settings' ); ?>">

	<?php $this->_render( 'page-header', array(
		'title'                 => esc_html__( 'Sitemaps', 'wds' ),
		'documentation_chapter' => 'sitemap',
		'utm_campaign'          => 'smartcrawl_sitemap_docs',
		'extra_actions'         => $sitemap_crawler_available ? 'sitemap/sitemap-extra-actions' : '',
	) ); ?>

	<?php $this->_render( 'floating-notices', array(
		'message' => $ping_google || $ping_bing
			? esc_html__( 'Your Sitemap is updated and Search Engines are being notified with changes.', 'wds' )
			: esc_html__( 'Your sitemap has been updated.', 'wds' ),
		'keys'    => array(
			'wds-email-recipient-notice',
			'wds-sitemap-manually-updated',
			'wds-sitemap-manually-notify-search-engines',
		),
	) ); ?>
	<?php $this->_render( 'sitemap/sitemap-notices' ); ?>

	<?php
	if ( $sitemaps_enabled ) {

		?>
		<form action='<?php echo esc_attr( $_view['action_url'] ); ?>' method='post' class="wds-form">
			<?php $this->settings_fields( $_view['option_name'] ); ?>

			<input type="hidden"
			       name='<?php echo esc_attr( $_view['option_name'] ); ?>[<?php echo esc_attr( $_view['slug'] ); ?>-setup]'
			       value="1">

			<?php if ( $sitemap_crawler_available ): ?>
				<div id="wds-crawl-summary-container">
					<?php $this->_render( 'sitemap/sitemap-crawl-stats', array(
						'crawl_report'    => $crawl_report,
						'override_native' => $override_native,
					) ); ?>
				</div>
			<?php endif; ?>

			<div class="wds-vertical-tabs-container sui-row-with-sidenav" id="sitemap-settings-tabs">

				<?php $this->_render( 'sitemap/sitemap-side-nav', array(
					'active_tab'                => $active_tab,
					'sitemap_crawler_available' => $sitemap_crawler_available,
					'override_native'           => $override_native,
				) ); ?>

				<?php
				// The last tab is on top in the markup because we want the item-per-sitemap setting in the native sitemap tab to override the regular field
				$settings_tab = $this->_load( 'vertical-tab', array(
					'tab_id'       => 'tab_settings',
					'tab_name'     => esc_html__( 'Settings', 'wds' ),
					'is_active'    => 'tab_settings' === $active_tab,
					'tab_sections' => array(
						array( 'section_template' => 'sitemap/sitemap-section-advanced' ),
					),
				) );
				if ( $override_native ) {
					echo $settings_tab;
				} else {
					printf( '<div style="display: none;">%s</div>', $settings_tab );
				}
				?>

				<?php
				$this->_render( 'sitemap/sitemap-section-news', array(
					'is_active' => 'tab_news' === $active_tab,
				) );

				$this->_render( 'vertical-tab', array(
					'tab_id'       => 'tab_sitemap',
					'tab_name'     =>
						$override_native
							? esc_html__( 'General Sitemap', 'wds' )
							: esc_html__( 'WP Core Sitemap', 'wds' ),
					'is_active'    => 'tab_sitemap' === $active_tab,
					'tab_sections' => array(
						array(
							'section_description' =>
								$override_native
									? esc_html__( 'Automatically generate a sitemap and regularly send updates to Google.', 'wds' )
									: esc_html__( 'Set up your sitemaps to tell search engines what content you want them to crawl and index.', 'wds' ),
							'section_template'    => 'sitemap/sitemap-section-settings',
							'section_args'        => array(
								'post_types'            => $post_types,
								'taxonomies'            => $taxonomies,
								'smartcrawl_buddypress' => $smartcrawl_buddypress,
								'extra_urls'            => ! empty( $extra_urls ) ? $extra_urls : '',
								'ignore_urls'           => ! empty( $ignore_urls ) ? $ignore_urls : '',
								'ignore_post_ids'       => ! empty( $ignore_post_ids ) ? $ignore_post_ids : '',
								'override_native'       => $override_native,
							),
						),
					),
				) );
				?>

				<?php if ( $sitemap_crawler_available ) {
					$this->_render(
						'vertical-tab',
						array(
							'tab_id'              => 'tab_url_crawler',
							'tab_name'            => esc_html__( 'Crawler', 'wds' ),
							'title_actions_left'  => 'sitemap/sitemap-url-crawler-tab-title-left',
							'title_actions_right' => 'sitemap/sitemap-url-crawler-tab-title-right',
							'is_active'           => 'tab_url_crawler' === $active_tab,
							'button_text'         => false,
							'title_button'        => 'upgrade',
							'tab_sections'        => array(
								array(
									'section_template' => 'sitemap/sitemap-section-url-crawler',
								),
							),
						)
					);

					$this->_render(
						'vertical-tab-upsell',
						array(
							'tab_id'             => 'tab_url_crawler_reporting',
							'tab_name'           => esc_html__( 'Reporting', 'wds' ),
							'is_active'          => 'tab_url_crawler_reporting' === $active_tab,
							'title_actions_left' => 'sitemap/sitemap-reporting-title-pro-tag',
							'button_text'        => $is_member ? esc_html__( 'Save Settings', 'wds' ) : '',
							'tab_sections'       => array(
								array(
									'section_template' => 'sitemap/sitemap-section-reporting',
									'section_args'     => array(
										'email_recipients' => $email_recipients,
									),
								),
							),
						)
					);
				} ?>
			</div>
		</form>
	<?php } else {
		$this->_render( 'sitemap/sitemap-disabled' );
	} ?>
	<?php $this->_render( 'footer' ); ?>
	<?php $this->_render( 'upsell-modal' ); ?>

</div><!-- end wds-sitemap-settings -->
