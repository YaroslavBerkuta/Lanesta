<?php
$checkup_available = is_main_site();
$sitemap_crawler_available = Smartcrawl_Sitemap_Utils::crawler_available();

if ( ! $checkup_available && ! $sitemap_crawler_available ) {
	return;
}

$service = empty( $service ) ? null : $service;
if ( ! $service ) {
	return;
}
$last_checked = (boolean) $service->get_last_checked_timestamp();
$in_progress = empty( $in_progress ) ? false : $in_progress;
$last_checked_timestamp = $service->get_last_checked( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
$options = Smartcrawl_Settings::get_options();
$score = isset( $score ) ? $score : 0;
$dependents = array( Smartcrawl_Settings_Dashboard::BOX_SITEMAP, Smartcrawl_Settings_Dashboard::BOX_SEO_CHECKUP );
$dependents_attr = implode( ';', $dependents );
$status = empty( $status ) ? '' : $status;
$status_message = empty( $status_message ) ? '' : $status_message;

if ( null === $score || false === $score ) {
	$score_class = 'sui-icon-info sui-invalid';
} else {
	$issue_count = empty( $issue_count ) ? 0 : $issue_count;
	$score_class = $status === 'success'
		? "sui-icon-check-tick sui-success"
		: "sui-icon-info sui-{$status}";
}
$whitelabel_class = Smartcrawl_White_Label::get()->summary_class();
?>

<div id="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_TOP_STATS ); ?>"
     class="sui-box sui-summary sui-summary-sm wds-dashboard-widget <?php echo esc_attr( $whitelabel_class ); ?>"
     data-issue-count="<?php echo intval( $issue_count ); ?>"
     data-dependent="<?php echo esc_attr( $dependents_attr ); ?>">

	<div class="sui-summary-image-space">
	</div>

	<div class="sui-summary-segment">
		<?php if ( $checkup_available ): ?>
			<?php if ( $in_progress ) : ?>
				<div class="wds-summary-message">
					<strong><?php esc_html_e( 'Welcome!', 'wds' ); ?></strong>
					<p>
						<small><?php esc_html_e( 'Please wait while we finish the checkup ...', 'wds' ); ?></small>
					</p>
				</div>
			<?php elseif ( ! $last_checked ) : ?>
				<div class="wds-summary-message">
					<strong><?php esc_html_e( 'Welcome!', 'wds' ); ?></strong>
					<p>
						<small><?php esc_html_e( 'Run your first SEO checkup to see what needs improving!', 'wds' ); ?></small>
					</p>
					<button class="sui-button sui-button-blue wds-start-checkup">
						<span class="sui-loading-text">
							<span class="sui-icon-plus" aria-hidden="true"></span>
							<?php esc_html_e( 'Run checkup', 'wds' ); ?>
						</span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>
				</div>
			<?php else : ?>
				<div class="sui-summary-details">
					<div class="wds-checkup-summary">
						<span class="sui-summary-large"><?php echo esc_html( intval( $score ) ); ?></span>
						<span class="sui-summary-percent">/100</span>
						<span class="sui-tooltip sui-tooltip-constrained"
						      data-tooltip="<?php echo esc_html( $status_message ); ?>">
							<span class="<?php echo esc_attr( $score_class ); ?>" aria-hidden="true"></span>
						</span>
						<span class="sui-summary-sub"><?php esc_html_e( 'Current SEO Score', 'wds' ); ?></span>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<div class="sui-summary-segment">
		<ul class="sui-list">
			<?php if ( $checkup_available ): ?>
				<li>
					<span class="sui-list-label"><?php esc_html_e( 'Last checkup', 'wds' ); ?></span>
					<span class="sui-list-detail">
						<?php if ( $in_progress ): ?>
							<p><span class="sui-icon-loader sui-loading"
							         aria-hidden="true"></span> <small><?php echo esc_html__( 'Checkup in progress ...', 'wds' ); ?></small></p>
						<?php else: ?>
							<p><small><?php echo esc_html( $last_checked_timestamp ); ?></small></p>
						<?php endif; ?>
					</span>
				</li>
			<?php endif; ?>

			<?php $this->_render( 'dashboard/dashboard-top-sitemap-list-item' ); ?>
		</ul>
	</div>
</div>
