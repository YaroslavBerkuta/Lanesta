<?php
$lighthouse_start_time = empty( $lighthouse_start_time ) ? false : $lighthouse_start_time;
/**
 * @var $lighthouse_report Smartcrawl_Lighthouse_Report
 */
$lighthouse_report = empty( $lighthouse_report ) ? false : $lighthouse_report;
if ( ! $lighthouse_report || ! $lighthouse_report->has_data() || $lighthouse_report->has_errors() ) {
	return;
}

$content_warnings = $lighthouse_report
	->get_group( Smartcrawl_Lighthouse_Report::GROUP_CONTENT )
	->get_failing_count();
$visibility_warnings = $lighthouse_report
	->get_group( Smartcrawl_Lighthouse_Report::GROUP_VISIBILITY )
	->get_failing_count();
$responsive_warnings = $lighthouse_report
	->get_group( Smartcrawl_Lighthouse_Report::GROUP_RESPONSIVE )
	->get_failing_count();

$score = $lighthouse_report->get_score();
$grade = $lighthouse_report->get_score_grade();

$whitelabel_class = Smartcrawl_White_Label::get()->summary_class();
?>

<div class="sui-box sui-summary <?php echo esc_attr( $whitelabel_class ); ?>">

	<div class="sui-summary-image-space">
	</div>

	<div class="sui-summary-segment">
		<div class="sui-summary-details">
			<div id="wds-lighthouse-summary">
				<div class="wds-lighthouse-score">
					<div class="sui-tooltip sui-tooltip-constrained"
					     style="--tooltip-width: 240px;"
					     data-tooltip="<?php echo esc_attr( $lighthouse_report->get_status_message() ); ?>">

						<div class="sui-circle-score sui-circle-score-lg sui-grade-<?php echo esc_attr( $grade ); ?>"
						     data-score="<?php echo esc_attr( $score ); ?>"></div>
					</div>

					<div>
						<strong><?php esc_html_e( 'SEO', 'wds' ); ?></strong>
						<span class="wds-lighthouse-tooltip sui-tooltip sui-tooltip-constrained"
						      data-tooltip="<?php esc_html_e( 'Google Lighthouse SEO audits ensure that your page is optimized for search engine results ranking. Fix as many as possible to  ensure your site is discoverable.', 'wds' ); ?>">
							<span class="sui-notice-icon sui-icon-info sui-sm" aria-hidden="true"></span>
						</span>
						<p class="sui-description"><?php esc_html_e( 'Homepage score', 'wds' ); ?></p>
					</div>
				</div>

				<div class="sui-summary-sub">
					<strong><?php echo $lighthouse_report->get_last_checked(); ?></strong>
					<span><?php esc_html_e( 'Last test date', 'wds' ); ?></span>
				</div>
			</div>
		</div>
	</div>

	<div class="sui-summary-segment">
		<ul class="sui-list">
			<li>
				<span class="sui-list-label"><?php esc_html_e( 'Content audits', 'wds' ); ?></span>
				<span class="sui-list-detail">
					<?php if ( $content_warnings ): ?>
						<span class="sui-tag sui-tag-warning">
							<?php echo esc_html( $content_warnings ); ?>
						</span>
					<?php else: ?>
						<span class="sui-icon-check-tick sui-md sui-success" aria-hidden="true"></span>
					<?php endif; ?>
				</span>
			</li>

			<li>
				<span class="sui-list-label"><?php esc_html_e( 'Indexing audits', 'wds' ); ?></span>
				<span class="sui-list-detail">
					<?php if ( $visibility_warnings ): ?>
						<span class="sui-tag sui-tag-warning"><?php echo esc_html( $visibility_warnings ); ?></span>
					<?php else: ?>
						<span class="sui-icon-check-tick sui-md sui-success" aria-hidden="true"></span>
					<?php endif; ?>
				</span>
			</li>

			<li>
				<span class="sui-list-label">
					<?php esc_html_e( 'Responsive audits', 'wds' ); ?>
				</span>
				<span class="sui-list-detail">
					<?php if ( $responsive_warnings ): ?>
						<span class="sui-tag sui-tag-warning"><?php echo esc_html( $responsive_warnings ); ?></span>
					<?php else: ?>
						<span class="sui-icon-check-tick sui-md sui-success" aria-hidden="true"></span>
					<?php endif; ?>
				</span>
			</li>
		</ul>
	</div>
</div>
