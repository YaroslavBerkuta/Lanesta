<?php
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

$lighthouse_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_HEALTH ) . '&tab=tab_settings#seo-test-mode';
$user = Smartcrawl_Model_User::get( get_current_user_id() );
?>
<div class="notice-warning notice">
	<p style="margin-bottom: 10px;">
		<?php printf(
			esc_html__( "Heads up, %s! %s functionality will soon be deprecated in favor of %s powered by %s. We recommend switching to SEO Audits now.", 'wds' ),
			$user->get_first_name(),
			sprintf( '<strong>%s</strong>', 'SmartCrawl’s SEO Checkup' ),
			sprintf( '<strong>%s</strong>', 'SEO Audits' ),
			sprintf( '<strong>%s</strong>', 'Google Lighthouse' )
		); ?>
	</p>
	<a href="<?php echo esc_attr( $lighthouse_url ); ?>"
	   class="button button-primary">
		<?php esc_html_e( 'Switch to SEO Audits', 'wds' ); ?>
	</a>
	<a target="_blank"
	   class="wds-inline-notice-link"
	   href="https://wpmudev.com/blog/lighthouse-seo-scan-smartcrawl/"><?php esc_html_e( 'Learn More', 'wds' ); ?></a>
	<p></p>
</div>
