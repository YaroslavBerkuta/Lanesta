<?php
$device = smartcrawl_get_array_value( $_GET, 'device' );
if ( ! in_array( $device, array( 'desktop', 'mobile' ), true ) ) {
	$device = 'desktop';
}
$page_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH );
$desktop_url = $page_url . '&device=desktop';
$mobile_url = $page_url . '&device=mobile';
/**
 * @var $lighthouse_report Smartcrawl_Lighthouse_Report
 */
$lighthouse_report = empty( $lighthouse_report ) ? false : $lighthouse_report;
if ( ! $lighthouse_report || ! $lighthouse_report->has_data() || $lighthouse_report->has_errors() ) {
	return;
}
?>
<div class="wds-lh-device">
	<a class="<?php echo $device === 'desktop' ? 'active' : 'sui-tooltip'; ?>"
	   data-tooltip="<?php esc_html_e( 'Apply desktop simulation' ); ?>"
	   href="<?php echo esc_attr( $desktop_url ); ?>">
		<span class="sui-icon-monitor" aria-hidden="true"></span> <?php esc_html_e( 'Desktop', 'wds' ); ?>
	</a>
	<a class="<?php echo $device === 'mobile' ? 'active' : 'sui-tooltip'; ?>"
	   data-tooltip="<?php esc_html_e( 'Apply mobile simulation' ); ?>"
	   href="<?php echo esc_attr( $mobile_url ); ?>">
		<span class="sui-icon-tablet-portrait" aria-hidden="true"></span> <?php esc_html_e( 'Mobile', 'wds' ); ?>
	</a>
</div>
