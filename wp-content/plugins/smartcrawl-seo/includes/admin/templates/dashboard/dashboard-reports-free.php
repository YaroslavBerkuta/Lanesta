<section id="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_REPORTS ); ?>"
         data-dependent="<?php echo esc_attr( Smartcrawl_Settings_Dashboard::BOX_REPORTS ); ?>"
         class="sui-box wds-dashboard-widget">

	<div class="sui-box-header">
		<h2 class="sui-box-title">
			<span class="sui-icon-graph-bar" aria-hidden="true"></span><?php esc_html_e( 'Emails & Report', 'wds' ); ?>
		</h2>

		<div class="sui-actions-left">
			<span class="sui-tag sui-tag-pro"><?php esc_html_e( 'Pro', 'wds' ); ?></span>
		</div>

		<div class="sui-actions-right">
			<a class="sui-button sui-button-purple"
			   target="_blank"
			   href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_dash_reports_upsell_notice">
				<?php esc_html_e( 'Upgrade to Pro', 'wds' ); ?>
			</a>
		</div>
	</div>

	<div class="sui-box-body">
		<p><?php esc_html_e( 'Manage your email notifications and report schedules.', 'wds' ); ?></p>

		<?php if ( Smartcrawl_Health_Settings::is_test_mode_checkup() ): ?>
			<div class="wds-separator-top wds-draw-left-padded">
				<span class="sui-icon-smart-crawl" aria-hidden="true"></span>
				<small><strong><?php esc_html_e( 'SEO Checkup', 'wds' ); ?></strong></small>
				<span class="sui-tag sui-tag-pro"><?php esc_html_e( 'Pro', 'wds' ); ?></span>
				<div class="wds-right">
					<span class="sui-tag sui-tag-sm sui-tag-disabled"><?php esc_html_e( 'Inactive', 'wds' ); ?></span>
				</div>
			</div>
		<?php else: ?>
			<div class="wds-separator-top wds-draw-left-padded">
				<span class="wds-lighthouse-icon" aria-hidden="true"></span>
				<small><strong><?php esc_html_e( 'SEO Audits', 'wds' ); ?></strong></small>
				<span class="sui-tag sui-tag-pro"><?php esc_html_e( 'Pro', 'wds' ); ?></span>
				<div class="wds-right">
					<span class="sui-tag sui-tag-sm sui-tag-disabled"><?php esc_html_e( 'Inactive', 'wds' ); ?></span>
				</div>
			</div>
		<?php endif; ?>

		<div class="wds-separator-top wds-draw-left-padded">
			<span class="sui-icon-web-globe-world" aria-hidden="true"></span>
			<small><strong><?php esc_html_e( 'Sitemap Crawler', 'wds' ); ?></strong></small>
			<span class="sui-tag sui-tag-pro"><?php esc_html_e( 'Pro', 'wds' ); ?></span>
			<div class="wds-right">
				<span class="sui-tag sui-tag-sm sui-tag-disabled"><?php esc_html_e( 'Inactive', 'wds' ); ?></span>
			</div>
		</div>

		<div class="wds-separator-top wds-draw-left-padded">
			<?php $this->_render( 'mascot-message', array(
				'key'         => 'seo-checkup-upsell',
				'dismissible' => false,
				'image_name'  => 'graphic-dashboard-reports',
				'message'     => sprintf(
					'%s <a target="_blank" class="sui-button sui-button-purple" href="https://wpmudev.com/project/smartcrawl-wordpress-seo/?utm_source=smartcrawl&utm_medium=plugin&utm_campaign=smartcrawl_dash_reports_upsell_notice">%s</a>',
					esc_html__( 'Schedule automatic reports and get them emailed direct to your inbox to stay on top of potential SEO issues. Get Reports as part of a WPMU DEV membership.', 'wds' ),
					esc_html__( 'Try Pro Free Today', 'wds' )
				),
			) ); ?>
		</div>
	</div>
</section>
