<?php
$options = empty( $_view['options'] ) ? array() : $_view['options'];
$cron = Smartcrawl_Controller_Cron::get();
$frequencies = $cron->get_frequencies();

$health_reporting_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH ) . '&tab=tab_reporting';
$health_available = is_main_site();

$checkup_cron_enabled = Smartcrawl_Checkup_Options::is_cron_enabled();
$checkup_freq = Smartcrawl_Checkup_Options::reporting_frequency();
$checkup_freq_readable = smartcrawl_get_array_value( $frequencies, $checkup_freq );

$lighthouse_cron_enabled = Smartcrawl_Lighthouse_Options::is_cron_enabled();
$lighthouse_freq = Smartcrawl_Lighthouse_Options::reporting_frequency();
$lighthouse_freq_readable = smartcrawl_get_array_value( $frequencies, $lighthouse_freq );

$crawler_available = Smartcrawl_Sitemap_Utils::crawler_available();
$sitemap_enabled = Smartcrawl_Settings::get_setting( 'sitemap' );
$crawler_cron_enabled = ! empty( $_view['options']['crawler-cron-enable'] );
$crawler_reporting_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_SITEMAP ) . '&tab=tab_url_crawler_reporting';
$crawler_freq = empty( $_view['options']['crawler-frequency'] ) ? false : $_view['options']['crawler-frequency'];
$crawler_freq_readable = smartcrawl_get_array_value( $frequencies, $crawler_freq );
?>

<section id="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_REPORTS ); ?>"
         data-dependent="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_REPORTS ); ?>"
         class="sui-box wds-dashboard-widget">

	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<span class="sui-icon-graph-bar" aria-hidden="true"></span><?php esc_html_e( 'Emails & Report', 'wds' ); ?>
		</h2>
	</div>

	<div class="sui-box-body">
		<p><?php esc_html_e( 'Manage your email notifications and report schedules.', 'wds' ); ?></p>

		<table class="sui-table wds-draw-left">
			<tbody>
			<?php if ( $health_available ): ?>
				<?php if ( Smartcrawl_Health_Settings::is_test_mode_checkup() ): ?>
					<tr>
						<td>
							<span class="sui-icon-smart-crawl" aria-hidden="true"></span>
							<small><strong><?php esc_html_e( 'SEO Checkup', 'wds' ); ?></strong></small>
						</td>

						<td>
							<?php if ( $checkup_cron_enabled ): ?>
								<span class="sui-tag sui-tag-sm sui-tag-blue"><?php echo esc_html( $checkup_freq_readable ); ?></span>
							<?php else: ?>
								<span class="sui-tag sui-tag-sm sui-tag-disabled"><?php esc_html_e( 'Inactive', 'wds' ); ?></span>
							<?php endif; ?>
						</td>

						<td>
							<a href="<?php echo esc_attr( $health_reporting_url ); ?>"
							   aria-label="<?php esc_html_e( 'Configure SEO checkup reports', 'wds' ); ?>">
								<?php if ( $checkup_cron_enabled ): ?>
									<span class="sui-icon-widget-settings-config" aria-hidden="true"></span>
								<?php else: ?>
									<span class="sui-icon-plus" aria-hidden="true"></span>
								<?php endif; ?>
							</a>
						</td>
					</tr>
				<?php else: ?>
					<tr>
						<td>
							<span class="wds-lighthouse-icon" aria-hidden="true"></span>
							<small><strong><?php esc_html_e( 'SEO Audits', 'wds' ); ?></strong></small>
						</td>

						<td>
							<?php if ( $lighthouse_cron_enabled ): ?>
								<span class="sui-tag sui-tag-sm sui-tag-blue"><?php echo esc_html( $lighthouse_freq_readable ); ?></span>
							<?php else: ?>
								<span class="sui-tag sui-tag-sm sui-tag-disabled"><?php esc_html_e( 'Inactive', 'wds' ); ?></span>
							<?php endif; ?>
						</td>

						<td>
							<a href="<?php echo esc_attr( $health_reporting_url ); ?>"
							   aria-label="<?php esc_html_e( 'Configure SEO audit reports', 'wds' ); ?>">
								<?php if ( $lighthouse_cron_enabled ): ?>
									<span class="sui-icon-widget-settings-config" aria-hidden="true"></span>
								<?php else: ?>
									<span class="sui-icon-plus" aria-hidden="true"></span>
								<?php endif; ?>
							</a>
						</td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $crawler_available ): ?>
				<tr>
					<td>
						<span class="sui-icon-web-globe-world" aria-hidden="true"></span>
						<small><strong><?php esc_html_e( 'Sitemap Crawler', 'wds' ); ?></strong></small>
					</td>

					<td>
						<?php if ( $sitemap_enabled && $crawler_cron_enabled ): ?>
							<span class="sui-tag sui-tag-sm sui-tag-blue"><?php echo esc_html( $crawler_freq_readable ); ?></span>
						<?php else: ?>
							<span class="sui-tag sui-tag-sm sui-tag-disabled"><?php esc_html_e( 'Inactive', 'wds' ); ?></span>
						<?php endif; ?>
					</td>

					<td>
						<a href="<?php echo esc_attr( $crawler_reporting_url ); ?>"
						   aria-label="<?php esc_html_e( 'Configure crawler reports', 'wds' ); ?>">
							<?php if ( $crawler_cron_enabled ): ?>
								<span class="sui-icon-widget-settings-config" aria-hidden="true"></span>
							<?php else: ?>
								<span class="sui-icon-plus" aria-hidden="true"></span>
							<?php endif; ?>
						</a>
					</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>

		<p class="sui-description wds-documentation-link">
			<?php echo smartcrawl_format_link(
				esc_html__( 'You can also set up scheduled PDF reports for your clients via %s.', 'wds' ),
				'https://wpmudev.com/hub2/',
				esc_html__( 'The Hub', 'wds' ),
				'_blank'
			); ?>
		</p>
	</div>
</section>
