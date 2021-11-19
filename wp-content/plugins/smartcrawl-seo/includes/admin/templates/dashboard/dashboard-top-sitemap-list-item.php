<?php
$lighthouse = Smartcrawl_Service::get( Smartcrawl_Service::SERVICE_LIGHTHOUSE );
$option_name = Smartcrawl_Settings::TAB_SETTINGS . '_options';
$sitemap_enabled = Smartcrawl_Settings::get_setting( 'sitemap' );
$is_member = $lighthouse->is_member();
$sitemap_crawler_available = Smartcrawl_Sitemap_Utils::crawler_available();

if ( ! $sitemap_crawler_available ) {
	return;
}
?>
<li>
	<span class="sui-list-label"><?php esc_html_e( 'Sitemap', 'wds' ); ?></span>
	<span class="sui-list-detail">
		<?php if ( ! $is_member ) : ?>
			<span class="sui-tag sui-tag-inactive"><?php esc_html_e( 'No Data Available', 'wds' ); ?></span>
		<?php elseif ( $sitemap_enabled ) : ?>

			<?php
			$this->_render( 'url-crawl-master', array(
				'ready_template'    => 'dashboard/dashboard-url-crawl-stats',
				'progress_template' => 'dashboard/dashboard-url-crawl-in-progress-small',
				'no_data_template'  => 'dashboard/dashboard-url-crawl-no-data-small',
			) );
			?>

		<?php else : ?>

			<button type="button"
			        data-option-id="<?php echo esc_attr( $option_name ); ?>"
			        data-flag="<?php echo 'sitemap'; ?>"
			        aria-label="<?php esc_html_e( 'Activate sitemap component', 'wds' ); ?>"
			        class="wds-activate-component wds-disabled-during-request sui-button sui-button-blue">
	
				<span class="sui-loading-text"><?php esc_html_e( 'Activate Sitemap', 'wds' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		<?php endif; ?>
	</span>
</li>
