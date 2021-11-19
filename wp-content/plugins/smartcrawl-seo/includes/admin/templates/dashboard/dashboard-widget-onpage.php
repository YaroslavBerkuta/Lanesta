<?php
if ( ! Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_ONPAGE ) ) {
	return;
}

$page_url = Smartcrawl_Settings_Admin::admin_url( Smartcrawl_Settings::TAB_ONPAGE );
$public_post_types = get_post_types( array( 'public' => true ) );
$show_on_front = get_option( 'show_on_front' );
$options = $_view['options'];
$option_name = Smartcrawl_Settings::TAB_SETTINGS . '_options';
$onpage_enabled = Smartcrawl_Settings::get_setting( 'onpage' );
?>
<section id="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_ONPAGE ); ?>" class="sui-box wds-dashboard-widget">
	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<span class="sui-icon-pencil" aria-hidden="true"></span><?php esc_html_e( 'Titles & Meta', 'wds' ); ?>
		</h2>
	</div>

	<div class="sui-box-body">
		<p><?php esc_html_e( 'Control how your website’s pages, posts and custom post types appear in search engines like Google and Bing.', 'wds' ); ?></p>

		<?php if ( $onpage_enabled ) : ?>
			<div class="wds-separator-top wds-draw-left-padded">
				<small><strong><?php esc_html_e( 'Homepage', 'wds' ); ?></strong></small>
				<span class="wds-right">
					<small><?php 'page' === $show_on_front ? esc_html_e( 'A Static Page', 'wds' ) : esc_html_e( 'Latest Posts', 'wds' ); ?></small>
				</span>
			</div>

			<div class="wds-separator-top wds-draw-left-padded">
				<small><strong><?php esc_html_e( 'Public post types', 'wds' ); ?></strong></small>
				<span class="wds-right">
					<small><?php echo esc_html( count( $public_post_types ) ); ?></small>
				</span>
			</div>
		<?php endif; ?>
	</div>
	<div class="sui-box-footer">
		<?php if ( $onpage_enabled ): ?>
			<a href="<?php echo esc_attr( $page_url ); ?>"
			   aria-label="<?php esc_html_e( 'Configure titles and meta component', 'wds' ); ?>"
			   class="sui-button sui-button-ghost">

				<span class="sui-icon-wrench-tool"
				      aria-hidden="true"></span> <?php esc_html_e( 'Configure', 'wds' ); ?>
			</a>
		<?php else : ?>
			<button type="button"
			        data-option-id="<?php echo esc_attr( $option_name ); ?>"
			        data-flag="<?php echo esc_attr( 'onpage' ); ?>"
			        aria-label="<?php esc_html_e( 'Activate title and meta component', 'wds' ); ?>"
			        class="wds-activate-component wds-disabled-during-request sui-button sui-button-blue">

				<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'wds' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>
		<?php endif; ?>
	</div>
</section>
