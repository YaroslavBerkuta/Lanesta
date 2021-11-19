<?php
$sitemap_available = Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SITEMAP );
$sitemap_crawler_available = Smartcrawl_Sitemap_Utils::crawler_available();
if ( ! $sitemap_available ) {
	return;
}

$page_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SITEMAP );
$options = $_view['options'];
$sitemap_enabled = Smartcrawl_Settings::get_setting( 'sitemap' );
$option_name = Smartcrawl_Settings::TAB_SETTINGS . '_options';
$service = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_CHECKUP );
$is_member = $service->is_member();
$override_native = Smartcrawl_Sitemap_Utils::override_native();
$tooltip_text = $override_native
	? esc_html__( "You can switch to the WordPress core sitemap through the configure button.", 'wds' )
	: esc_html__( "You're using the default WordPress sitemap. You can switch to SmartCrawl's advanced sitemaps at any time.", 'wds' );
$sitemap_notice_text = smartcrawl_format_link(
	esc_html__( 'Your sitemap is available at %s', 'wds' ),
	smartcrawl_get_sitemap_url(), '/sitemap.xml', '_blank'
);
$core_sitemap_notice_text = smartcrawl_format_link(
	esc_html__( 'Your WordPress core sitemap is available at %s', 'wds' ),
	home_url( '/wp-sitemap.xml' ), '/wp-sitemap.xml', '_blank'
);
$news_sitemap_notice_text = smartcrawl_format_link(
	esc_html__( 'Your news sitemap is available at %s', 'wds' ),
	home_url( '/news-sitemap.xml' ), '/news-sitemap.xml', '_blank'
);
$news_sitemap_enabled = smartcrawl_get_array_value( $options, 'enable-news-sitemap' );
?>
<section id="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_SITEMAP ); ?>"
         class="sui-box wds-dashboard-widget"
         data-dependent="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_TOP_STATS ); ?>">

	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<span class="sui-icon-web-globe-world" aria-hidden="true"></span> <?php esc_html_e( 'Sitemaps', 'wds' ); ?>
		</h2>
		<?php
		if ( $sitemap_enabled && $is_member && $sitemap_crawler_available ) {
			$this->_render( 'url-crawl-master', array(
				'progress_template' => 'dashboard/dashboard-box-title-url-crawl-in-progress',
				'ready_template'    => 'dashboard/dashboard-box-title-url-crawl-stats',
			) );
		}
		?>
	</div>
	<div class="sui-box-body">
		<p><?php esc_html_e( 'Automatically generate detailed sitemaps to tell search engines what content you want them to crawl and index.', 'wds' ); ?></p>

		<div class="wds-separator-top wds-draw-left-padded">
			<small><strong><?php esc_html_e( 'XML Sitemap', 'wds' ); ?></strong></small>
			<?php if ( $sitemap_enabled ) : ?>
				<span class="wds-sitemap-type-tag sui-tag sui-tooltip sui-tooltip-constrained"
				      data-tooltip="<?php echo esc_attr( $tooltip_text ); ?>">
					<?php $override_native
						? esc_html_e( 'SmartCrawl Sitemap', 'wds' )
						: esc_html_e( 'WP Core Sitemap', 'wds' ); ?>
				</span>

				<?php
				$this->_render( 'notice', array(
					'class'   => 'sui-notice-info',
					'message' => $override_native ? $sitemap_notice_text : $core_sitemap_notice_text,
				) );
				?>

			<?php else : ?>
				<p>
					<small><?php esc_html_e( 'Enables an XML page that search engines will use to crawl and index your website pages.', 'wds' ); ?></small>
				</p>

				<?php
				$this->_render( 'dismissable-notice', array(
					'key'     => 'dashboard-sitemap-disabled-warning',
					'message' => __( 'Your sitemap is currently disabled. We highly recommend you enable this feature if you don’t already have a sitemap.', 'wds' ),
					'class'   => 'sui-notice-warning',
				) );
				?>
				<button type="button"
				        data-option-id="<?php echo esc_attr( $option_name ); ?>"
				        data-flag="<?php echo 'sitemap'; ?>"
				        aria-label="<?php esc_html_e( 'Activate sitemap component', 'wds' ); ?>"
				        class="wds-activate-component sui-button sui-button-blue wds-disabled-during-request">

					<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'wds' ); ?></span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>
			<?php endif; ?>
		</div>

		<?php if ( $news_sitemap_enabled ): ?>
			<div class="wds-separator-top wds-draw-left-padded">
				<small><strong><?php esc_html_e( 'News Sitemap', 'wds' ); ?></strong></small>

				<?php $this->_render( 'notice', array(
					'class'   => 'sui-notice-info',
					'message' => $news_sitemap_notice_text,
				) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $sitemap_crawler_available ): ?>
			<div class="wds-separator-top cf <?php echo $is_member ? 'wds-draw-left-padded' : 'wds-box-blocked-area wds-draw-down wds-draw-left'; ?>">
				<small><strong><?php esc_html_e( 'URL Crawler', 'wds' ); ?></strong></small>
				<?php if ( $is_member ) : ?>
					<?php if ( $sitemap_enabled ) : ?>
						<?php
						$this->_render( 'url-crawl-master', array(
							'ready_template'    => 'dashboard/dashboard-url-crawl-stats',
							'progress_template' => 'dashboard/dashboard-url-crawl-in-progress',
							'no_data_template'  => 'dashboard/dashboard-url-crawl-no-data-small',
						) );
						?>
					<?php else : ?>
						<p>
							<small><?php esc_html_e( 'Automatically schedule SmartCrawl to run check for URLs that are missing from your Sitemap.', 'wds' ); ?></small>
						</p>
						<div><span class="sui-tag sui-tag-inactive">
							<?php esc_html_e( 'Sitemaps must be activated', 'wds' ); ?>
						</span></div>
					<?php endif; ?>
				<?php else : ?>
					<a href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_dash_crawl_pro_tag"
					   target="_blank">
						<span class="sui-tag sui-tag-pro sui-tooltip"
						      data-tooltip="<?php esc_attr_e( 'Try SmartCrawl Pro Free', 'wds' ); ?>">
							<?php esc_html_e( 'Pro', 'wds' ); ?>
						</span>
					</a>
					<p>
						<small><?php esc_html_e( 'Automatically schedule SmartCrawl to run check for URLs that are missing from your Sitemap.', 'wds' ); ?></small>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="sui-box-footer">
		<a href="<?php echo esc_attr( $page_url ); ?>"
		   aria-label="<?php esc_html_e( 'Configure sitemap component', 'wds' ); ?>"
		   class="sui-button sui-button-ghost">

			<span class="sui-icon-wrench-tool"
			      aria-hidden="true"></span> <?php esc_html_e( 'Configure', 'wds' ); ?>
		</a>
	</div>
</section>
