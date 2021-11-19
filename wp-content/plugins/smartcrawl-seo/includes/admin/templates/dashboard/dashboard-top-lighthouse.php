<?php
$lighthouse_available = is_main_site();
$sitemap_crawler_available = Smartcrawl_Sitemap_Utils::crawler_available();

if ( ! $lighthouse_available && ! $sitemap_crawler_available ) {
	return;
}

$lighthouse_start_time = empty( $lighthouse_start_time ) ? false : $lighthouse_start_time;
/**
 * @var $lighthouse_report Smartcrawl_Lighthouse_Report|WP_Error|false
 */
$lighthouse_report = empty( $lighthouse_report ) || ! $lighthouse_report->has_data() || $lighthouse_report->has_errors()
	? false
	: $lighthouse_report;
$whitelabel_class = Smartcrawl_White_Label::get()->summary_class();
?>

<div id="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_TOP_STATS ); ?>"
     class="sui-box sui-summary sui-summary-sm wds-dashboard-widget <?php echo esc_attr( $whitelabel_class ); ?>"
     data-dependent="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_SITEMAP ); ?>">

	<div class="sui-summary-image-space">
	</div>

	<div class="sui-summary-segment">
		<?php if ( $lighthouse_report || $lighthouse_start_time ): ?>
			<div class="sui-summary-details">
				<div id="wds-lighthouse-summary">
					<div class="wds-lighthouse-score">
						<?php if ( $lighthouse_start_time ): ?>
							<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
						<?php else: ?>
							<div class="sui-tooltip sui-tooltip-constrained"
							     style="--tooltip-width: 240px;"
							     data-tooltip="<?php echo esc_attr( $lighthouse_report->get_status_message() ); ?>">

								<div class="sui-circle-score sui-circle-score-lg sui-grade-<?php echo esc_attr( $lighthouse_report->get_score_grade() ); ?>"
								     data-score="<?php echo esc_attr( $lighthouse_report->get_score() ); ?>"></div>
							</div>
						<?php endif; ?>

						<div>
							<strong><?php esc_html_e( 'SEO', 'wds' ); ?></strong>
							<span class="wds-lighthouse-tooltip sui-tooltip sui-tooltip-constrained"
							      data-tooltip="<?php esc_html_e( 'Google Lighthouse SEO audits ensure that your page is optimized for search engine results ranking. Fix as many as possible to  ensure your site is discoverable.', 'wds' ); ?>">
								<span class="sui-notice-icon sui-icon-info sui-sm" aria-hidden="true"></span>
							</span>
							<p class="sui-description">
								<?php esc_html_e( 'Homepage score', 'wds' ); ?>
								<?php
								echo Smartcrawl_Lighthouse_Options::dashboard_widget_device() === 'desktop'
									? esc_html__( '(Desktop)', 'wds' )
									: esc_html__( '(Mobile)', 'wds' );
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="wds-summary-message">
				<strong><?php esc_html_e( 'Welcome!', 'wds' ); ?></strong>
				<p>
					<small><?php esc_html_e( 'Run a new SEO audit to see what needs improving!', 'wds' ); ?></small>
				</p>
				<button class="sui-button sui-button-blue wds-lighthouse-start-test">
						<span class="sui-loading-text">
							<span class="sui-icon-plus" aria-hidden="true"></span>
							<?php esc_html_e( 'Run Test', 'wds' ); ?>
						</span>

					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>
			</div>
		<?php endif; ?>
	</div>

	<div class="sui-summary-segment">
		<ul class="sui-list">
			<li>
				<span class="sui-list-label"><?php esc_html_e( 'SEO Audits', 'wds' ); ?></span>
				<span class="sui-list-detail">
					<?php if ( $lighthouse_start_time ): ?>
						<p><span class="sui-icon-loader sui-loading"
						         aria-hidden="true"></span> <small><?php echo esc_html__( 'SEO Test in progress ...', 'wds' ); ?></small></p>
					<?php elseif ( $lighthouse_report ): ?>
						<?php if ( $lighthouse_report->get_failed_audits_count() > 0 ): ?>
							<span class="sui-tag sui-tag-yellow">
								<?php echo $lighthouse_report->get_failed_audits_count(); ?>
							</span>
						<?php else: ?>
							<span class="sui-icon-check-tick sui-success sui-md"
							      aria-hidden="true"></span> <small><?php esc_html_e( 'No audits', 'wds' ); ?></small>
						<?php endif; ?>
					<?php else: ?>
						-
					<?php endif; ?>
				</span>
			</li>

			<?php $this->_render( 'dashboard/dashboard-top-sitemap-list-item' ); ?>
		</ul>
	</div>
</div>
