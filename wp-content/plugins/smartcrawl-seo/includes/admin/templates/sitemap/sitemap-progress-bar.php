<?php
/**
 * @var Smartcrawl_SeoReport $crawl_report
 */
$crawl_report = empty( $crawl_report ) ? null : $crawl_report;
$start_timestamp = $crawl_report->get_start_timestamp();
$start_timestamp = empty( $start_timestamp ) ? time() : $start_timestamp;
$mins_since_start = ( time() - $start_timestamp ) / 60;
$dash_profile_data = smartcrawl_get_dash_profile_data();
$user_email = $dash_profile_data ? $dash_profile_data->user_email : false;
if ( $start_timestamp && $user_email && $mins_since_start > 10 ) {
	$progress_state = sprintf(
		esc_html__( "The crawl is taking longer than expected. We'll email %s when the crawl has completed.", 'wds' ),
		"<strong>$user_email</strong>"
	);
} else {
	$progress_state = esc_html__( 'Crawling website...', 'wds' );
}
?>

<div class="wds-crawl-results-report wds-report">
	<p><?php esc_html_e( "We're looking for issues with your sitemap, please wait…", 'wds' ); ?></p>
	<div class="wds-url-crawler-progress">
		<?php
		$this->_render( 'progress-bar', array(
			'progress'       => $crawl_report->get_progress(),
			'progress_state' => $progress_state,
		) );
		?>
		<?php $this->_render( 'progress-notice' ); ?>
	</div>
</div>
