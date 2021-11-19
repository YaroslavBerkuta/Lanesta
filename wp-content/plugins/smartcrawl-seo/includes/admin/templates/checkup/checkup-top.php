<?php
$service = empty( $service ) ? null : $service;
if ( ! $service ) {
	return;
}
$is_member = empty( $is_member ) ? false : true;
$in_progress = empty( $in_progress ) ? false : true;
if ( $in_progress ) {
	return;
}

$score = isset( $score ) ? $score : 0;
if ( null === $score ) {
	return;
}
$error = empty( $error ) ? '' : $error;
if ( $error ) {
	return;
}

$items = empty( $items ) ? array() : $items;
if ( empty( $items ) ) {
	return;
}

$last_checked_timestamp = empty( $last_checked_timestamp ) ? '' : $last_checked_timestamp;
if ( empty( $last_checked_timestamp ) ) {
	return;
}

$status = empty( $status ) ? '' : $status;
$status_message = empty( $status_message ) ? '' : $status_message;
$issue_count = empty( $issue_count ) ? 0 : $issue_count;
$score_class = $status === 'success'
	? 'sui-icon-check-tick sui-success'
	: "sui-icon-info sui-$status";
if ( $score === 100 ) {
	$dot_count = 3;
} elseif ( $score >= 80 ) {
	$dot_count = 2;
} elseif ( $score >= 60 ) {
	$dot_count = 1;
} else {
	$dot_count = 0;
}
$reporting_enabled = Smartcrawl_Checkup_Options::is_cron_enabled();
$cron = Smartcrawl_Controller_Cron::get();
$frequencies = $cron->get_frequencies();
$whitelabel_class = Smartcrawl_White_Label::get()->summary_class();
?>
<div class="sui-box sui-summary <?php echo esc_attr( $whitelabel_class ); ?>"
     data-issue-count="<?php echo esc_attr( $issue_count ); ?>">

	<div class="sui-summary-image-space">
	</div>

	<div class="sui-summary-segment">
		<div class="sui-summary-details">
			<div class="wds-checkup-summary">
				<span class="sui-summary-large"><?php echo esc_html( intval( $score ) ); ?></span>
				<span class="<?php echo esc_attr( $score_class ); ?>" aria-hidden="true"></span>
				<span class="sui-summary-percent">/100</span>
				<span class="sui-summary-sub">
					<?php echo esc_html( $status_message ); ?>
					<br/>
					<?php if ( $dot_count ) {
						foreach ( range( 1, $dot_count ) as $filled_dot ) {
							?><span class="wds-checkup-status-dot-full"></span><?php
						}
						if ( 3 - $dot_count > 0 ) {
							foreach ( range( 1, 3 - $dot_count ) as $empty_dot ) {
								?><span class="wds-checkup-status-dot"></span><?php
							}
						}
					} ?>
				</span>
			</div>
		</div>
	</div>

	<div class="sui-summary-segment">
		<ul class="sui-list">
			<li>
				<span class="sui-list-label"><?php esc_html_e( 'Last SEO Checkup', 'wds' ); ?></span>
				<span class="sui-list-detail"><?php echo esc_html( $service->get_last_checked( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ); ?></span>
			</li>

			<li>
				<span class="sui-list-label"><?php esc_html_e( 'SEO Issues', 'wds' ); ?></span>
				<span class="sui-list-detail">
					<?php if ( $issue_count > 0 ): ?>
						<span class="sui-tag sui-tag-warning"><?php echo esc_html( $issue_count ); ?></span>
					<?php else: ?>
						<span class="sui-icon-check-tick sui-success" aria-hidden="true"></span>
						<small><?php esc_html_e( 'No issues', 'wds' ); ?></small>
					<?php endif; ?>
				</span>
			</li>

			<li>
				<span class="sui-list-label">
					<?php esc_html_e( 'Scheduled Reports', 'wds' ); ?>
					<?php if ( ! $is_member ) : ?>
						<a href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_seocheckup_top_reports_pro_tag"
						   target="_blank">
							<span class="sui-tag sui-tag-pro sui-tooltip sui-tooltip-constrained"
							      style="--tooltip-width: 200px;"
							      data-tooltip="<?php esc_attr_e( 'Upgrade to Pro to schedule automated checkups and email reports', 'wds' ); ?>"> <?php esc_html_e( 'Pro', 'wds' ); ?></span>
						</a>
					<?php endif; ?>
				</span>
				<span class="sui-list-detail">
					<?php if ( $is_member ) : ?>
						<?php if ( $reporting_enabled ) : ?>

							<?php
							$monday = strtotime( 'this Monday' );
							$first_of_month = strtotime( 'first day of this month' );
							$midnight = strtotime( 'today' );
							$checkup_frequency = Smartcrawl_Checkup_Options::reporting_frequency();
							$checkup_dow = Smartcrawl_Checkup_Options::reporting_dow();
							$checkup_tod = Smartcrawl_Checkup_Options::reporting_tod();
							?>

							<?php
							if ( 'daily' === $checkup_frequency ) {
								printf(
									esc_html__( '%1$s at %2$s' ),
									esc_html( smartcrawl_get_array_value( $frequencies, $checkup_frequency ) ),
									esc_html( date_i18n( get_option( 'time_format' ), $midnight + ( $checkup_tod * HOUR_IN_SECONDS ) ) )
								);
							} elseif ( 'weekly' === $checkup_frequency ) {
								printf(
									esc_html__( '%1$s on %2$ss at %3$s' ),
									esc_html( smartcrawl_get_array_value( $frequencies, $checkup_frequency ) ),
									esc_html( date_i18n( 'l', $monday + ( $checkup_dow * DAY_IN_SECONDS ) ) ),
									esc_html( date_i18n( get_option( 'time_format' ), $midnight + ( $checkup_tod * HOUR_IN_SECONDS ) ) )
								);
							} else {
								printf(
									esc_html__( '%1$s on the %2$s at %3$s' ),
									esc_html( smartcrawl_get_array_value( $frequencies, $checkup_frequency ) ),
									esc_html( date_i18n( 'jS', $first_of_month + ( ( $checkup_dow - 1 ) * DAY_IN_SECONDS ) ) ),
									esc_html( date_i18n( get_option( 'time_format' ), $midnight + ( $checkup_tod * HOUR_IN_SECONDS ) ) )
								);
							}
							?>

						<?php else : ?>
							<button class="sui-button sui-button-blue wds-enable-reporting"
							        aria-label="<?php esc_html_e( 'Enable checkup reporting', 'wds' ); ?>">
								<?php esc_html_e( 'Enable', 'wds' ); ?>
							</button>
							<button class="sui-button sui-button-blue wds-disable-reporting"
							        aria-label="<?php esc_html_e( 'Disable checkup reporting', 'wds' ); ?>"
							        style="display: none;">
								<?php esc_html_e( 'Disable', 'wds' ); ?>
							</button>
						<?php endif; ?>
					<?php else : /* Not a member, this is a pro feature */ ?>
						<span class="sui-tag sui-tag-inactive"><?php esc_html_e( 'Inactive', 'wds' ); ?></span>
					<?php endif; ?>
				</span>
			</li>
		</ul>
	</div>
</div>
