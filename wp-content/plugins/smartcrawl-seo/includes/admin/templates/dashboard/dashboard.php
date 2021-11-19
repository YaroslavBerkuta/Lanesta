<?php
/**
 * Dashboard root template
 *
 * @package wpmu-dev-seo
 */

$configs_available = is_main_site();
?>
<?php $this->_render( 'before-page-container' ); ?>

<div id="container" class="<?php smartcrawl_wrap_class( 'wds-dashboard' ); ?>">
	<?php $this->_render( 'page-header', array(
		'title'                 => esc_html__( 'Dashboard', 'wds' ),
		'documentation_chapter' => 'dashboard',
		'utm_campaign'          => 'smartcrawl_dashboard_docs',
	) ); ?>

	<?php $this->_render( 'floating-notices', array(
		'keys' => array( 'wds-config-notice' ),
	) ); ?>

	<div class="sui-row">
		<div class="sui-col-md-12">
			<?php
			if ( Smartcrawl_Health_Settings::is_test_mode_checkup() ) {
				Smartcrawl_Checkup_Renderer::render( 'dashboard/dashboard-top' );
			} else {
				Smartcrawl_Lighthouse_Dashboard_Renderer::render( 'dashboard/dashboard-top-lighthouse' );
			}
			?>
		</div>

		<div class="sui-col">
			<?php
			if ( Smartcrawl_Health_Settings::is_test_mode_checkup() ) {
				Smartcrawl_Checkup_Renderer::render( 'dashboard/dashboard-widget-seo-checkup' );
			} else {
				Smartcrawl_Lighthouse_Dashboard_Renderer::render( 'dashboard/dashboard-widget-lighthouse' );
			}
			$this->_render( 'dashboard/dashboard-widget-content-analysis' );
			$this->_render( 'dashboard/dashboard-widget-social' );
			$this->_render( 'dashboard/dashboard-widget-schema' );
			if ( Smartcrawl_Settings_Admin::is_tab_allowed( Smartcrawl_Settings::TAB_SETTINGS )
			     && $configs_available ) {
				$this->_render( 'dashboard/dashboard-widget-configs' );
			}
			?>
		</div>

		<div class="sui-col">
			<?php
			$this->_render( 'dashboard/dashboard-widget-upgrade' );
			$this->_render( 'dashboard/dashboard-widget-onpage' );
			$this->_render( 'dashboard/dashboard-widget-sitemap' );
			$this->_render( 'dashboard/dashboard-widget-advanced-tools' );
			$this->_render( 'dashboard/dashboard-widget-reports' );
			?>
		</div>
	</div>

	<?php do_action( 'wds-dshboard-after_settings' ); ?>

	<?php $this->_render( 'dashboard/dashboard-cross-sell-footer' ); ?>
	<?php $this->_render( 'footer' ); ?>
</div>
